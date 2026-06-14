<?php
include '../includes/config.php';

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

// ── CSRF verification ────────────────────────────────────────────────────────
if (
    empty($_POST['csrf_token']) ||
    empty($_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {
    http_response_code(403);
    die('Invalid request. Please go back and try again.');
}
// Regenerate CSRF token after use
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// ── Collect & sanitize inputs ────────────────────────────────────────────────
$room_id    = (int)($_POST['room_id']   ?? 0);
$check_in   = trim($_POST['check_in']   ?? '');
$check_out  = trim($_POST['check_out']  ?? '');
$guests     = max(1, min(10, (int)($_POST['guests'] ?? 1)));
$full_name  = trim($_POST['full_name']  ?? '');
$ic_number  = trim($_POST['ic_number']  ?? '');
$phone      = trim($_POST['phone']      ?? '');
$email      = trim($_POST['email']      ?? '');
$notes      = trim($_POST['notes']      ?? '');
$pay_method = 'duitnow_qr'; // only option currently
$today      = date('Y-m-d');

$errors = [];
$old    = compact('full_name', 'ic_number', 'phone', 'email', 'notes');

// ── Validate dates ───────────────────────────────────────────────────────────
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $check_in) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $check_out)) {
    $errors[] = 'Invalid date format.';
} elseif ($check_in < $today) {
    $errors[] = 'Check-in date cannot be in the past.';
} elseif ($check_out <= $check_in) {
    $errors[] = 'Check-out date must be after check-in.';
}

// ── Validate guest fields ────────────────────────────────────────────────────
if (mb_strlen($full_name) < 2)  $errors[] = 'Full name is required.';
if (mb_strlen($full_name) > 150) $errors[] = 'Full name is too long.';

if (empty($ic_number))          $errors[] = 'IC / Passport number is required.';
if (mb_strlen($ic_number) > 20) $errors[] = 'IC / Passport number is too long.';

if (empty($phone))              $errors[] = 'Phone number is required.';
if (!preg_match('/^[\d\s\+\-\(\)]{7,20}$/', $phone)) $errors[] = 'Phone number format is invalid.';

if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Email address format is invalid.';
}
if (mb_strlen($notes) > 1000)   $errors[] = 'Special requests are too long (max 1000 characters).';

// ── Terms agreement ──────────────────────────────────────────────────────────
if (empty($_POST['agree_terms'])) {
    $errors[] = 'You must agree to the House Policies to proceed.';
}

// ── Redirect back with errors ────────────────────────────────────────────────
if (!empty($errors)) {
    $_SESSION['booking_errors'] = $errors;
    $_SESSION['booking_old']    = $old;
    $back = 'book.php?room_id=' . $room_id
          . '&check_in='  . urlencode($check_in)
          . '&check_out=' . urlencode($check_out)
          . '&guests='    . $guests;
    header('Location: ' . $back);
    exit;
}

// ── DB required from here ────────────────────────────────────────────────────
if (!$conn) {
    $_SESSION['booking_errors'] = ['Our booking system is temporarily unavailable. Please contact us via WhatsApp.'];
    $_SESSION['booking_old']    = $old;
    header('Location: book.php?room_id=' . $room_id . '&check_in=' . urlencode($check_in) . '&check_out=' . urlencode($check_out) . '&guests=' . $guests);
    exit;
}

// ── Verify room exists and is active ────────────────────────────────────────
$stmt = $conn->prepare("SELECT * FROM rooms WHERE id = ? AND is_active = 1");
$stmt->bind_param('i', $room_id);
$stmt->execute();
$room = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$room) {
    header('Location: search.php');
    exit;
}

// Verify capacity
if ($guests > (int)$room['capacity']) {
    $_SESSION['booking_errors'] = ['Selected guests exceed room capacity. Please choose a suitable room.'];
    $_SESSION['booking_old']    = $old;
    header('Location: book.php?room_id=' . $room_id . '&check_in=' . urlencode($check_in) . '&check_out=' . urlencode($check_out) . '&guests=' . $guests);
    exit;
}

// ── Begin transaction — prevents double-booking race conditions ──────────────
$conn->begin_transaction();

try {
    // Re-check availability inside transaction (SELECT ... FOR UPDATE equivalent via table lock)
    $stmt = $conn->prepare(
        "SELECT COUNT(*) AS cnt FROM bookings
         WHERE room_id = ? AND status NOT IN ('cancelled')
           AND check_in < ? AND check_out > ?
         FOR UPDATE"
    );
    $stmt->bind_param('iss', $room_id, $check_out, $check_in);
    $stmt->execute();
    $conflict = (int)$stmt->get_result()->fetch_assoc()['cnt'];
    $stmt->close();

    if ($conflict > 0) {
        $conn->rollback();
        header('Location: search.php?check_in=' . urlencode($check_in)
             . '&check_out=' . urlencode($check_out)
             . '&guests='    . $guests
             . '&unavailable=1');
        exit;
    }

    // ── Insert or find user record ───────────────────────────────────────────
    // If logged in, use existing user_id; otherwise look up by phone
    if (!empty($_SESSION['user_id'])) {
        $user_id = (int)$_SESSION['user_id'];
        // Update their details with whatever they typed in the form
        $stmt = $conn->prepare(
            "UPDATE users SET full_name = ?, ic_number = ?, email = ? WHERE id = ?"
        );
        $stmt->bind_param('sssi', $full_name, $ic_number, $email, $user_id);
        $stmt->execute();
        $stmt->close();
    } else {
        // Walk-in: find by phone or create new row (no password)
        $stmt = $conn->prepare("SELECT id FROM users WHERE phone = ? LIMIT 1");
        $stmt->bind_param('s', $phone);
        $stmt->execute();
        $existing_user = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($existing_user) {
            $user_id = (int)$existing_user['id'];
            $stmt = $conn->prepare(
                "UPDATE users SET full_name = ?, ic_number = ?, email = ? WHERE id = ?"
            );
            $stmt->bind_param('sssi', $full_name, $ic_number, $email, $user_id);
            $stmt->execute();
            $stmt->close();
        } else {
            $stmt = $conn->prepare(
                "INSERT INTO users (full_name, ic_number, phone, email) VALUES (?, ?, ?, ?)"
            );
            $stmt->bind_param('ssss', $full_name, $ic_number, $phone, $email);
            $stmt->execute();
            $user_id = (int)$conn->insert_id;
            $stmt->close();
        }
    }

    // ── Generate unique booking code ─────────────────────────────────────────
    do {
        $code = 'ZGH-' . date('Ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 4));
        $stmt = $conn->prepare("SELECT id FROM bookings WHERE booking_code = ?");
        $stmt->bind_param('s', $code);
        $stmt->execute();
        $exists = $stmt->get_result()->num_rows > 0;
        $stmt->close();
    } while ($exists);

    // ── Calculate totals ─────────────────────────────────────────────────────
    $nights       = (int)((strtotime($check_out) - strtotime($check_in)) / 86400);
    $total_amount = $nights * (float)$room['price_per_night'];
    $deposit      = round($total_amount * 0.5, 2);

    // ── Insert booking ───────────────────────────────────────────────────────
    $stmt = $conn->prepare(
        "INSERT INTO bookings
            (booking_code, user_id, room_id, check_in, check_out,
             total_nights, total_amount, deposit_amount, status, notes)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', ?)"
    );
    $stmt->bind_param(
        'siissidds',
        $code, $user_id, $room_id, $check_in, $check_out,
        $nights, $total_amount, $deposit, $notes
    );
    $stmt->execute();
    $stmt->close();

    $conn->commit();

    // Store confirmation data in session (avoid passing sensitive data in URL)
    $_SESSION['booking_confirmed'] = [
        'code'       => $code,
        'room_name'  => $room['name'],
        'check_in'   => $check_in,
        'check_out'  => $check_out,
        'nights'     => $nights,
        'total'      => $total_amount,
        'deposit'    => $deposit,
        'full_name'  => $full_name,
        'phone'      => $phone,
        'email'      => $email,
        'pay_method' => $pay_method,
    ];

    header('Location: confirmation.php');
    exit;

} catch (Exception $e) {
    $conn->rollback();
    error_log('Booking error: ' . $e->getMessage());
    $_SESSION['booking_errors'] = ['An unexpected error occurred. Please try again or contact us.'];
    $_SESSION['booking_old']    = $old;
    header('Location: book.php?room_id=' . $room_id
         . '&check_in='  . urlencode($check_in)
         . '&check_out=' . urlencode($check_out)
         . '&guests='    . $guests);
    exit;
}

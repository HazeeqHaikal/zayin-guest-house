<?php
include '../includes/config.php';

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

// ── Detect post_max_size overflow (happens before CSRF check) ─────────────────
// When the body exceeds post_max_size, PHP empties $_POST entirely,
// making CSRF appear to fail. Catch this first and redirect with a clear error.
function _parse_ini_bytes(string $val): int {
    $val  = trim($val);
    $last = strtolower($val[-1] ?? '');
    $num  = (int)$val;
    return match ($last) {
        'g' => $num * 1024 * 1024 * 1024,
        'm' => $num * 1024 * 1024,
        'k' => $num * 1024,
        default => $num,
    };
}
$content_length = (int)($_SERVER['CONTENT_LENGTH'] ?? 0);
$post_max       = _parse_ini_bytes(ini_get('post_max_size'));
if ($content_length > 0 && $post_max > 0 && $content_length > $post_max) {
    $limit = ini_get('post_max_size');
    $_SESSION['upload_error'] = "File too large. Maximum upload size is {$limit}B. Please compress or reduce your file and try again.";
    $back = $_SERVER['HTTP_REFERER'] ?? '../customer/my_bookings.php';
    header('Location: ' . $back);
    exit;
}

// ── CSRF verification ─────────────────────────────────────────────────────────
if (
    empty($_POST['csrf_token']) ||
    empty($_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {
    http_response_code(403);
    die('Invalid request. Please go back and try again.');
}
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// ── Validate booking code ─────────────────────────────────────────────────────
$booking_code = trim($_POST['booking_code'] ?? '');
if (!preg_match('/^[A-Z0-9\-]{5,20}$/', $booking_code)) {
    http_response_code(400);
    die('Invalid booking reference.');
}

if (!$conn) {
    http_response_code(500);
    die('Database unavailable. Please try again later.');
}

// ── Look up booking — must be pending with no receipt yet ─────────────────────
$stmt = $conn->prepare(
    "SELECT id FROM bookings WHERE booking_code = ? AND status = 'pending' AND receipt_path IS NULL"
);
$stmt->bind_param('s', $booking_code);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$row) {
    http_response_code(404);
    die('Booking not found, receipt already submitted, or booking is not pending.');
}
$booking_id = (int)$row['id'];

// ── File validation ───────────────────────────────────────────────────────────
if (empty($_FILES['receipt']) || $_FILES['receipt']['error'] !== UPLOAD_ERR_OK) {
    $upload_errors = [
        UPLOAD_ERR_INI_SIZE   => 'File exceeds server upload limit.',
        UPLOAD_ERR_FORM_SIZE  => 'File exceeds form size limit.',
        UPLOAD_ERR_PARTIAL    => 'File was only partially uploaded.',
        UPLOAD_ERR_NO_FILE    => 'No file was uploaded.',
    ];
    $code = $_FILES['receipt']['error'] ?? UPLOAD_ERR_NO_FILE;
    $msg  = $upload_errors[$code] ?? 'Upload error. Please try again.';
    http_response_code(400);
    die(htmlspecialchars($msg));
}

$file     = $_FILES['receipt'];
$max_size = 5 * 1024 * 1024; // 5 MB

if ($file['size'] > $max_size) {
    http_response_code(400);
    die('File too large. Maximum 5 MB allowed.');
}

// Validate MIME type from file content — not from client-supplied type
$allowed_types = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/webp' => 'webp',
    'application/pdf' => 'pdf',
];
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime  = $finfo->file($file['tmp_name']);

if (!array_key_exists($mime, $allowed_types)) {
    http_response_code(400);
    die('Invalid file type. Only JPG, PNG, WEBP, or PDF are accepted.');
}

$ext = $allowed_types[$mime];

// ── Save file ─────────────────────────────────────────────────────────────────
$upload_dir = '../uploads/receipts/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

$filename      = $booking_code . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
$dest          = $upload_dir . $filename;
$relative_path = 'uploads/receipts/' . $filename;

if (!move_uploaded_file($file['tmp_name'], $dest)) {
    http_response_code(500);
    die('Could not save file. Please try again.');
}

// ── Update database ───────────────────────────────────────────────────────────
$stmt = $conn->prepare(
    "UPDATE bookings SET receipt_path = ?, receipt_uploaded_at = NOW() WHERE id = ?"
);
$stmt->bind_param('si', $relative_path, $booking_id);
$stmt->execute();
$stmt->close();

// ── Success page ──────────────────────────────────────────────────────────────
$pageTitle = 'Receipt Received';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> — Zayin Guest House</title>
    <meta name="robots" content="noindex">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        boutique: {
                            50: '#f4f7f7', 100: '#e3ecec', 400: '#75a3a3',
                            600: '#2b7a78', 800: '#17252a', 900: '#0f171e',
                        }
                    },
                    fontFamily: {
                        serif: ['"Playfair Display"', 'serif'],
                        sans:  ['"Plus Jakarta Sans"', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; } h1, h2, h3 { font-family: 'Playfair Display', serif; }</style>
</head>
<body class="bg-boutique-50 text-slate-700 antialiased">

<?php include '../includes/header.php'; ?>

<main class="max-w-xl mx-auto px-4 py-16 text-center">

    <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-6">
        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
    </div>

    <h1 class="text-3xl font-serif text-boutique-800 mb-3">Receipt Received!</h1>
    <p class="text-slate-500 mb-2">
        Your payment receipt for booking
        <strong class="font-mono text-boutique-800"><?= htmlspecialchars($booking_code) ?></strong>
        has been uploaded successfully.
    </p>
    <p class="text-slate-500 mb-8">We will review your payment and confirm your booking within a few hours.</p>

    <a href="../index.php"
       class="inline-flex items-center gap-2 bg-boutique-800 hover:bg-boutique-600 text-white px-6 py-3 text-sm font-bold tracking-widest uppercase transition-colors">
        Back to Home
    </a>

</main>

<?php include '../includes/footer.php'; ?>
</body>
</html>

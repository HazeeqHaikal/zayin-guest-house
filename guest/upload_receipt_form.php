<?php
$basePath = '../';
include '../includes/config.php';

// Require login
if (empty($_SESSION['user_id'])) {
    $_SESSION['login_redirect'] = (defined('SITE_URL') ? SITE_URL : '') . '/customer/my_bookings.php';
    header('Location: ../auth/login.php');
    exit;
}

$user_id      = (int)$_SESSION['user_id'];
$booking_code = trim($_GET['code'] ?? '');
$booking      = null;
$error        = '';

if (!preg_match('/^[A-Z0-9\-]{5,20}$/', $booking_code)) {
    $error = 'Invalid booking reference.';
} elseif ($conn) {
    $stmt = $conn->prepare(
        "SELECT b.booking_code, b.check_in, b.check_out, b.total_nights,
                b.total_amount, b.deposit_amount, b.receipt_path,
                r.name AS room_name
         FROM bookings b
         JOIN rooms r ON r.id = b.room_id
         WHERE b.booking_code = ? AND b.user_id = ? AND b.status = 'pending'"
    );
    $stmt->bind_param('si', $booking_code, $user_id);
    $stmt->execute();
    $booking = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$booking) {
        $error = 'Booking not found or is no longer pending.';
    } elseif (!empty($booking['receipt_path'])) {
        // Receipt already uploaded — redirect to my bookings
        header('Location: my_bookings.php');
        exit;
    }
}

$pageTitle = 'Upload Payment Receipt';
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
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        h1, h2, h3 { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-boutique-50 text-slate-700 antialiased">

<?php include '../includes/header.php'; ?>

<main class="max-w-2xl mx-auto px-4 py-12 lg:py-16">

    <div class="mb-6">
        <a href="my_bookings.php" class="inline-flex items-center gap-1.5 text-xs text-slate-400 hover:text-boutique-600 font-semibold tracking-widest uppercase transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            My Bookings
        </a>
    </div>

    <h1 class="text-3xl font-serif text-boutique-800 mb-2">Upload Payment Receipt</h1>

    <?php if ($error): ?>
    <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 mb-6">
        <?= htmlspecialchars($error) ?>
    </div>

    <?php else: ?>
    <?php
    $upload_error = $_SESSION['upload_error'] ?? '';
    unset($_SESSION['upload_error']);
    ?>
    <?php if ($upload_error): ?>
    <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 mb-6">
        <?= htmlspecialchars($upload_error) ?>
    </div>
    <?php endif; ?>
    <!-- Booking summary -->
    <div class="bg-white border border-slate-100 shadow-sm p-5 mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-xs text-slate-400 font-semibold tracking-widest uppercase mb-1">Booking Reference</p>
                <p class="font-mono font-bold text-boutique-800 text-xl"><?= htmlspecialchars($booking['booking_code']) ?></p>
            </div>
            <span class="text-xs font-bold tracking-widest uppercase px-3 py-1 border bg-amber-50 text-amber-700 border-amber-200">PENDING</span>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm border-t border-slate-100 mt-4 pt-4">
            <div>
                <p class="text-xs text-slate-400 font-semibold tracking-widest uppercase mb-1">Room</p>
                <p class="font-medium text-boutique-800"><?= htmlspecialchars($booking['room_name']) ?></p>
            </div>
            <div>
                <p class="text-xs text-slate-400 font-semibold tracking-widest uppercase mb-1">Check-in</p>
                <p class="font-medium text-boutique-800"><?= date('d M Y', strtotime($booking['check_in'])) ?></p>
            </div>
            <div>
                <p class="text-xs text-slate-400 font-semibold tracking-widest uppercase mb-1">Deposit Due</p>
                <p class="font-semibold text-boutique-600 text-lg">RM <?= number_format($booking['deposit_amount'], 2) ?></p>
            </div>
        </div>
    </div>

    <!-- Upload form -->
    <div class="bg-white border border-slate-100 shadow-sm p-6">
        <p class="text-sm text-slate-500 mb-5">
            After completing your DuitNow transfer, upload the payment screenshot or PDF here.
            Accepted: JPG, PNG, WEBP, PDF &mdash; max 5 MB.
        </p>

        <form method="POST" action="../guest/upload_receipt.php" enctype="multipart/form-data" class="space-y-5">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
            <input type="hidden" name="booking_code" value="<?= htmlspecialchars($booking['booking_code']) ?>">

            <label class="block">
                <span class="block text-xs font-semibold text-slate-600 tracking-widest uppercase mb-2">Receipt File</span>
                <input type="file" name="receipt" accept="image/jpeg,image/png,image/webp,application/pdf" required
                       class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:border file:border-slate-300 file:text-sm file:font-semibold file:text-boutique-800 file:bg-boutique-50 hover:file:bg-boutique-100 cursor-pointer">
            </label>

            <button type="submit"
                    class="w-full flex items-center justify-center gap-2 bg-boutique-800 hover:bg-boutique-600 text-white py-3 text-sm font-bold tracking-widest uppercase transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                Submit Receipt
            </button>
        </form>
    </div>
    <?php endif; ?>

</main>

<?php include '../includes/footer.php'; ?>
</body>
</html>

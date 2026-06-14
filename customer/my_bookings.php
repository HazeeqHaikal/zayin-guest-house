<?php
$basePath = '../';
include '../includes/config.php';

// Require login
if (empty($_SESSION['user_id'])) {
    $_SESSION['login_redirect'] = (defined('SITE_URL') ? SITE_URL : '') . '/customer/my_bookings.php';
    header('Location: ../auth/login.php');
    exit;
}

$user_id   = (int)$_SESSION['user_id'];
$bookings  = [];

if ($conn) {
    $stmt = $conn->prepare(
        "SELECT b.booking_code, b.check_in, b.check_out, b.total_nights,
                b.total_amount, b.deposit_amount, b.status, b.created_at,
                r.name AS room_name, r.room_type
         FROM bookings b
         JOIN rooms r ON r.id = b.room_id
         WHERE b.user_id = ?
         ORDER BY b.created_at DESC"
    );
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
    $stmt->close();
}

$statusColors = [
    'pending'   => 'bg-amber-50 text-amber-700 border-amber-200',
    'confirmed' => 'bg-green-50 text-green-700 border-green-200',
    'completed' => 'bg-slate-50 text-slate-500 border-slate-200',
    'cancelled' => 'bg-red-50 text-red-600 border-red-200',
];

$pageTitle = 'My Bookings';
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
        h1, h2, h3, .font-serif { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-boutique-50 text-slate-700 antialiased">

<?php include '../includes/header.php'; ?>

<main class="max-w-4xl mx-auto px-4 lg:px-8 py-10 lg:py-14">

    <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-serif text-boutique-800 mb-1">My Bookings</h1>
            <p class="text-sm text-slate-500">Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?></p>
        </div>
        <a href="../index.php#booking-widget"
           class="bg-boutique-800 hover:bg-boutique-900 text-white px-6 py-3 text-xs font-bold tracking-widest uppercase transition-colors">
            + New Booking
        </a>
    </div>

    <?php if (empty($bookings)): ?>
    <div class="text-center py-20 bg-white border border-slate-100 shadow-sm">
        <svg class="w-14 h-14 text-slate-200 mx-auto mb-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        <h3 class="text-xl font-serif text-boutique-800 mb-2">No bookings yet</h3>
        <p class="text-slate-500 text-sm mb-6 max-w-xs mx-auto">
            Your booking history will appear here once you make a reservation.
        </p>
        <a href="../index.php#booking-widget"
           class="inline-block bg-boutique-800 hover:bg-boutique-900 text-white px-8 py-3 text-xs font-bold tracking-widest uppercase transition-colors">
            Browse Rooms
        </a>
    </div>

    <?php else: ?>
    <div class="space-y-4">
        <?php foreach ($bookings as $b):
            $colorClass = $statusColors[$b['status']] ?? 'bg-slate-50 text-slate-500 border-slate-200';
        ?>
        <div class="bg-white border border-slate-100 shadow-sm p-6">
            <div class="flex flex-wrap items-start justify-between gap-4 mb-4">
                <div>
                    <p class="text-xs text-slate-400 font-semibold tracking-widest uppercase mb-1">Booking Code</p>
                    <p class="font-mono font-bold text-boutique-800 text-lg"><?= htmlspecialchars($b['booking_code']) ?></p>
                </div>
                <span class="text-xs font-bold tracking-widest uppercase px-3 py-1 border <?= $colorClass ?>">
                    <?= ucfirst(htmlspecialchars($b['status'])) ?>
                </span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm border-t border-slate-100 pt-4">
                <div>
                    <p class="text-xs text-slate-400 font-semibold tracking-widest uppercase mb-1">Room</p>
                    <p class="font-medium text-boutique-800"><?= htmlspecialchars($b['room_name']) ?></p>
                    <p class="text-xs text-slate-400 capitalize"><?= htmlspecialchars($b['room_type']) ?></p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 font-semibold tracking-widest uppercase mb-1">Check-in</p>
                    <p class="font-medium text-boutique-800"><?= date('d M Y', strtotime($b['check_in'])) ?></p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 font-semibold tracking-widest uppercase mb-1">Check-out</p>
                    <p class="font-medium text-boutique-800"><?= date('d M Y', strtotime($b['check_out'])) ?></p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 font-semibold tracking-widest uppercase mb-1">Total</p>
                    <p class="font-semibold text-boutique-800">RM <?= number_format($b['total_amount'], 2) ?></p>
                    <p class="text-xs text-slate-400">
                        Deposit: RM <?= number_format($b['deposit_amount'], 2) ?>
                    </p>
                </div>
            </div>

            <p class="text-xs text-slate-400 mt-4">
                Booked on <?= date('d M Y, g:i A', strtotime($b['created_at'])) ?>
                &bull; <?= (int)$b['total_nights'] ?> night<?= $b['total_nights'] > 1 ? 's' : '' ?>
            </p>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

</main>

<?php include '../includes/footer.php'; ?>

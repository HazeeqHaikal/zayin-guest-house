<?php
$basePath = '../';
include '../includes/config.php';

// ── Guard: must come via process_booking.php ─────────────────────────────────
if (empty($_SESSION['booking_confirmed'])) {
    header('Location: ../index.php#booking-widget');
    exit;
}

$b = $_SESSION['booking_confirmed'];
unset($_SESSION['booking_confirmed']); // one-time display

$pageTitle = 'Booking Confirmed';
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
        @media print {
            .no-print { display: none !important; }
            .print-only { display: block !important; }
            body { background: white; }
        }
    </style>
</head>
<body class="bg-boutique-50 text-slate-700 antialiased">

<?php include '../includes/header.php'; ?>

<main class="max-w-3xl mx-auto px-4 py-12 lg:py-16">

    <!-- Success Banner -->
    <div class="flex flex-col items-center text-center mb-10">
        <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <h1 class="text-3xl lg:text-4xl font-serif text-boutique-800 mb-2">Booking Received!</h1>
        <p class="text-slate-500 max-w-sm">
            Your booking is now <strong class="text-amber-600">Pending</strong>. 
            Please complete the deposit payment below to confirm your room.
        </p>
    </div>

    <!-- Booking Reference Card -->
    <div class="bg-white border border-slate-100 shadow-sm overflow-hidden mb-6">
        <div class="bg-boutique-800 px-6 py-5 flex items-center justify-between">
            <div>
                <p class="text-boutique-400 text-xs font-bold tracking-widest uppercase mb-1">Booking Reference</p>
                <p class="text-white text-2xl font-bold tracking-widest font-mono"><?= htmlspecialchars($b['code']) ?></p>
            </div>
            <span class="bg-amber-400/20 text-amber-300 text-xs font-bold tracking-widest uppercase px-3 py-1.5 border border-amber-400/30">
                PENDING
            </span>
        </div>

        <div class="p-6 grid grid-cols-2 sm:grid-cols-3 gap-5 text-sm border-b border-slate-100">
            <div>
                <p class="text-xs tracking-wider uppercase text-slate-400 mb-1">Room</p>
                <p class="font-semibold text-boutique-800"><?= htmlspecialchars($b['room_name']) ?></p>
            </div>
            <div>
                <p class="text-xs tracking-wider uppercase text-slate-400 mb-1">Check-in</p>
                <p class="font-semibold text-boutique-800"><?= date('d M Y', strtotime($b['check_in'])) ?></p>
                <p class="text-xs text-slate-400">from 3:00 PM</p>
            </div>
            <div>
                <p class="text-xs tracking-wider uppercase text-slate-400 mb-1">Check-out</p>
                <p class="font-semibold text-boutique-800"><?= date('d M Y', strtotime($b['check_out'])) ?></p>
                <p class="text-xs text-slate-400">by 12:00 PM</p>
            </div>
            <div>
                <p class="text-xs tracking-wider uppercase text-slate-400 mb-1">Duration</p>
                <p class="font-semibold text-boutique-800"><?= (int)$b['nights'] ?> night<?= $b['nights'] > 1 ? 's' : '' ?></p>
            </div>
            <div>
                <p class="text-xs tracking-wider uppercase text-slate-400 mb-1">Guest</p>
                <p class="font-semibold text-boutique-800"><?= htmlspecialchars($b['full_name']) ?></p>
            </div>
            <div>
                <p class="text-xs tracking-wider uppercase text-slate-400 mb-1">Contact</p>
                <p class="font-semibold text-boutique-800"><?= htmlspecialchars($b['phone']) ?></p>
            </div>
        </div>

        <div class="p-6 bg-boutique-50">
            <div class="flex justify-between text-sm mb-2">
                <span class="text-slate-500">Total Stay</span>
                <span class="font-semibold text-boutique-800">RM <?= number_format((float)$b['total'], 2) ?></span>
            </div>
            <div class="flex justify-between text-sm font-bold border-t border-boutique-100 pt-2">
                <span class="text-boutique-800">Deposit Required (50%)</span>
                <span class="text-boutique-600 text-lg">RM <?= number_format((float)$b['deposit'], 2) ?></span>
            </div>
            <p class="text-xs text-slate-400 mt-1">Remaining RM <?= number_format((float)$b['total'] - (float)$b['deposit'], 2) ?> payable at check-in.</p>
        </div>
    </div>

    <!-- DuitNow QR Payment Section -->
    <div class="bg-white border border-slate-100 shadow-sm p-6 mb-6">
        <h2 class="text-lg font-serif text-boutique-800 mb-4 pb-3 border-b border-slate-100 flex items-center gap-2">
            <svg class="w-5 h-5 text-[#ED1C24]" viewBox="0 0 24 24" fill="currentColor">
                <path d="M3 4a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 9a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H4a1 1 0 01-1-1v-4zm9-9a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V4zm0 9a1 1 0 011-1h1v1h-1v1a1 1 0 01-1-1v-1zm3 0v1h1v-1h-1zm0 2v1h-1v1h2v-2h-1zm2 0h-1v1h1v1h1v-2h-1zm-3 2h-1v1h1v-1zm2 0v1h1v-1h-1z"/>
            </svg>
            Pay Deposit via DuitNow QR
        </h2>

        <div class="flex flex-col sm:flex-row gap-8 items-start">

            <!-- QR Code -->
            <div class="shrink-0 flex flex-col items-center gap-3">
                <div class="w-44 h-44 border-2 border-slate-200 overflow-hidden bg-white flex items-center justify-center relative" id="qrWrapper">
                    <img src="../assets/duitnow-qr.png"
                         alt="DuitNow QR Code"
                         class="w-full h-full object-contain"
                         id="qrImg"
                         onerror="document.getElementById('qrWrapper').innerHTML = document.getElementById('qrPlaceholder').innerHTML">
                </div>
                <!-- Placeholder shown via JS if image missing -->
                <template id="qrPlaceholder">
                    <div class="flex flex-col items-center justify-center w-full h-full gap-2 p-4 text-center">
                        <svg class="w-10 h-10 text-slate-300" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M3 4a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 9a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H4a1 1 0 01-1-1v-4zm9-9a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V4zm5 9a1 1 0 011-1h1v2h-2v-1zm-4 0h1v1h-1v-1zm0 2h1v1h1v-1h-1v-1h-1v2zm4 0v-1h-1v1h-1v1h2v-1zm-2 2v-1h-1v1h1zm2 0h-1v1h1v-1zm2-4h-1v1h1v-1zm-4 4h-1v1h1v-1z"/>
                        </svg>
                        <p class="text-[10px] text-slate-400 leading-tight">QR code pending<br>admin upload</p>
                    </div>
                </template>
                <p class="text-xs text-slate-500 font-semibold">Scan with any banking app</p>
                <span class="text-xs bg-[#ED1C24]/10 text-[#ED1C24] font-bold px-3 py-1 tracking-widest uppercase">DuitNow QR</span>
            </div>

            <!-- Instructions -->
            <div class="flex-1">
                <p class="text-sm font-semibold text-boutique-800 mb-1">
                    Pay exactly <span class="text-boutique-600">RM <?= number_format((float)$b['deposit'], 2) ?></span>
                </p>
                <p class="text-xs text-slate-400 mb-4">Deposit (50% of total stay). Balance payable at check-in.</p>

                <ol class="space-y-3 text-sm text-slate-600">
                    <li class="flex gap-3">
                        <span class="shrink-0 w-6 h-6 rounded-full bg-boutique-800 text-white text-xs font-bold flex items-center justify-center">1</span>
                        <span>Open your banking app (Maybank, CIMB, RHB, etc.) and tap <strong class="text-boutique-800">Scan QR</strong>.</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="shrink-0 w-6 h-6 rounded-full bg-boutique-800 text-white text-xs font-bold flex items-center justify-center">2</span>
                        <span>Scan the QR code above and enter the exact amount: <strong class="text-boutique-800">RM <?= number_format((float)$b['deposit'], 2) ?></strong>.</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="shrink-0 w-6 h-6 rounded-full bg-boutique-800 text-white text-xs font-bold flex items-center justify-center">3</span>
                        <span>Use <strong class="text-boutique-800 font-mono"><?= htmlspecialchars($b['code']) ?></strong> as the payment reference / description.</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="shrink-0 w-6 h-6 rounded-full bg-boutique-800 text-white text-xs font-bold flex items-center justify-center">4</span>
                        <span>Screenshot or save your payment receipt, then <strong class="text-boutique-800">upload it below</strong>. We'll confirm your booking within a few hours.</span>
                    </li>
                </ol>

                <!-- 24-hour deadline warning -->
                <div class="mt-4 flex items-start gap-3 bg-amber-50 border border-amber-200 px-4 py-3 text-xs text-amber-800">
                    <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Complete payment within <strong>24 hours</strong> or your booking will be automatically cancelled.</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Receipt Upload Section -->
    <div class="bg-white border border-slate-100 shadow-sm p-6 mb-6 no-print" id="uploadSection">
        <h2 class="text-lg font-serif text-boutique-800 mb-4 pb-3 border-b border-slate-100 flex items-center gap-2">
            <svg class="w-5 h-5 text-boutique-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
            </svg>
            Upload Payment Receipt
        </h2>

        <p class="text-sm text-slate-500 mb-4">Upload a screenshot or PDF of your payment confirmation. Accepted: JPG, PNG, WEBP, PDF — max 5 MB.</p>

        <?php
        $upload_error = $_SESSION['upload_error'] ?? '';
        unset($_SESSION['upload_error']);
        if ($upload_error): ?>
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 mb-4">
            <?= htmlspecialchars($upload_error) ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="upload_receipt.php" enctype="multipart/form-data" class="space-y-4">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
            <input type="hidden" name="booking_code" value="<?= htmlspecialchars($b['code']) ?>">

            <label class="block">
                <span class="sr-only">Choose receipt file</span>
                <input type="file" name="receipt" accept="image/jpeg,image/png,image/webp,application/pdf" required
                       class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:border file:border-slate-300 file:text-sm file:font-semibold file:text-boutique-800 file:bg-boutique-50 hover:file:bg-boutique-100 cursor-pointer">
            </label>

            <button type="submit"
                    class="flex items-center justify-center gap-2 bg-boutique-800 hover:bg-boutique-600 text-white px-6 py-3 text-sm font-bold tracking-widest uppercase transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                Submit Receipt
            </button>
        </form>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row gap-3 no-print">
        <button onclick="window.print()"
                class="flex-1 flex items-center justify-center gap-2 border border-slate-200 hover:border-slate-400 text-slate-600 py-4 text-sm font-bold tracking-widest uppercase transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Print / Save PDF
        </button>
    </div>

    <p class="text-center text-xs text-slate-400 mt-6">
        Questions? Email us at
        <a href="mailto:zayinguesthouse@gmail.com" class="text-boutique-600 hover:underline">zayinguesthouse@gmail.com</a>
        or WhatsApp us directly.
    </p>

</main>

<?php include '../includes/footer.php'; ?>
</body>
</html>

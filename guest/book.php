<?php
$basePath = '../';
include '../includes/config.php';

// ── Input sanitization ───────────────────────────────────────────────────────
$room_id   = (int)($_GET['room_id']   ?? 0);
$check_in  = trim($_GET['check_in']   ?? '');
$check_out = trim($_GET['check_out']  ?? '');
$guests    = max(1, min(10, (int)($_GET['guests'] ?? 1)));
$today     = date('Y-m-d');

// ── Step detection ───────────────────────────────────────────────────────────
// Step 1 — room_id only (came from homepage room card, no dates yet)
// Step 2 — room_id + dates (came from search results, show full booking form)
$has_dates = (
    preg_match('/^\d{4}-\d{2}-\d{2}$/', $check_in)  &&
    preg_match('/^\d{4}-\d{2}-\d{2}$/', $check_out) &&
    $check_in  >= $today &&
    $check_out >  $check_in
);

if (!$room_id) {
    header('Location: ../index.php#booking-widget');
    exit;
}

// ── Fetch room ───────────────────────────────────────────────────────────────
$room = null;
if ($conn) {
    $stmt = $conn->prepare("SELECT * FROM rooms WHERE id = ? AND is_active = 1");
    $stmt->bind_param('i', $room_id);
    $stmt->execute();
    $room = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

if (!$room) {
    header('Location: search.php');
    exit;
}

// ── If dates provided, verify room is still available ────────────────────────
$still_available = true;
if ($has_dates && $conn) {
    $stmt = $conn->prepare(
        "SELECT COUNT(*) AS cnt FROM bookings
         WHERE room_id = ? AND status NOT IN ('cancelled')
           AND check_in < ? AND check_out > ?"
    );
    $stmt->bind_param('iss', $room_id, $check_out, $check_in);
    $stmt->execute();
    $cnt = (int)$stmt->get_result()->fetch_assoc()['cnt'];
    $stmt->close();
    $still_available = ($cnt === 0);
}

if ($has_dates && !$still_available) {
    header('Location: search.php?check_in=' . urlencode($check_in)
         . '&check_out=' . urlencode($check_out)
         . '&guests='    . $guests
         . '&unavailable=1');
    exit;
}

// ── Pricing ──────────────────────────────────────────────────────────────────
$nights  = $has_dates ? (int)((strtotime($check_out) - strtotime($check_in)) / 86400) : 0;
$total   = $nights * (float)$room['price_per_night'];
$deposit = round($total * 0.5, 2); // 50% deposit to confirm

// ── CSRF token ───────────────────────────────────────────────────────────────
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

// ── Flash errors from failed submission ──────────────────────────────────────
$errors = $_SESSION['booking_errors'] ?? [];
$old    = $_SESSION['booking_old']    ?? [];
unset($_SESSION['booking_errors'], $_SESSION['booking_old']);

// ── Pre-fill from logged-in customer session (only when no $old override) ────
if (!empty($_SESSION['user_id']) && empty($old)) {
    $old['full_name'] = $_SESSION['user_name']  ?? '';
    $old['phone']     = $_SESSION['user_phone'] ?? '';
    $old['email']     = $_SESSION['user_email'] ?? '';
}

$pageTitle = $has_dates ? 'Complete Your Booking' : 'Select Dates';
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
        body  { font-family: 'Plus Jakarta Sans', sans-serif; scroll-behavior: smooth; }
        h1, h2, h3, .font-serif { font-family: 'Playfair Display', serif; }
        .field-error { border-color: #ef4444 !important; }
    </style>
</head>
<body class="bg-boutique-50 text-slate-700 antialiased">

<?php include '../includes/header.php'; ?>

<main class="max-w-6xl mx-auto px-4 lg:px-8 py-10 lg:py-14">

    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-xs font-bold tracking-widest uppercase text-slate-400 mb-8">
        <a href="../index.php#booking-widget" class="hover:text-boutique-600 transition-colors">Home</a>
        <span>/</span>
        <a href="search.php?check_in=<?= urlencode($check_in) ?>&check_out=<?= urlencode($check_out) ?>&guests=<?= $guests ?>"
           class="hover:text-boutique-600 transition-colors">Available Rooms</a>
        <span>/</span>
        <span class="text-boutique-800"><?= htmlspecialchars($room['name']) ?></span>
    </div>

    <!-- Validation errors -->
    <?php if (!empty($errors)): ?>
    <div class="bg-red-50 border border-red-200 text-red-800 px-5 py-4 text-sm mb-8">
        <p class="font-semibold mb-2">Please fix the following before continuing:</p>
        <ul class="list-disc list-inside space-y-1">
            <?php foreach ($errors as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <!-- ================================================================
         STEP 1 — No dates: show date picker for this specific room
    ================================================================ -->
    <?php if (!$has_dates): ?>
    <div class="max-w-xl mx-auto">
        <h1 class="text-3xl font-serif text-boutique-800 mb-2"><?= htmlspecialchars($room['name']) ?></h1>
        <p class="text-slate-500 mb-8 text-sm">Select your dates to see pricing and availability for this room.</p>

        <!-- Room thumbnail -->
        <div class="aspect-[16/7] overflow-hidden mb-8 relative bg-boutique-100">
            <?php if (!empty($room['image']) && file_exists(__DIR__ . '/../' . $room['image'])): ?>
            <img src="../<?= htmlspecialchars($room['image']) ?>"
                 alt="<?= htmlspecialchars($room['name']) ?>"
                 class="w-full h-full object-cover">
            <?php else: ?>
            <div class="absolute inset-0 flex flex-col items-center justify-center gap-2">
                <svg class="w-10 h-10 text-boutique-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <p class="text-xs tracking-widest uppercase font-semibold text-boutique-300">Photo Coming Soon</p>
            </div>
            <?php endif; ?>
        </div>

        <form method="GET" action="" class="bg-white border border-slate-100 shadow-sm p-6 space-y-4">
            <input type="hidden" name="room_id" value="<?= $room_id ?>">

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold tracking-widest uppercase text-slate-400 mb-2">Check-in</label>
                    <input type="date" name="check_in" required min="<?= $today ?>"
                           class="w-full border border-slate-200 px-4 py-3 text-boutique-800 font-medium focus:outline-none focus:border-boutique-600 text-sm"
                           id="step1CheckIn">
                </div>
                <div>
                    <label class="block text-xs font-bold tracking-widest uppercase text-slate-400 mb-2">Check-out</label>
                    <input type="date" name="check_out" required min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                           class="w-full border border-slate-200 px-4 py-3 text-boutique-800 font-medium focus:outline-none focus:border-boutique-600 text-sm"
                           id="step1CheckOut">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold tracking-widest uppercase text-slate-400 mb-2">Guests</label>
                <select name="guests"
                        class="w-full border border-slate-200 px-4 py-3 text-boutique-800 font-medium focus:outline-none focus:border-boutique-600 text-sm bg-white">
                    <?php for ($i = 1; $i <= min((int)$room['capacity'], 5); $i++): ?>
                        <option value="<?= $i ?>" <?= $guests === $i ? 'selected' : '' ?>><?= $i ?> Guest<?= $i > 1 ? 's' : '' ?></option>
                    <?php endfor; ?>
                </select>
                <p class="text-xs text-slate-400 mt-1">Max capacity: <?= (int)$room['capacity'] ?> guests</p>
            </div>
            <button type="submit"
                    class="w-full bg-boutique-800 hover:bg-boutique-900 text-white py-4 text-sm font-bold tracking-widest uppercase transition-colors">
                Continue to Booking
            </button>
        </form>
    </div>

    <script>
        (function () {
            var ci = document.getElementById('step1CheckIn');
            var co = document.getElementById('step1CheckOut');
            if (!ci || !co) return;
            ci.addEventListener('change', function () {
                if (!ci.value) return;
                var nd = new Date(ci.value);
                nd.setDate(nd.getDate() + 1);
                co.min = nd.toISOString().split('T')[0];
                if (co.value && co.value <= ci.value) co.value = co.min;
                co.focus();
            });
        })();
    </script>

    <?php else: ?>
    <!-- ================================================================
         STEP 2 — Dates selected: full booking form + sticky summary
    ================================================================ -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 lg:gap-14 items-start">

        <!-- ── Left: Guest Details Form ─────────────────────────────── -->
        <div class="lg:col-span-2">
            <h1 class="text-3xl font-serif text-boutique-800 mb-2">Complete Your Booking</h1>
            <p class="text-slate-500 mb-8 text-sm">Fill in your details below. We'll hold your room upon confirmed payment.</p>

            <form action="process_booking.php" method="POST" novalidate>
                <input type="hidden" name="csrf_token"  value="<?= htmlspecialchars($csrf_token) ?>">
                <input type="hidden" name="room_id"     value="<?= $room_id ?>">
                <input type="hidden" name="check_in"    value="<?= htmlspecialchars($check_in) ?>">
                <input type="hidden" name="check_out"   value="<?= htmlspecialchars($check_out) ?>">
                <input type="hidden" name="guests"      value="<?= $guests ?>">

                <!-- Guest Information -->
                <div class="bg-white border border-slate-100 shadow-sm p-6 mb-6">
                    <h2 class="text-lg font-serif text-boutique-800 mb-5 pb-4 border-b border-slate-100">Your Details</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <!-- Full Name -->
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold tracking-widest uppercase text-slate-500 mb-2" for="full_name">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="full_name" name="full_name" required autocomplete="name"
                                   value="<?= htmlspecialchars($old['full_name'] ?? '') ?>"
                                   placeholder="As per IC / Passport"
                                   class="w-full border border-slate-200 px-4 py-3 text-boutique-800 text-sm focus:outline-none focus:border-boutique-600 <?= in_array('full_name', array_column($errors, 0) ?? []) ? 'field-error' : '' ?>">
                        </div>

                        <!-- IC / Passport -->
                        <div>
                            <label class="block text-xs font-bold tracking-widest uppercase text-slate-500 mb-2" for="ic_number">
                                IC / Passport No. <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="ic_number" name="ic_number" required autocomplete="off"
                                   value="<?= htmlspecialchars($old['ic_number'] ?? '') ?>"
                                   placeholder="e.g. 900101-01-1234"
                                   class="w-full border border-slate-200 px-4 py-3 text-boutique-800 text-sm focus:outline-none focus:border-boutique-600">
                        </div>

                        <!-- Phone -->
                        <div>
                            <label class="block text-xs font-bold tracking-widest uppercase text-slate-500 mb-2" for="phone">
                                Phone Number <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" id="phone" name="phone" required autocomplete="tel"
                                   value="<?= htmlspecialchars($old['phone'] ?? '') ?>"
                                   placeholder="e.g. 0123456789"
                                   class="w-full border border-slate-200 px-4 py-3 text-boutique-800 text-sm focus:outline-none focus:border-boutique-600">
                        </div>

                        <!-- Email -->
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold tracking-widest uppercase text-slate-500 mb-2" for="email">
                                Email Address <span class="text-slate-400 font-normal normal-case text-xs">(optional, for confirmation)</span>
                            </label>
                            <input type="email" id="email" name="email" autocomplete="email"
                                   value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                                   placeholder="you@example.com"
                                   class="w-full border border-slate-200 px-4 py-3 text-boutique-800 text-sm focus:outline-none focus:border-boutique-600">
                        </div>

                        <!-- Special Requests -->
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold tracking-widest uppercase text-slate-500 mb-2" for="notes">
                                Special Requests <span class="text-slate-400 font-normal normal-case text-xs">(optional)</span>
                            </label>
                            <textarea id="notes" name="notes" rows="3"
                                      placeholder="Early check-in, late check-out, dietary requirements, etc."
                                      class="w-full border border-slate-200 px-4 py-3 text-boutique-800 text-sm focus:outline-none focus:border-boutique-600 resize-none"><?= htmlspecialchars($old['notes'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="bg-white border border-slate-100 shadow-sm p-6 mb-6">
                    <h2 class="text-lg font-serif text-boutique-800 mb-5 pb-4 border-b border-slate-100">Payment</h2>

                    <div class="flex items-start gap-4">
                        <!-- DuitNow icon -->
                        <div class="shrink-0 w-12 h-12 bg-[#ED1C24]/10 rounded flex items-center justify-center">
                            <svg class="w-7 h-7 text-[#ED1C24]" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M3 4a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 9a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H4a1 1 0 01-1-1v-4zm9-9a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V4zm0 9a1 1 0 011-1h1v1h-1v1a1 1 0 01-1-1v-1zm3 0v1h1v-1h-1zm0 2v1h-1v1h2v-2h-1zm2 0h-1v1h1v1h1v-2h-1zm-3 2h-1v1h1v-1zm2 0v1h1v-1h-1z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-boutique-800 text-sm">DuitNow QR</p>
                            <p class="text-xs text-slate-500 leading-relaxed mt-1">
                                After confirming your booking, you'll be shown our DuitNow QR code to scan and pay.
                                A <strong class="text-boutique-800">50% deposit (RM <?= number_format($deposit, 2) ?>)</strong> is required
                                within <strong class="text-boutique-800">24 hours</strong> to secure your room.
                                The remaining balance is payable at check-in.
                            </p>
                        </div>
                    </div>
                    <input type="hidden" name="payment_method" value="duitnow_qr">
                </div>

                <!-- Terms -->
                <div class="bg-white border border-slate-100 shadow-sm p-6 mb-6">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="agree_terms" id="agreeTerms" required
                               class="mt-1 accent-boutique-600">
                        <span class="text-sm text-slate-600 leading-relaxed">
                            I agree to the
                            <a href="../index.php#rules" target="_blank" class="text-boutique-600 underline hover:text-boutique-800">House Policies</a>.
                            I understand a 50% deposit is required within 24 hours and the booking is
                            non-refundable within 48 hours of check-in.
                        </span>
                    </label>
                </div>

                <button type="submit" id="submitBtn"
                        class="w-full bg-boutique-800 hover:bg-boutique-900 text-white py-4 text-sm font-bold tracking-widest uppercase transition-colors flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Confirm Booking
                </button>
                <p class="text-center text-xs text-slate-400 mt-3">
                    Your booking will be <strong>Pending</strong> until deposit is received.
                </p>
            </form>
        </div>

        <!-- ── Right: Booking Summary (sticky) ──────────────────────── -->
        <div class="lg:sticky lg:top-28">
            <div class="bg-white border border-slate-100 shadow-sm overflow-hidden">
                <!-- Room image -->
                <div class="relative aspect-[16/9] overflow-hidden bg-boutique-100">
                    <?php if (!empty($room['image']) && file_exists(__DIR__ . '/../' . $room['image'])): ?>
                    <img src="../<?= htmlspecialchars($room['image']) ?>"
                         alt="<?= htmlspecialchars($room['name']) ?>"
                         class="absolute inset-0 w-full h-full object-cover">
                    <?php else: ?>
                    <div class="absolute inset-0 flex flex-col items-center justify-center gap-2">
                        <svg class="w-8 h-8 text-boutique-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p class="text-xs tracking-widest uppercase font-semibold text-boutique-300">Photo Coming Soon</p>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="p-6">
                    <span class="text-xs tracking-widest uppercase text-boutique-600 font-bold"><?= htmlspecialchars(ucfirst($room['room_type'])) ?></span>
                    <h3 class="text-xl font-serif text-boutique-800 mt-1 mb-4"><?= htmlspecialchars($room['name']) ?></h3>

                    <div class="border-t border-slate-100 pt-4 space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Check-in</span>
                            <span class="font-medium text-boutique-800"><?= date('d M Y', strtotime($check_in)) ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Check-out</span>
                            <span class="font-medium text-boutique-800"><?= date('d M Y', strtotime($check_out)) ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Duration</span>
                            <span class="font-medium text-boutique-800"><?= $nights ?> night<?= $nights > 1 ? 's' : '' ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Guests</span>
                            <span class="font-medium text-boutique-800"><?= $guests ?></span>
                        </div>
                    </div>

                    <div class="border-t border-slate-100 mt-4 pt-4 space-y-2 text-sm">
                        <div class="flex justify-between text-slate-500">
                            <span>RM <?= number_format($room['price_per_night'], 0) ?> &times; <?= $nights ?> night<?= $nights > 1 ? 's' : '' ?></span>
                            <span>RM <?= number_format($total, 2) ?></span>
                        </div>
                        <div class="flex justify-between font-bold text-boutique-800 text-base pt-1 border-t border-slate-100">
                            <span>Total</span>
                            <span>RM <?= number_format($total, 2) ?></span>
                        </div>
                        <div class="flex justify-between text-boutique-600 text-xs">
                            <span>Deposit required (50%)</span>
                            <span>RM <?= number_format($deposit, 2) ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Need help? -->
            <a href="https://wa.me/60XXXXXXXXXX?text=Hi%2C+I+need+help+booking+<?= urlencode($room['name']) ?>."
               target="_blank" rel="noopener"
               class="mt-4 flex items-center justify-center gap-2 text-xs text-slate-500 hover:text-boutique-600 transition-colors">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                Need help? Chat with us on WhatsApp
            </a>
        </div>

    </div><!-- end grid -->
    <?php endif; ?>

</main>

<?php include '../includes/footer.php'; ?>

<script>
    // Prevent double-submit
    document.getElementById('submitBtn') && document.getElementById('submitBtn').closest('form').addEventListener('submit', function () {
        var btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.textContent = 'Processing…';
    });
</script>
</body>
</html>

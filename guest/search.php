<?php
$pageTitle = 'Available Rooms';
$basePath  = '../';
include '../includes/config.php';

$check_in  = trim($_GET['check_in']  ?? '');
$check_out = trim($_GET['check_out'] ?? '');
$guests    = max(1, min(10, (int)($_GET['guests'] ?? 1)));
$today     = date('Y-m-d');
$tomorrow  = date('Y-m-d', strtotime('+1 day'));

$error    = '';
$rooms    = [];
$nights   = 0;
$searched = ($check_in !== '' || $check_out !== '');

if ($searched) {
    // Server-side validation
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $check_in) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $check_out)) {
        $error = 'Invalid date format. Please use the date picker.';
    } elseif ($check_in < $today) {
        $error = 'Check-in date cannot be in the past.';
    } elseif ($check_out <= $check_in) {
        $error = 'Check-out date must be after check-in.';
    } else {
        $nights = (int)((strtotime($check_out) - strtotime($check_in)) / 86400);

        if ($nights < 1) {
            $error = 'Minimum stay is 1 night.';
        } elseif (!$conn) {
            $error = 'Our booking system is temporarily unavailable. Please contact us via WhatsApp to check availability.';
        } else {
            // Check for full-house blocks covering any date in the range
            $stmt = $conn->prepare(
                "SELECT COUNT(*) AS cnt FROM blocked_dates
                 WHERE block_type = 'fullhouse' AND block_date >= ? AND block_date < ?"
            );
            $stmt->bind_param('ss', $check_in, $check_out);
            $stmt->execute();
            $blocked_count = (int)$stmt->get_result()->fetch_assoc()['cnt'];
            $stmt->close();

            if ($blocked_count > 0) {
                $error = 'The guest house is fully booked for those dates. Please try different dates or contact us directly.';
            } else {
                // Rooms available = active, enough capacity, not overlapping a confirmed/pending booking
                $stmt = $conn->prepare(
                    "SELECT * FROM rooms
                     WHERE is_active = 1
                       AND capacity >= ?
                       AND id NOT IN (
                           SELECT room_id FROM bookings
                           WHERE status NOT IN ('cancelled')
                             AND check_in  < ?
                             AND check_out > ?
                       )
                     ORDER BY price_per_night ASC"
                );
                $stmt->bind_param('iss', $guests, $check_out, $check_in);
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    $rooms[] = $row;
                }
                $stmt->close();
            }
        }
    }
}

// Flash message from a stale room (sent from book.php redirect)
$unavailable_flash = isset($_GET['unavailable']) ? 'That room was just booked. Here are other available rooms.' : '';
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
        body { font-family: 'Plus Jakarta Sans', sans-serif; scroll-behavior: smooth; }
        h1, h2, h3, .font-serif { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-boutique-50 text-slate-700 antialiased">

<?php include '../includes/header.php'; ?>

<main class="max-w-6xl mx-auto px-4 lg:px-8 py-10 lg:py-14">

    <!-- Breadcrumb -->
    <a href="../index.php#booking-widget"
       class="inline-flex items-center gap-2 text-xs font-bold tracking-widest uppercase text-boutique-600 hover:text-boutique-800 transition-colors mb-8">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Modify Search
    </a>

    <!-- Search Form (refinement bar) -->
    <div class="bg-white border border-slate-100 shadow-sm p-5 mb-8">
        <form method="GET" action="" id="searchForm"
              class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs font-bold tracking-widest uppercase text-slate-400 mb-2">Check-in</label>
                <input type="date" name="check_in" id="srchCheckIn"
                       value="<?= htmlspecialchars($check_in) ?>"
                       required min="<?= $today ?>"
                       class="w-full border border-slate-200 px-4 py-3 text-boutique-800 font-medium focus:outline-none focus:border-boutique-600 text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold tracking-widest uppercase text-slate-400 mb-2">Check-out</label>
                <input type="date" name="check_out" id="srchCheckOut"
                       value="<?= htmlspecialchars($check_out) ?>"
                       required min="<?= $tomorrow ?>"
                       class="w-full border border-slate-200 px-4 py-3 text-boutique-800 font-medium focus:outline-none focus:border-boutique-600 text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold tracking-widest uppercase text-slate-400 mb-2">Guests</label>
                <select name="guests"
                        class="w-full border border-slate-200 px-4 py-3 text-boutique-800 font-medium focus:outline-none focus:border-boutique-600 text-sm bg-white">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <option value="<?= $i ?>" <?= $guests === $i ? 'selected' : '' ?>>
                            <?= $i ?> Guest<?= $i > 1 ? 's' : '' ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div>
                <button type="submit"
                        class="w-full bg-boutique-800 hover:bg-boutique-900 text-white py-3 text-sm font-bold tracking-widest uppercase transition-colors">
                    Search
                </button>
            </div>
        </form>
    </div>

    <!-- Flash / Error Messages -->
    <?php if ($unavailable_flash): ?>
    <div class="bg-amber-50 border border-amber-200 text-amber-800 px-5 py-3 text-sm mb-6 flex items-start gap-3">
        <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <?= htmlspecialchars($unavailable_flash) ?>
    </div>
    <?php endif; ?>

    <?php if ($error): ?>
    <div class="bg-red-50 border border-red-200 text-red-800 px-5 py-3 text-sm mb-6 flex items-start gap-3">
        <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <?= htmlspecialchars($error) ?>
    </div>
    <?php elseif ($searched): ?>

        <!-- Results summary bar -->
        <div class="flex flex-wrap items-center gap-3 mb-6 pb-6 border-b border-slate-100">
            <p class="text-sm text-slate-500">
                <span class="font-semibold text-boutique-800"><?= date('D, d M Y', strtotime($check_in)) ?></span>
                <span class="mx-2 text-slate-300">→</span>
                <span class="font-semibold text-boutique-800"><?= date('D, d M Y', strtotime($check_out)) ?></span>
            </p>
            <span class="text-xs bg-boutique-100 text-boutique-600 font-semibold px-3 py-1">
                <?= $nights ?> night<?= $nights > 1 ? 's' : '' ?>
            </span>
            <span class="text-xs bg-slate-100 text-slate-600 font-semibold px-3 py-1">
                <?= $guests ?> guest<?= $guests > 1 ? 's' : '' ?>
            </span>
            <?php if (!empty($rooms)): ?>
            <span class="text-xs bg-green-50 text-green-700 font-semibold px-3 py-1">
                <?= count($rooms) ?> room<?= count($rooms) > 1 ? 's' : '' ?> available
            </span>
            <?php endif; ?>
        </div>

        <?php if (empty($rooms)): ?>
        <!-- No results -->
        <div class="text-center py-20">
            <svg class="w-14 h-14 text-slate-200 mx-auto mb-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                      d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="text-xl font-serif text-boutique-800 mb-2">No rooms available</h3>
            <p class="text-slate-500 mb-6 text-sm max-w-xs mx-auto">
                No rooms match your search for those dates. Try different dates or fewer guests.
            </p>
            <a href="https://wa.me/60XXXXXXXXXX?text=Hi%2C+I'm+looking+for+rooms+from+<?= urlencode($check_in) ?>+to+<?= urlencode($check_out) ?>+for+<?= $guests ?>+guest(s)."
               target="_blank" rel="noopener"
               class="inline-flex items-center gap-2 text-sm font-semibold text-boutique-600 hover:text-boutique-800 transition-colors">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                Contact us on WhatsApp for alternatives
            </a>
        </div>

        <?php else: ?>
        <!-- Room results -->
        <div class="space-y-5">
            <?php foreach ($rooms as $room):
                $room_total = $nights * $room['price_per_night'];
                $book_url   = 'book.php?room_id=' . $room['id']
                            . '&check_in='  . urlencode($check_in)
                            . '&check_out=' . urlencode($check_out)
                            . '&guests='    . $guests;
            ?>
            <div class="bg-white border border-slate-100 shadow-sm hover:shadow-md transition-shadow overflow-hidden flex flex-col md:flex-row">
                <!-- Image -->
                <div class="w-full md:w-64 lg:w-72 shrink-0 relative aspect-[4/3] md:aspect-auto overflow-hidden bg-boutique-100">
                    <?php if (!empty($room['image']) && file_exists(__DIR__ . '/../' . $room['image'])): ?>
                    <img src="../<?= htmlspecialchars($room['image']) ?>"
                         alt="<?= htmlspecialchars($room['name']) ?>"
                         class="absolute inset-0 w-full h-full object-cover">
                    <?php else: ?>
                    <div class="absolute inset-0 flex flex-col items-center justify-center gap-2">
                        <svg class="w-10 h-10 text-boutique-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p class="text-xs tracking-widest uppercase font-semibold text-boutique-300">Photo Coming Soon</p>
                    </div>
                    <?php endif; ?>
                    <div class="absolute top-3 left-3 bg-white/90 backdrop-blur px-2 py-0.5 text-xs tracking-widest uppercase font-semibold text-boutique-800">
                        <?= htmlspecialchars(ucfirst($room['room_type'])) ?>
                    </div>
                </div>

                <!-- Details + Price -->
                <div class="flex-1 p-5 lg:p-6 flex flex-col sm:flex-row gap-5">
                    <!-- Info -->
                    <div class="flex-1">
                        <h3 class="text-xl font-serif text-boutique-800 mb-2"><?= htmlspecialchars($room['name']) ?></h3>
                        <p class="text-sm text-slate-500 leading-relaxed mb-4"><?= htmlspecialchars($room['description']) ?></p>
                        <div class="flex flex-wrap gap-3 text-xs text-slate-500">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-boutique-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Up to <?= (int)$room['capacity'] ?> guest<?= $room['capacity'] > 1 ? 's' : '' ?>
                            </span>
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-boutique-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Free Wi-Fi, AC, Hot Shower
                            </span>
                        </div>
                    </div>

                    <!-- Pricing & CTA -->
                    <div class="sm:text-right flex sm:flex-col items-center sm:items-end justify-between sm:justify-between gap-4 shrink-0 pt-1">
                        <div>
                            <p class="text-xs text-slate-400 mb-0.5">
                                RM <?= number_format($room['price_per_night'], 0) ?> &times; <?= $nights ?> night<?= $nights > 1 ? 's' : '' ?>
                            </p>
                            <p class="text-2xl font-bold text-boutique-800 leading-none">
                                RM <?= number_format($room_total, 0) ?>
                            </p>
                            <p class="text-xs text-slate-400 mt-0.5">total stay</p>
                        </div>
                        <a href="<?= htmlspecialchars($book_url) ?>"
                           class="bg-boutique-800 hover:bg-boutique-900 text-white px-6 py-3 text-xs font-bold tracking-widest uppercase transition-colors whitespace-nowrap">
                            Book Now
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

    <?php elseif (!$searched): ?>
    <!-- Not searched yet — prompt -->
    <div class="text-center py-16 text-slate-400">
        <svg class="w-12 h-12 mx-auto mb-4 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        <p class="text-sm">Enter your check-in and check-out dates above to see available rooms.</p>
    </div>
    <?php endif; ?>

</main>

<?php include '../includes/footer.php'; ?>

<script>
    (function () {
        var ci = document.getElementById('srchCheckIn');
        var co = document.getElementById('srchCheckOut');
        if (!ci || !co) return;
        ci.addEventListener('change', function () {
            if (!ci.value) return;
            var nd = new Date(ci.value);
            nd.setDate(nd.getDate() + 1);
            var ndStr = nd.toISOString().split('T')[0];
            co.min = ndStr;
            if (co.value && co.value <= ci.value) co.value = ndStr;
        });
    })();
</script>
</body>
</html>

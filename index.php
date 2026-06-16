<?php
$pageTitle = 'Welcome';
$pageDesc  = 'Zayin Guest House — 8 beautifully designed rooms with a swimming pool in Jitra, Kedah. Perfect for families, travelers, and corporate guests. Book directly for the best rates.';
$basePath  = '';
include 'includes/config.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' — Zayin Guest House' : 'Zayin Guest House' ?></title>
    <meta name="description" content="<?= isset($pageDesc) ? htmlspecialchars($pageDesc) : 'Zayin Guest House — Comfortable and affordable rooms for families, travelers, and corporate guests.' ?>">

    <meta property="og:title" content="<?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' — Zayin Guest House' : 'Zayin Guest House' ?>">
    <meta property="og:description" content="Comfortable and affordable accommodation. Book directly with us for the best rates.">
    <meta property="og:type" content="website">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        boutique: {
                            50: '#f4f7f7',
                            100: '#e3ecec',
                            400: '#75a3a3',
                            600: '#2b7a78',
                            800: '#17252a',
                            900: '#0f171e',
                        }
                    },
                    fontFamily: {
                        serif: ['"Playfair Display"', 'serif'],
                        sans: ['"Plus Jakarta Sans"', 'sans-serif']
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
        .room-card .room-img-wrapper img { transition: transform 0.7s ease; }
        .room-card:hover .room-img-wrapper img { transform: scale(1.05); }
        input[type="date"] {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            max-width: 100%;
            box-sizing: border-box;
        }
        .facility-icon-wrap { transition: background-color 0.3s ease; }
        .facility-card:hover .facility-icon-wrap { background-color: #2b7a78; }
        .facility-card:hover .facility-icon-wrap svg { color: white; }
        .facility-icon-wrap svg { transition: color 0.3s ease; }
    </style>
</head>
<body class="bg-boutique-50 text-slate-700 antialiased selection:bg-boutique-600 selection:text-white overflow-x-hidden">

<?php include 'includes/header.php'; ?>

<?php if (!isset($conn) || !$conn): ?>
<div class="bg-red-50 border-b border-red-100 text-red-800 text-sm text-center px-4 py-3 font-medium">
    ⚠️ Database connection unavailable. Room availability is temporarily offline. Please contact us directly to book.
</div>
<?php endif; ?>

<main>

<!-- ============================================================
     HERO + BOOKING SEARCH WIDGET
============================================================ -->
<section id="booking-widget" class="relative flex flex-col items-center justify-center bg-boutique-900 overflow-hidden min-h-[90vh] py-20 pb-16 lg:py-24 scroll-mt-0">
    <img src="assets/banner.jpg" alt="Zayin Guest House"
         class="absolute inset-0 w-full h-full object-cover"
         onerror="this.src='https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80'">
    <div class="absolute inset-0 bg-gradient-to-b from-boutique-900/50 via-boutique-900/55 to-boutique-900/85"></div>

    <div class="relative z-10 text-center px-6 mb-10 lg:mb-12">
        <span class="tracking-[0.2em] text-boutique-400 text-xs font-bold uppercase mb-5 block">Welcome to</span>
        <h1 class="text-5xl lg:text-7xl font-serif text-white leading-[1.1] mb-5">
            Zayin <br><span class="italic text-boutique-400 font-light">Guest House</span>
        </h1>
        <p class="text-lg text-white/70 max-w-xl mx-auto leading-relaxed">
            8 beautifully designed rooms with a swimming pool in Jitra, Kedah. Book directly for the best rates — no fees, no middlemen.
        </p>
    </div>

    <!-- Widget wrapper: full section width, padding creates the side margins -->
    <div class="relative z-10 w-full px-4 sm:px-8 lg:px-12">
    <div class="max-w-5xl mx-auto">
        <form action="guest/search.php" method="GET" id="heroSearchForm"
              class="bg-white shadow-2xl flex flex-col sm:flex-row items-stretch overflow-hidden">
            <!-- Check-in -->
            <div class="flex-1 min-w-0 px-5 py-4 border-b border-slate-100 sm:border-b-0 sm:border-r">
                <label for="heroCheckIn" class="block text-xs font-bold tracking-widest uppercase text-slate-400 mb-1.5">Check-in</label>
                <input type="date" name="check_in" id="heroCheckIn" required
                       min="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d') ?>"
                       class="w-full border-0 p-0 text-boutique-800 font-medium focus:outline-none text-sm bg-transparent">
            </div>
            <!-- Check-out -->
            <div class="flex-1 min-w-0 px-5 py-4 border-b border-slate-100 sm:border-b-0 sm:border-r">
                <label for="heroCheckOut" class="block text-xs font-bold tracking-widest uppercase text-slate-400 mb-1.5">Check-out</label>
                <input type="date" name="check_out" id="heroCheckOut" required
                       min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                       class="w-full border-0 p-0 text-boutique-800 font-medium focus:outline-none text-sm bg-transparent">
            </div>
            <!-- Guests -->
            <div class="flex-1 min-w-0 px-5 py-4 border-b border-slate-100 sm:border-b-0 sm:border-r">
                <label for="heroGuests" class="block text-xs font-bold tracking-widest uppercase text-slate-400 mb-1.5">Guests</label>
                <select name="guests" id="heroGuests"
                        class="w-full border-0 p-0 text-boutique-800 font-medium focus:outline-none text-sm bg-transparent appearance-none">
                    <option value="1">1 Guest</option>
                    <option value="2" selected>2 Guests</option>
                    <option value="3">3 Guests</option>
                    <option value="4">4 Guests</option>
                    <option value="5">5 Guests</option>
                </select>
            </div>
            <!-- Submit -->
            <div class="shrink-0">
                <button type="submit"
                        class="w-full sm:w-auto h-full bg-boutique-600 hover:bg-boutique-800 text-white px-8 py-4 text-sm font-bold tracking-widest uppercase transition-colors flex items-center justify-center gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Check Availability
                </button>
            </div>
        </form>
        <p class="text-center text-white/40 text-xs mt-4 tracking-wide">
            Select your dates to see real-time availability and pricing
        </p>
    </div>
    </div>
</section>

<script>
    (function () {
        var ci = document.getElementById('heroCheckIn');
        var co = document.getElementById('heroCheckOut');
        if (!ci || !co) return;
        ci.addEventListener('change', function () {
            if (!ci.value) return;
            var nextDay = new Date(ci.value);
            nextDay.setDate(nextDay.getDate() + 1);
            var nd = nextDay.toISOString().split('T')[0];
            co.min = nd;
            if (co.value && co.value <= ci.value) { co.value = nd; }
            co.focus();
        });
    })();
</script>

<!-- ============================================================
     THE STORY
============================================================ -->
<section class="py-16 lg:py-20 px-6 lg:px-20 bg-boutique-800 text-boutique-100">
    <div class="max-w-7xl mx-auto">

        <!-- Two-column: About (left) + Room Concepts (right) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-start mb-12">

            <!-- Left: About Text + Stats -->
            <div>
                <h2 class="text-3xl lg:text-5xl font-serif text-white mb-6">A Home Away <br><span class="italic text-boutique-400">from Home</span></h2>
                <p class="text-base text-boutique-100 leading-relaxed mb-4 font-light">
                    Nestled in the peaceful surroundings of Tanjung Pauh, Jitra, Zayin Guest House welcomes you with 8 beautifully designed rooms across three unique concepts — Modern, Cabin, and Traditional.
                </p>
                <p class="text-base text-boutique-100 leading-relaxed font-light">
                    Take a dip in our swimming pool, gather in the open kitchen, or simply relax in our gazebo. Book directly with us to secure the best rates without hidden fees.
                </p>

                <!-- Quick Stats -->
                <div class="mt-8 grid grid-cols-3 gap-4 border-t border-boutique-600 pt-6">
                    <div>
                        <p class="text-3xl font-serif text-boutique-400 font-bold">8</p>
                        <p class="text-xs text-boutique-100/60 uppercase tracking-wider mt-1">Rooms</p>
                    </div>
                    <div>
                        <p class="text-3xl font-serif text-boutique-400 font-bold">3</p>
                        <p class="text-xs text-boutique-100/60 uppercase tracking-wider mt-1">Concepts</p>
                    </div>
                    <div>
                        <p class="text-3xl font-serif text-boutique-400 font-bold">32</p>
                        <p class="text-xs text-boutique-100/60 uppercase tracking-wider mt-1">Max Guests</p>
                    </div>
                </div>
            </div>

            <!-- Right: Room Concepts -->
            <div class="border-t border-boutique-600 pt-10 lg:border-t-0 lg:pt-0 lg:border-l lg:border-boutique-600 lg:pl-20">
                <span class="tracking-[0.2em] text-boutique-400 text-xs font-bold uppercase mb-8 block">Three Unique Concepts</span>
                <div class="space-y-8">
                    <div class="flex gap-5 items-start group">
                        <span class="shrink-0 text-4xl font-serif text-boutique-600 font-bold leading-none group-hover:text-boutique-400 transition-colors">01</span>
                        <div class="border-l border-boutique-600 pl-5">
                            <h3 class="text-white font-serif text-xl mb-1.5">Modern</h3>
                            <p class="text-sm text-boutique-100/60 font-light leading-relaxed">Sleek interiors with clean lines and contemporary furnishings — ideal for couples and business travelers seeking a refined stay.</p>
                        </div>
                    </div>
                    <div class="flex gap-5 items-start group">
                        <span class="shrink-0 text-4xl font-serif text-boutique-600 font-bold leading-none group-hover:text-boutique-400 transition-colors">02</span>
                        <div class="border-l border-boutique-600 pl-5">
                            <h3 class="text-white font-serif text-xl mb-1.5">Cabin</h3>
                            <p class="text-sm text-boutique-100/60 font-light leading-relaxed">Warm wooden tones and cozy textures inspired by nature — perfect for families who want a rustic retreat feel.</p>
                        </div>
                    </div>
                    <div class="flex gap-5 items-start group">
                        <span class="shrink-0 text-4xl font-serif text-boutique-600 font-bold leading-none group-hover:text-boutique-400 transition-colors">03</span>
                        <div class="border-l border-boutique-600 pl-5">
                            <h3 class="text-white font-serif text-xl mb-1.5">Traditional</h3>
                            <p class="text-sm text-boutique-100/60 font-light leading-relaxed">Rich cultural aesthetics with classic Malay-inspired touches — a soulful space that celebrates local heritage and warmth.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Full House Package — full-width strip below both columns -->
        <div class="border-t border-boutique-600 pt-10">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6 sm:gap-8">
                <div class="shrink-0 w-12 h-12 rounded-full border border-boutique-600 flex items-center justify-center text-boutique-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 22V12h6v10"/></svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-white font-serif text-xl mb-1">Full House Package — RM1,400 <span class="text-boutique-400 text-base font-sans font-light">/night</span></h3>
                    <p class="text-sm text-boutique-100 font-light">Book all 8 rooms for up to 32 guests. Pool, kitchen, and all facilities become exclusively yours. Perfect for family days, reunions &amp; celebrations.</p>
                </div>
                <div class="shrink-0">
                    <a href="guest/search.php" class="inline-flex items-center gap-2 border border-boutique-400 text-boutique-400 hover:bg-boutique-400 hover:text-boutique-900 px-6 py-3 text-sm font-bold tracking-widest uppercase transition-all whitespace-nowrap">
                        Book Now &rarr;
                    </a>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- ============================================================
     AMENITIES
============================================================ -->
<section id="amenities" class="py-16 lg:py-20 bg-boutique-900 scroll-mt-10">
    <div class="max-w-7xl mx-auto px-6 lg:px-20">

        <div class="text-center mb-14">
            <span class="tracking-[0.2em] text-boutique-400 text-xs font-bold uppercase mb-4 block">What's Included</span>
            <h2 class="text-4xl lg:text-5xl font-serif text-white mb-3">Room <span class="italic text-boutique-400">Amenities</span></h2>
            <p class="text-boutique-100/60 text-sm max-w-md mx-auto">All units are fully equipped for a comfortable and enjoyable stay.</p>
        </div>

        <?php
        $amenity_cats = [
            [
                'label' => 'Bedroom & Comfort',
                'items' => [
                    ['label' => 'Queen + Single Beds', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v13M21 7v13M3 12h18M6 7h12a1 1 0 011 1v4H5V8a1 1 0 011-1z"/>'],
                    ['label' => 'Aircon & Fan', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 3v2.25M14.25 3v2.25M4.5 7.5h15M4.5 12h15M4.5 16.5h15M9.75 21v-2.25M14.25 21v-2.25"/>'],
                    ['label' => 'Smart TV', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>'],
                    ['label' => 'Towel Provided', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 4v16M17 4v2M17 10v2M17 16v2M7 4h10M7 20h10"/>'],
                    ['label' => 'Water Heater', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m0 16v1m8-9h1M3 12H2m14.5-6.5l-.7.7M6.2 17.8l-.7.7M17.8 17.8l.7.7M6.9 6.2l-.7-.7M12 7a5 5 0 110 10A5 5 0 0112 7z"/>'],
                    ['label' => 'Extra Mattress', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 12h16M4 12a2 2 0 01-2-2V8a2 2 0 012-2h16a2 2 0 012 2v2a2 2 0 01-2 2M4 12v4a2 2 0 002 2h12a2 2 0 002-2v-4"/>'],
                    ['label' => 'Body Wash', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 2h6M8 6l-2 3v11a2 2 0 002 2h8a2 2 0 002-2V9l-2-3M8 6h8M12 10v6m-2-3h4"/>'],
                ],
            ],
            [
                'label' => 'Kitchen',
                'items' => [
                    ['label' => 'Gas Stove', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 8h16v10a2 2 0 01-2 2H6a2 2 0 01-2-2V8zm0 0V6a2 2 0 012-2h12a2 2 0 012 2v2M9 13a1 1 0 100 2 1 1 0 000-2zm6 0a1 1 0 100 2 1 1 0 000-2z"/>'],
                    ['label' => 'Microwave', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7h16a1 1 0 011 1v8a1 1 0 01-1 1H4a1 1 0 01-1-1V8a1 1 0 011-1zm12 2h1v4h-1V9zm-9 1h7v2H7v-2z"/>'],
                    ['label' => 'Rice Cooker', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14a1 1 0 011 1v7a3 3 0 01-3 3H7a3 3 0 01-3-3V9a1 1 0 011-1zm3-4h8M12 4v4"/>'],
                    ['label' => 'Freezer', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v18M3 12h18M7.757 7.757l8.486 8.486M16.243 7.757l-8.486 8.486"/>'],
                    ['label' => 'Refrigerator', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3h14a1 1 0 011 1v16a1 1 0 01-1 1H5a1 1 0 01-1-1V4a1 1 0 011-1zm0 8h14M9 6v3M9 14v3"/>'],
                    ['label' => 'Coway', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 3h8a2 2 0 012 2v14a2 2 0 01-2 2H8a2 2 0 01-2-2V5a2 2 0 012-2zm4 5v2m0 0c-1.1 0-2 .9-2 2v4h4v-4c0-1.1-.9-2-2-2z"/>'],
                    ['label' => 'Tableware / Crockery', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 2v6m0 0a2 2 0 100 4m0-4a2 2 0 000 4m0 0v10M18 2l-2 7h4L18 2zm0 7v11"/>'],
                ],
            ],
            [
                'label' => 'General Facilities',
                'items' => [
                    ['label' => 'Swimming Pool', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 20c1.5 0 2.5-1 4-1s2.5 1 4 1 2.5-1 4-1M3 16c1.5 0 2.5 1 4 1s2.5-1 4-1 2.5 1 4 1 2.5-1 4-1M12 3v8m0 0c-2.5 0-4 1-5 3m5-3c2.5 0 4 1 5 3"/>'],
                    ['label' => 'Washing Machine', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4h16a1 1 0 011 1v14a1 1 0 01-1 1H4a1 1 0 01-1-1V5a1 1 0 011-1zm8 4a4 4 0 100 8 4 4 0 000-8z"/>'],
                    ['label' => 'Iron & Iron Board', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 19h18M7 19V12h10v7M7 12c0-3.3 2.7-6 6-6h2v6H7z"/>'],
                    ['label' => 'Surau & Prayer Space', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 2L4 7v13h16V7L12 2zm0 0v18M4 7h16"/>'],
                    ['label' => 'Spacious Parking', 'icon' => '<rect x="3" y="3" width="18" height="18" rx="2" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17V7h4a3 3 0 010 6H9"/>'],
                    ['label' => 'WiFi 800 Mbps', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>'],
                    ['label' => 'Petanque Court', 'icon' => '<circle cx="12" cy="12" r="9" stroke-width="1.5"/><circle cx="9" cy="10" r="2" stroke-width="1.5"/><circle cx="15" cy="14" r="2" stroke-width="1.5"/>'],
                    ['label' => 'Entertainment Corner', 'soon' => true, 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.277A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>'],
                    ['label' => 'Gazebo & BBQ Pit', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"/>'],
                ],
            ],
        ];
        ?>

        <div class="space-y-10 lg:space-y-14">
            <?php foreach ($amenity_cats as $cat): ?>
            <div>
                <div class="flex items-center gap-4 mb-8">
                    <div class="h-px flex-1 bg-boutique-600/50"></div>
                    <span class="text-xs font-bold tracking-[0.25em] uppercase text-boutique-400 shrink-0"><?= htmlspecialchars($cat['label']) ?></span>
                    <div class="h-px flex-1 bg-boutique-600/50"></div>
                </div>
                <div class="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-6 gap-6 lg:gap-8">
                    <?php foreach ($cat['items'] as $item): ?>
                    <div class="flex flex-col items-center gap-3 text-center group">
                        <div class="relative w-16 h-16 rounded-full border border-boutique-600 flex items-center justify-center text-boutique-400 group-hover:bg-boutique-600 group-hover:border-boutique-400 transition-all duration-300">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><?= $item['icon'] ?></svg>
                            <?php if (!empty($item['soon'])): ?>
                            <span class="absolute -top-1 -right-1 w-4 h-4 bg-boutique-600 rounded-full flex items-center justify-center">
                                <span class="text-white text-[8px] font-bold leading-none">!</span>
                            </span>
                            <?php endif; ?>
                        </div>
                        <span class="text-[11px] text-boutique-100/70 leading-tight tracking-widest uppercase font-medium"><?= htmlspecialchars($item['label']) ?></span>
                        <?php if (!empty($item['soon'])): ?>
                        <span class="text-[10px] text-boutique-400/70 tracking-wide -mt-1">Coming Soon</span>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<!-- ============================================================
     SHARING FACILITIES
============================================================ -->
<section id="facilities" class="py-16 lg:py-20 bg-boutique-50 scroll-mt-10">
    <div class="max-w-7xl mx-auto px-6 lg:px-20">

        <div class="text-center mb-12">
            <span class="tracking-[0.2em] text-boutique-600 text-xs font-bold uppercase mb-4 block">Available to All Guests</span>
            <h2 class="text-4xl lg:text-5xl font-serif text-boutique-800 mb-4">Shared <span class="italic text-slate-400">Spaces</span></h2>
            <p class="text-slate-500 max-w-xl mx-auto">All shared facilities become exclusively private when you reserve the full house.</p>
        </div>

        <?php
        $facilities = [
            [
                'name' => 'Swimming Pool',
                'desc' => 'Open daily 7–11am & 5–10pm. Separate pools for adults and kids, complete with a water slide. Cool off and unwind anytime.',
                'hours' => '7–11am · 5–10pm',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 20c1.5 0 2.5-1 4-1s2.5 1 4 1 2.5-1 4-1M3 16c1.5 0 2.5 1 4 1s2.5-1 4-1 2.5 1 4 1 2.5-1 4-1M12 3v8m0 0c-2.5 0-4 1-5 3m5-3c2.5 0 4 1 5 3"/>',
                'image' => '',
                'soon' => false,
            ],
            [
                'name' => 'Open Kitchen',
                'desc' => 'Fully equipped with rice cooker, air fryer, microwave, blender, fridge, and more. Cook anytime.',
                'hours' => 'Available 24h',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M3 14h18M5 6h.01M8 6h.01M11 6h.01M5 18h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>',
                'image' => '',
                'soon' => false,
            ],
            [
                'name' => 'Gazebo & BBQ Pit',
                'desc' => 'Relax outdoors in our shaded gazebo. BBQ pit available for rent at RM10 per session (bring your own charcoal).',
                'hours' => 'BBQ: RM10/session',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"/>',
                'image' => '',
                'soon' => false,
            ],
            [
                'name' => 'Surau',
                'desc' => 'A dedicated prayer space available to all guests throughout their stay.',
                'hours' => 'Open 24h',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 2L4 7v13h16V7L12 2zm0 0v18M4 7h16"/>',
                'image' => '',
                'soon' => false,
            ],
            [
                'name' => 'Petanque Court',
                'desc' => 'Challenge your group to a friendly game of petanque — a fun outdoor activity for all ages.',
                'hours' => 'Daylight hours',
                'icon' => '<circle cx="12" cy="12" r="9" stroke-width="1.5"/><circle cx="9" cy="10" r="2" stroke-width="1.5"/><circle cx="15" cy="14" r="2" stroke-width="1.5"/>',
                'image' => '',
                'soon' => false,
            ],
            [
                'name' => 'Entertainment Corner',
                'desc' => 'A dedicated cinema and playroom packed with mini games — perfect for families and group stays.',
                'hours' => 'Coming soon',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.277A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>',
                'image' => '',
                'soon' => true,
            ],
        ];
        ?>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            <?php foreach ($facilities as $f): ?>
            <div class="facility-card bg-white overflow-hidden shadow-sm group">
                <!-- Image or icon placeholder -->
                <?php if (!empty($f['image'])): ?>
                <div class="aspect-[4/3] overflow-hidden">
                    <img src="<?= htmlspecialchars($f['image']) ?>" alt="<?= htmlspecialchars($f['name']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                </div>
                <?php else: ?>
                <div class="aspect-[4/3] bg-boutique-100 flex flex-col items-center justify-center gap-4 relative">
                    <?php if ($f['soon']): ?>
                    <div class="absolute top-3 right-3">
                        <span class="bg-boutique-600 text-white text-xs font-bold tracking-widest uppercase px-3 py-1">Coming Soon</span>
                    </div>
                    <?php endif; ?>
                    <span class="facility-icon-wrap w-16 h-16 rounded-full bg-boutique-50 border border-boutique-200 flex items-center justify-center text-boutique-400">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><?= $f['icon'] ?></svg>
                    </span>
                    <p class="text-xs tracking-widest uppercase font-semibold text-boutique-300">Photo Coming Soon</p>
                </div>
                <?php endif; ?>
                <div class="p-6">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <h3 class="font-serif text-boutique-800 text-xl"><?= htmlspecialchars($f['name']) ?></h3>
                        <span class="shrink-0 text-xs text-boutique-600 font-medium bg-boutique-50 border border-boutique-100 px-2 py-1 whitespace-nowrap"><?= htmlspecialchars($f['hours']) ?></span>
                    </div>
                    <p class="text-sm text-slate-500 leading-relaxed"><?= htmlspecialchars($f['desc']) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Full House CTA Banner -->
        <div class="bg-boutique-800 text-white p-8 lg:p-12">
            <div class="flex flex-col lg:flex-row items-center gap-8 lg:gap-12">
                <!-- Icon -->
                <div class="shrink-0 w-16 h-16 rounded-full border border-boutique-600 flex items-center justify-center text-boutique-400">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 22V12h6v10"/></svg>
                </div>
                <div class="text-center lg:text-left flex-1">
                    <span class="tracking-[0.2em] text-boutique-400 text-xs font-bold uppercase mb-2 block">Book All 8 Rooms</span>
                    <h3 class="text-2xl lg:text-3xl font-serif mb-2">Make It All Yours</h3>
                    <p class="text-boutique-100 max-w-lg mb-1">Pool, kitchen, gazebo, and entertainment corner — all shared spaces become <em>exclusively private</em> when you book the Full House Package.</p>
                    <p class="text-boutique-400 text-sm">Up to 32 guests &middot; RM1,400/night &middot; Free BBQ Pit included</p>
                </div>
                <div class="shrink-0">
                    <a href="guest/search.php"
                       class="inline-flex items-center gap-3 bg-boutique-600 hover:bg-boutique-400 text-white px-8 py-4 text-sm font-bold tracking-widest uppercase transition-colors whitespace-nowrap">
                        Check Availability
                    </a>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- ============================================================
     OUR ACCOMMODATIONS
============================================================ -->
<section id="suites" class="py-16 lg:py-20 bg-white scroll-mt-10">
    <div class="max-w-7xl mx-auto px-6 lg:px-20">
        
        <div class="text-center mb-14">
            <h2 class="text-4xl lg:text-5xl font-serif text-boutique-800 mb-4">Our <span class="italic text-slate-400">Accommodations</span></h2>
            <p class="text-slate-500 max-w-2xl mx-auto">Explore our thoughtfully designed rooms, blending minimalist aesthetics with ultimate comfort.</p>
        </div>

        <div class="space-y-16 lg:space-y-24">
            <?php 
            $has_rooms = false;
            if (isset($conn) && $conn):
                $rooms_result = $conn->query("SELECT * FROM rooms WHERE is_active = 1 ORDER BY id ASC");
                if ($rooms_result && $rooms_result->num_rows > 0):
                    $has_rooms = true;
                    $index = 0;
                    while ($room = $rooms_result->fetch_assoc()): 
                        $isReversed = $index % 2 !== 0;
            ?>
                        <div class="room-card group flex flex-col <?= $isReversed ? 'lg:flex-row-reverse' : 'lg:flex-row' ?> items-center gap-10 lg:gap-16">
                            <!-- Image side -->
                            <div class="w-full lg:w-1/2 room-img-wrapper overflow-hidden relative bg-boutique-50 aspect-[4/3] lg:aspect-[5/4]">
                                <?php if (!empty($room['image']) && file_exists(__DIR__ . '/' . $room['image'])): ?>
                                <img src="<?= htmlspecialchars($room['image']) ?>"
                                     alt="<?= htmlspecialchars($room['name']) ?>"
                                     class="w-full h-full object-cover">
                                <?php else: ?>
                                <div class="absolute inset-0 flex flex-col items-center justify-center gap-4 bg-boutique-100">
                                    <span class="w-16 h-16 rounded-full border border-boutique-300 flex items-center justify-center text-boutique-300">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </span>
                                    <p class="text-xs tracking-widest uppercase font-semibold text-boutique-300">Photo Coming Soon</p>
                                </div>
                                <?php endif; ?>
                                <div class="absolute top-4 <?= $isReversed ? 'right-4' : 'left-4' ?> bg-white/90 backdrop-blur px-3 py-1 text-xs tracking-widest uppercase font-semibold text-boutique-800">
                                    <?= htmlspecialchars($room['room_type']) ?>
                                </div>
                                <!-- Room number badge on image -->
                                <div class="absolute bottom-4 <?= $isReversed ? 'left-4' : 'right-4' ?> text-4xl font-serif text-white/20 font-bold leading-none select-none pointer-events-none">
                                    <?= str_pad($index + 1, 2, '0', STR_PAD_LEFT) ?>
                                </div>
                            </div>
                            
                            <!-- Content side -->
                            <div class="w-full lg:w-1/2 flex flex-col justify-center">
                                <h3 class="text-3xl font-serif text-boutique-800 mb-3"><?= htmlspecialchars($room['name']) ?></h3>
                                <p class="text-slate-500 mb-6 leading-relaxed">
                                    <?= htmlspecialchars($room['description']) ?>
                                </p>
                                
                                <div class="flex items-center gap-8 mb-6 border-y border-slate-100 py-4">
                                    <div class="flex items-center gap-3">
                                        <span class="w-8 h-8 rounded-full bg-boutique-50 border border-boutique-100 flex items-center justify-center text-boutique-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        </span>
                                        <div>
                                            <p class="text-xs tracking-wider uppercase text-slate-400">Capacity</p>
                                            <p class="font-medium text-boutique-800 text-sm">Up to <?= (int)$room['capacity'] ?> Guest<?= $room['capacity'] > 1 ? 's' : '' ?></p>
                                        </div>
                                    </div>
                                    <div class="w-px h-10 bg-slate-200"></div>
                                    <div class="flex items-center gap-3">
                                        <span class="w-8 h-8 rounded-full bg-boutique-50 border border-boutique-100 flex items-center justify-center text-boutique-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </span>
                                        <div>
                                            <p class="text-xs tracking-wider uppercase text-slate-400">Rate</p>
                                            <p class="font-medium text-boutique-800 text-sm">RM <?= number_format($room['price_per_night'], 0) ?> <span class="text-xs text-slate-500 font-normal">/night</span></p>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <a href="guest/book.php?room_id=<?= $room['id'] ?>"
                                       class="inline-flex items-center gap-3 text-sm font-semibold tracking-widest uppercase text-boutique-800 hover:text-boutique-600 transition-colors group/btn">
                                        Book This Room
                                        <span class="w-8 h-px bg-boutique-800 group-hover/btn:w-12 transition-all duration-300"></span>
                                    </a>
                                </div>
                            </div>
                        </div>
            <?php 
                    $index++;
                    endwhile;
                endif;
            endif;
            
            if (!$has_rooms):
                $placeholder_rooms = [
                    ['name' => 'Standard Room', 'type' => 'Standard', 'desc' => 'A comfortable queen bed room in our Modern or Traditional concept, perfect for couples or solo travelers. Includes TV, air conditioning, and hot water shower.', 'cap' => '2', 'price' => '165'],
                    ['name' => 'Superior Room', 'type' => 'Superior', 'desc' => 'More space, more flexibility. Our Superior rooms feature a queen bed plus an additional single bed — ideal for small families or groups of three.', 'cap' => '3', 'price' => '185'],
                    ['name' => 'Family Cabin', 'type' => 'Family', 'desc' => 'Our Cabin concept Family rooms sleep up to 5 guests with one queen bed and three single beds — designed for the whole family.', 'cap' => '5', 'price' => '195']
                ];
                foreach ($placeholder_rooms as $index => $pr): 
                    $isReversed = $index % 2 !== 0;
            ?>
                <div class="room-card group flex flex-col <?= $isReversed ? 'lg:flex-row-reverse' : 'lg:flex-row' ?> items-center gap-10 lg:gap-16">
                    <div class="w-full lg:w-1/2 room-img-wrapper overflow-hidden relative bg-boutique-100 aspect-[4/3] lg:aspect-[5/4] flex flex-col items-center justify-center gap-4">
                        <span class="w-16 h-16 rounded-full border border-boutique-300 flex items-center justify-center text-boutique-300">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </span>
                        <span class="text-boutique-300 text-xs tracking-widest uppercase font-semibold">Image Pending</span>
                        <!-- Room number watermark on image -->
                        <div class="absolute bottom-4 right-4 text-4xl font-serif text-boutique-200 font-bold leading-none select-none pointer-events-none">
                            <?= str_pad($index + 1, 2, '0', STR_PAD_LEFT) ?>
                        </div>
                    </div>
                    
                    <div class="w-full lg:w-1/2 flex flex-col justify-center">
                        <h3 class="text-3xl font-serif text-boutique-800 mb-3"><?= $pr['name'] ?></h3>
                        <p class="text-slate-500 mb-6 leading-relaxed"><?= $pr['desc'] ?></p>
                        
                        <div class="flex items-center gap-8 mb-6 border-y border-slate-100 py-4">
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-full bg-boutique-50 border border-boutique-100 flex items-center justify-center text-boutique-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </span>
                                <div>
                                    <p class="text-xs tracking-wider uppercase text-slate-400">Capacity</p>
                                    <p class="font-medium text-boutique-800 text-sm">Up to <?= $pr['cap'] ?> Guests</p>
                                </div>
                            </div>
                            <div class="w-px h-10 bg-slate-200"></div>
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-full bg-boutique-50 border border-boutique-100 flex items-center justify-center text-boutique-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </span>
                                <div>
                                    <p class="text-xs tracking-wider uppercase text-slate-400">Rate</p>
                                    <p class="font-medium text-boutique-800 text-sm">RM <?= $pr['price'] ?> <span class="text-xs text-slate-500 font-normal">/night</span></p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <a href="#booking-widget" class="inline-flex items-center gap-3 text-sm font-semibold tracking-widest uppercase text-boutique-800 hover:text-boutique-600 transition-colors group/btn">
                                Check Availability <span class="w-8 h-px bg-boutique-800 group-hover/btn:w-12 transition-all duration-300"></span>
                            </a>
                        </div>
                    </div>
                </div>
            <?php 
                endforeach;
            endif; 
            ?>
        </div>
    </div>
</section>

<!-- ============================================================
     HOUSE POLICIES
============================================================ -->
<section id="rules" class="py-16 lg:py-20 bg-boutique-50 scroll-mt-10">
    <div class="max-w-7xl mx-auto px-6 lg:px-20">
        <div class="mb-12">
            <span class="tracking-[0.2em] text-boutique-600 text-xs font-bold uppercase mb-4 block">To Ensure a Pleasant Stay</span>
            <h2 class="text-3xl lg:text-4xl font-serif text-boutique-800">House Policies</h2>
        </div>

        <?php
        $rules = [
            [
                'title' => 'Check-in & Out',
                'desc'  => 'Check-in from 3:00 PM. Check-out by 12:00 PM. Please inform us in advance for special timing requests.',
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>',
            ],
            [
                'title' => 'Deposit Requirement',
                'desc'  => 'A refundable RM100 deposit per room is required to confirm your booking. Returned via online transfer by 6pm on check-out day, subject to no damages or policy violations.',
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>',
            ],
            [
                'title' => 'Occupancy & Extra Guests',
                'desc'  => 'Rooms are strictly limited to their stated capacity. An extra charge of RM20 per person per night applies for guests exceeding the room limit.',
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>',
            ],
            [
                'title' => 'Smoke-Free Environment',
                'desc'  => 'For the comfort of all, smoking is strictly prohibited indoors. Designated outdoor areas are provided.',
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>',
            ],
            [
                'title' => 'Quiet Hours',
                'desc'  => 'We request all guests to respect quiet hours from 10:00 PM to ensure a peaceful rest for everyone.',
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>',
            ],
            [
                'title' => 'Cleanliness',
                'desc'  => 'Help us maintain our standards. Damages or excessive cleaning requirements may incur additional fees.',
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>',
            ],
            [
                'title' => 'No Parties or Loud Noises',
                'desc'  => 'Be considerate of fellow guests and neighbours. Parties, loud music, and disruptive behaviour are strictly prohibited at all times.',
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"/>',
            ],
            [
                'title' => 'Report Damages & Inquiries',
                'desc'  => 'Any damages, incidents, or concerns must be reported to management immediately. Prompt reporting helps us resolve issues quickly for everyone.',
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>',
            ],
        ];
        ?>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($rules as $rule): ?>
            <div class="bg-white p-6 flex gap-4 items-start">
                <span class="shrink-0 w-10 h-10 rounded-full bg-boutique-50 border border-boutique-100 flex items-center justify-center text-boutique-600 mt-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><?= $rule['icon'] ?></svg>
                </span>
                <div>
                    <h3 class="font-semibold text-boutique-800 text-sm mb-1.5"><?= htmlspecialchars($rule['title']) ?></h3>
                    <p class="text-sm text-slate-500 leading-relaxed"><?= htmlspecialchars($rule['desc']) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================================
     SWIMMING POOL RULES
============================================================ -->
<section id="pool-rules" class="py-16 lg:py-20 bg-boutique-900 scroll-mt-10">
    <div class="max-w-5xl mx-auto px-6 lg:px-20">

        <div class="text-center mb-12">
            <span class="tracking-[0.2em] text-boutique-400 text-xs font-bold uppercase mb-4 block">Peraturan Kolam Renang</span>
            <h2 class="text-3xl lg:text-4xl font-serif text-white">Swimming Pool <span class="italic text-boutique-400">Rules</span></h2>
        </div>

        <?php
        $pool_rules = [
            ['label' => 'Shower Before Entering Pool', 'no' => false, 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m0 16v1m8-9h1M3 12H2m2.93-5.07l.7.7M18.37 6.63l.7-.7M6.63 18.37l-.7.7M18.37 17.37l.7.7M8 12a4 4 0 108 0 4 4 0 00-8 0z"/>'],
            ['label' => 'Watch Your Children',        'no' => false, 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>'],
            ['label' => 'Use Restrooms',               'no' => false, 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>'],
            ['label' => "Don't Run",                   'no' => true,  'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 5a1 1 0 100-2 1 1 0 000 2zm-4.5 3.5l1.5-2.5 2 2 2-1.5M5 20l3-5 2 2 3-4"/>'],
            ['label' => 'No Glass Containers',         'no' => true,  'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 3h6l1 9H8L9 3zm-1 9l1 9h6l1-9M3 3l18 18"/>'],
            ['label' => 'No Pets',                     'no' => true,  'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 3s-1 1-1 3 1 3 1 3M15 3s1 1 1 3-1 3-1 3M7 9c-1.5 1-2 3-1 5l2 4h8l2-4c1-2 .5-4-1-5M3 3l18 18"/>'],
            ['label' => 'No Rough Play',               'no' => true,  'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>'],
            ['label' => 'No Food',                     'no' => true,  'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 5.636a9 9 0 010 12.728M5.636 5.636a9 9 0 000 12.728M12 8v4m0 4h.01M3 3l18 18"/>'],
        ];
        ?>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-8">
            <?php foreach ($pool_rules as $pr): ?>
            <div class="flex flex-col items-center gap-3 text-center">
                <div class="relative w-16 h-16 rounded-full border-2 <?= $pr['no'] ? 'border-red-500/60 bg-red-950/30' : 'border-boutique-500 bg-boutique-800' ?> flex items-center justify-center">
                    <svg class="w-7 h-7 <?= $pr['no'] ? 'text-red-400' : 'text-boutique-300' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><?= $pr['icon'] ?></svg>
                </div>
                <span class="text-[11px] font-bold tracking-widest uppercase leading-tight <?= $pr['no'] ? 'text-red-400/80' : 'text-boutique-100/70' ?>"><?= htmlspecialchars($pr['label']) ?></span>
            </div>
            <?php endforeach; ?>
        </div>

        <p class="text-center text-boutique-400/60 text-xs mt-12 tracking-wide">Please follow the above guidelines for safety and comfort of all guests.</p>

    </div>
</section>

<!-- ============================================================
     SELF CHECK-IN & CHECK-OUT
============================================================ -->
<section id="checkin-guide" class="py-16 lg:py-20 bg-boutique-50 scroll-mt-10">
    <div class="max-w-5xl mx-auto px-6 lg:px-20">

        <div class="text-center mb-12">
            <span class="tracking-[0.2em] text-boutique-600 text-xs font-bold uppercase mb-4 block">Easy &amp; Seamless</span>
            <h2 class="text-3xl lg:text-4xl font-serif text-boutique-800">Self Check-in <span class="italic text-slate-400">&amp; Check-out</span></h2>
            <div class="w-12 h-0.5 bg-boutique-600 mx-auto mt-4"></div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            <div class="bg-white p-6 flex flex-col items-center text-center gap-4 shadow-sm">
                <div class="w-12 h-12 rounded-full bg-boutique-900 flex items-center justify-center text-boutique-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-boutique-800 text-sm tracking-wide uppercase mb-2">Deposit Required</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">A security deposit of <strong class="text-boutique-700">RM100 (Refundable)</strong> is required prior to your arrival.</p>
                </div>
            </div>

            <div class="bg-white p-6 flex flex-col items-center text-center gap-4 shadow-sm">
                <div class="w-12 h-12 rounded-full bg-boutique-900 flex items-center justify-center text-boutique-400">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-boutique-800 text-sm tracking-wide uppercase mb-2">Get the Code</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">The lockbox code is provided before check-in <strong class="text-boutique-700">via WhatsApp</strong>.</p>
                </div>
            </div>

            <div class="bg-white p-6 flex flex-col items-center text-center gap-4 shadow-sm">
                <div class="w-12 h-12 rounded-full bg-boutique-900 flex items-center justify-center text-boutique-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-boutique-800 text-sm tracking-wide uppercase mb-2">Retrieve &amp; Return Key</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Insert the code &amp; turn <strong class="text-boutique-700">clockwise</strong>. Please return the key to the box after check-out.</p>
                </div>
            </div>

            <div class="bg-white p-6 flex flex-col items-center text-center gap-4 shadow-sm">
                <div class="w-12 h-12 rounded-full bg-boutique-900 flex items-center justify-center text-boutique-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-boutique-800 text-sm tracking-wide uppercase mb-2">Photo Update</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Kindly <strong class="text-boutique-700">send check-in &amp; check-out photos</strong> to our WhatsApp for verification.</p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ============================================================
     NEARBY ESTABLISHMENTS
============================================================ -->
<section id="nearby" class="py-16 lg:py-20 bg-white scroll-mt-10">
    <div class="max-w-5xl mx-auto px-6 lg:px-20">

        <div class="mb-10">
            <span class="tracking-[0.2em] text-boutique-600 text-xs font-bold uppercase mb-4 block">Explore Jitra</span>
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-2">
                <h2 class="text-3xl lg:text-4xl font-serif text-boutique-800">Nearby <span class="italic text-slate-400">Establishments</span></h2>
                <p class="text-sm text-slate-400">Everything is just a short drive away from Zayin Guest House.</p>
            </div>
        </div>

        <?php
        $nearby_cats = [
            [
                'label' => 'Health & Services',
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>',
                'items' => [
                    ['name' => 'Hospital Jitra',                        'dist' => '8 min · 3.6 km'],
                    ['name' => 'Masjid Al-Fateh Tanjung Pauh',          'dist' => '3 min · 1.2 km'],
                ],
            ],
            [
                'label' => 'Groceries & Shopping',
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>',
                'items' => [
                    ['name' => 'C-Mart BDI Jitra',     'dist' => '3 min · 1.3 km'],
                    ['name' => "Lotus's Jitra",         'dist' => '5 min · 2.0 km'],
                    ['name' => 'Pasaraya Yawata',       'dist' => '5 min · 2.0 km'],
                    ['name' => 'Eco-Shop @ Jitra',      'dist' => '2 min · 600 m'],
                ],
            ],
            [
                'label' => 'Food & Drinks',
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/><circle cx="12" cy="12" r="9" stroke-width="1.5"/>',
                'items' => [
                    ['name' => 'Restoran Tok Keramat', 'dist' => '1 min · 400 m'],
                    ['name' => 'Pulut Cafe, Jitra',    'dist' => '1 min · 300 m'],
                ],
            ],
            [
                'label' => 'Banks',
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>',
                'items' => [
                    ['name' => 'Maybank',                     'dist' => '3 min · 1.2 km'],
                    ['name' => 'Hong Leong Bank',             'dist' => '2 min · 950 m'],
                    ['name' => 'Public Bank',                 'dist' => '5 min · 1.9 km'],
                    ['name' => 'AmBank',                      'dist' => '4 min · 1.7 km'],
                    ['name' => 'Bank Islam & Bank Rakyat',    'dist' => '4 min · 1.6 km'],
                ],
            ],
            [
                'label' => 'Education',
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>',
                'items' => [
                    ['name' => 'Polimas (Politeknik Sultan Abdul Halim)', 'dist' => '4 min · 2.0 km'],
                    ['name' => 'IKBN Jitra',                             'dist' => '10 min · 7.0 km'],
                    ['name' => 'IPG Kampus Darulaman (IPDA)',            'dist' => '5 min · 2.2 km'],
                    ['name' => 'Institut Aminudin Baki (IAB)',           'dist' => '5 min · 2.3 km'],
                ],
            ],
            [
                'label' => 'Recreation',
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 20c1.5 0 2.5-1 4-1s2.5 1 4 1 2.5-1 4-1M3 16c1.5 0 2.5 1 4 1s2.5-1 4-1 2.5 1 4 1 2.5-1 4-1M12 3v8m0 0c-2.5 0-4 1-5 3m5-3c2.5 0 4 1 5 3"/>',
                'items' => [
                    ['name' => 'Tasik Darulaman',              'dist' => '10 min · 5.5 km'],
                    ['name' => 'Fantasia Aquapark Fun-tastik', 'dist' => '10 min · 5.6 km'],
                ],
            ],
        ];
        ?>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($nearby_cats as $cat): ?>
            <div>
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-8 h-8 rounded bg-boutique-50 border border-boutique-100 flex items-center justify-center text-boutique-600 shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><?= $cat['icon'] ?></svg>
                    </div>
                    <h3 class="font-bold text-boutique-800 text-sm tracking-wide uppercase"><?= htmlspecialchars($cat['label']) ?></h3>
                </div>
                <div class="space-y-0">
                    <?php foreach ($cat['items'] as $item): ?>
                    <div class="flex justify-between items-center py-2 border-b border-slate-100 text-sm">
                        <span class="text-slate-700"><?= htmlspecialchars($item['name']) ?></span>
                        <span class="text-boutique-600 font-semibold text-xs shrink-0 ml-4 text-right"><?= htmlspecialchars($item['dist']) ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<!-- ============================================================
     LOCATION & BOOKING CTA
============================================================ -->
<section id="location" class="relative bg-boutique-800 scroll-mt-10">
    <div class="absolute inset-0 z-0 opacity-40 mix-blend-luminosity pointer-events-none lg:pointer-events-auto">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.085941817543!2d100.41903707462772!3d6.25240659373604!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x304b59b603ecd21f%3A0x88151196b9124c1f!2sZayin%20Guest%20House!5e0!3m2!1sen!2smy!4v1781624947135!5m2!1sen!2smy"
            class="w-full h-full"
            style="border:0;"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            title="Location Map">
        </iframe>
    </div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-20 py-16 lg:py-20 flex lg:justify-end">
        <div class="bg-white p-8 lg:p-12 shadow-2xl max-w-md w-full">
            <h2 class="text-3xl font-serif text-boutique-800 mb-6">Plan Your Stay</h2>
            
            <address class="not-italic text-sm text-slate-600 mb-6 leading-relaxed flex gap-3 items-start">
                <span class="shrink-0 w-8 h-8 rounded-full bg-boutique-50 border border-boutique-100 flex items-center justify-center text-boutique-600 mt-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </span>
                <span>
                    <strong class="block text-boutique-800 font-serif mb-0.5">Zayin Guest House</strong>
                    Lot 116, Kampung Dato Keramat,<br>
                    Tanjung Pauh, 06000 Jitra,<br>
                    Kedah, Malaysia
                </span>
            </address>
            
            <div class="space-y-3 mb-8">
                <a href="https://wa.me/60103345184" target="_blank" rel="noopener" class="flex items-center gap-3 text-slate-600 hover:text-boutique-600 transition">
                    <span class="w-8 h-8 rounded-full bg-boutique-50 flex items-center justify-center text-boutique-600 shrink-0">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                    </span>
                    <span class="text-sm font-medium">+60 10-334 5184</span>
                </a>
                <a href="mailto:zayin.guesthouse@gmail.com" class="flex items-center gap-3 text-slate-600 hover:text-boutique-600 transition">
                    <span class="w-8 h-8 rounded-full bg-boutique-50 flex items-center justify-center text-boutique-600 shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </span>
                    <span class="text-sm font-medium">zayin.guesthouse@gmail.com</span>
                </a>
                <a href="https://vt.tiktok.com/ZS9CR24My/" target="_blank" rel="noopener" class="flex items-center gap-3 text-slate-600 hover:text-boutique-600 transition">
                    <span class="w-8 h-8 rounded-full bg-boutique-50 flex items-center justify-center text-boutique-600 shrink-0">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.69a8.18 8.18 0 0 0 4.78 1.52V6.75a4.85 4.85 0 0 1-1.01-.06Z"/></svg>
                    </span>
                    <span class="text-sm font-medium">TikTok</span>
                </a>
                <a href="https://www.facebook.com/share/p/1HNJaqnesr/" target="_blank" rel="noopener" class="flex items-center gap-3 text-slate-600 hover:text-boutique-600 transition">
                    <span class="w-8 h-8 rounded-full bg-boutique-50 flex items-center justify-center text-boutique-600 shrink-0">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </span>
                    <span class="text-sm font-medium">Facebook</span>
                </a>
            </div>
            
            <a href="guest/search.php"
               class="block w-full bg-boutique-800 hover:bg-boutique-900 text-white text-center py-4 text-sm font-semibold tracking-wider uppercase transition-colors">
                Check Availability &amp; Book
            </a>
            
            <a href="https://maps.app.goo.gl/r4b7qxK5v3nRj3tR8"
               target="_blank" rel="noopener"
               class="block w-full border border-slate-200 hover:border-slate-400 text-slate-600 text-center py-4 text-sm font-semibold tracking-wider uppercase transition-colors mt-3">
                Get Directions
            </a>
        </div>
    </div>
</section>

</main>

<?php include 'includes/footer.php'; ?>
</body>
</html>
<?php
$pageTitle = 'Welcome';
$pageDesc  = 'Zayin Guest House — Comfortable and affordable rooms for families, travelers, and corporate guests. Book directly with us for the best rates.';
$basePath  = '';
// Assuming this handles your DB connection ($conn)
include 'includes/config.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' — Zayin Guest House' : 'Zayin Guest House' ?></title>
    <meta name="description" content="<?= isset($pageDesc) ? htmlspecialchars($pageDesc) : 'Zayin Guest House — Comfortable and affordable rooms for families, travelers, and corporate guests.' ?>">

    <!-- Open Graph -->
    <meta property="og:title" content="<?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' — Zayin Guest House' : 'Zayin Guest House' ?>">
    <meta property="og:description" content="Comfortable and affordable accommodation. Book directly with us for the best rates.">
    <meta property="og:type" content="website">

    <!-- TailwindCSS via CDN -->
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
                            600: '#2b7a78', // Teal
                            800: '#17252a', // Deep Slate
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

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; scroll-behavior: smooth; }
        h1, h2, h3, .font-serif { font-family: 'Playfair Display', serif; }
        
        /* Subtle image zoom on hover for room cards */
        .room-img-wrapper overflow-hidden img { transition: transform 0.7s ease; }
        .room-card:hover .room-img-wrapper img { transform: scale(1.05); }
    </style>
</head>
<body class="bg-boutique-50 text-slate-700 antialiased selection:bg-boutique-600 selection:text-white">

<?php include 'includes/header.php'; ?>

<!-- ============================================================
     NOTICE BANNER (DB error fallback)
============================================================ -->
<?php if (!isset($conn) || !$conn): ?>
<div class="bg-red-50 border-b border-red-100 text-red-800 text-sm text-center px-4 py-3 font-medium">
    ⚠️ Database connection unavailable. Room availability is temporarily offline. Please contact us directly to book.
</div>
<?php endif; ?>

<main>

<!-- ============================================================
     HERO + BOOKING SEARCH WIDGET
============================================================ -->
<section id="booking-widget" class="relative flex flex-col items-center justify-center bg-boutique-900 overflow-hidden min-h-[85vh] py-20 lg:py-24 scroll-mt-0">
    <!-- Background Image -->
    <img src="assets/banner.jpg" alt="Zayin Guest House"
         class="absolute inset-0 w-full h-full object-cover"
         onerror="this.src='https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80'">
    <!-- Gradient Overlay -->
    <div class="absolute inset-0 bg-gradient-to-b from-boutique-900/50 via-boutique-900/55 to-boutique-900/85"></div>

    <!-- Hero Title -->
    <div class="relative z-10 text-center px-6 mb-10 lg:mb-12">
        <span class="tracking-[0.2em] text-boutique-400 text-xs font-bold uppercase mb-5 block">Welcome to</span>
        <h1 class="text-5xl lg:text-7xl font-serif text-white leading-[1.1] mb-5">
            Zayin <br><span class="italic text-boutique-400 font-light">Guest House</span>
        </h1>
        <p class="text-lg text-white/70 max-w-xl mx-auto leading-relaxed">
            Comfortable and affordable rooms in Malaysia. Book directly for the best rates — no fees, no middlemen.
        </p>
    </div>

    <!-- Booking Search Widget -->
    <div class="relative z-10 w-full max-w-4xl mx-auto px-4 lg:px-6">
        <form action="guest/search.php" method="GET" id="heroSearchForm"
              class="bg-white shadow-2xl p-5 lg:p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
            <div>
                <label for="heroCheckIn" class="block text-xs font-bold tracking-widest uppercase text-slate-400 mb-2">Check-in</label>
                <input type="date" name="check_in" id="heroCheckIn" required
                       min="<?= date('Y-m-d') ?>"
                       class="w-full border border-slate-200 px-4 py-3 text-boutique-800 font-medium focus:outline-none focus:border-boutique-600 text-sm">
            </div>
            <div>
                <label for="heroCheckOut" class="block text-xs font-bold tracking-widest uppercase text-slate-400 mb-2">Check-out</label>
                <input type="date" name="check_out" id="heroCheckOut" required
                       min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                       class="w-full border border-slate-200 px-4 py-3 text-boutique-800 font-medium focus:outline-none focus:border-boutique-600 text-sm">
            </div>
            <div>
                <label for="heroGuests" class="block text-xs font-bold tracking-widest uppercase text-slate-400 mb-2">Guests</label>
                <select name="guests" id="heroGuests"
                        class="w-full border border-slate-200 px-4 py-3 text-boutique-800 font-medium focus:outline-none focus:border-boutique-600 text-sm bg-white">
                    <option value="1">1 Guest</option>
                    <option value="2" selected>2 Guests</option>
                    <option value="3">3 Guests</option>
                    <option value="4">4 Guests</option>
                    <option value="5">5 Guests</option>
                </select>
            </div>
            <div>
                <button type="submit"
                        class="w-full bg-boutique-600 hover:bg-boutique-800 text-white py-3 text-sm font-bold tracking-widest uppercase transition-colors flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Check Availability
                </button>
            </div>
        </form>
        <p class="text-center text-white/40 text-xs mt-4 tracking-wide">
            Select your dates to see real-time availability and pricing
        </p>
    </div>
</section>

<script>
    // Keep check-out min = check-in + 1 day; auto-focus check-out after check-in is picked
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
            if (co.value && co.value <= ci.value) {
                co.value = nd;
            }
            co.focus();
        });
    })();
</script>

<!-- ============================================================
     THE EXPERIENCE (Combined About + Amenities)
============================================================ -->
<section class="py-24 px-6 lg:px-20 bg-boutique-800 text-boutique-100">
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 lg:gap-24 items-start">
        
        <!-- About Text -->
        <div>
            <h2 class="text-3xl lg:text-5xl font-serif text-white mb-8">Crafted for <br><span class="italic text-boutique-400">Tranquility</span></h2>
            <p class="text-lg text-boutique-100 leading-relaxed mb-6 font-light">
                Zayin Guest House offers a warm and welcoming environment with 8 fully-furnished rooms designed to meet the needs of every guest. 
            </p>
            <p class="text-lg text-boutique-100 leading-relaxed font-light">
                Whether you're looking for a cozy Standard room or a spacious Family suite, we provide the perfect blend of essential comforts and a peaceful atmosphere. Book directly with us to secure the best rates without hidden fees.
            </p>

            <!-- Full House Note -->
            <div class="mt-12 p-6 border border-boutique-600 bg-boutique-900/50">
                <h3 class="text-white font-serif text-xl mb-2">Exclusive Full House Booking</h3>
                <p class="text-sm text-boutique-100 leading-relaxed mb-4">
                    Planning a retreat or family gathering? Reserve all 8 rooms for absolute privacy.
                </p>
                <a href="https://wa.me/60XXXXXXXXXX?text=Hi%2C+I'm+interested+in+booking+the+full+house." class="text-sm font-semibold text-boutique-400 hover:text-white uppercase tracking-wider transition-colors inline-flex items-center gap-2">
                    Enquire Now <span aria-hidden="true">&rarr;</span>
                </a>
            </div>
        </div>

        <!-- Amenities Grid -->
        <div>
            <span class="tracking-[0.2em] text-boutique-400 text-xs font-bold uppercase mb-8 block border-b border-boutique-600 pb-4">The Amenities</span>
            <ul class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-6">
                <?php
                $amenities = [
                    'Free High-Speed Wi-Fi',
                    'Air Conditioning',
                    'Hot Water Showers',
                    'Ample Free Parking',
                    'In-Room Television',
                    'Fresh Premium Linens',
                    '24/7 Secured Premises',
                    'Shared Kitchen Access'
                ];
                foreach ($amenities as $item): ?>
                <li class="flex items-center gap-4 text-boutique-50 text-sm tracking-wide">
                    <svg class="w-5 h-5 text-boutique-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"></path></svg>
                    <?= $item ?>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        
    </div>
</section>

<!-- ============================================================
     OUR SUITES (Alternating Layout Rooms)
============================================================ -->
<section id="suites" class="py-24 bg-white scroll-mt-10">
    <div class="max-w-7xl mx-auto px-6 lg:px-20">
        
        <div class="text-center mb-20">
            <h2 class="text-4xl lg:text-5xl font-serif text-boutique-800 mb-4">Our <span class="italic text-slate-400">Accommodations</span></h2>
            <p class="text-slate-500 max-w-2xl mx-auto">Explore our thoughtfully designed rooms, blending minimalist aesthetics with ultimate comfort.</p>
        </div>

        <div class="space-y-24 lg:space-y-32">
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
                        <!-- Room Feature Block -->
                        <div class="room-card group flex flex-col <?= $isReversed ? 'lg:flex-row-reverse' : 'lg:flex-row' ?> items-center gap-10 lg:gap-20">
                            <!-- Image side -->
                            <div class="w-full lg:w-1/2 room-img-wrapper overflow-hidden relative bg-boutique-50 aspect-[4/3] lg:aspect-[5/4]">
                                <?php if (!empty($room['image']) && file_exists(__DIR__ . '/' . $room['image'])): ?>
                                <img src="<?= htmlspecialchars($room['image']) ?>"
                                     alt="<?= htmlspecialchars($room['name']) ?>"
                                     class="w-full h-full object-cover">
                                <?php else: ?>
                                <div class="absolute inset-0 flex flex-col items-center justify-center gap-3">
                                    <svg class="w-12 h-12 text-boutique-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <p class="text-xs tracking-widest uppercase font-semibold text-boutique-300">Photo Coming Soon</p>
                                </div>
                                <?php endif; ?>
                                <div class="absolute top-4 <?= $isReversed ? 'right-4' : 'left-4' ?> bg-white/90 backdrop-blur px-3 py-1 text-xs tracking-widest uppercase font-semibold text-boutique-800">
                                    <?= htmlspecialchars($room['room_type']) ?>
                                </div>
                            </div>
                            
                            <!-- Content side -->
                            <div class="w-full lg:w-1/2 flex flex-col justify-center">
                                <span class="text-boutique-400 text-6xl lg:text-8xl font-serif opacity-20 absolute -z-10 -translate-y-10 lg:-translate-x-10 select-none">
                                    <?= str_pad($index + 1, 2, '0', STR_PAD_LEFT) ?>
                                </span>
                                
                                <h3 class="text-3xl font-serif text-boutique-800 mb-4"><?= htmlspecialchars($room['name']) ?></h3>
                                <p class="text-slate-500 mb-6 leading-relaxed">
                                    <?= htmlspecialchars($room['description']) ?>
                                </p>
                                
                                <div class="flex items-center gap-8 mb-8 border-y border-slate-100 py-4">
                                    <div>
                                        <p class="text-xs tracking-wider uppercase text-slate-400 mb-1">Capacity</p>
                                        <p class="font-medium text-boutique-800">Up to <?= (int)$room['capacity'] ?> Guest<?= $room['capacity'] > 1 ? 's' : '' ?></p>
                                    </div>
                                    <div class="w-px h-10 bg-slate-200"></div>
                                    <div>
                                        <p class="text-xs tracking-wider uppercase text-slate-400 mb-1">Rate</p>
                                        <p class="font-medium text-boutique-800">RM <?= number_format($room['price_per_night'], 0) ?> <span class="text-sm text-slate-500 font-normal">/night</span></p>
                                    </div>
                                </div>

                                <div>
                                    <a href="guest/book.php?room_id=<?= $room['id'] ?>"
                                       class="inline-flex items-center gap-3 text-sm font-semibold tracking-widest uppercase text-boutique-800 hover:text-boutique-600 transition-colors group/btn">
                                        Book This Room
                                        <span class="w-8 h-px bg-boutique-800 group-hover/btn:w-12 transition-all"></span>
                                    </a>
                                </div>
                            </div>
                        </div>
            <?php 
                    $index++;
                    endwhile;
                endif;
            endif;
            
            // Fallback content if DB is down or empty
            if (!$has_rooms):
                $placeholder_rooms = [
                    ['name' => 'Standard Double', 'type' => 'Standard', 'desc' => 'A cozy retreat perfect for couples or solo travelers, featuring premium bedding and essential modern comforts.', 'cap' => '2', 'price' => '80'],
                    ['name' => 'Deluxe Twin', 'type' => 'Deluxe', 'desc' => 'Spacious and bright, our twin room offers flexible sleeping arrangements with elegant decor and extra living space.', 'cap' => '2', 'price' => '110'],
                    ['name' => 'Family Suite', 'type' => 'Suite', 'desc' => 'Designed for togetherness. This expansive suite accommodates the whole family comfortably without compromising on style.', 'cap' => '4', 'price' => '160']
                ];
                foreach ($placeholder_rooms as $index => $pr): 
                    $isReversed = $index % 2 !== 0;
            ?>
                <!-- Placeholder Block -->
                <div class="room-card group flex flex-col <?= $isReversed ? 'lg:flex-row-reverse' : 'lg:flex-row' ?> items-center gap-10 lg:gap-20">
                    <div class="w-full lg:w-1/2 room-img-wrapper overflow-hidden relative bg-boutique-100 aspect-[4/3] lg:aspect-[5/4] flex items-center justify-center">
                        <span class="text-boutique-400 text-sm tracking-widest uppercase">Image Pending</span>
                    </div>
                    
                    <div class="w-full lg:w-1/2 flex flex-col justify-center relative">
                         <span class="text-boutique-400 text-6xl lg:text-8xl font-serif opacity-10 absolute -z-10 -translate-y-12 select-none">
                            <?= str_pad($index + 1, 2, '0', STR_PAD_LEFT) ?>
                        </span>
                        <h3 class="text-3xl font-serif text-boutique-800 mb-4"><?= $pr['name'] ?></h3>
                        <p class="text-slate-500 mb-6 leading-relaxed"><?= $pr['desc'] ?></p>
                        
                        <div class="flex items-center gap-8 mb-8 border-y border-slate-100 py-4">
                            <div>
                                <p class="text-xs tracking-wider uppercase text-slate-400 mb-1">Capacity</p>
                                <p class="font-medium text-boutique-800">Up to <?= $pr['cap'] ?> Guests</p>
                            </div>
                            <div class="w-px h-10 bg-slate-200"></div>
                            <div>
                                <p class="text-xs tracking-wider uppercase text-slate-400 mb-1">Rate</p>
                                <p class="font-medium text-boutique-800">RM <?= $pr['price'] ?> <span class="text-sm text-slate-500 font-normal">/night</span></p>
                            </div>
                        </div>

                        <div>
                            <a href="#booking-widget" class="inline-flex items-center gap-3 text-sm font-semibold tracking-widest uppercase text-boutique-800 hover:text-boutique-600 transition-colors group/btn">
                                Check Availability <span class="w-8 h-px bg-boutique-800 group-hover/btn:w-12 transition-all"></span>
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
     ESSENTIAL POLICIES (Column Text Layout instead of Accordion)
============================================================ -->
<section id="rules" class="py-24 bg-boutique-50 scroll-mt-10">
    <div class="max-w-7xl mx-auto px-6 lg:px-20">
        <div class="mb-16">
            <span class="tracking-[0.2em] text-boutique-600 text-xs font-bold uppercase mb-4 block">To Ensure a Pleasant Stay</span>
            <h2 class="text-3xl lg:text-4xl font-serif text-boutique-800">House Policies</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-12 gap-y-10">
            <?php
            $rules = [
                ['Check-in & Out', 'Check-in from 3:00 PM. Check-out by 12:00 PM. Please inform us in advance for special timing requests.'],
                ['Deposit Requirement', 'A non-refundable deposit is required to confirm bookings, applicable unless cancelled 48hrs prior.'],
                ['Occupancy', 'Rooms are strictly limited to their stated capacity to ensure comfort and safety for all guests.'],
                ['Smoke-Free Environment', 'For the comfort of all, smoking is strictly prohibited indoors. Designated outdoor areas are provided.'],
                ['Quiet Hours', 'We request all guests to respect quiet hours from 10:00 PM to ensure a peaceful rest for everyone.'],
                ['Cleanliness', 'Help us maintain our standards. Damages or excessive cleaning requirements may incur additional fees.']
            ];
            foreach ($rules as $rule): ?>
            <div>
                <h3 class="font-semibold text-boutique-800 text-base mb-2"><?= htmlspecialchars($rule[0]) ?></h3>
                <p class="text-sm text-slate-600 leading-relaxed"><?= htmlspecialchars($rule[1]) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================================
     LOCATION & BOOKING CTA
============================================================ -->
<section id="location" class="relative bg-boutique-800 scroll-mt-10">
    <!-- Map Background -->
    <div class="absolute inset-0 z-0 opacity-40 mix-blend-luminosity pointer-events-none lg:pointer-events-auto">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d127481.64!2d101.686855!3d3.139003!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31cc362abd08e7d3%3A0x232d6e1b3a00fd80!2sKuala%20Lumpur%2C%20Malaysia!5e0!3m2!1sen!2smy!4v1234567890"
            class="w-full h-full"
            style="border:0;"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            title="Location Map">
        </iframe>
    </div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-20 py-24 flex lg:justify-end">
        <!-- Floating Contact Card -->
        <div class="bg-white p-10 lg:p-14 shadow-2xl max-w-md w-full">
            <h2 class="text-3xl font-serif text-boutique-800 mb-6">Plan Your Stay</h2>
            
            <address class="not-italic text-sm text-slate-600 mb-8 leading-relaxed">
                <strong class="block text-boutique-800 text-base mb-2 font-serif">Zayin Guest House</strong>
                [Street Address],<br>
                [City, Postcode],<br>
                Malaysia
            </address>
            
            <div class="space-y-4 mb-10">
                <a href="https://wa.me/60XXXXXXXXXX" target="_blank" rel="noopener" class="flex items-center gap-3 text-slate-600 hover:text-boutique-600 transition">
                    <span class="w-8 h-8 rounded-full bg-boutique-50 flex items-center justify-center text-boutique-600">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                    </span>
                    <span class="text-sm font-medium">+60 X-XXXX-XXXX</span>
                </a>
                <a href="mailto:zayinguesthouse@gmail.com" class="flex items-center gap-3 text-slate-600 hover:text-boutique-600 transition">
                    <span class="w-8 h-8 rounded-full bg-boutique-50 flex items-center justify-center text-boutique-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </span>
                    <span class="text-sm font-medium">zayinguesthouse@gmail.com</span>
                </a>
            </div>
            
            <a href="guest/search.php"
               class="block w-full bg-boutique-800 hover:bg-boutique-900 text-white text-center py-4 text-sm font-semibold tracking-wider uppercase transition-colors">
                Check Availability &amp; Book
            </a>
            
            <a href="https://maps.google.com/?q=Zayin+Guest+House+Malaysia"
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
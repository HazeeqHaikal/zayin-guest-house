<?php
$pageTitle = 'Welcome';
$pageDesc  = 'Zayin Guest House — Comfortable and affordable rooms for families, travelers, and corporate guests. Book directly with us for the best rates.';
$basePath  = '';
include 'includes/config.php';
?>
<?php include 'includes/header.php'; ?>

<main>

<!-- ============================================================
     HERO
============================================================ -->
<section class="relative bg-gradient-to-br from-amber-900 via-stone-800 to-stone-900 text-white overflow-hidden">
    <!-- Decorative overlay -->
    <div class="absolute inset-0 opacity-10"
         style="background-image: url('assets/banner.jpg'); background-size: cover; background-position: center;">
    </div>
    <div class="absolute inset-0 bg-gradient-to-b from-transparent to-stone-900/60"></div>

    <div class="relative max-w-6xl mx-auto px-4 py-28 md:py-40 text-center">
        <span class="inline-block bg-amber-600/20 text-amber-300 text-xs font-semibold tracking-widest uppercase px-4 py-1.5 rounded-full mb-4 border border-amber-600/30">
            Your Home Away From Home
        </span>
        <h1 class="text-4xl md:text-6xl font-extrabold mb-4 leading-tight">
            Zayin Guest House
        </h1>
        <p class="text-lg md:text-xl text-stone-300 max-w-2xl mx-auto mb-10 leading-relaxed">
            Comfortable, clean, and affordable accommodation.<br>
            8 rooms available for families, corporate guests &amp; solo travelers.
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="#rooms"
               class="bg-amber-600 hover:bg-amber-500 text-white px-8 py-3.5 rounded-xl font-semibold transition text-base shadow-lg shadow-amber-900/40">
                View Rooms &amp; Rates
            </a>
            <a href="https://wa.me/60XXXXXXXXXX?text=Hi%2C+I'd+like+to+check+room+availability."
               target="_blank" rel="noopener"
               class="bg-white/10 hover:bg-white/20 text-white border border-white/20 px-8 py-3.5 rounded-xl font-semibold transition text-base backdrop-blur-sm">
                📱 WhatsApp Enquiry
            </a>
        </div>

        <!-- Quick stats -->
        <div class="mt-16 grid grid-cols-3 gap-6 max-w-md mx-auto border-t border-white/10 pt-10">
            <div>
                <p class="text-2xl font-bold text-white">8</p>
                <p class="text-xs text-stone-400 mt-0.5">Rooms</p>
            </div>
            <div>
                <p class="text-2xl font-bold text-white">RM80</p>
                <p class="text-xs text-stone-400 mt-0.5">From / night</p>
            </div>
            <div>
                <p class="text-2xl font-bold text-white">24/7</p>
                <p class="text-xs text-stone-400 mt-0.5">Support</p>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     NOTICE BANNER (DB error fallback)
============================================================ -->
<?php if (!$conn): ?>
<div class="bg-amber-50 border-b border-amber-200 text-amber-800 text-sm text-center px-4 py-3">
    ⚠️ Database connection unavailable. Room availability and booking features are temporarily offline.
</div>
<?php endif; ?>

<!-- ============================================================
     ABOUT
============================================================ -->
<section class="max-w-6xl mx-auto px-4 py-16 md:py-20">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
        <div>
            <span class="text-amber-700 font-semibold text-sm uppercase tracking-widest">About Us</span>
            <h2 class="text-3xl md:text-4xl font-bold text-stone-800 mt-2 mb-5 leading-tight">
                A Comfortable Stay<br>at Every Visit
            </h2>
            <p class="text-stone-600 leading-relaxed mb-4">
                Zayin Guest House offers a warm and welcoming environment with 8 fully-furnished
                rooms designed to meet the needs of families, corporate employees, and leisure travelers.
            </p>
            <p class="text-stone-600 leading-relaxed">
                We offer a range of room types — from cozy Standard rooms to spacious Deluxe and Family rooms —
                all at competitive rates. Book directly with us and avoid third-party fees.
            </p>
            <a href="#rooms" class="inline-block mt-6 bg-amber-800 text-white px-6 py-3 rounded-xl hover:bg-amber-900 transition font-semibold text-sm">
                Explore Rooms →
            </a>
        </div>
        <div class="relative rounded-2xl overflow-hidden bg-stone-200 h-64 md:h-80 shadow-lg">
            <img src="assets/banner.jpg"
                 alt="Zayin Guest House exterior"
                 class="w-full h-full object-cover"
                 onerror="this.parentElement.classList.add('flex','items-center','justify-center')">
            <p class="absolute inset-0 flex items-center justify-center text-stone-400 text-sm hidden" id="img-placeholder">
                📷 Property image coming soon
            </p>
        </div>
    </div>
</section>

<!-- ============================================================
     ROOMS
============================================================ -->
<section id="rooms" class="bg-stone-100 py-16 md:py-20 scroll-mt-16">
    <div class="max-w-6xl mx-auto px-4">
        <div class="text-center mb-12">
            <span class="text-amber-700 font-semibold text-sm uppercase tracking-widest">Accommodation</span>
            <h2 class="text-3xl md:text-4xl font-bold text-stone-800 mt-2">Our Rooms</h2>
            <p class="text-stone-500 mt-3 max-w-xl mx-auto">
                Choose from our 8 thoughtfully designed rooms. All rooms include air conditioning,
                hot water shower, and free Wi-Fi.
            </p>
        </div>

        <!-- Room cards grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php if ($conn):
                $rooms_result = $conn->query("SELECT * FROM rooms WHERE is_active = 1 ORDER BY id ASC");
                if ($rooms_result && $rooms_result->num_rows > 0):
                    while ($room = $rooms_result->fetch_assoc()): ?>

                    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-stone-200 hover:shadow-md transition group">
                        <!-- Room image -->
                        <div class="relative h-44 bg-stone-200 overflow-hidden">
                            <img src="<?= htmlspecialchars($room['image']) ?>"
                                 alt="<?= htmlspecialchars($room['name']) ?>"
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                            <div class="absolute inset-0 items-center justify-center text-stone-400 text-sm hidden"
                                 style="display:none">
                                📷 No image yet
                            </div>
                            <!-- Room type badge -->
                            <span class="absolute top-2 right-2 bg-amber-800/90 text-white text-xs px-2 py-0.5 rounded-full capitalize">
                                <?= htmlspecialchars($room['room_type']) ?>
                            </span>
                        </div>

                        <!-- Room info -->
                        <div class="p-4">
                            <h3 class="font-semibold text-stone-800 text-base">
                                <?= htmlspecialchars($room['name']) ?>
                            </h3>
                            <p class="text-xs text-stone-500 mt-1 leading-relaxed line-clamp-2">
                                <?= htmlspecialchars($room['description']) ?>
                            </p>
                            <p class="text-xs text-stone-400 mt-2">
                                👥 Up to <?= (int)$room['capacity'] ?> guest<?= $room['capacity'] > 1 ? 's' : '' ?>
                            </p>
                            <div class="flex items-end justify-between mt-3">
                                <p class="text-amber-800 font-bold text-xl">
                                    RM<?= number_format($room['price_per_night'], 0) ?>
                                    <span class="text-xs font-normal text-stone-400">/ night</span>
                                </p>
                            </div>
                            <a href="https://wa.me/60XXXXXXXXXX?text=Hi%2C+I'd+like+to+book+<?= urlencode($room['name']) ?>."
                               target="_blank" rel="noopener"
                               class="mt-3 block text-center bg-amber-800 hover:bg-amber-900 text-white py-2 rounded-lg transition text-sm font-medium">
                                Book This Room
                            </a>
                        </div>
                    </div>

                    <?php endwhile;
                else: ?>
                    <p class="col-span-full text-center text-stone-400 py-10">
                        Room details are being updated. Please check back soon or contact us via WhatsApp.
                    </p>
                <?php endif;
            else: ?>
                <!-- Static placeholder cards when DB is unavailable -->
                <?php
                $placeholder_rooms = [
                    ['Standard Room A','2 guests','80'],
                    ['Standard Room B','2 guests','80'],
                    ['Standard Room C','2 guests','80'],
                    ['Standard Room D','2 guests','80'],
                    ['Deluxe Room A','3 guests','110'],
                    ['Deluxe Room B','3 guests','110'],
                    ['Family Room','5 guests','140'],
                    ['Master Suite','4 guests','160'],
                ];
                foreach ($placeholder_rooms as $pr): ?>
                <div class="bg-white rounded-2xl border border-stone-200 p-4">
                    <div class="h-44 bg-stone-100 rounded-xl mb-3 flex items-center justify-center text-stone-300 text-sm">
                        📷 Image coming soon
                    </div>
                    <h3 class="font-semibold text-stone-800"><?= $pr[0] ?></h3>
                    <p class="text-xs text-stone-400 mt-1">👥 Up to <?= $pr[1] ?></p>
                    <p class="text-amber-800 font-bold text-xl mt-2">RM<?= $pr[2] ?> <span class="text-xs font-normal text-stone-400">/ night</span></p>
                    <a href="#booking" class="mt-3 block text-center bg-amber-800 text-white py-2 rounded-lg text-sm font-medium">Enquire</a>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Fullhouse note -->
        <div class="mt-10 bg-amber-50 border border-amber-200 rounded-2xl p-6 flex flex-col md:flex-row items-start md:items-center gap-4">
            <div class="text-3xl">🏠</div>
            <div>
                <h3 class="font-semibold text-amber-900 text-base">Full House Booking Available</h3>
                <p class="text-amber-800 text-sm mt-1">
                    Planning a family gathering, company retreat, or celebration? Book the entire guest house exclusively.
                    Contact us on WhatsApp to check full-house availability and pricing.
                </p>
            </div>
            <a href="https://wa.me/60XXXXXXXXXX?text=Hi%2C+I'm+interested+in+booking+the+full+house."
               target="_blank" rel="noopener"
               class="shrink-0 bg-amber-800 text-white px-5 py-2.5 rounded-xl hover:bg-amber-900 transition text-sm font-semibold">
                Enquire Full House
            </a>
        </div>
    </div>
</section>

<!-- ============================================================
     AMENITIES
============================================================ -->
<section id="amenities" class="max-w-6xl mx-auto px-4 py-16 md:py-20 scroll-mt-16">
    <div class="text-center mb-12">
        <span class="text-amber-700 font-semibold text-sm uppercase tracking-widest">What's Included</span>
        <h2 class="text-3xl md:text-4xl font-bold text-stone-800 mt-2">Amenities</h2>
        <p class="text-stone-500 mt-3">Everything you need for a comfortable stay.</p>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-5">
        <?php
        $amenities = [
            ['🛜', 'Free Wi-Fi',         'High-speed internet throughout the property'],
            ['❄️', 'Air Conditioning',    'Individual AC unit in every room'],
            ['🚿', 'Hot Water Shower',    'Electric water heater in all bathrooms'],
            ['🅿️', 'Free Parking',        'Ample parking space within the compound'],
            ['📺', 'Television',          'Cable/satellite TV in every room'],
            ['🛏️', 'Fresh Linens',        'Clean towels and bed linens provided'],
            ['🔒', 'Secure Premises',     'Gated compound with 24-hour security'],
            ['🍳', 'Shared Kitchen',      'Common kitchen available for light cooking'],
        ];
        foreach ($amenities as $a): ?>
        <div class="bg-white border border-stone-200 rounded-2xl p-5 text-center hover:shadow-sm transition">
            <div class="text-3xl mb-2"><?= $a[0] ?></div>
            <h3 class="font-semibold text-stone-800 text-sm"><?= $a[1] ?></h3>
            <p class="text-xs text-stone-500 mt-1 leading-relaxed"><?= $a[2] ?></p>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- ============================================================
     HOUSE RULES
============================================================ -->
<section id="rules" class="bg-stone-100 py-16 md:py-20 scroll-mt-16">
    <div class="max-w-3xl mx-auto px-4">
        <div class="text-center mb-10">
            <span class="text-amber-700 font-semibold text-sm uppercase tracking-widest">Policies</span>
            <h2 class="text-3xl md:text-4xl font-bold text-stone-800 mt-2">House Rules</h2>
            <p class="text-stone-500 mt-3">Please read and respect these rules for a pleasant stay for all guests.</p>
        </div>

        <?php
        $rules = [
            ['Check-in & Check-out', 'Check-in time is 3:00 PM. Check-out time is 12:00 PM (noon). Early check-in or late check-out is subject to availability and may incur additional charges. Please inform us in advance.'],
            ['Deposit Policy', 'A deposit is required to confirm your booking. The deposit amount will be communicated upon booking. Deposits are non-refundable for cancellations made less than 48 hours before check-in.'],
            ['Guest Capacity', 'Each room has a maximum occupancy limit. Extra guests beyond the stated capacity are not permitted without prior arrangement and may result in additional charges.'],
            ['No Smoking', 'Smoking is strictly prohibited inside all rooms and indoor areas. A designated smoking area is available outside the property.'],
            ['Noise & Quiet Hours', 'Please keep noise levels to a minimum after 10:00 PM. Loud music, parties, or gatherings that disturb other guests are not allowed.'],
            ['Visitors', 'Registered guests only are permitted inside the rooms. External visitors must seek permission and are not allowed to stay overnight.'],
            ['Cleanliness', 'Guests are responsible for keeping their rooms tidy. Please dispose of rubbish in the bins provided. Any damage to property will be charged to the guest.'],
            ['Cancellation Policy', 'Cancellations must be made at least 48 hours before check-in to receive a partial refund. No-shows and same-day cancellations forfeit the full deposit.'],
        ];
        foreach ($rules as $idx => $rule): ?>
        <details class="bg-white rounded-2xl border border-stone-200 mb-3 group overflow-hidden" <?= $idx === 0 ? 'open' : '' ?>>
            <summary class="flex items-center justify-between px-5 py-4 cursor-pointer font-semibold text-stone-800 select-none hover:bg-stone-50 transition list-none">
                <span class="flex items-center gap-3">
                    <span class="w-6 h-6 bg-amber-100 text-amber-800 rounded-full flex items-center justify-center text-xs font-bold"><?= $idx + 1 ?></span>
                    <?= htmlspecialchars($rule[0]) ?>
                </span>
                <svg class="w-4 h-4 text-stone-400 transition-transform group-open:rotate-180 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </summary>
            <div class="px-5 pb-4 pt-1 text-stone-600 text-sm leading-relaxed border-t border-stone-100">
                <?= htmlspecialchars($rule[1]) ?>
            </div>
        </details>
        <?php endforeach; ?>
    </div>
</section>

<!-- ============================================================
     LOCATION
============================================================ -->
<section id="location" class="max-w-6xl mx-auto px-4 py-16 md:py-20 scroll-mt-16">
    <div class="text-center mb-10">
        <span class="text-amber-700 font-semibold text-sm uppercase tracking-widest">Find Us</span>
        <h2 class="text-3xl md:text-4xl font-bold text-stone-800 mt-2">Location</h2>
        <p class="text-stone-500 mt-3">Conveniently located and easy to reach.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
        <!-- Map embed -->
        <div class="md:col-span-2 rounded-2xl overflow-hidden border border-stone-200 shadow-sm h-72 bg-stone-100">
            <!--
                SETUP: Replace the src below with your actual Google Maps embed URL.
                1. Go to maps.google.com and search for your address.
                2. Click Share → Embed a map → Copy the src URL from the iframe.
                3. Paste the URL below.
            -->
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d127481.64!2d101.686855!3d3.139003!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31cc362abd08e7d3%3A0x232d6e1b3a00fd80!2sKuala%20Lumpur%2C%20Malaysia!5e0!3m2!1sen!2smy!4v1234567890"
                class="w-full h-full"
                style="border:0;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                title="Zayin Guest House Location">
            </iframe>
        </div>

        <!-- Address & directions -->
        <div class="bg-white rounded-2xl border border-stone-200 p-6">
            <h3 class="font-semibold text-stone-800 text-base mb-4">Getting Here</h3>
            <div class="space-y-4 text-sm text-stone-600">
                <div>
                    <p class="font-medium text-stone-700">📍 Address</p>
                    <p class="mt-1 text-stone-500 leading-relaxed">
                        Zayin Guest House<br>
                        [Street Address],<br>
                        [City, Postcode],<br>
                        Malaysia
                    </p>
                </div>
                <div>
                    <p class="font-medium text-stone-700">📱 WhatsApp</p>
                    <a href="https://wa.me/60XXXXXXXXXX" target="_blank" rel="noopener"
                       class="mt-1 text-amber-700 hover:text-amber-900 transition">+60X-XXXX-XXXX</a>
                </div>
                <div>
                    <p class="font-medium text-stone-700">📧 Email</p>
                    <a href="mailto:zayinguesthouse@gmail.com"
                       class="mt-1 text-amber-700 hover:text-amber-900 transition">zayinguesthouse@gmail.com</a>
                </div>
            </div>
            <a href="https://maps.google.com/?q=Zayin+Guest+House+Malaysia"
               target="_blank" rel="noopener"
               class="mt-5 block text-center bg-stone-800 hover:bg-stone-900 text-white py-2.5 rounded-xl transition text-sm font-medium">
                Open in Google Maps ↗
            </a>
        </div>
    </div>
</section>

<!-- ============================================================
     BOOKING CTA
============================================================ -->
<section id="booking" class="bg-gradient-to-br from-amber-900 to-stone-900 text-white py-16 md:py-20 scroll-mt-16">
    <div class="max-w-2xl mx-auto px-4 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Ready to Book?</h2>
        <p class="text-stone-300 text-lg leading-relaxed mb-8">
            Contact us directly on WhatsApp to check availability, confirm your booking,
            and get the best rates — no third-party fees.
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="https://wa.me/60XXXXXXXXXX?text=Hi%2C+I'd+like+to+book+a+room+at+Zayin+Guest+House."
               target="_blank" rel="noopener"
               class="bg-green-500 hover:bg-green-400 text-white px-8 py-3.5 rounded-xl font-semibold transition text-base shadow-lg">
                📱 Book via WhatsApp
            </a>
            <a href="mailto:zayinguesthouse@gmail.com"
               class="bg-white/10 hover:bg-white/20 text-white border border-white/20 px-8 py-3.5 rounded-xl font-semibold transition text-base backdrop-blur-sm">
                ✉️ Send an Email
            </a>
        </div>
        <p class="mt-8 text-stone-400 text-sm">
            Online booking calendar coming soon. Walk-ins are also welcome.
        </p>
    </div>
</section>

</main>

<?php include 'includes/footer.php'; ?>

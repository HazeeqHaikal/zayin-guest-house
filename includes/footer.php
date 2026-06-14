<?php $basePath = isset($basePath) ? $basePath : ''; ?>

<!-- ============================================================
     Footer
============================================================ -->
<footer class="bg-stone-900 text-stone-400 mt-20">
    <div class="max-w-6xl mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">

            <!-- Brand -->
            <div>
                <h3 class="text-white font-bold text-lg mb-3">Zayin Guest House</h3>
                <p class="text-sm leading-relaxed text-stone-400">
                    Comfortable and affordable accommodation for families,
                    corporate guests, and travelers. Book directly with us
                    for the best rates.
                </p>
                <p class="mt-4 text-sm text-stone-500">
                    📍 <span id="footer-address">Malaysia</span>
                </p>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-white font-semibold text-base mb-3">Quick Links</h3>
                <ul class="text-sm space-y-2">
                    <li><a href="<?= $basePath ?>index.php#rooms"      class="hover:text-white transition">Rooms &amp; Rates</a></li>
                    <li><a href="<?= $basePath ?>index.php#amenities"  class="hover:text-white transition">Amenities</a></li>
                    <li><a href="<?= $basePath ?>index.php#rules"      class="hover:text-white transition">House Rules</a></li>
                    <li><a href="<?= $basePath ?>index.php#location"   class="hover:text-white transition">Location &amp; Directions</a></li>
                    <li><a href="<?= $basePath ?>index.php#booking"    class="hover:text-white transition">Book Now</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h3 class="text-white font-semibold text-base mb-3">Contact Us</h3>
                <ul class="text-sm space-y-3">
                    <li>
                        <a href="https://wa.me/60XXXXXXXXXX?text=Hi%2C+I'd+like+to+enquire+about+room+availability."
                           target="_blank" rel="noopener"
                           class="flex items-center gap-2 hover:text-white transition">
                            <span class="text-green-400">●</span> WhatsApp Us
                        </a>
                    </li>
                    <li>
                        <a href="mailto:zayinguesthouse@gmail.com"
                           class="flex items-center gap-2 hover:text-white transition">
                            <span class="text-amber-400">●</span> zayinguesthouse@gmail.com
                        </a>
                    </li>
                </ul>

                <div class="mt-5 pt-5 border-t border-stone-700">
                    <p class="text-xs text-stone-500">Check-in: <span class="text-stone-300 font-medium">3:00 PM</span></p>
                    <p class="text-xs text-stone-500 mt-1">Check-out: <span class="text-stone-300 font-medium">12:00 PM</span></p>
                </div>
            </div>

        </div>

        <!-- Bottom bar -->
        <div class="border-t border-stone-800 mt-10 pt-6 flex flex-col md:flex-row justify-between items-center gap-2 text-xs text-stone-600">
            <p>&copy; <?= date('Y') ?> Zayin Guest House. All rights reserved.</p>
            <p>Built by <a href="https://wa.me/60112432697" class="hover:text-stone-400 transition">Hazeeq Programming</a></p>
        </div>
    </div>
</footer>

</body>
</html>

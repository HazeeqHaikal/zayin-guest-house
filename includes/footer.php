<?php $basePath = isset($basePath) ? $basePath : ''; ?>

<!-- ============================================================
     Footer
============================================================ -->
<footer class="bg-boutique-900 text-slate-400 pt-20 pb-10 border-t-4 border-boutique-800">
    <div class="max-w-7xl mx-auto px-6 lg:px-20">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-12 lg:gap-8">

            <!-- Brand (col-span-5) -->
            <div class="md:col-span-12 lg:col-span-5">
                <h3 class="text-white font-serif text-3xl mb-4">
                    Zayin <br><span class="italic text-boutique-400 font-light text-2xl">Guest House</span>
                </h3>
                <p class="text-sm leading-relaxed text-slate-400 max-w-sm mb-6">
                    A peaceful retreat in Jitra, Kedah. 8 beautifully designed rooms, a private pool, and shared facilities for families, solo travelers, and groups. Book directly for the best rates.
                </p>
                <p class="text-sm text-slate-400 flex items-start gap-3">
                    <svg class="w-5 h-5 text-boutique-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span id="footer-address" class="leading-relaxed">Lot 116, Kampung Dato Keramat,<br>Tanjung Pauh, 06000 Jitra, Kedah, Malaysia</span>
                </p>
            </div>

            <!-- Quick Links (col-span-3) -->
            <div class="md:col-span-6 lg:col-span-3 lg:pl-8">
                <h4 class="text-white font-serif text-xl mb-6">Explore</h4>
                <ul class="text-sm space-y-4">
                    <li><a href="<?= $basePath ?>index.php#suites" class="hover:text-white hover:translate-x-1 inline-block transition-all">Our Suites</a></li>
                    <li><a href="<?= $basePath ?>index.php#rules" class="hover:text-white hover:translate-x-1 inline-block transition-all">House Policies</a></li>
                    <li><a href="<?= $basePath ?>index.php#location" class="hover:text-white hover:translate-x-1 inline-block transition-all">Location &amp; Map</a></li>
                </ul>
            </div>

            <!-- Contact (col-span-4) -->
            <div class="md:col-span-6 lg:col-span-4">
                <h4 class="text-white font-serif text-xl mb-6">Connect</h4>
                <ul class="text-sm space-y-4">
                    <li>
                        <a href="https://wa.me/60103345184?text=Hi%2C+I'd+like+to+enquire+about+Zayin+Guest+House."
                           target="_blank" rel="noopener"
                           class="flex items-center gap-3 hover:text-white transition-colors group">
                            <span class="w-8 h-8 rounded-full bg-boutique-800 flex items-center justify-center text-boutique-400 group-hover:bg-boutique-600 group-hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                            </span>
                            WhatsApp Enquiry
                        </a>
                    </li>
                    <li>
                        <a href="mailto:zayinguesthouse@gmail.com"
                           class="flex items-center gap-3 hover:text-white transition-colors group">
                            <span class="w-8 h-8 rounded-full bg-boutique-800 flex items-center justify-center text-boutique-400 group-hover:bg-boutique-600 group-hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </span>
                            zayinguesthouse@gmail.com
                        </a>
                    </li>
                    <li>
                        <a href="https://vt.tiktok.com/ZS9CR24My/" target="_blank" rel="noopener"
                           class="flex items-center gap-3 hover:text-white transition-colors group">
                            <span class="w-8 h-8 rounded-full bg-boutique-800 flex items-center justify-center text-boutique-400 group-hover:bg-boutique-600 group-hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.69a8.18 8.18 0 0 0 4.78 1.52V6.75a4.85 4.85 0 0 1-1.01-.06Z"/></svg>
                            </span>
                            TikTok
                        </a>
                    </li>
                    <li>
                        <a href="https://www.facebook.com/share/p/1HNJaqnesr/" target="_blank" rel="noopener"
                           class="flex items-center gap-3 hover:text-white transition-colors group">
                            <span class="w-8 h-8 rounded-full bg-boutique-800 flex items-center justify-center text-boutique-400 group-hover:bg-boutique-600 group-hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </span>
                            Facebook
                        </a>
                    </li>
                </ul>

                <!-- Check in/out Box -->
                <div class="mt-8 p-5 bg-boutique-800/50 border border-boutique-800 grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-[10px] uppercase tracking-widest text-boutique-400 mb-1">Check-in</p>
                        <p class="text-white text-sm font-medium">3:00 PM</p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase tracking-widest text-boutique-400 mb-1">Check-out</p>
                        <p class="text-white text-sm font-medium">12:00 PM</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Bottom bar -->
        <div class="mt-16 pt-8 border-t border-boutique-800 flex flex-col md:flex-row justify-between items-center gap-4 text-xs tracking-wider text-slate-500">
            <p>&copy; <?= date('Y') ?> Zayin Guest House. All rights reserved.</p>
            <p>Built by <a href="https://wa.me/60112432697" class="text-boutique-400 hover:text-white transition-colors">Hazeeq Programming</a></p>
        </div>
    </div>
</footer>
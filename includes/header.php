<?php $basePath = isset($basePath) ? $basePath : ''; ?>

<header class="bg-white border-b border-slate-100 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6 lg:px-20">
        <div class="flex items-center h-16 gap-10">

            <!-- Brand -->
            <a href="<?= $basePath ?>index.php" class="flex flex-col leading-none shrink-0">
                <span class="font-serif text-lg text-boutique-800 leading-none">Zayin</span>
                <span class="text-[9px] tracking-[0.22em] uppercase text-slate-400 mt-0.5">Guest House</span>
            </a>

            <!-- Divider -->
            <div class="hidden md:block w-px h-5 bg-slate-200 shrink-0"></div>

            <!-- Desktop Nav -->
            <nav class="hidden md:flex items-center gap-8">
                <a href="<?= $basePath ?>index.php#suites"   class="text-[11px] font-semibold tracking-widest uppercase text-slate-500 hover:text-boutique-800 transition-colors">Our Suites</a>
                <a href="<?= $basePath ?>index.php#rules"    class="text-[11px] font-semibold tracking-widest uppercase text-slate-500 hover:text-boutique-800 transition-colors">Policies</a>
                <a href="<?= $basePath ?>index.php#location" class="text-[11px] font-semibold tracking-widest uppercase text-slate-500 hover:text-boutique-800 transition-colors">Location</a>
            </nav>

            <!-- Desktop Right Actions -->
            <div class="hidden md:flex items-center gap-4 ml-auto">
                <?php if (!empty($_SESSION['admin_id'])): ?>
                    <a href="<?= $basePath ?>admin/dashboard.php" class="text-[11px] font-semibold tracking-widest uppercase text-slate-500 hover:text-boutique-800 transition-colors">Admin</a>
                    <a href="<?= $basePath ?>auth/logout.php"     class="text-[11px] font-semibold tracking-widest uppercase text-slate-400 hover:text-red-500 transition-colors">Logout</a>
                <?php elseif (!empty($_SESSION['user_id'])): ?>
                    <a href="<?= $basePath ?>customer/my_bookings.php" class="text-[11px] font-semibold tracking-widest uppercase text-slate-500 hover:text-boutique-800 transition-colors">My Bookings</a>
                    <a href="<?= $basePath ?>auth/logout.php"          class="text-[11px] font-semibold tracking-widest uppercase text-slate-400 hover:text-red-500 transition-colors">Logout</a>
                <?php else: ?>
                    <a href="<?= $basePath ?>auth/login.php"    class="text-[11px] font-semibold tracking-widest uppercase text-slate-500 hover:text-boutique-800 transition-colors">Login</a>
                    <a href="<?= $basePath ?>auth/register.php" class="text-[11px] font-semibold tracking-widest uppercase text-slate-500 hover:text-boutique-800 border border-slate-200 hover:border-boutique-600 px-4 py-2 transition-colors">Register</a>
                <?php endif; ?>
                <a href="<?= $basePath ?>index.php#booking-widget"
                   class="text-[11px] font-bold tracking-widest uppercase bg-boutique-800 hover:bg-boutique-600 text-white px-5 py-2.5 transition-colors">
                    Book Now
                </a>
            </div>

            <!-- Mobile Hamburger -->
            <button id="mobileMenuBtn" class="md:hidden ml-auto p-1.5 text-boutique-800" aria-label="Toggle menu">
                <svg id="menuIcon" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

        </div>
    </div>

    <!-- Mobile Dropdown Menu -->
    <div id="mobileMenu" class="hidden md:hidden border-t border-slate-100 bg-white">
        <div class="px-6 pt-2 pb-5 flex flex-col">
            <a href="<?= $basePath ?>index.php#suites"   class="flex items-center justify-between py-3.5 text-[11px] font-semibold tracking-widest uppercase text-slate-500 border-b border-slate-50 hover:text-boutique-800 transition-colors">Our Suites <span class="text-slate-300 font-normal">→</span></a>
            <a href="<?= $basePath ?>index.php#rules"    class="flex items-center justify-between py-3.5 text-[11px] font-semibold tracking-widest uppercase text-slate-500 border-b border-slate-50 hover:text-boutique-800 transition-colors">Policies <span class="text-slate-300 font-normal">→</span></a>
            <a href="<?= $basePath ?>index.php#location" class="flex items-center justify-between py-3.5 text-[11px] font-semibold tracking-widest uppercase text-slate-500 border-b border-slate-50 hover:text-boutique-800 transition-colors">Location <span class="text-slate-300 font-normal">→</span></a>

            <?php if (!empty($_SESSION['admin_id'])): ?>
                <a href="<?= $basePath ?>admin/dashboard.php" class="flex items-center justify-between py-3.5 text-[11px] font-semibold tracking-widest uppercase text-slate-500 border-b border-slate-50 hover:text-boutique-800 transition-colors">Admin <span class="text-slate-300 font-normal">→</span></a>
                <a href="<?= $basePath ?>auth/logout.php"     class="py-3.5 text-[11px] font-semibold tracking-widest uppercase text-slate-400 hover:text-red-500 transition-colors">Logout</a>
            <?php elseif (!empty($_SESSION['user_id'])): ?>
                <a href="<?= $basePath ?>customer/my_bookings.php" class="flex items-center justify-between py-3.5 text-[11px] font-semibold tracking-widest uppercase text-slate-500 border-b border-slate-50 hover:text-boutique-800 transition-colors">My Bookings <span class="text-slate-300 font-normal">→</span></a>
                <a href="<?= $basePath ?>auth/logout.php"          class="py-3.5 text-[11px] font-semibold tracking-widest uppercase text-slate-400 hover:text-red-500 transition-colors">Logout</a>
            <?php else: ?>
                <a href="<?= $basePath ?>auth/login.php"    class="flex items-center justify-between py-3.5 text-[11px] font-semibold tracking-widest uppercase text-slate-500 border-b border-slate-50 hover:text-boutique-800 transition-colors">Login <span class="text-slate-300 font-normal">→</span></a>
                <a href="<?= $basePath ?>auth/register.php" class="flex items-center justify-between py-3.5 text-[11px] font-semibold tracking-widest uppercase text-slate-500 border-b border-slate-50 hover:text-boutique-800 transition-colors">Register <span class="text-slate-300 font-normal">→</span></a>
            <?php endif; ?>

            <a href="<?= $basePath ?>index.php#booking-widget"
               class="mt-4 w-full bg-boutique-800 hover:bg-boutique-600 text-white text-center py-3.5 text-[11px] font-bold tracking-widest uppercase transition-colors">
                Book Now
            </a>
        </div>
    </div>
</header>

<script>
(function () {
    var btn  = document.getElementById('mobileMenuBtn');
    var menu = document.getElementById('mobileMenu');
    var icon = document.getElementById('menuIcon');
    if (!btn || !menu || !icon) return;

    var openPath  = 'M6 18L18 6M6 6l12 12';
    var closePath = 'M4 6h16M4 12h16M4 18h16';

    function setIcon(open) {
        icon.querySelector('path').setAttribute('d', open ? openPath : closePath);
    }

    btn.addEventListener('click', function (e) {
        e.stopPropagation();
        var isOpen = !menu.classList.contains('hidden');
        menu.classList.toggle('hidden');
        setIcon(!isOpen);
    });

    // Close when a nav link is tapped
    menu.querySelectorAll('a').forEach(function (a) {
        a.addEventListener('click', function () {
            menu.classList.add('hidden');
            setIcon(false);
        });
    });

    // Close on outside click
    document.addEventListener('click', function (e) {
        if (!btn.contains(e.target) && !menu.contains(e.target)) {
            menu.classList.add('hidden');
            setIcon(false);
        }
    });
})();
</script>
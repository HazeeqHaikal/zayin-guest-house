<?php $basePath = isset($basePath) ? $basePath : ''; ?>

<header class="bg-white/95 backdrop-blur-md border-b border-boutique-100 sticky top-0 z-50 transition-all">
<nav>
    <div class="max-w-7xl mx-auto px-6 lg:px-20 py-4 flex items-center justify-between">

        <!-- Logo / Brand -->
        <a href="<?= $basePath ?>index.php" class="flex items-center gap-3 group">
            <img src="<?= $basePath ?>assets/logo.png"
                 alt="Zayin Guest House"
                 class="h-10 w-auto group-hover:opacity-80 transition-opacity"
                 onerror="this.style.display='none'">
            <span class="font-serif text-2xl text-boutique-800 leading-none">
                Zayin<br>
                <span class="italic font-light text-sm text-boutique-400 tracking-wide">Guest House</span>
            </span>
        </a>

        <!-- Desktop Nav -->
        <ul class="hidden md:flex items-center gap-8 text-xs font-semibold tracking-widest uppercase text-slate-500">
            <li><a href="<?= $basePath ?>index.php#suites" class="hover:text-boutique-600 transition-colors">Our Suites</a></li>
            <li><a href="<?= $basePath ?>index.php#rules" class="hover:text-boutique-600 transition-colors">Policies</a></li>
            <li><a href="<?= $basePath ?>index.php#location" class="hover:text-boutique-600 transition-colors">Location</a></li>
            <li>
                <a href="<?= $basePath ?>index.php#booking-widget"
                   class="bg-boutique-800 text-white px-6 py-3 hover:bg-boutique-900 transition-colors inline-block text-center shadow-sm">
                    Book Now
                </a>
            </li>
            <?php if (!empty($_SESSION['admin_id'])): ?>
            <li class="flex items-center gap-4 pl-4 border-l border-slate-200">
                <a href="<?= $basePath ?>admin/dashboard.php" class="hover:text-boutique-600 transition-colors">Admin Panel</a>
                <a href="<?= $basePath ?>auth/logout.php" class="hover:text-red-500 transition-colors">Logout</a>
            </li>
            <?php elseif (!empty($_SESSION['user_id'])): ?>
            <li class="flex items-center gap-4 pl-4 border-l border-slate-200">
                <span class="text-boutique-600 normal-case tracking-normal font-semibold text-xs">
                    Hi, <?= htmlspecialchars(explode(' ', $_SESSION['user_name'])[0]) ?>
                </span>
                <a href="<?= $basePath ?>customer/my_bookings.php" class="hover:text-boutique-600 transition-colors">My Bookings</a>
                <a href="<?= $basePath ?>auth/logout.php" class="hover:text-red-500 transition-colors">Logout</a>
            </li>
            <?php else: ?>
            <li class="flex items-center gap-3 pl-4 border-l border-slate-200">
                <a href="<?= $basePath ?>auth/login.php" class="hover:text-boutique-600 transition-colors">Login</a>
                <a href="<?= $basePath ?>auth/register.php"
                   class="border border-boutique-600 text-boutique-600 px-4 py-2 hover:bg-boutique-600 hover:text-white transition-colors">
                    Register
                </a>
            </li>
            <?php endif; ?>
        </ul>

        <!-- Mobile menu button -->
        <button id="menuToggle" aria-label="Open menu"
                class="md:hidden p-2 text-boutique-800 hover:bg-boutique-50 transition-colors rounded-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path id="menuIcon" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </div>

    <!-- Mobile Menu Dropdown -->
    <div id="mobileMenu" class="hidden md:hidden bg-white border-t border-boutique-100 px-6 pb-6 shadow-2xl absolute w-full left-0 top-full">
        <ul class="flex flex-col gap-2 text-xs font-semibold tracking-widest uppercase text-slate-500 pt-4">
            <li><a href="<?= $basePath ?>index.php#suites" class="block py-3 border-b border-boutique-50 hover:text-boutique-600">Our Suites</a></li>
            <li><a href="<?= $basePath ?>index.php#rules" class="block py-3 border-b border-boutique-50 hover:text-boutique-600">Policies</a></li>
            <li><a href="<?= $basePath ?>index.php#location" class="block py-3 border-b border-boutique-50 hover:text-boutique-600">Location</a></li>
            <li>
                <a href="<?= $basePath ?>index.php#booking-widget"
                   class="block text-center bg-boutique-800 text-white py-4 hover:bg-boutique-900 transition-colors shadow-sm mt-2">
                    Book Now
                </a>
            </li>
            <?php if (!empty($_SESSION['admin_id'])): ?>
            <li class="pt-2 mt-2 border-t border-boutique-50">
                <a href="<?= $basePath ?>admin/dashboard.php" class="block py-2 hover:text-boutique-600">Admin Panel</a>
            </li>
            <li>
                <a href="<?= $basePath ?>auth/logout.php" class="block py-2 hover:text-red-500">Logout</a>
            </li>
            <?php elseif (!empty($_SESSION['user_id'])): ?>
            <li class="pt-3 mt-2 border-t border-boutique-50">
                <span class="block py-1 text-boutique-600 normal-case tracking-normal">
                    Hi, <?= htmlspecialchars(explode(' ', $_SESSION['user_name'])[0]) ?>
                </span>
            </li>
            <li>
                <a href="<?= $basePath ?>customer/my_bookings.php" class="block py-2 hover:text-boutique-600">My Bookings</a>
            </li>
            <li>
                <a href="<?= $basePath ?>auth/logout.php" class="block py-2 hover:text-red-500">Logout</a>
            </li>
            <?php else: ?>
            <li class="pt-2 mt-2 border-t border-boutique-50">
                <a href="<?= $basePath ?>auth/login.php" class="block py-2 hover:text-boutique-600">Login</a>
            </li>
            <li>
                <a href="<?= $basePath ?>auth/register.php" class="block py-2 hover:text-boutique-600">Register</a>
            </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<script>
    (function () {
        var btn  = document.getElementById('menuToggle');
        var menu = document.getElementById('mobileMenu');
        var icon = document.getElementById('menuIcon');
        
        btn.addEventListener('click', function () {
            menu.classList.toggle('hidden');
            
            // Swap hamburger to X icon when opened
            if (menu.classList.contains('hidden')) {
                icon.setAttribute('d', 'M4 6h16M4 12h16M4 18h16');
            } else {
                icon.setAttribute('d', 'M6 18L18 6M6 6l12 12');
            }
        });
        
        // Close menu on link click
        menu.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () {
                menu.classList.add('hidden');
                icon.setAttribute('d', 'M4 6h16M4 12h16M4 18h16');
            });
        });
    })();
</script>
</header>
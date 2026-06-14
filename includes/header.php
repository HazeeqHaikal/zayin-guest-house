<?php
// $basePath is set by the including page to handle relative paths at different folder depths.
// Root pages (index.php):     $basePath = '';
// Nested pages (admin/, guest/): $basePath = '../';
$basePath = isset($basePath) ? $basePath : '';
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
                        primary: {
                            DEFAULT: '#92400e',
                            light:   '#b45309',
                            dark:    '#78350f',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif']
                    }
                }
            }
        }
    </script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; scroll-behavior: smooth; }
        /* Smooth accordion transition */
        details > div { animation: fadeDown 0.2s ease; }
        @keyframes fadeDown {
            from { opacity: 0; transform: translateY(-6px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-stone-50 text-stone-800 antialiased">

<!-- ============================================================
     Navigation
============================================================ -->
<nav class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">

        <!-- Logo / Brand -->
        <a href="<?= $basePath ?>index.php" class="flex items-center gap-2">
            <img src="<?= $basePath ?>assets/logo.png"
                 alt="Zayin Guest House"
                 class="h-10 w-auto"
                 onerror="this.style.display='none'">
            <span class="font-bold text-xl text-amber-800 leading-tight">Zayin<br><span class="font-normal text-sm tracking-wide text-stone-500">Guest House</span></span>
        </a>

        <!-- Desktop Nav -->
        <ul class="hidden md:flex items-center gap-6 text-sm font-medium text-stone-600">
            <li><a href="<?= $basePath ?>index.php#rooms"      class="hover:text-amber-800 transition">Rooms</a></li>
            <li><a href="<?= $basePath ?>index.php#amenities"  class="hover:text-amber-800 transition">Amenities</a></li>
            <li><a href="<?= $basePath ?>index.php#rules"      class="hover:text-amber-800 transition">House Rules</a></li>
            <li><a href="<?= $basePath ?>index.php#location"   class="hover:text-amber-800 transition">Location</a></li>
            <li>
                <a href="<?= $basePath ?>index.php#booking"
                   class="bg-amber-800 text-white px-5 py-2 rounded-lg hover:bg-amber-900 transition font-semibold">
                    Book Now
                </a>
            </li>
        </ul>

        <!-- Mobile menu button -->
        <button id="menuToggle" aria-label="Open menu"
                class="md:hidden p-2 rounded-lg text-stone-500 hover:bg-stone-100 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path id="menuIcon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="hidden md:hidden bg-white border-t border-stone-100 px-4 pb-4">
        <ul class="flex flex-col gap-1 text-sm font-medium text-stone-600 pt-2">
            <li><a href="<?= $basePath ?>index.php#rooms"     class="block py-2 px-3 rounded-lg hover:bg-stone-50 hover:text-amber-800">Rooms</a></li>
            <li><a href="<?= $basePath ?>index.php#amenities" class="block py-2 px-3 rounded-lg hover:bg-stone-50 hover:text-amber-800">Amenities</a></li>
            <li><a href="<?= $basePath ?>index.php#rules"     class="block py-2 px-3 rounded-lg hover:bg-stone-50 hover:text-amber-800">House Rules</a></li>
            <li><a href="<?= $basePath ?>index.php#location"  class="block py-2 px-3 rounded-lg hover:bg-stone-50 hover:text-amber-800">Location</a></li>
            <li class="pt-1">
                <a href="<?= $basePath ?>index.php#booking"
                   class="block text-center bg-amber-800 text-white py-2 px-3 rounded-lg hover:bg-amber-900 font-semibold">
                    Book Now
                </a>
            </li>
        </ul>
    </div>
</nav>

<script>
    (function () {
        var btn  = document.getElementById('menuToggle');
        var menu = document.getElementById('mobileMenu');
        btn.addEventListener('click', function () {
            menu.classList.toggle('hidden');
        });
        // Close on link click
        menu.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () {
                menu.classList.add('hidden');
            });
        });
    })();
</script>

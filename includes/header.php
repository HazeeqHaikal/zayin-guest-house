<?php $basePath = isset($basePath) ? $basePath : ''; ?>

<style>
*{box-sizing:border-box;}
.zh{background:#fff;border-bottom:1px solid #e8e8e8;position:sticky;top:0;z-index:50;}
.zh-inner{max-width:1280px;margin:0 auto;padding:0 2.5rem;height:60px;display:flex;align-items:center;gap:3rem;}
.zh-brand{text-decoration:none;flex-shrink:0;display:flex;flex-direction:column;gap:1px;}
.zh-brand-name{font-size:16px;font-weight:500;letter-spacing:0.02em;color:#111;line-height:1;}
.zh-brand-sub{font-size:9px;letter-spacing:0.2em;text-transform:uppercase;color:#aaa;line-height:1;}
.zh-divider{width:1px;height:20px;background:#e0e0e0;flex-shrink:0;}
.zh-nav{display:flex;align-items:center;gap:2rem;list-style:none;margin:0;padding:0;}
.zh-nav a{font-size:11px;font-weight:500;letter-spacing:0.1em;text-transform:uppercase;color:#888;text-decoration:none;}
.zh-nav a:hover{color:#111;}
.zh-right{margin-left:auto;display:flex;align-items:center;gap:1.5rem;flex-shrink:0;}
.zh-book{font-size:11px;font-weight:500;letter-spacing:0.1em;text-transform:uppercase;text-decoration:none;padding:9px 20px;background:#111;color:#fff;}
.zh-book:hover{background:#333;}
.zh-link{font-size:11px;font-weight:500;letter-spacing:0.08em;text-transform:uppercase;color:#888;text-decoration:none;}
.zh-link:hover{color:#111;}
.zh-register{font-size:11px;font-weight:500;letter-spacing:0.08em;text-transform:uppercase;color:#888;text-decoration:none;border:1px solid #ddd;padding:7px 14px;}
.zh-register:hover{border-color:#999;color:#111;}
.zh-logout{font-size:11px;font-weight:500;letter-spacing:0.08em;text-transform:uppercase;color:#bbb;text-decoration:none;}
.zh-logout:hover{color:#c0392b;}
.zh-mob-btn{display:none;background:none;border:none;cursor:pointer;color:#111;padding:4px;}
.zh-mob-menu{display:none;border-top:1px solid #f0f0f0;background:#fff;}
.zh-mob-menu.open{display:block;}
.zh-mob-menu a{display:flex;align-items:center;justify-content:space-between;padding:13px 2.5rem;font-size:11px;font-weight:500;letter-spacing:0.1em;text-transform:uppercase;color:#777;text-decoration:none;border-bottom:1px solid #f5f5f5;}
.zh-mob-menu a:hover{color:#111;}
.zh-mob-book{background:#111!important;color:#fff!important;justify-content:center!important;border:none!important;margin:1rem 2.5rem 1.5rem!important;padding:13px!important;}
.zh-mob-logout{color:#ccc!important;}
.zh-mob-logout:hover{color:#c0392b!important;}
@media(max-width:768px){
    .zh-nav,.zh-right,.zh-divider{display:none!important;}
    .zh-mob-btn{display:flex!important;}
}
</style>

<header class="zh">
<nav>
    <div class="zh-inner">

        <a href="<?= $basePath ?>index.php" class="zh-brand">
            <span class="zh-brand-name">Zayin</span>
            <span class="zh-brand-sub">Guest House</span>
        </a>

        <div class="zh-divider"></div>

        <ul class="zh-nav">
            <li><a href="<?= $basePath ?>index.php#suites">Our Suites</a></li>
            <li><a href="<?= $basePath ?>index.php#rules">Policies</a></li>
            <li><a href="<?= $basePath ?>index.php#location">Location</a></li>
        </ul>

        <div class="zh-right">
            <a href="<?= $basePath ?>index.php#booking-widget" class="zh-book">Book Now</a>

            <?php if (!empty($_SESSION['admin_id'])): ?>
                <a href="<?= $basePath ?>admin/dashboard.php" class="zh-link">Admin</a>
                <a href="<?= $basePath ?>auth/logout.php" class="zh-logout">Logout</a>

            <?php elseif (!empty($_SESSION['user_id'])): ?>
                <a href="<?= $basePath ?>customer/my_bookings.php" class="zh-link">My Bookings</a>
                <a href="<?= $basePath ?>auth/logout.php" class="zh-logout">Logout</a>

            <?php else: ?>
                <a href="<?= $basePath ?>auth/login.php" class="zh-link">Login</a>
                <a href="<?= $basePath ?>auth/register.php" class="zh-register">Register</a>
            <?php endif; ?>
        </div>

        <button class="zh-mob-btn" id="zhBtn" aria-label="Toggle menu">
            <svg id="zhIco" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
                <path d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </div>

    <div class="zh-mob-menu" id="zhMob">
        <a href="<?= $basePath ?>index.php#suites">Our Suites <span>→</span></a>
        <a href="<?= $basePath ?>index.php#rules">Policies <span>→</span></a>
        <a href="<?= $basePath ?>index.php#location">Location <span>→</span></a>
        <?php if (!empty($_SESSION['admin_id'])): ?>
            <a href="<?= $basePath ?>admin/dashboard.php">Admin <span>→</span></a>
            <a href="<?= $basePath ?>auth/logout.php" class="zh-mob-logout">Logout</a>
        <?php elseif (!empty($_SESSION['user_id'])): ?>
            <a href="<?= $basePath ?>customer/my_bookings.php">My Bookings <span>→</span></a>
            <a href="<?= $basePath ?>auth/logout.php" class="zh-mob-logout">Logout</a>
        <?php else: ?>
            <a href="<?= $basePath ?>auth/login.php">Login</a>
            <a href="<?= $basePath ?>auth/register.php">Register</a>
        <?php endif; ?>
        <a href="<?= $basePath ?>index.php#booking-widget" class="zh-mob-book">Book Now</a>
    </div>
</nav>

<script>
(function(){
    var b=document.getElementById('zhBtn'),m=document.getElementById('zhMob'),i=document.getElementById('zhIco');
    b.addEventListener('click',function(){
        m.classList.toggle('open');
        i.querySelector('path').setAttribute('d',m.classList.contains('open')?'M6 18L18 6M6 6l12 12':'M4 6h16M4 12h16M4 18h16');
    });
    m.querySelectorAll('a').forEach(function(a){a.addEventListener('click',function(){m.classList.remove('open');i.querySelector('path').setAttribute('d','M4 6h16M4 12h16M4 18h16');});});
})();
</script>
</header>
<?php
$basePath = '../';
include '../includes/config.php';

// Already logged in
if (!empty($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}
if (!empty($_SESSION['admin_id'])) {
    header('Location: ../admin/dashboard.php');
    exit;
}

$errors = [];
$old    = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ── CSRF ──────────────────────────────────────────────────────────────────
    if (
        empty($_POST['csrf_token']) ||
        empty($_SESSION['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    ) {
        http_response_code(403);
        die('Invalid request. Please go back and try again.');
    }

    $full_name        = trim($_POST['full_name']        ?? '');
    $ic_number        = trim($_POST['ic_number']        ?? '');
    $phone            = trim($_POST['phone']            ?? '');
    $email            = trim($_POST['email']            ?? '');
    $password         = $_POST['password']              ?? '';
    $confirm_password = $_POST['confirm_password']      ?? '';

    $old = compact('full_name', 'ic_number', 'phone', 'email');

    // ── Validate ──────────────────────────────────────────────────────────────
    if (mb_strlen($full_name) < 2)   $errors[] = 'Full name is required.';
    if (mb_strlen($full_name) > 150) $errors[] = 'Full name is too long.';

    if (empty($phone)) {
        $errors[] = 'Phone number is required.';
    } elseif (!preg_match('/^[\d\s\+\-\(\)]{7,20}$/', $phone)) {
        $errors[] = 'Phone number format is invalid.';
    }

    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email address format is invalid.';
    }
    if (mb_strlen($email) > 100) $errors[] = 'Email address is too long.';

    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    } elseif ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match.';
    }

    // ── Check uniqueness ──────────────────────────────────────────────────────
    if (empty($errors) && $conn) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE phone = ? LIMIT 1");
        $stmt->bind_param('s', $phone);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $errors[] = 'That phone number is already registered. Try logging in.';
        }
        $stmt->close();

        if (!empty($email) && empty($errors)) {
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
            $stmt->bind_param('s', $email);
            $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) {
                $errors[] = 'That email address is already registered. Try logging in.';
            }
            $stmt->close();
        }
    }

    // ── Insert ────────────────────────────────────────────────────────────────
    if (empty($errors)) {
        if (!$conn) {
            $errors[] = 'System is temporarily unavailable. Please try again later.';
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare(
                "INSERT INTO users (full_name, ic_number, phone, email, password_hash, is_active)
                 VALUES (?, ?, ?, ?, ?, 1)"
            );
            $stmt->bind_param('sssss', $full_name, $ic_number, $phone, $email, $hash);

            if ($stmt->execute()) {
                $stmt->close();
                $_SESSION['auth_success'] = 'Account created! You can now sign in.';
                header('Location: login.php');
                exit;
            } else {
                $stmt->close();
                $errors[] = 'Registration failed. Please try again.';
            }
        }
    }
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];
$pageTitle  = 'Create Account';
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
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        h1, h2, h3, .font-serif { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-boutique-50 text-slate-700 antialiased">

<?php include '../includes/header.php'; ?>

<main class="max-w-lg mx-auto px-4 py-14">

    <div class="text-center mb-8">
        <h1 class="text-3xl font-serif text-boutique-800 mb-2">Create Account</h1>
        <p class="text-sm text-slate-500">Register to track your bookings easily next time.</p>
    </div>

    <?php if (!empty($errors)): ?>
    <div class="bg-red-50 border border-red-200 text-red-800 px-5 py-4 text-sm mb-6">
        <p class="font-semibold mb-2">Please fix the following:</p>
        <ul class="list-disc list-inside space-y-1">
            <?php foreach ($errors as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <div class="bg-white border border-slate-100 shadow-sm p-8">
        <form method="POST" action="" novalidate>
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

            <div class="space-y-5">

                <!-- Full Name -->
                <div>
                    <label class="block text-xs font-bold tracking-widest uppercase text-slate-500 mb-2" for="full_name">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="full_name" name="full_name" required autocomplete="name"
                           value="<?= htmlspecialchars($old['full_name'] ?? '') ?>"
                           placeholder="As per IC / Passport"
                           class="w-full border border-slate-200 px-4 py-3 text-boutique-800 text-sm focus:outline-none focus:border-boutique-600">
                </div>

                <!-- IC / Passport (optional) -->
                <div>
                    <label class="block text-xs font-bold tracking-widest uppercase text-slate-500 mb-2" for="ic_number">
                        IC / Passport No. <span class="text-slate-400 font-normal normal-case text-xs">(optional)</span>
                    </label>
                    <input type="text" id="ic_number" name="ic_number" autocomplete="off"
                           value="<?= htmlspecialchars($old['ic_number'] ?? '') ?>"
                           placeholder="e.g. 900101-01-1234"
                           class="w-full border border-slate-200 px-4 py-3 text-boutique-800 text-sm focus:outline-none focus:border-boutique-600">
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-xs font-bold tracking-widest uppercase text-slate-500 mb-2" for="phone">
                        Phone Number <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" id="phone" name="phone" required autocomplete="tel"
                           value="<?= htmlspecialchars($old['phone'] ?? '') ?>"
                           placeholder="e.g. 0123456789"
                           class="w-full border border-slate-200 px-4 py-3 text-boutique-800 text-sm focus:outline-none focus:border-boutique-600">
                    <p class="text-xs text-slate-400 mt-1">Used to identify your account and bookings.</p>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-xs font-bold tracking-widest uppercase text-slate-500 mb-2" for="email">
                        Email Address <span class="text-slate-400 font-normal normal-case text-xs">(optional)</span>
                    </label>
                    <input type="email" id="email" name="email" autocomplete="email"
                           value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                           placeholder="you@example.com"
                           class="w-full border border-slate-200 px-4 py-3 text-boutique-800 text-sm focus:outline-none focus:border-boutique-600">
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-xs font-bold tracking-widest uppercase text-slate-500 mb-2" for="password">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required
                               autocomplete="new-password"
                               placeholder="Minimum 8 characters"
                               class="w-full border border-slate-200 px-4 py-3 pr-12 text-boutique-800 text-sm focus:outline-none focus:border-boutique-600">
                        <button type="button" id="togglePw"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-boutique-600 transition-colors"
                                aria-label="Toggle password visibility">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-xs font-bold tracking-widest uppercase text-slate-500 mb-2" for="confirm_password">
                        Confirm Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" id="confirm_password" name="confirm_password" required
                           autocomplete="new-password"
                           placeholder="Re-enter your password"
                           class="w-full border border-slate-200 px-4 py-3 text-boutique-800 text-sm focus:outline-none focus:border-boutique-600">
                </div>

                <button type="submit"
                        class="w-full bg-boutique-800 hover:bg-boutique-900 text-white py-4 text-sm font-bold tracking-widest uppercase transition-colors">
                    Create Account
                </button>
            </div>
        </form>

        <div class="mt-6 pt-6 border-t border-slate-100 text-center">
            <p class="text-sm text-slate-500">
                Already have an account?
                <a href="login.php" class="text-boutique-600 font-semibold hover:underline">Sign in</a>
            </p>
        </div>
    </div>

    <p class="text-center text-xs text-slate-400 mt-6">
        Booking without an account?
        <a href="../index.php#booking-widget" class="text-boutique-600 hover:underline">Continue as guest</a>
    </p>

</main>

<?php include '../includes/footer.php'; ?>

<script>
    (function () {
        var btn = document.getElementById('togglePw');
        var pw  = document.getElementById('password');
        if (!btn || !pw) return;
        btn.addEventListener('click', function () {
            pw.type = pw.type === 'password' ? 'text' : 'password';
        });
    })();
</script>

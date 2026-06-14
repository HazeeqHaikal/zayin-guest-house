<?php
$basePath = '../';
include '../includes/config.php';

// Already logged in — redirect
if (!empty($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}
if (!empty($_SESSION['admin_id'])) {
    header('Location: ../admin/dashboard.php');
    exit;
}

$error   = $_SESSION['auth_error']   ?? '';
$success = $_SESSION['auth_success'] ?? '';
unset($_SESSION['auth_error'], $_SESSION['auth_success']);

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

    $identifier = trim($_POST['identifier'] ?? '');
    $password   = $_POST['password'] ?? '';

    if (empty($identifier) || empty($password)) {
        $error = 'Please fill in all fields.';
    } elseif (!$conn) {
        $error = 'System is temporarily unavailable. Please try again later.';
    } else {
        $logged_in = false;

        // ── Try customer login (email or phone) ───────────────────────────────
        $stmt = $conn->prepare(
            "SELECT id, full_name, email, phone, password_hash
             FROM users
             WHERE (email = ? OR phone = ?) AND password_hash IS NOT NULL AND is_active = 1
             LIMIT 1"
        );
        $stmt->bind_param('ss', $identifier, $identifier);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['user_id']    = (int)$user['id'];
            $_SESSION['user_name']  = $user['full_name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_phone'] = $user['phone'];
            $logged_in = true;

            $redirect = $_SESSION['login_redirect'] ?? '../index.php';
            unset($_SESSION['login_redirect']);
            header('Location: ' . $redirect);
            exit;
        }

        // ── Try admin login (username) ────────────────────────────────────────
        if (!$logged_in) {
            $stmt = $conn->prepare(
                "SELECT id, full_name, username, password_hash
                 FROM admin_users
                 WHERE username = ? AND is_active = 1
                 LIMIT 1"
            );
            $stmt->bind_param('s', $identifier);
            $stmt->execute();
            $admin = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if ($admin && password_verify($password, $admin['password_hash'])) {
                session_regenerate_id(true);
                $_SESSION['admin_id']   = (int)$admin['id'];
                $_SESSION['admin_name'] = $admin['full_name'];

                // Update last_login
                $stmt = $conn->prepare("UPDATE admin_users SET last_login = NOW() WHERE id = ?");
                $stmt->bind_param('i', $admin['id']);
                $stmt->execute();
                $stmt->close();

                $logged_in = true;
                header('Location: ../admin/dashboard.php');
                exit;
            }
        }

        if (!$logged_in) {
            $error = 'Incorrect email / phone / username or password.';
        }
    }
}

// Regenerate CSRF token if not present
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];
$pageTitle  = 'Login';
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

<main class="min-h-[70vh] flex items-center justify-center px-4 py-14">
    <div class="w-full max-w-md">

        <div class="text-center mb-8">
            <h1 class="text-3xl font-serif text-boutique-800 mb-2">Welcome Back</h1>
            <p class="text-sm text-slate-500">Sign in to your account or continue as a <a href="../index.php#booking-widget" class="text-boutique-600 hover:underline">guest</a>.</p>
        </div>

        <?php if ($success): ?>
        <div class="bg-green-50 border border-green-200 text-green-800 px-5 py-3 text-sm mb-6 flex items-start gap-3">
            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <?= htmlspecialchars($success) ?>
        </div>
        <?php endif; ?>

        <?php if ($error): ?>
        <div class="bg-red-50 border border-red-200 text-red-800 px-5 py-3 text-sm mb-6 flex items-start gap-3">
            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <div class="bg-white border border-slate-100 shadow-sm p-8">
            <form method="POST" action="" novalidate>
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

                <div class="space-y-5">
                    <div>
                        <label class="block text-xs font-bold tracking-widest uppercase text-slate-500 mb-2" for="identifier">
                            Email, Phone, or Username
                        </label>
                        <input type="text" id="identifier" name="identifier" required autofocus
                               autocomplete="username"
                               value="<?= htmlspecialchars($_POST['identifier'] ?? '') ?>"
                               placeholder="e.g. you@email.com or 0123456789"
                               class="w-full border border-slate-200 px-4 py-3 text-boutique-800 text-sm focus:outline-none focus:border-boutique-600">
                    </div>

                    <div>
                        <label class="block text-xs font-bold tracking-widest uppercase text-slate-500 mb-2" for="password">
                            Password
                        </label>
                        <div class="relative">
                            <input type="password" id="password" name="password" required
                                   autocomplete="current-password"
                                   placeholder="Your password"
                                   class="w-full border border-slate-200 px-4 py-3 pr-12 text-boutique-800 text-sm focus:outline-none focus:border-boutique-600">
                            <button type="button" id="togglePw"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-boutique-600 transition-colors"
                                    aria-label="Toggle password visibility">
                                <svg id="pwEye" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full bg-boutique-800 hover:bg-boutique-900 text-white py-4 text-sm font-bold tracking-widest uppercase transition-colors">
                        Sign In
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-6 border-t border-slate-100 text-center">
                <p class="text-sm text-slate-500">
                    Don't have an account?
                    <a href="register.php" class="text-boutique-600 font-semibold hover:underline">Register here</a>
                </p>
            </div>
        </div>

        <p class="text-center text-xs text-slate-400 mt-6">
            Booking without an account?
            <a href="../index.php#booking-widget" class="text-boutique-600 hover:underline">Continue as guest</a>
        </p>

    </div>
</main>

<?php include '../includes/footer.php'; ?>

<script>
    (function () {
        var btn   = document.getElementById('togglePw');
        var input = document.getElementById('password');
        if (!btn || !input) return;
        btn.addEventListener('click', function () {
            input.type = input.type === 'password' ? 'text' : 'password';
        });
    })();
</script>

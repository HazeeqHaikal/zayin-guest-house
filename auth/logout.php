<?php
include '../includes/config.php';

// Unset customer session keys
unset(
    $_SESSION['user_id'],
    $_SESSION['user_name'],
    $_SESSION['user_email'],
    $_SESSION['user_phone']
);

// Unset admin session keys
unset(
    $_SESSION['admin_id'],
    $_SESSION['admin_name']
);

header('Location: ../index.php');
exit;

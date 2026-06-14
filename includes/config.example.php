<?php
// ============================================================
// ZAYIN GUEST HOUSE — Config Template
// ============================================================
// This file is committed to Git as a reference template.
// To use: copy this file to config.php and fill in real values.
//   cp includes/config.example.php includes/config.php
// ============================================================

define('DB_HOST', 'localhost');
define('DB_USER', 'YOUR_DB_USERNAME');
define('DB_PASS', 'YOUR_DB_PASSWORD');
define('DB_NAME', 'YOUR_DB_NAME');

define('SITE_URL', 'http://localhost/zayin-guest-house');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    $conn = null;
} else {
    $conn->set_charset('utf8mb4');
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

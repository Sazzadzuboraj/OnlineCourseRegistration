<?php
// Admin Config - same database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'onlinecourse');

$bd = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$bd) {
    die("Database connection failed: " . mysqli_connect_error());
}
mysqli_set_charset($bd, "utf8mb4");

define('APP_NAME', 'CourseHub');
define('APP_TAGLINE', 'Admin Panel');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function clean($data) {
    global $bd;
    return mysqli_real_escape_string($bd, trim(htmlspecialchars($data, ENT_QUOTES, 'UTF-8')));
}

function setFlash($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function requireAdmin() {
    if (empty($_SESSION['alogin'])) {
        header('Location: index.php');
        exit;
    }
}
?>

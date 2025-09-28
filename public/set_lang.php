<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../app/Helpers/url.php';

$lang = $_GET['lang'] ?? 'en';
if (in_array($lang, ['en', 'ar', 'tr'], true)) {
    $_SESSION['lang'] = $lang;
}

$back = $_SERVER['HTTP_REFERER'] ?? url('index.php');
header('Location: ' . $back);
exit;


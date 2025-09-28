<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
session_destroy();
require_once __DIR__ . '/../../app/Helpers/url.php';
header("Location: " . url('auth/login.php'));
exit;




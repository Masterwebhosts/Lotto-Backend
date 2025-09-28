<?php
// app/Helpers/csrf.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function csrf_token(): string {
    if (empty($_SESSION['_csrf_token'])) {
        $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf_token'];
}

function csrf_field(): string {
    $token = htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8');
    return '<input type="hidden" name="_csrf_token" value="'.$token.'">';
}

function verify_csrf(): bool {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST['_csrf_token'] ?? '';
        return hash_equals($_SESSION['_csrf_token'] ?? '', $token);
    }
    return true;
}

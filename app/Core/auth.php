<?php
// app/Core/auth.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * ✅ Check if the user is logged in
 *
 * @param string|null $role Expected role ("user" or "admin")
 */
function require_login(?string $role = null): void
{
    if (!isset($_SESSION['user_id'])) {
        header("Location: /lotto-backend/auth/login.php");
        exit;
    }

    if ($role !== null && ($_SESSION['role'] ?? '') !== $role) {
        header("Location: /lotto-backend/auth/login.php");
        exit;
    }
}

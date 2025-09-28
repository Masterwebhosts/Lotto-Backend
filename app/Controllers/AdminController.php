<?php
class AdminController {
    public function login($email, $password) {
        if ($email === "admin@example.com" && $password === "admin123") {
            $_SESSION['admin'] = true;
            return true;
        }
        return false;
    }

    public function dashboard() {
        if (!isset($_SESSION['admin'])) {
            header("Location: /lotto-backend/public/admin/login.php");
            exit;
        }
        echo "<h1>مرحباً بك في لوحة تحكم الأدمن</h1>";
    }

    public function logout() {
        unset($_SESSION['admin']);
        header("Location: /lotto-backend/public/admin/login.php");
        exit;
    }
}

<?php
require_once __DIR__ . '/../Core/Router.php';

$router = new Router();

// صفحة تسجيل الدخول (GET)
$router->get('/login', 'AuthController@loginForm');

// معالجة تسجيل الدخول (POST)
$router->post('/login', 'AuthController@login');

// صفحة تسجيل الخروج
$router->get('/logout', 'AuthController@logout');

// صفحة لوحة تحكم الادمن
$router->get('/admin/dashboard', function() {
    require_once __DIR__ . '/../Controllers/AdminController.php';
    $adminController = new AdminController();
    $adminController->dashboard();
});

// صفحة لوحة تحكم المستخدم
$router->get('/user/dashboard', function() {
    require_once __DIR__ . '/../Controllers/UserController.php';
    $userController = new UserController();
    $userController->dashboard();
});

// تنفيذ الـ Router
$router->dispatch();

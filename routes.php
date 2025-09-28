<?php
require_once __DIR__ . '/../Core/Router.php';

$router = new Router();

// صفحة تسجيل الدخول
$router->get('/login', 'AuthController@loginForm');
$router->post('/login', 'AuthController@login');

// تسجيل الخروج
$router->get('/logout', 'AuthController@logout');

// لوحة تحكم الأدمن
$router->get('/admin/dashboard', 'AdminController@dashboard');

// ✅ أضف هذا الجديد لإدارة السحوبات
$router->get('/admin/lotteries', 'AdminController@manageLotteries');
$router->post('/admin/lotteries/create', 'AdminController@createLottery');
$router->post('/admin/lotteries/winners', 'AdminController@setWinners');

// لوحة تحكم المستخدم
$router->get('/user/dashboard', 'UserController@dashboard');

$router->dispatch();

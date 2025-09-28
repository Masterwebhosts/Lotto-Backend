<?php
require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

// قراءة ملف .env
if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();
} else {
    die("❌ ملف .env غير موجود في جذر المشروع\n");
}

// إعداد اتصال PDO بقاعدة البيانات
$host = $_ENV['DB_HOST'] ?? 'localhost';
$db   = $_ENV['DB_NAME'] ?? 'lotto_backend';
$user = $_ENV['DB_USER'] ?? 'root';
$pass = $_ENV['DB_PASSWORD'] ?? '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("❌ فشل الاتصال بقاعدة البيانات: " . $e->getMessage());
}



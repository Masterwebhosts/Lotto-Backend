<?php
require __DIR__ . '/bootstrap.php';

// تحميل متغيرات البيئة
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$host = $_ENV['DB_HOST'] ?? 'localhost';
$db   = $_ENV['DB_NAME'] ?? 'lotto_backend';
$user = $_ENV['DB_USER'] ?? 'root';
$pass = $_ENV['DB_PASSWORD'] ?? '';

$pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "✅ الاتصال بقاعدة البيانات ناجح!\n";

// حساب المسؤول الافتراضي من .env
$adminEmail = $_ENV['DEFAULT_ADMIN_EMAIL'] ?? 'admin@example.com';
$adminPassword = $_ENV['DEFAULT_ADMIN_PASSWORD'] ?? 'admin123';

// جلب جميع المستخدمين
$stmt = $pdo->query("SELECT id, email, password, role FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($users as $userRow) {
    $id = $userRow['id'];
    $email = $userRow['email'];
    $role = $userRow['role'];

    // إذا كانت كلمة المرور ليست مشفرة بـ password_hash
    if (!password_verify($adminPassword, $userRow['password'])) {
        // إذا كان البريد الإلكتروني للمسؤول الافتراضي
        if ($email === $adminEmail) {
            $hashed = password_hash($adminPassword, PASSWORD_DEFAULT);
            $pdo->exec("UPDATE users SET password='$hashed', role='admin' WHERE id=$id");
            echo "✅ تم تحديث كلمة المرور وتعيين الدور Admin للبريد: $email\n";
        } else {
            // للمستخدمين العاديين، لا نغير كلمة المرور إلا إذا كانت MD5 (32 حرف)
            if (strlen($userRow['password']) === 32) {
                $hashed = password_hash($userRow['password'], PASSWORD_DEFAULT);
                $pdo->exec("UPDATE users SET password='$hashed' WHERE id=$id");
                echo "ℹ️ تم تحديث كلمة المرور للمستخدم: $email\n";
            }
        }
    }
}

echo "🎉 جميع الحسابات جاهزة الآن لتسجيل الدخول.\n";

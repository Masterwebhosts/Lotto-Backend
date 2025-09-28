<?php
require __DIR__ . '/bootstrap.php';

use Dotenv\Dotenv;

// تحميل متغيرات البيئة
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// إعدادات قاعدة البيانات من .env
$host = $_ENV['DB_HOST'] ?? 'localhost';
$db   = $_ENV['DB_NAME'] ?? 'lotto_backend';
$user = $_ENV['DB_USER'] ?? 'root';
$pass = $_ENV['DB_PASSWORD'] ?? '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ الاتصال بقاعدة البيانات ناجح!\n";

    // ======== إنشاء الجداول ========
    $queries = [

        // جدول المستخدمين
        "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(150) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            role ENUM('admin','user') NOT NULL DEFAULT 'user',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB;",

        // جدول المعاملات
        "CREATE TABLE IF NOT EXISTS transactions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            amount DECIMAL(10,2) NOT NULL,
            type ENUM('deposit','withdraw') NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB;",

        // جدول اليانصيب
        "CREATE TABLE IF NOT EXISTS lotteries (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            draw_date DATE NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB;",

        // جدول الرهانات
        "CREATE TABLE IF NOT EXISTS bets (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            lottery_id INT NOT NULL,
            number VARCHAR(20) NOT NULL,
            amount DECIMAL(10,2) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (lottery_id) REFERENCES lotteries(id) ON DELETE CASCADE
        ) ENGINE=InnoDB;"
    ];

    foreach ($queries as $query) {
        $pdo->exec($query);
    }

    echo "✅ جميع الجداول تم إنشاؤها بنجاح.\n";

    // ======== إنشاء حساب مسؤول افتراضي ========
    $adminEmail = $_ENV['DEFAULT_ADMIN_EMAIL'] ?? 'admin@example.com';
    $adminPassword = $_ENV['DEFAULT_ADMIN_PASSWORD'] ?? 'admin123';

    // تحقق إذا كان موجودًا بالفعل
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
    $stmt->execute(['email' => $adminEmail]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$existing) {
        $hashedPassword = password_hash($adminPassword, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name,email,password,role) VALUES (:name,:email,:password,'admin')");
        $stmt->execute([
            'name' => 'Administrator',
            'email' => $adminEmail,
            'password' => $hashedPassword
        ]);
        echo "✅ تم إنشاء حساب المسؤول الافتراضي: $adminEmail / $adminPassword\n";
    } else {
        echo "ℹ️ حساب المسؤول موجود بالفعل.\n";
    }

    echo "🎉 جاهز لتسجيل الدخول الآن.\n";

} catch (PDOException $e) {
    echo "❌ فشل الاتصال: " . $e->getMessage() . "\n";
    exit;
}

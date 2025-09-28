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
    // إنشاء اتصال PDO
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ الاتصال بقاعدة البيانات ناجح!\n";

    // =========================
    // جداول Frontend فقط
    // =========================
    $queries = [

        "CREATE TABLE IF NOT EXISTS results (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            draw_date DATE NOT NULL,
            winning_numbers VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

        "CREATE TABLE IF NOT EXISTS tickets (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(255) NOT NULL,
            numbers VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
    ];

    foreach ($queries as $query) {
        $pdo->exec($query);
    }

    echo "✅ جداول Frontend تم إنشاؤها بنجاح.\n";

} catch (PDOException $e) {
    echo "❌ فشل إنشاء جداول Frontend: " . $e->getMessage() . "\n";
    exit;
}

<?php
try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=lotto_backend;charset=utf8mb4", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $newPassword = "admin1234";
    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("UPDATE users SET password=? WHERE email=?");
    $stmt->execute([$hashedPassword, "admin@example.com"]);

    echo "✅ تم تحديث كلمة مرور الأدمن بنجاح.<br>";
    echo "البريد: admin@example.com<br>";
    echo "كلمة المرور الجديدة: {$newPassword}<br>";
    echo "<a href='public/auth/login.php'>الذهاب إلى تسجيل الدخول</a>";
} catch (PDOException $e) {
    echo "❌ خطأ في قاعدة البيانات: " . $e->getMessage();
}

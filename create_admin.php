<?php
require_once __DIR__ . '/app/Models/User.php';

$userModel = new User();

// كلمة المرور الجديدة للأدمن
$password = "admin1234";
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// التحقق إن كان الأدمن موجود مسبقاً
$existingAdmin = $userModel->getByEmail("admin@example.com");
if ($existingAdmin) {
    echo "⚠️ حساب الأدمن موجود مسبقاً. يمكنك تسجيل الدخول مباشرة.";
    exit;
}

// إنشاء الأدمن
$success = $userModel->create([
    'name'     => 'Admin',
    'email'    => 'admin@example.com',
    'password' => $hashedPassword,
    'role'     => 'admin'
]);

if ($success) {
    echo "✅ تم إنشاء حساب الأدمن بنجاح.<br>";
    echo "البريد: admin@example.com<br>";
    echo "كلمة المرور: admin1234<br>";
    echo "الآن يمكنك الذهاب إلى <a href='public/auth/login.php'>صفحة تسجيل الدخول</a>";
} else {
    echo "❌ فشل إنشاء حساب الأدمن.";
}

<?php
// إعدادات قاعدة البيانات
$db_host = "localhost";          // السيرفر
$db_user = "root";               // المستخدم الافتراضي في XAMPP
$db_pass = "";                   // الباسورد الافتراضي (غالباً فاضي في XAMPP)
$db_name = "lotto-backend";      // اسم قاعدة البيانات

// إنشاء الاتصال
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

// تحديد الترميز UTF-8
$conn->set_charset("utf8mb4");
?>

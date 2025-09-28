<?php
require_once __DIR__ . '/../app/Core/Database.php';

echo "<h2>🔍 فحص اتصال قاعدة البيانات</h2>";

try {
    $db = new Database();

    if ($db->pdo instanceof PDO) {
        echo "<p style='color:green'>✅ تم الاتصال بقاعدة البيانات بنجاح</p>";

        // فحص الجداول الأساسية
        $tables = ['users', 'lotteries', 'bets', 'transactions'];
        echo "<h3>📋 فحص الجداول:</h3><ul>";

        foreach ($tables as $table) {
            try {
                $stmt = $db->pdo->query("SELECT COUNT(*) as cnt FROM `$table`");
                $count = $stmt->fetch(PDO::FETCH_ASSOC)['cnt'];
                echo "<li>✅ الجدول <b>$table</b> موجود ويحوي <b>$count</b> صفوف</li>";
            } catch (PDOException $e) {
                echo "<li style='color:red'>❌ الجدول <b>$table</b> غير موجود أو خطأ: " . $e->getMessage() . "</li>";
            }
        }

        echo "</ul>";
    } else {
        echo "<p style='color:red'>❌ الاتصال لم يتم بشكل صحيح.</p>";
    }
} catch (Exception $e) {
    echo "<p style='color:red'>❌ فشل الاتصال بقاعدة البيانات: " . $e->getMessage() . "</p>";
}

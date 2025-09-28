<?php
// استدعاء ملف الاتصال الصحيح من داخل مجلد config
require_once __DIR__ . '/config/config.php';

// تجربة استعلام: جلب أول 5 مستخدمين من جدول users
$sql = "SELECT id, name, email, balance, role, created_at FROM users LIMIT 5";
$result = $conn->query($sql);

if ($result === false) {
    die("خطأ في الاستعلام: " . $conn->error);
}

if ($result->num_rows > 0) {
    echo "<h2>أول 5 مستخدمين في قاعدة البيانات:</h2>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr>
            <th>ID</th>
            <th>الاسم</th>
            <th>البريد</th>
            <th>الرصيد</th>
            <th>الدور</th>
            <th>تاريخ الإنشاء</th>
          </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['name']}</td>
                <td>{$row['email']}</td>
                <td>{$row['balance']}</td>
                <td>{$row['role']}</td>
                <td>{$row['created_at']}</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "لا يوجد مستخدمين في قاعدة البيانات.";
}

// إغلاق الاتصال
$conn->close();
?>

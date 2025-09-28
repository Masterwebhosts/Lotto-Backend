<?php
// استدعاء الاتصال بقاعدة البيانات
require_once __DIR__ . '/config/config.php';

// استعلام: جلب آخر 10 عمليات مالية
$sql = "SELECT t.id, u.name AS user_name, t.amount, t.type, t.note, t.created_at
        FROM transactions t
        LEFT JOIN users u ON t.user_id = u.id
        ORDER BY t.created_at DESC
        LIMIT 10";

$result = $conn->query($sql);

if ($result === false) {
    die("خطأ في الاستعلام: " . $conn->error);
}

if ($result->num_rows > 0) {
    echo "<h2>أحدث 10 معاملات (Transactions)</h2>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr>
            <th>ID</th>
            <th>المستخدم</th>
            <th>المبلغ</th>
            <th>النوع</th>
            <th>الوصف</th>
            <th>تاريخ الإنشاء</th>
          </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['user_name']}</td>
                <td>{$row['amount']}</td>
                <td>{$row['type']}</td>
                <td>{$row['note']}</td>
                <td>{$row['created_at']}</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "لا توجد معاملات في قاعدة البيانات.";
}

// إغلاق الاتصال
$conn->close();
?>

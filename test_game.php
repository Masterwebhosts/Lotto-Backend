<?php
// استدعاء الاتصال بقاعدة البيانات
require_once __DIR__ . '/config/config.php';

// استعلام: جلب أول 10 رهانات من جدول game_bets
$sql = "SELECT g.id, u.name AS user_name, g.numbers, g.multiplier, g.amount, g.result, g.status, g.profit, g.demo, g.created_at
        FROM game_bets g
        LEFT JOIN users u ON g.user_id = u.id
        ORDER BY g.created_at DESC
        LIMIT 10";

$result = $conn->query($sql);

if ($result === false) {
    die("خطأ في الاستعلام: " . $conn->error);
}

if ($result->num_rows > 0) {
    echo "<h2>أحدث 10 رهانات (Game Bets)</h2>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr>
            <th>ID</th>
            <th>المستخدم</th>
            <th>الأرقام</th>
            <th>المضاعف</th>
            <th>المبلغ</th>
            <th>النتيجة</th>
            <th>الحالة</th>
            <th>الربح</th>
            <th>نوع اللعب</th>
            <th>تاريخ الإنشاء</th>
          </tr>";

    while ($row = $result->fetch_assoc()) {
        $demo = $row['demo'] ? "تجريبي" : "حقيقي";
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['user_name']}</td>
                <td>{$row['numbers']}</td>
                <td>{$row['multiplier']}</td>
                <td>{$row['amount']}</td>
                <td>{$row['result']}</td>
                <td>{$row['status']}</td>
                <td>{$row['profit']}</td>
                <td>{$demo}</td>
                <td>{$row['created_at']}</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "لا توجد رهانات في قاعدة البيانات.";
}

// إغلاق الاتصال
$conn->close();
?>

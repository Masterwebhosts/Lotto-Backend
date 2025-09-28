<?php
use App\Core\Database;

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: /auth/login.php");
    exit;
}

$db = Database::getInstance();

// جلب بيانات السحوبات مع إحصائيات
$sql = "
    SELECT 
        l.id,
        l.name,
        l.start_at,
        l.end_at,
        l.winning_numbers,
        COUNT(b.id) AS bets_count,
        COALESCE(SUM(b.amount), 0) AS total_bets,
        (
            SELECT COALESCE(SUM(t.amount), 0) 
            FROM transactions t 
            WHERE t.user_id IN (SELECT user_id FROM bets WHERE lottery_id = l.id)
              AND t.type = 'win'
        ) AS total_prizes
    FROM lotteries l
    LEFT JOIN bets b ON l.id = b.lottery_id
    GROUP BY l.id
    ORDER BY l.id DESC
";
$lotteries = $db->query($sql)->fetchAll();
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<h1>تقرير السحوبات</h1>

<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>#</th>
            <th>اسم السحب</th>
            <th>الفترة</th>
            <th>الأرقام الفائزة</th>
            <th>عدد المشاركين</th>
            <th>إجمالي المبالغ المرهونة</th>
            <th>إجمالي الجوائز المصروفة</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($lotteries as $lottery): ?>
            <tr>
                <td><?= $lottery['id'] ?></td>
                <td><?= htmlspecialchars($lottery['name']) ?></td>
                <td>
                    <?= htmlspecialchars($lottery['start_at']) ?> → 
                    <?= htmlspecialchars($lottery['end_at']) ?>
                </td>
                <td><?= $lottery['winning_numbers'] ?: '-' ?></td>
                <td><?= $lottery['bets_count'] ?></td>
                <td><?= number_format($lottery['total_bets'], 2) ?> $</td>
                <td><?= number_format($lottery['total_prizes'], 2) ?> $</td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include __DIR__ . '/../includes/footer.php'; ?>

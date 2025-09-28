<?php
use App\Core\Database;

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: /auth/login.php");
    exit;
}

$db = Database::getInstance();

// جلب عمليات الشحن فقط
$sql = "
    SELECT 
        t.id,
        t.amount,
        t.created_at,
        u.name,
        u.email
    FROM transactions t
    JOIN users u ON t.user_id = u.id
    WHERE t.type = 'deposit'
    ORDER BY t.created_at DESC
";
$transactions = $db->query($sql)->fetchAll();
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<h1>تقرير عمليات شحن الرصيد</h1>

<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>#</th>
            <th>المستخدم</th>
            <th>البريد الإلكتروني</th>
            <th>المبلغ</th>
            <th>تاريخ العملية</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($transactions as $t): ?>
            <tr>
                <td><?= $t['id'] ?></td>
                <td><?= htmlspecialchars($t['name']) ?></td>
                <td><?= htmlspecialchars($t['email']) ?></td>
                <td><?= number_format($t['amount'], 2) ?> $</td>
                <td><?= $t['created_at'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include __DIR__ . '/../includes/footer.php'; ?>

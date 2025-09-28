<?php
require_once __DIR__ . '/../../app/Helpers/csrf.php';

use App\Core\Database;
use App\Controllers\Lottery;

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: /auth/login.php");
    exit;
}

$db = Database::getInstance();
$message = "";

// جلب السحوبات اللي لها أرقام فائزة لكن ما تم معالجتها
$lotteries = $db->query("
    SELECT * FROM lotteries 
    WHERE winning_numbers IS NOT NULL 
    ORDER BY end_at DESC
")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $message = "⚠️ خطأ أمني.";
    } else {
        $lotteryId = intval($_POST['lottery_id']);
        if (Lottery::processWinners($lotteryId)) {
            $message = "✅ تم إعلان النتائج وتوزيع الجوائز للسحب #$lotteryId";
        } else {
            $message = "❌ لم يتم العثور على السحب أو لا يحتوي على أرقام فائزة.";
        }
    }
}
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<h1>إعلان النتائج وتوزيع الجوائز</h1>
<?php if ($message): ?><div><?= htmlspecialchars($message) ?></div><?php endif; ?>

<form method="post">
    <?= csrf_field(); ?>
    <label>اختر السحب</label>
    <select name="lottery_id" required>
        <?php foreach ($lotteries as $lottery): ?>
            <option value="<?= $lottery['id'] ?>">
                <?= htmlspecialchars($lottery['name']) ?> (<?= $lottery['end_at'] ?>) - أرقام فائزة: <?= $lottery['winning_numbers'] ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit">إعلان النتائج وتوزيع الجوائز</button>
</form>

<?php include __DIR__ . '/../includes/footer.php'; ?>

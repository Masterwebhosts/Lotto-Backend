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

// معالجة الطلبات
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $message = "⚠️ خطأ أمني.";
    } else {
        if (isset($_POST['action']) && $_POST['action'] === 'create') {
            $name     = trim($_POST['name']);
            $start_at = $_POST['start_at'];
            $end_at   = $_POST['end_at'];

            $stmt = $db->prepare("INSERT INTO lotteries (name, start_at, end_at) VALUES (?, ?, ?)");
            if ($stmt->execute([$name, $start_at, $end_at])) {
                $message = "✅ تم إنشاء السحب الجديد.";
            } else {
                $message = "❌ فشل إنشاء السحب.";
            }
        }

        if (isset($_POST['action']) && $_POST['action'] === 'set_winners') {
            $lotteryId = intval($_POST['lottery_id']);
            $numbers   = trim($_POST['winning_numbers']);

            $stmt = $db->prepare("UPDATE lotteries SET winning_numbers = ?, draw_date = NOW() WHERE id = ?");
            if ($stmt->execute([$numbers, $lotteryId])) {
                $message = "✅ تم تحديد الأرقام الفائزة.";
            } else {
                $message = "❌ فشل تحديد الأرقام.";
            }
        }

        if (isset($_POST['action']) && $_POST['action'] === 'announce') {
            $lotteryId = intval($_POST['lottery_id']);
            if (Lottery::processWinners($lotteryId)) {
                $message = "🎉 تم إعلان النتائج وتوزيع الجوائز للسحب #$lotteryId";
            } else {
                $message = "❌ لم يتم إعلان النتائج، تحقق من الأرقام.";
            }
        }
    }
}

// جلب السحوبات
$lotteries = $db->query("SELECT * FROM lotteries ORDER BY id DESC")->fetchAll();
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<h1>لوحة إدارة السحوبات</h1>
<?php if ($message): ?><div style="margin:10px 0;color:darkblue;"><?= htmlspecialchars($message) ?></div><?php endif; ?>

<!-- 🟢 إنشاء سحب جديد -->
<section style="border:1px solid #ccc;padding:15px;margin-bottom:20px;">
    <h2>إنشاء سحب جديد</h2>
    <form method="post">
        <?= csrf_field(); ?>
        <input type="hidden" name="action" value="create">

        <label>اسم السحب</label>
        <input type="text" name="name" required>

        <label>تاريخ البداية</label>
        <input type="datetime-local" name="start_at" required>

        <label>تاريخ النهاية</label>
        <input type="datetime-local" name="end_at" required>

        <button type="submit">إنشاء</button>
    </form>
</section>

<!-- 🟡 تحديد الأرقام الفائزة -->
<section style="border:1px solid #ccc;padding:15px;margin-bottom:20px;">
    <h2>تحديد الأرقام الفائزة</h2>
    <form method="post">
        <?= csrf_field(); ?>
        <input type="hidden" name="action" value="set_winners">

        <label>اختر السحب</label>
        <select name="lottery_id" required>
            <?php foreach ($lotteries as $lottery): ?>
                <option value="<?= $lottery['id'] ?>">
                    <?= htmlspecialchars($lottery['name']) ?> (<?= $lottery['end_at'] ?>)
                </option>
            <?php endforeach; ?>
        </select>

        <label>الأرقام الفائزة (مثال: 5,12,23,34,45,50)</label>
        <input type="text" name="winning_numbers" required>

        <button type="submit">تحديد</button>
    </form>
</section>

<!-- 🔵 إعلان النتائج -->
<section style="border:1px solid #ccc;padding:15px;">
    <h2>إعلان النتائج وتوزيع الجوائز</h2>
    <form method="post">
        <?= csrf_field(); ?>
        <input type="hidden" name="action" value="announce">

        <label>اختر السحب</label>
        <select name="lottery_id" required>
            <?php foreach ($lotteries as $lottery): ?>
                <?php if (!empty($lottery['winning_numbers'])): ?>
                    <option value="<?= $lottery['id'] ?>">
                        <?= htmlspecialchars($lottery['name']) ?> - أرقام: <?= htmlspecialchars($lottery['winning_numbers']) ?>
                    </option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>

        <button type="submit">إعلان النتائج</button>
    </form>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>

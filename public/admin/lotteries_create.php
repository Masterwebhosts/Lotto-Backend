<?php
require_once __DIR__ . '/../../app/Helpers/csrf.php';

use App\Core\Database;

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: /auth/login.php");
    exit;
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $message = "⚠️ خطأ أمني.";
    } else {
        $name     = trim($_POST['name']);
        $start_at = $_POST['start_at'];
        $end_at   = $_POST['end_at'];

        $db = Database::getInstance();
        $stmt = $db->prepare("INSERT INTO lotteries (name, start_at, end_at) VALUES (?, ?, ?)");
        if ($stmt->execute([$name, $start_at, $end_at])) {
            $message = "✅ تم إنشاء السحب بنجاح";
        } else {
            $message = "❌ فشل إنشاء السحب.";
        }
    }
}
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<h1>إنشاء سحب جديد</h1>
<?php if ($message): ?><div><?= $message ?></div><?php endif; ?>

<form method="post">
    <?= csrf_field(); ?>
    <label>اسم السحب</label>
    <input type="text" name="name" required>

    <label>تاريخ البداية</label>
    <input type="datetime-local" name="start_at" required>

    <label>تاريخ النهاية</label>
    <input type="datetime-local" name="end_at" required>

    <button type="submit">إنشاء</button>
</form>

<?php include __DIR__ . '/../includes/footer.php'; ?>

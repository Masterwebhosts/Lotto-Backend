<?php
require_once __DIR__ . '/../../app/Helpers/csrf.php';

use App\Models\User;
use App\Models\Bet;
use App\Models\Transaction;

// حماية الجلسة
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /auth/login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$user   = User::find($userId);
$message = "";

// معالجة الرهان
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $message = "⚠️ خطأ أمني، أعد المحاولة.";
    } else {
        $lotteryId = intval($_POST['lottery_id'] ?? 0);
        $amount    = floatval($_POST['amount'] ?? 0);
        $numbers   = trim($_POST['numbers'] ?? '');

        if ($lotteryId && $amount > 0 && $numbers) {
            if ($user['balance'] >= $amount) {
                // خصم الرصيد
                User::updateBalance($userId, -$amount);

                // تسجيل الرهان
                Bet::create($userId, $lotteryId, $amount, $numbers);

                // إضافة معاملة
                Transaction::create($userId, -$amount, "bet");

                $message = "✅ تم تسجيل رهانك على السحب رقم {$lotteryId}";
                $user = User::find($userId); // تحديث الرصيد
            } else {
                $message = "❌ رصيدك غير كافٍ.";
            }
        } else {
            $message = "❌ البيانات غير صحيحة.";
        }
    }
}
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<h1>الرهانات</h1>
<p>الرصيد الحالي: <strong><?= htmlspecialchars($user['balance']) ?> $</strong></p>

<?php if ($message): ?>
    <div class="alert"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<form method="post">
    <?= csrf_field(); ?>
    <label>رقم السحب (Lottery ID)</label>
    <input type="number" name="lottery_id" required>

    <label>الأرقام (مثال: 5,12,23,34,45,50)</label>
    <input type="text" name="numbers" required>

    <label>المبلغ</label>
    <input type="number" name="amount" step="0.01" required>

    <button type="submit">تأكيد الرهان</button>
</form>

<hr>

<h2>رهاناتك السابقة</h2>
<table border="1" cellpadding="8">
    <thead>
        <tr>
            <th>التاريخ</th>
            <th>السحب</th>
            <th>الأرقام</th>
            <th>المبلغ</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach (Bet::findByUser($userId) as $bet): ?>
            <tr>
                <td><?= htmlspecialchars($bet['created_at']) ?></td>
                <td><?= htmlspecialchars($bet['lottery_name']) ?> (#<?= $bet['lottery_id'] ?>)</td>
                <td><?= htmlspecialchars($bet['numbers']) ?></td>
                <td><?= htmlspecialchars($bet['amount']) ?> $</td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include __DIR__ . '/../includes/footer.php'; ?>

<?php
require_once __DIR__ . '/../../app/Helpers/csrf.php';

use App\Core\Database;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: /lotto-backend/public/auth/login.php");
    exit;
}

$message = "";

// ✅ FIX: use getConnection instead of getInstance
$db = Database::getConnection();

// Fetch lotteries without winning numbers
$lotteries = $db->query("
    SELECT id, title, end_at 
    FROM lotteries 
    WHERE winning_numbers IS NULL 
    ORDER BY end_at DESC
")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $message = "⚠️ Security error.";
    } else {
        $lotteryId = intval($_POST['lottery_id']);
        $numbers   = trim($_POST['winning_numbers']);

        $stmt = $db->prepare("UPDATE lotteries SET winning_numbers = ?, draw_date = NOW() WHERE id = ?");
        if ($stmt->execute([$numbers, $lotteryId])) {
            $message = "✅ Winning numbers have been set successfully.";
        } else {
            $message = "❌ Failed to update winning numbers.";
        }
    }
}
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="card shadow p-4">
  <h1 class="mb-4">🎟️ Set Winning Numbers</h1>

  <?php if ($message): ?>
    <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <?php if (empty($lotteries)): ?>
    <div class="alert alert-warning">No lotteries pending results.</div>
  <?php else: ?>
    <form method="post" class="row g-3">
        <?= csrf_field(); ?>

        <div class="col-md-6">
          <label for="lottery_id" class="form-label">Select Lottery</label>
          <select name="lottery_id" id="lottery_id" class="form-select" required>
              <?php foreach ($lotteries as $lottery): ?>
                  <option value="<?= $lottery['id'] ?>">
                      <?= htmlspecialchars($lottery['title']) ?> (Ends <?= date('Y-m-d H:i', strtotime($lottery['end_at'])) ?>)
                  </option>
              <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-6">
          <label for="winning_numbers" class="form-label">Winning Numbers</label>
          <input type="text" name="winning_numbers" id="winning_numbers" 
                 placeholder="e.g., 5,12,23,34,45,50"
                 class="form-control" required>
        </div>

        <div class="col-12">
          <button type="submit" class="btn btn-success">✅ Save</button>
          <a href="/lotto-backend/public/admin/admin_dashboard.php" class="btn btn-secondary">⬅️ Back</a>
        </div>
    </form>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

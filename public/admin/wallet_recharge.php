<?php
require_once __DIR__ . '/../../app/Helpers/csrf.php';
use App\Core\Database;
use App\Models\User;
use App\Models\Transaction;
require_once __DIR__ . '/../../app/Helpers/mail.php';

// Start session safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Admin check
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: /lotto-backend/public/auth/login.php");
    exit;
}

// ✅ FIX: use getConnection (not getInstance)
$db = Database::getConnection();
$message = "";

// Fetch users
$users = $db->query("
    SELECT id, name, email, balance 
    FROM users 
    WHERE role = 'user' 
    ORDER BY id DESC
")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $message = "⚠️ Security error.";
    } else {
        $userId = intval($_POST['user_id']);
        $amount = floatval($_POST['amount']);

        if ($amount > 0) {
            // Update balance
            User::updateBalance($userId, $amount);

            // Add transaction
            Transaction::create($userId, $amount, "deposit");

            // Fetch user info
            $stmt = $db->prepare("SELECT name, email FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch();

            if ($user) {
                // Send email
                $subject = "💰 Your balance has been updated on Lotto Grande";
                $body = "
                    <h2>Hello " . htmlspecialchars($user['name']) . "</h2>
                    <p>An amount of <b>$" . number_format($amount, 2) . "</b> has been added to your balance successfully.</p>
                    <p>You can now participate in the lotteries using your account.</p>
                    <br>
                    <p>Thank you,<br>Lotto Grande Team</p>
                ";
                send_mail($user['email'], $subject, $body);

                $message = "✅ Successfully credited " . htmlspecialchars($user['name']) . " with $" . number_format($amount, 2);
            } else {
                $message = "❌ User not found.";
            }
        } else {
            $message = "❌ Amount must be greater than 0.";
        }
    }
}
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="card shadow p-4">
  <h1 class="mb-4">💳 Credit User Balance</h1>

  <?php if ($message): ?>
    <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <form method="post" class="row g-3">
      <?= csrf_field(); ?>

      <div class="col-md-6">
        <label for="user_id" class="form-label">Select User</label>
        <select name="user_id" id="user_id" class="form-select" required>
            <?php foreach ($users as $u): ?>
                <option value="<?= $u['id'] ?>">
                    <?= htmlspecialchars($u['name']) ?> (Balance: $<?= number_format($u['balance'], 2) ?>)
                </option>
            <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-6">
        <label for="amount" class="form-label">Amount (USD)</label>
        <input type="number" step="0.01" name="amount" id="amount" class="form-control" required>
      </div>

      <div class="col-12">
        <button type="submit" class="btn btn-success">💰 Credit Balance</button>
        <a href="/lotto-backend/public/admin/admin_dashboard.php" class="btn btn-secondary">⬅️ Back</a>
      </div>
  </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

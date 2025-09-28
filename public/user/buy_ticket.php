<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../app/Core/auth.php';

use App\Core\Database;

require_login('user');

// ✅ الاتصال الصحيح
$conn = Database::getConnection();

$user_id = $_SESSION['user_id'];

// جلب رصيد المستخدم
$stmt = $conn->prepare("SELECT balance FROM users WHERE id=?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
$balance = $user['balance'] ?? 0;

// جلب أحدث سحب مفتوح
$stmt = $conn->query("SELECT * FROM lotteries WHERE status='open' ORDER BY id DESC LIMIT 1");
$lottery = $stmt->fetch();

if (!$lottery) {
    die("❌ No active lottery.");
}

// ✅ معالجة شراء التذكرة
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $d1 = (int)$_POST['d1'];
    $d2 = (int)$_POST['d2'];
    $d3 = (int)$_POST['d3'];
    $multiplier = (int)$_POST['multiplier'];

    $ticket_price = (float)$lottery['price'];
    $cost = $ticket_price * $multiplier;

    if ($balance < $cost) {
        $error = "❌ Not enough balance.";
    } else {
        try {
            $conn->beginTransaction();

            // خصم الرصيد
            $stmt = $conn->prepare("UPDATE users SET balance = balance - ? WHERE id=?");
            $stmt->execute([$cost, $user_id]);

            // حفظ التذكرة
            $numbers = "$d1-$d2-$d3";
            $stmt = $conn->prepare("INSERT INTO tickets (user_id, lottery_id, amount, numbers, multiplier, status, created_at) 
                                    VALUES (?, ?, ?, ?, ?, 'pending', NOW())");
            $stmt->execute([$user_id, $lottery['id'], $cost, $numbers, $multiplier]);

            // سجل المعاملة
            $stmt = $conn->prepare("INSERT INTO transactions (user_id, type, amount, description, created_at) 
                                    VALUES (?, 'bet', ?, ?, NOW())");
            $stmt->execute([$user_id, $cost, "Bet on $numbers ×$multiplier"]);

            $conn->commit();
            $success = "✅ Ticket purchased successfully!";
        } catch (Exception $e) {
            $conn->rollBack();
            $error = "❌ Error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Buy Ticket</title>
  <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="card shadow">
    <div class="card-header bg-success text-white">
      <h4>🎟️ Buy Ticket</h4>
    </div>
    <div class="card-body">

      <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
      <?php endif; ?>
      <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
      <?php endif; ?>

      <p><strong>Balance:</strong> <?= number_format($balance,2) ?> USD</p>
      <p><strong>Ticket Price:</strong> <?= $lottery['price'] ?> USD</p>
      <p><strong>Grand Prize:</strong> <?= $lottery['grand_prize'] ?> USD</p>

      <form method="post">
        <div class="mb-3">
          <label class="form-label">Digits (0–9)</label>
          <div class="d-flex gap-2">
            <input type="number" name="d1" min="0" max="9" class="form-control" required>
            <input type="number" name="d2" min="0" max="9" class="form-control" required>
            <input type="number" name="d3" min="0" max="9" class="form-control" required>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Multiplier</label>
          <select name="multiplier" class="form-select">
            <option value="1">×1</option>
            <option value="2">×2</option>
            <option value="3">×3</option>
            <option value="4">×4</option>
            <option value="5">×5</option>
          </select>
        </div>

        <button type="submit" class="btn btn-success">Buy</button>
      </form>

      <a href="/user/dashboard.php" class="btn btn-secondary mt-3">⬅️ Back to Dashboard</a>
    </div>
  </div>
</div>

</body>
</html>

<?php
require_once __DIR__.'/../app/Core/Database.php';
require_once __DIR__.'/../app/Core/auth.php';
require_once __DIR__.'/../app/Models/User.php';
require_once __DIR__.'/../app/Models/Transaction.php';
require_once __DIR__.'/../app/Models/Bet.php';

use App\Core\Database;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Bet;

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['error' => 'Method not allowed']);
  exit;
}

require_login();
$u = current_user();

$nums = [$_POST['n1'] ?? null, $_POST['n2'] ?? null, $_POST['n3'] ?? null];
$mult = (int)($_POST['multiplier'] ?? 1);

foreach ($nums as $i => $v) {
  if ($v === '' || !is_numeric($v) || $v < 0 || $v > 9) {
    echo json_encode(['success' => false, 'message' => 'Invalid numbers']);
    exit;
  }
  $nums[$i] = (int)$v;
}

if ($mult < 1) {
  echo json_encode(['success' => false, 'message' => 'Multiplier must be ≥ 1']);
  exit;
}

$betAmount = 1.0 * $mult;

if ($u['balance'] < $betAmount) {
  echo json_encode([
    'success' => false,
    'message' => 'Insufficient balance',
    'balance' => number_format($u['balance'],2)
  ]);
  exit;
}

$pdo = Database::pdo();
$pdo->beginTransaction();

try {
  // خصم الرهان
  User::adjustBalance((int)$u['id'], -$betAmount);
  Transaction::log((int)$u['id'], -$betAmount, 'lottery', 'Bet placed');

  // توليد النتيجة
  $result = [random_int(0,9), random_int(0,9), random_int(0,9)];

  // حساب التطابق
  $matches = 0;
  for ($i=0;$i<3;$i++) if ($nums[$i] === $result[$i]) $matches++;

  $profit = 0.0;
  $status = 'lost';
  $msg = "😢 You lost — bet amount deducted.";

  if ($matches === 1) {
    $status = 'neutral';
    $msg = "🙂 One number matched — no win, no loss.";
  } elseif ($matches === 2) {
    $profit = 10 * $mult;
    $status = 'won';
    $msg = "🎉 Two numbers matched! You win +$profit";
  } elseif ($matches === 3) {
    $profit = 100 * $mult;
    $status = 'won';
    $msg = "🏆 Jackpot! Three numbers matched! You win +$profit";
  }

  if ($profit > 0) {
    User::adjustBalance((int)$u['id'], $profit);
    Transaction::log((int)$u['id'], $profit, 'lottery', 'Winning prize');
  }

  Bet::log((int)$u['id'], implode('', $nums), $mult, $betAmount, implode('', $result), $status, $profit, 0);

  $pdo->commit();

  $newBalance = User::find((int)$u['id'])['balance'];

  echo json_encode([
    'result' => $result,
    'message' => $msg,
    'profit' => $profit,
    'balance' => number_format($newBalance,2),
    'success' => $status === 'won'
  ]);

} catch (Exception $e) {
  $pdo->rollBack();
  echo json_encode(['success' => false, 'message' => 'Server error']);
}

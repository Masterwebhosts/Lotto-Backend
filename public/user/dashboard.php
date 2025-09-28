<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../app/Core/auth.php';
require_login('user');
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="card shadow p-4">
  <h2>🎲 User Dashboard</h2>
  <p class="text-muted">Welcome to your dashboard.</p>

  <div class="d-grid gap-3 d-md-flex">
    <a href="<?= url('user/wallet.php') ?>" class="btn btn-primary">💰 Wallet</a>
    <a href="<?= url('user/tickets.php') ?>" class="btn btn-success">🎟️ My Tickets</a>
    <a href="<?= url('user/transactions.php') ?>" class="btn btn-warning">📜 Transactions</a>
    <a href="<?= url('user/lotteries.php') ?>" class="btn btn-info">🎰 Lotteries</a>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

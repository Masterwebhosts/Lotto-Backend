<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../app/Core/auth.php';
require_login('user');
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="card shadow p-4">
  <h2>💰 My Wallet</h2>
  <p class="text-muted">Check and manage your wallet balance.</p>

  <div class="alert alert-info">Current Balance: $0.00</div>

  <a href="<?= url('user/dashboard.php') ?>" class="btn btn-secondary mt-3">⬅ Back to Dashboard</a>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>


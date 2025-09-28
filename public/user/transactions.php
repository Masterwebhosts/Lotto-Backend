<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../app/Core/auth.php';
require_login('user');
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="card shadow p-4">
  <h2>📜 Transactions</h2>
  <p class="text-muted">Your transaction history.</p>

  <table class="table table-striped table-hover mt-3">
    <thead>
      <tr>
        <th>Date</th>
        <th>Type</th>
        <th>Amount</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <tr><td colspan="4" class="text-center text-muted">No transactions found.</td></tr>
    </tbody>
  </table>

  <a href="<?= url('user/dashboard.php') ?>" class="btn btn-secondary mt-3">⬅ Back to Dashboard</a>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

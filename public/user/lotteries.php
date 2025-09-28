<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../app/Core/auth.php';
require_login('user');
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="card shadow p-4">
  <h2>🎰 Lotteries</h2>
  <p class="text-muted">Available lotteries to join.</p>

  <table class="table table-striped table-hover mt-3">
    <thead>
      <tr>
        <th>Lottery</th>
        <th>Ticket Price</th>
        <th>Grand Prize</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <tr><td colspan="5" class="text-center text-muted">No lotteries available.</td></tr>
    </tbody>
  </table>

  <a href="<?= url('user/dashboard.php') ?>" class="btn btn-secondary mt-3">⬅ Back to Dashboard</a>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

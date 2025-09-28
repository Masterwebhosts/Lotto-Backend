<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../app/Core/auth.php';
require_login('admin');
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="card shadow p-4">
  <h2>🎲 Manage Lotteries</h2>
  <p class="text-muted">Create, update, or close lotteries.</p>

  <table class="table table-striped table-hover">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Price</th>
        <th>Grand Prize</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <tr><td colspan="6" class="text-center text-muted">No lotteries available.</td></tr>
    </tbody>
  </table>

  <a href="<?= url('admin/admin_dashboard.php') ?>" class="btn btn-secondary mt-3">⬅ Back to Dashboard</a>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

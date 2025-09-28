<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../app/Core/auth.php';
require_login('admin');
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="card shadow p-4">
  <h2>👥 Manage Users</h2>
  <p class="text-muted">List of registered users and actions.</p>

  <table class="table table-striped table-hover">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Balance</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <tr><td colspan="6" class="text-center text-muted">No users found.</td></tr>
    </tbody>
  </table>

  <a href="<?= url('admin/admin_dashboard.php') ?>" class="btn btn-secondary mt-3">⬅ Back to Dashboard</a>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

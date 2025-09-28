<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../app/Core/auth.php';
require_login('admin');

$adminName = $_SESSION['user_name'] ?? 'Admin';
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="card shadow p-4">
  <h2>🛠️ Welcome, <?= htmlspecialchars($adminName) ?>!</h2>
  <p class="text-muted">Manage users, lotteries, and settings.</p>

  <div class="d-grid gap-3 d-md-flex">
    <a href="<?= url('admin/users.php') ?>" class="btn btn-primary">👥 Manage Users</a>
    <a href="<?= url('admin/lotteries.php') ?>" class="btn btn-warning">🎲 Manage Lotteries</a>
    <a href="<?= url('admin/settings.php') ?>" class="btn btn-info">⚙️ Settings</a>
    <a href="<?= url('auth/logout.php') ?>" class="btn btn-danger">🚪 Logout</a>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>


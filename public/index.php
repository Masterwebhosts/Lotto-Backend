<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Helpers/url.php';
require_once __DIR__ . '/../app/Helpers/lang.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$userId   = $_SESSION['user_id'] ?? null;
$userRole = $_SESSION['role']    ?? null;

include __DIR__ . '/includes/header.php';
?>

<!-- 🎨 Background balls -->
<div id="lotto-bg" aria-hidden="true"></div>

<div class="card p-5 text-center shadow">
  <!-- Logo -->
  <img src="<?= url('assets/img/logo.png') ?>" alt="Lotto Grande" class="win-hero mb-4">

  <?php if ($userId): ?>
    <h2 class="mb-3"><?= __('welcome') ?></h2>
    <p class="text-muted mb-4">Choose an option below:</p>

    <div class="list-group mb-4">
      <?php if ($userRole === 'user'): ?>
        <a href="<?= url('user/dashboard.php') ?>" class="list-group-item list-group-item-action">🏠 <?= __('dashboard') ?></a>
        <a href="<?= url('user/wallet.php') ?>" class="list-group-item list-group-item-action">💰 <?= __('wallet') ?></a>
        <a href="<?= url('user/transactions.php') ?>" class="list-group-item list-group-item-action">📜 <?= __('transactions') ?></a>
        <a href="<?= url('user/tickets.php') ?>" class="list-group-item list-group-item-action">🎟️ <?= __('tickets') ?></a>
      <?php elseif ($userRole === 'admin'): ?>
        <a href="<?= url('admin/admin_dashboard.php') ?>" class="list-group-item list-group-item-action">🛠 <?= __('admin_dashboard') ?></a>
      <?php endif; ?>
    </div>

    <a href="<?= url('auth/logout.php') ?>" class="btn btn-danger btn-lg">🚪 <?= __('logout') ?></a>

  <?php else: ?>
    <h2 class="mb-3"><?= __('welcome') ?></h2>
    <p class="text-muted mb-4">Get started:</p>
    <div class="d-grid gap-3 col-md-6 mx-auto">
      <a href="<?= url('auth/login.php') ?>" class="btn btn-primary btn-lg">🔑 <?= __('login') ?></a>
      <a href="<?= url('auth/register.php') ?>" class="btn btn-success btn-lg">📝 <?= __('register') ?></a>
    </div>
  <?php endif; ?>
</div>


<?php include __DIR__ . '/includes/footer.php'; ?>

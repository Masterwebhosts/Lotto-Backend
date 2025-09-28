<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../app/Core/auth.php';
require_login('user');
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="card shadow p-4">
  <h2>🎟️ My Tickets</h2>
  <p class="text-muted">Your purchased tickets.</p>

  <ul class="list-group">
    <li class="list-group-item text-muted">No tickets purchased yet.</li>
  </ul>

  <a href="<?= url('user/dashboard.php') ?>" class="btn btn-secondary mt-3">⬅ Back to Dashboard</a>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

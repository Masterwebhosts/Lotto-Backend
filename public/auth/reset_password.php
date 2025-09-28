<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../app/Helpers/url.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card shadow p-4 mt-5">
      <h2 class="mb-4 text-center">🔐 Reset Password</h2>
      <p class="text-muted text-center">Enter your new password below.</p>

      <form method="post" action="<?= url('auth/reset_password.php') ?>">
        <div class="mb-3">
          <label class="form-label">New Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Confirm New Password</label>
          <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success w-100">Update Password</button>
      </form>

      <div class="text-center mt-3">
        <a href="<?= url('auth/login.php') ?>" class="btn btn-link">🔑 Back to Login</a>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

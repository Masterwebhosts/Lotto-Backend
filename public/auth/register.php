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
      <h2 class="mb-4 text-center">📝 Create Account</h2>

      <form method="post" action="<?= url('auth/register.php') ?>">
        <div class="mb-3">
          <label class="form-label">Full Name</label>
          <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Confirm Password</label>
          <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success w-100">Register</button>
      </form>

      <div class="text-center mt-3">
        <a href="<?= url('auth/login.php') ?>" class="btn btn-link">🔑 Already have an account? Login</a>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

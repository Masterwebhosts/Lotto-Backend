<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../app/Helpers/url.php';
require_once __DIR__ . '/../../app/Controllers/AuthController.php';

use App\Controllers\AuthController;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = '';

// معالجة تسجيل الدخول
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth = new AuthController();
    $result = $auth->login($_POST['email'], $_POST['password']);

    if ($result['success']) {
        $user = $_SESSION['user'];

        // التوجيه حسب الدور
        if ($user['role'] === 'admin') {
            header("Location: " . url('admin/admin_dashboard.php'));
        } else {
            header("Location: " . url('user/dashboard.php'));
            // أو لو بدك مباشرة على اللعبة:
            // header("Location: " . url('game/index.php'));
        }
        exit;
    } else {
        $error = $result['message'];
    }
}
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card shadow p-4 mt-5">
      <h2 class="mb-4 text-center">🔑 Login</h2>

      <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="post" action="">
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Login</button>
      </form>

      <div class="text-center mt-3">
        <a href="<?= url('auth/register.php') ?>" class="btn btn-link">📝 Create a new account</a><br>
        <a href="<?= url('auth/forgot_password.php') ?>" class="btn btn-link">Forgot Password?</a>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

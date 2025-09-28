<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
  <div class="container">
    <!-- البراند: أيقونة لوتو + اسم الموقع -->
    <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="<?= url('index.php') ?>">
      <!-- أيقونة كرات اللوتو (SVG) -->
      <span class="lotto-icon" aria-hidden="true">
        <svg width="28" height="28" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" role="img">
          <!-- ثلاث كرات متداخلة -->
          <circle cx="24" cy="32" r="12" stroke="currentColor" stroke-width="3" fill="none"/>
          <circle cx="36" cy="28" r="12" stroke="currentColor" stroke-width="3" fill="none" opacity="0.9"/>
          <circle cx="42" cy="38" r="12" stroke="currentColor" stroke-width="3" fill="none" opacity="0.8"/>
          <!-- أرقام بسيطة داخل الكرات -->
          <text x="24" y="36" text-anchor="middle" font-size="10" font-weight="700" fill="currentColor">7</text>
          <text x="36" y="32" text-anchor="middle" font-size="10" font-weight="700" fill="currentColor">9</text>
          <text x="42" y="41" text-anchor="middle" font-size="10" font-weight="700" fill="currentColor">3</text>
        </svg>
      </span>
      <span>Lotto Grande</span>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center">

        <?php if (isset($_SESSION['user_id'])): ?>
          <?php if (($_SESSION['role'] ?? '') === 'user'): ?>
            <li class="nav-item"><a class="nav-link" href="<?= url('user/dashboard.php') ?>"><?= __('dashboard') ?></a></li>
            <li class="nav-item"><a class="nav-link" href="<?= url('user/wallet.php') ?>"><?= __('wallet') ?></a></li>
            <li class="nav-item"><a class="nav-link" href="<?= url('user/tickets.php') ?>"><?= __('tickets') ?></a></li>
            <li class="nav-item"><a class="nav-link" href="<?= url('user/transactions.php') ?>"><?= __('transactions') ?></a></li>
          <?php elseif (($_SESSION['role'] ?? '') === 'admin'): ?>
            <li class="nav-item"><a class="nav-link" href="<?= url('admin/admin_dashboard.php') ?>"><?= __('admin_dashboard') ?></a></li>
          <?php endif; ?>
          <li class="nav-item"><a class="nav-link text-danger" href="<?= url('auth/logout.php') ?>"><?= __('logout') ?></a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="<?= url('auth/login.php') ?>"><?= __('login') ?></a></li>
          <li class="nav-item"><a class="nav-link" href="<?= url('auth/register.php') ?>"><?= __('register') ?></a></li>
        <?php endif; ?>

        <!-- 🌐 Language dropdown -->
        <li class="nav-item dropdown ms-2">
          <a class="nav-link dropdown-toggle" href="#" id="langDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            🌐 <?= strtoupper(current_lang()) ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="langDropdown">
            <li><a class="dropdown-item" href="<?= url('set_lang.php?lang=en') ?>">English</a></li>
            <li><a class="dropdown-item" href="<?= url('set_lang.php?lang=tr') ?>">Türkçe</a></li>
            <li><a class="dropdown-item" href="<?= url('set_lang.php?lang=ar') ?>">العربية</a></li>
          </ul>
        </li>

        <!-- 🌙/☀️ Dark mode toggle -->
        <li class="nav-item ms-2">
          <button id="toggleDark" class="btn btn-sm btn-outline-light">☀️</button>
        </li>

      </ul>
    </div>
  </div>
</nav>

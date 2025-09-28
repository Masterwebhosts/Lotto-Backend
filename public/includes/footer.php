</div> <!-- container -->

<footer class="bg-primary text-white text-center py-3 mt-5">
  <div class="container">
    <p class="mb-0">&copy; <?= date('Y') ?> Lotto Grande. All rights reserved.</p>
    <small>
      <a href="<?= url('index.php') ?>" class="text-white text-decoration-underline">Home</a> |
      <a href="<?= url('auth/login.php') ?>" class="text-white text-decoration-underline">Login</a> |
      <a href="<?= url('auth/register.php') ?>" class="text-white text-decoration-underline">Register</a>
    </small>
  </div>
</footer>

<!-- ✅ Bootstrap JS (bundle) via CDN: ضروري لعمل المنسدلة -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- 🌙 Dark Mode logic (افتراضي داكن + يتذكر الاختيار) -->
<script>
  const toggleDark = document.getElementById("toggleDark");

  // افتراضي: داكن
  document.body.classList.add("dark-mode");
  if (localStorage.getItem("theme") === "light") {
    document.body.classList.remove("dark-mode");
  }

  if (toggleDark) {
    const syncIcon = () => {
      toggleDark.textContent = document.body.classList.contains("dark-mode") ? "☀️" : "🌙";
    };
    syncIcon();

    toggleDark.addEventListener("click", () => {
      document.body.classList.toggle("dark-mode");
      localStorage.setItem("theme",
        document.body.classList.contains("dark-mode") ? "dark" : "light"
      );
      syncIcon();
    });
  }
</script>

</body>
</html>











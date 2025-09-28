document.addEventListener("DOMContentLoaded", () => {
  const buttons = document.querySelectorAll(".toggle-lottery");

  buttons.forEach(btn => {
    btn.addEventListener("click", (e) => {
      e.preventDefault();

      const lotteryId = btn.dataset.id;
      const action = btn.dataset.action; // close أو reopen

      if (!lotteryId || !action) return;

      if (!confirm(`Are you sure you want to ${action} this lottery?`)) return;

      fetch(`/admin/toggle_lottery.php`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id: lotteryId, action: action })
      })
      .then(res => res.json())
      .then(data => {
        alert(data.message);
        if (data.success) {
          // تحديث النص واللون في الجدول مباشرة
          const statusCell = document.querySelector(`#lottery-status-${lotteryId}`);
          if (statusCell) {
            statusCell.textContent = (action === "close") ? "Closed" : "Open";
            statusCell.className = (action === "close")
              ? "badge bg-danger"
              : "badge bg-success";
          }

          // تحديث الزر نفسه
          btn.textContent = (action === "close") ? "Reopen" : "Close";
          btn.dataset.action = (action === "close") ? "reopen" : "close";
          btn.className = (action === "close")
            ? "toggle-lottery btn btn-sm btn-success"
            : "toggle-lottery btn btn-sm btn-danger";
        }
      })
      .catch(err => {
        console.error("❌ Error:", err);
        alert("Something went wrong. Please try again.");
      });
    });
  });

  console.log("✅ admin.js loaded for lotteries page");
});

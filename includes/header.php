<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!-- includes/header.php -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm px-4">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="index.php">
      <i class="bi bi-kanban-fill"></i> Task Manager
    </a>

    <div class="d-flex align-items-center ms-auto gap-3">
      <span class="text-light d-flex align-items-center gap-1">
        <i class="bi bi-person-circle"></i>
        <?= $_SESSION['username'] ?? 'Guest' ?>
      </span>

      <a href="logout.php" class="btn btn-sm btn-outline-light d-flex align-items-center gap-1">
        <i class="bi bi-box-arrow-right"></i> Logout
      </a>

      <button onclick="toggleTheme()" class="btn btn-sm btn-outline-light" title="Toggle Dark Mode">
      🌓
      </button>
    </div>
  </div>
</nav>

<script>
function toggleTheme() {
    const body = document.body;
    body.classList.toggle("dark-mode");
    localStorage.setItem("darkMode", body.classList.contains("dark-mode"));
}
window.onload = () => {
    if (localStorage.getItem("darkMode") === "true") {
        document.body.classList.add("dark-mode");
    }
};
</script>

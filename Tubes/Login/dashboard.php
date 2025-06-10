<?php
session_start();
require_once __DIR__ . '/includes/verif.php';

// Redirect jika belum login
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}
?>

<div class="dashboard">
    <h1>Selamat datang, <?php echo htmlspecialchars($_SESSION['user_nama']); ?>!</h1>
    <p>Email: <?php echo htmlspecialchars($_SESSION['user_email']); ?></p>
    <p>Ini adalah halaman dashboard Anda.</p>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
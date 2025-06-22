<?php
session_start();
include '../koneksi.php';

if (isset($_SESSION['kd_cs'])) {
    $kode_cs = $_SESSION['kd_cs'];
}

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = "SELECT * FROM customer WHERE email ='$username'";
    $result = mysqli_query($conn, $query);

    if ($result === false) {
        die("Query error: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
            $_SESSION['kd_cs'] = $user['customer_id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['nama'] = $user['nama'];
            header("Location: produk.php");
            exit();
        } else {
            $login_error = "Password salah!";
        }
    } else {
        $login_error = "Akun tidak ditemukan!";
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: produk.php");
    exit();
}
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Styrk Industries</title>
    <link rel="stylesheet" href="./css/header.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container_header">
            <a class="navbar-brand" href="./HOME/index.php">
                <img src="https://i.postimg.cc/855ZSty7/no-bg.png" alt="Styrk Industries">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-list" style="color: var(--primary-yellow); font-size: 2rem;"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0" style="gap: 2rem;">
                    <li class="nav-item">
                        <a class="nav-link" href="./HOME/index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./produk.php">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./HOME/index.php#about">About Us</a>
                    </li>
                    <?php
                    $cart_count = 0;
                    if (isset($_SESSION['kd_cs'])) {
                        $kode_cs = $_SESSION['kd_cs'];
                        $result = mysqli_query($conn, "SELECT COUNT(cart_id) as count from carts where customer_id ='$kode_cs'");
                        if ($result) {
                            $row = mysqli_fetch_assoc($result);
                            $cart_count = $row['count'];
                        }
                    }
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="./cart.php">
                            <i class="bi-cart-fill me-2"></i>
                            Carts [<?= $cart_count ?>]
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <?php if (isset($_SESSION['email'])): ?>
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-fill me-2"></i>
                                <?= $_SESSION['nama'] ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="profile.php"><i class="bi bi-person-circle me-2"></i>Profil Saya</a></li>
                                <li><a class="dropdown-item" href="riwayat_belanja.php"><i class="bi bi-receipt me-2"></i>Riwayat Belanja</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="?logout=1"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                            </ul>
                        <?php else: ?>
                            <a class="nav-link dropdown-toggle" href="#" id="loginDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-box-arrow-in-right me-2"></i>
                                Login
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="loginDropdown">
                                <li>
                                    <div class="login-form">
                                        <form method="post" action="">
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="text" class="form-control" id="username" name="username" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="password" class="form-label">Password</label>
                                                <input type="password" class="form-control" id="password" name="password" required>
                                            </div>
                                            <?php if (isset($login_error)): ?>
                                                <div class="login-error"><?= $login_error ?>
                                                    <a href="forgot_password.php">forgot password?</a>
                                                </div>

                                            <?php endif; ?>
                                            <button type="submit" name="login" class="btn btn-warning">
                                                <i class="bi bi-box-arrow-in-right me-2"></i>
                                                Login
                                            </button>
                                        </form>
                                        <div class="text-center mt-3">
                                            <a href="./Login/registrasi.php" style="color: var(--black); text-decoration: none; font-size: 1.1rem;">
                                                <i class="bi bi-person-plus me-2"></i>
                                                Belum punya akun? Daftar
                                            </a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
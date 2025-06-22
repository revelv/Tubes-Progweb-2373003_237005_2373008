<?php
session_start();
include './koneksi.php'; // Sesuaikan

$otp_input = $_POST['otp'];
$new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

if ($_SESSION['otp'] == $otp_input) {
    $email = $_SESSION['email'];

    $update = mysqli_query($conn, "UPDATE customer SET password = '$new_password' WHERE email = '$email'");
    if ($update) {
        echo "Password berhasil direset!";
        unset($_SESSION['otp']);
        unset($_SESSION['email']);
        header("Location: produk.php");
    } else {
        header("Location: otp.php");
    }
} else {
    header("Location: otp.php");
 
}
?>

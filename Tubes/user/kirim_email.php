<?php
session_start();
include './koneksi.php'; // Sesuaikan path
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';
require '../PHPMailer-master/src/Exception.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $otp = rand(100000, 999999);

    $_SESSION['otp'] = $otp;
    $_SESSION['email'] = $email;

    // Cek apakah email terdaftar
    $cek = mysqli_query($conn, "SELECT * FROM customer WHERE email = '$email'");
    if (mysqli_num_rows($cek) == 0) {
        echo "Email tidak terdaftar!";
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'jeremiadylan15@gmail.com'; // Ganti
        $mail->Password   = 'wkcg yxwe mxdj pgmy';    // Ganti
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('jeremiadylan15@gmail.com', 'Reset Password OTP');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Kode OTP Reset Password';
        $mail->Body    = "Kode OTP Anda adalah: <b>$otp</b>";

        $mail->send();
        header("Location: otp.php");
    } catch (Exception $e) {
        echo "Gagal kirim email. Error: {$mail->ErrorInfo}";
    }
}
?>

<?php
include '../koneksi.php';
session_start();

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari form
$nama = $_POST['nama'];
$email = $_POST['email'];
$alamat = $_POST['alamat'];
$no_telepon = $_POST['telp'];
$password = $_POST['password'];
$konfirmasi = $_POST['konfirmasi'];

// Validasi input
$errors = [];

// Cek apakah password dan konfirmasi password sama
if ($password !== $konfirmasi) {
    $errors[] = "Password dan konfirmasi password tidak sama";
}

// Cek apakah email sudah terdaftar
$check_email = $conn->prepare("SELECT email FROM customer WHERE email = ?");
$check_email->bind_param("s", $email);
$check_email->execute();
$check_email->store_result();

if ($check_email->num_rows > 0) {
    $errors[] = "Email sudah terdaftar";
}

$check_email->close();

// Jika ada error, simpan ke session lalu redirect balik ke form
if (!empty($errors)) {
    $_SESSION['register_error'] = implode('<br>', $errors);         
    $_SESSION['form_data'] = $_POST;                                
    header("Location: registrasi.php");                             
    exit;
}

// Hash password sebelum disimpan ke database
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Simpan data ke database
$stmt = $conn->prepare("INSERT INTO customer (nama, password, email, no_telepon, alamat) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $nama, $hashed_password, $email, $no_telepon, $alamat);

if ($stmt->execute()) {
    header("Refresh: 3; url=../produk.php");
} else {
    echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    echo "<a href='javascript:history.back()'>Kembali</a>";
}

$stmt->close();
$conn->close();
?>
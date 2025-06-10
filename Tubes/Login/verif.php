<?php
require_once __DIR__ . '/../config/koneksi.php';

function registerUser($nama, $email, $password) {
    global $pdo;
    
    // Cek apakah email sudah terdaftar
    $stmt = $pdo->prepare("SELECT customer_id FROM customer WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        return ['status' => 'error', 'message' => 'Email sudah terdaftar'];
    }
    
    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Simpan user baru
    $stmt = $pdo->prepare("INSERT INTO customer (nama, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$nama, $email, $hashedPassword]);
    
    return ['status' => 'success', 'message' => 'Registrasi berhasil'];
}

function loginUser($email, $password) {
    global $pdo;
    
    // Ambil data user
    $stmt = $pdo->prepare("SELECT * FROM customer WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if (!$user || !password_verify($password, $user['password'])) {
        return ['status' => 'error', 'message' => 'Email atau password salah'];
    }
    
    // Set session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_nama'] = $user['nama'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['logged_in'] = true;
    
    return ['status' => 'success', 'message' => 'Login berhasil'];
}

function isLoggedIn() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}
?>
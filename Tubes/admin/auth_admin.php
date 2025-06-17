<?php
session_start();
require_once 'koneksi.php';

// Data admin default from your database
const DEFAULT_ADMIN = [
    'username' => 'admin',
    'password' => '$2y$10$fbdJi7jdC0xfeKCuZSDaG.Fv6TM7Hiuway3HYMDNnwqKziU9TsOUy', // Note: In your DB it's stored as plaintext 'admin'
    'admin_id' => 101
];

function login($username, $password)
{
    // Cek admin default
    if ($username === DEFAULT_ADMIN['username']) {
        if (password_verify($password, DEFAULT_ADMIN['password'])) {
            $_SESSION['admin_id'] = DEFAULT_ADMIN['admin_id'];
            $_SESSION['username'] = DEFAULT_ADMIN['username'];
            $_SESSION['role'] = 'admin';
            return true;
        }
        return false;
    }

    // Jika bukan admin default, cek customer
    global $conn;
    $stmt = $conn->prepare("SELECT customer_id, nama, password, email FROM customer WHERE email = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['customer_id'] = $user['customer_id'];
            $_SESSION['username'] = $user['nama'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = 'customer';
            return true;
        }
    }
    return false;
}


function registerCustomer($nama, $password, $email, $no_telepon, $alamat)
{
    global $conn;

    // Check if email exists
    $stmt = $conn->prepare("SELECT customer_id FROM customer WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        throw new Exception("Email sudah digunakan");
    }

    try {
        // Insert customer data
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO customer (nama, password, email, no_telepon, alamat) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nama, $hashed_password, $email, $no_telepon, $alamat);
        $stmt->execute();
        
        return true;
    } catch (Exception $e) {
        throw $e;
    }
}

function isLoggedIn()
{
    return isset($_SESSION['customer_id']) || isset($_SESSION['admin_id']);
}

function isAdmin()
{
    return isLoggedIn() && $_SESSION['role'] === 'admin';
}

function isCustomer()
{
    return isLoggedIn() && $_SESSION['role'] === 'customer';
}

function logout() {
    // Unset semua session variables
    $_SESSION = array();
    
    // Hapus session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // Destroy session
    session_destroy();
    
    // Redirect ke halaman login
    header("Location: login.php");
    exit();
}

function redirectIfNotLoggedIn() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

function redirectIfNotAdmin() {
    if (!isAdmin()) {
        header("Location: login.php");
        exit();
    }
}

function redirectIfNotCustomer() {
    if (!isCustomer()) {
        header("Location: login.php");
        exit();
    }
}

function getCurrentUserId() {
    if (isAdmin()) {
        return $_SESSION['admin_id'];
    } elseif (isCustomer()) {
        return $_SESSION['customer_id'];
    }
    return null;
}
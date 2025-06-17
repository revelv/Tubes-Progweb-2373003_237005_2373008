<?php

include 'header.php';
include 'koneksi.php';

if (!isset($_SESSION['kd_cs'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $customer_id = $_SESSION['kd_cs'];
    
    // Cek apakah produk sudah ada di cart
    $check_query = "SELECT * FROM carts WHERE customer_id = $customer_id AND product_id = '$product_id'";
    $check_result = mysqli_query($conn, $check_query);
    
    if (mysqli_num_rows($check_result) > 0) {
        // Update jumlah jika produk sudah ada
        $update_query = "UPDATE carts SET jumlah_barang = jumlah_barang + 1 
                         WHERE customer_id = $customer_id AND product_id = '$product_id'";
        mysqli_query($conn, $update_query);
    } else {
        // Tambahkan produk baru ke cart
        $insert_query = "INSERT INTO carts (customer_id, product_id, jumlah_barang) 
                         VALUES ($customer_id, '$product_id', 1)";
        mysqli_query($conn, $insert_query);
    }
    
    // Redirect kembali ke halaman produk dengan pesan sukses
    $_SESSION['message'] = "Produk berhasil ditambahkan ke keranjang!";
    header("Location: produk.php");
    exit();
} else {
    // Jika tidak ada product_id, redirect ke halaman produk
    header("Location: produk.php");
    exit();
}
?>
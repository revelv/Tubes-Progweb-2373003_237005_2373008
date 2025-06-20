<?php
session_start();
include 'koneksi.php';

$cart_id = mysqli_real_escape_string($conn, $_POST['cart_id']);
$quantity = (int) $_POST['quantity'];
$customer_id = $_SESSION['kd_cs'] ?? null;

// Cek stok
$get_stok = mysqli_query($conn, "SELECT p.stok FROM products p 
    JOIN carts c ON p.product_id = c.product_id 
    WHERE c.cart_id = '$cart_id'");

if ($get_stok && mysqli_num_rows($get_stok) > 0) {
    $stok = mysqli_fetch_assoc($get_stok)['stok'];

    if ($quantity > $stok) {
        $_SESSION['alert'] = "Stok tidak mencukupi.";
    } else {
        $update = mysqli_query($conn, "UPDATE carts SET jumlah_barang = '$quantity' 
            WHERE cart_id = '$cart_id' AND customer_id = '$customer_id'");

        if ($update) {
           
        } else {
            $_SESSION['alert'] = "Gagal memperbarui keranjang.";
        }
    }
} else {
    $_SESSION['alert'] = "Data produk tidak ditemukan.";
}

exit(); // Jangan redirect karena dipanggil lewat fetch()


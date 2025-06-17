<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['kd_cs'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['update_cart']) && isset($_POST['quantity'])) {
    foreach ($_POST['quantity'] as $cart_id => $new_quantity) {
        $cart_id = mysqli_real_escape_string($conn, $cart_id);
        $new_quantity = (int)$new_quantity;
        $customer_id = $_SESSION['kd_cs'];
        
        // Validasi quantity minimal 1
        if ($new_quantity < 1) $new_quantity = 1;
        
        $update_query = "UPDATE carts SET jumlah_barang = '$new_quantity' 
                         WHERE cart_id = '$cart_id' AND customer_id = '$customer_id'";
        mysqli_query($conn, $update_query);
    }
    
    $_SESSION['success_msg'] = "Cart updated successfully!";
}

header("Location: cart.php");
exit();
?>
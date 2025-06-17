<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['kd_cs'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['cart_id'])) {
    $cart_id = mysqli_real_escape_string($conn, $_GET['cart_id']);
    $customer_id = $_SESSION['kd_cs'];
    
    $delete_query = "DELETE FROM carts WHERE cart_id = '$cart_id' AND customer_id = '$customer_id'";
    
    if (mysqli_query($conn, $delete_query)) {
        $_SESSION['success_msg'] = "Item removed from cart!";
    } else {
        $_SESSION['error_msg'] = "Failed to remove item: " . mysqli_error($conn);
    }
}

header("Location: cart.php");
exit();
?>
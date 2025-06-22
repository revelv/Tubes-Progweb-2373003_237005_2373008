<?php
session_start();
if (!isset($_SESSION['order_id'])) {
    header("Location: cart.php");
    exit();
}

$order_id = $_SESSION['order_id'];
unset($_SESSION['order_id']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payment Success</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card text-center p-4">
            <div class="card-body">
                <i class="fas fa-check-circle text-success mb-3" style="font-size: 4rem;"></i>
                <h1 class="card-title">Payment Successful!</h1>
                <p class="card-text">Thank you for your order. Your order ID is: <strong><?= $order_id ?></strong></p>
                <p class="card-text">We will process your order after verifying your payment.</p>
                <a href="orders.php" class="btn btn-primary mt-3">
                    <i class="fas fa-list me-2"></i> View My Orders
                </a>
            </div>
        </div>
    </div>
</body>
</html>
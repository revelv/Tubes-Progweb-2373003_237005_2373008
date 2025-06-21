<?php
include 'header.php';


if (!isset($_SESSION['kd_cs'])) {
    echo "<script>
        alert('Anda belum login. Silakan login terlebih dahulu.');
        window.location.href = 'produk.php';
    </script>";
    exit();
}



if (!isset($_SESSION['kd_cs'])) {
    header("Location: login.php");
    exit();
}

if (isset($_SESSION['message'])) {
    echo "<script>alert('" . $_SESSION['message'] . "');</script>";
    unset($_SESSION['message']);
}

if (isset($_SESSION['alert'])) {
    echo "<script>alert('" . $_SESSION['alert'] . "');</script>";
    unset($_SESSION['alert']);
}



$customer_id = $_SESSION['kd_cs'];
$query = "SELECT p.*, c.jumlah_barang, c.cart_id 
          FROM carts c 
          JOIN products p ON c.product_id = p.product_id 
          WHERE c.customer_id = '$customer_id'";
$result = mysqli_query($conn, $query);
$total = 0;
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/cart.css">
    <title>Styrk Industries</title>
</head>

<body>
    <div class="container_cart mt-4">
        <h2 class="mb-4">Your Shopping Cart</h2>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <form action="update_cart.php" method="post">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)):
                            $harga_clean = str_replace(['$', ','], '', $row['harga']);
                            $harga_numerik = (float)$harga_clean;
                            $subtotal = $harga_numerik * $row['jumlah_barang'];
                            $total += $subtotal;
                        ?>
                            <tr>
                                <td><?= $row['nama_produk']; ?></td>
                                <td>$<?= number_format($harga_numerik, 2); ?></td>
                                <td>
                                    <div class="input-group" style="width: 120px;">
                                        <button type="button" class="btn btn-outline-secondary btn-sm quantity-minus" data-id="<?= $row['cart_id']; ?>">-</button>
                                        <input type="number" name="quantity[<?= $row['cart_id']; ?>]"
                                            value="<?= $row['jumlah_barang']; ?>"
                                            min="1" class="form-control text-center" readonly>
                                        <button type="button" class="btn btn-outline-secondary btn-sm quantity-plus" data-id="<?= $row['cart_id']; ?>">+</button>
                                    </div>
                                </td>
                                <td>$<?= number_format($subtotal, 2); ?></td>
                                <td>
                                    <a href="remove_from_cart.php?cart_id=<?= $row['cart_id']; ?>"
                                        class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i> Remove
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3">Total</th>
                            <th colspan="2">$<?= number_format($total, 2); ?></th>
                        </tr>
                    </tfoot>
                </table>

            </form>
            <form action="payment.php">
                <div class="text-end">
                    <button type="submit" name="proceedd_payment" class="btn btn-success">
                        <i class="bi bi-credit-card"></i> Checkout
                    </button>
                </div>
            </form>
        <?php else: ?>
            <div class="alert alert-info">Your cart is empty. <a href="produk.php">Browse Products</a></div>
        <?php endif; ?>
    </div>

    <!-- JavaScript untuk tombol +/- -->
    <script>
        document.querySelectorAll('.quantity-plus, .quantity-minus').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentElement.querySelector('input');
                let currentQty = parseInt(input.value);
                const cartId = this.dataset.id;

                if (this.classList.contains('quantity-plus')) {
                    currentQty += 1;
                } else if (currentQty > 1) {
                    currentQty -= 1;
                }

                input.value = currentQty;

                // Kirim data ke update_cart.php via fetch/AJAX
                fetch('update_cart.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `cart_id=${cartId}&quantity=${currentQty}`
                    })
                    .then(response => response.text())
                    .then(data => {
                        console.log('Server response:', data);
                        // Refresh halaman atau perbarui subtotal jika mau
                        location.reload(); // atau update totalnya pakai JS
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    </script>
</body>

</html>
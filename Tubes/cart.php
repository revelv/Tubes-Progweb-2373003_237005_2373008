<?php
include 'header.php';

if (isset($_SESSION['kd_cs'])) {
    $kode_cs = $_SESSION['kd_cs'];
}
?>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="cart.css">
    <meta charset="UTF-8">
    <title>Styrk Industries</title>
</head>

<body>
    <div class="container_cart">
        <div class="row">
            <?php
            if (isset($_SESSION['kd_cs'])) {
                $result = mysqli_query($conn, "SELECT * FROM carts WHERE customer_id ='$kode_cs'");
                while ($row = mysqli_fetch_assoc($result)) {
                    $product_id = $row['product_id'];
                    $product = mysqli_query($conn, "SELECT * FROM products WHERE product_id='$product_id'");
                    $get_product = mysqli_fetch_assoc($product);
            ?>
                    <div class="col-sm-6 col-md-4">
                        <div class="thumbnail">
                            <a href="#"><img id="gambar" src="<?= $get_product['link_gambar']; ?>"> </a>
                            <div class="caption">
                                <h3><?= $get_product['nama_produk']; ?></h3>
                                <h4><?= $get_product['harga']; ?></h4>
                                <input type="checkbox" name="check" value="<?= $kode_cs ?>">
                            </div>
                        </div>

                    </div>
            <?php
                }
            } else {
                echo "Anda Belum Login";
            }
            ?>

            <?php
            $cek_cart = mysqli_query($conn, "SELECT COUNT(cart_id) as count from carts where customer_id ='$kode_cs'");
            $row = mysqli_fetch_assoc($cek_cart);
            $cart_count = $row['count'];

            if ($cart_count > 0) {
            ?>
                <div class="button">
                    <form action="">
                        <button type="">Proceed to Checkout</button>
                    </form>
                    <form method="post">
                        <button type="sumbit" name="hapus" onclick="return confirm('Yakin ingin remove dari cart?')">Remove</button>
                    </form>
                </div>
            <?php
            } else {
            ?>
                <h1>Anda Belum Menambahkan Item!</h1>
            <?php
            }
            ?>


        </div>
    </div>
</body>

</html>
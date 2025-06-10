<?php
include 'header.php';

if (isset($_SESSION['kd_cs'])) {
    $kode_cs = $_SESSION['kd_cs'];
}
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Styrk Industries</title>
</head>

<body>
    <div class="container">
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
                            </div>

                        </div>
                    </div>
            <?php
                }
            }else{
                echo "Anda Belum Login";
                
            }
            ?>
        </div>
    </div>
</body>

</html>
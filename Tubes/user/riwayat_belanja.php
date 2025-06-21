<?php
include '../koneksi.php';
include './header.php';

$customer_id = $_SESSION['kd_cs'];

$query_orders = "
    SELECT * FROM orders 
    WHERE customer_id = '$customer_id' 
    ORDER BY tgl_order DESC
";
$result_orders = mysqli_query($conn, $query_orders);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Riwayat Belanja</title>
    <link rel="stylesheet" href="./css/riwayat_belanja.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>

    <h2>Riwayat Belanja Anda</h2>

    <?php while ($order = mysqli_fetch_assoc($result_orders)) { ?>
        <div class="order-card">
            <div class="order-header">
                ID Order: <?= $order['order_id'] ?> |
                Tanggal: <?= $order['tgl_order'] ?> |
                Total: $<?= number_format($order['total_harga'], 0, ',', '.') ?> |
                Status: <strong id="status"><?= ucfirst($order['status']) ?></strong>
            </div>

            <div>
                <h4>Produk:</h4>
                <?php
                $order_id = $order['order_id'];
                $query_items = "
                SELECT od.*, p.nama_produk 
                FROM order_details od
                JOIN products p ON od.product_id = p.product_id
                WHERE od.order_id = '$order_id'
            ";
                $items = mysqli_query($conn, $query_items);
                while ($item = mysqli_fetch_assoc($items)) {
                    echo "<div class='product-item'>- {$item['nama_produk']} ({$item['jumlah']} pcs) - $" . number_format($item['subtotal'], 0, ',', '.') . "</div>";
                }
                ?>
            </div>

            <div class="tracking-log">
                <h4>Tracking Pesanan:</h4>
                <?php
                $query_tracking = "
                SELECT * FROM order_tracking 
                WHERE order_id = '$order_id' 
                ORDER BY timestamp ASC
            ";
                $tracking = mysqli_query($conn, $query_tracking);
                if (mysqli_num_rows($tracking) > 0) {
                    while ($track = mysqli_fetch_assoc($tracking)) {
                        echo "<p>[{$track['timestamp']}] <strong>{$track['status']}</strong> - {$track['description']}</p>";
                    }
                } else {
                    echo "<p>Belum ada tracking tersedia.</p>";
                }
                ?>
            </div>
        </div>
    <?php } ?>

</body>

</html>
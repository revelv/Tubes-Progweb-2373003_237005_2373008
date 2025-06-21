<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['kd_cs'])) {
    die("Anda harus login untuk melakukan checkout.");
}

$customer_id = $_SESSION['kd_cs'];

// Ambil isi cart milik customer beserta harga
$sql_cart = "SELECT c.*, p.harga, p.harga_diskon, p.status_diskon, p.stok 
             FROM carts c 
             JOIN products p ON c.product_id = p.product_id 
             WHERE c.customer_id = $customer_id";
$result_cart = mysqli_query($conn, $sql_cart);

if (mysqli_num_rows($result_cart) == 0) {
    die("Cart kosong.");
}

$total_harga = 0;
$cart_items = [];

while ($row = mysqli_fetch_assoc($result_cart)) {
    $harga_satuan = ($row['status_diskon'] == 1) ? $row['harga_diskon'] : $row['harga'];
    $subtotal = $harga_satuan * $row['jumlah_barang'];
    $total_harga += $subtotal;

    // Cek stok cukup
    if ($row['stok'] < $row['jumlah_barang']) {
        die("Stok untuk produk {$row['product_id']} tidak mencukupi.");
    }

    $cart_items[] = [
        'product_id' => $row['product_id'],
        'jumlah' => $row['jumlah_barang'],
        'harga_satuan' => $harga_satuan,
        'subtotal' => $subtotal
    ];
}

// Insert ke orders
$tanggal = date("Y-m-d H:i:s");
$status = 'proses';
$sql_order = "INSERT INTO orders (customer_id, tgl_order, total_harga, status) 
              VALUES ($customer_id, '$tanggal', $total_harga, '$status')";
mysqli_query($conn, $sql_order);
$order_id = mysqli_insert_id($conn);

// Insert ke order_details dan update stok
foreach ($cart_items as $item) {
    // Insert ke order_details
    $sql_detail = "INSERT INTO order_details (order_id, product_id, jumlah, harga_satuan, subtotal) 
                   VALUES ($order_id, '{$item['product_id']}', {$item['jumlah']}, {$item['harga_satuan']}, {$item['subtotal']})";
    mysqli_query($conn, $sql_detail);

    // Update stok produk
    $sql_update_stok = "UPDATE products 
                        SET stok = stok - {$item['jumlah']} 
                        WHERE product_id = '{$item['product_id']}'";
    mysqli_query($conn, $sql_update_stok);
}

// Kosongkan cart
mysqli_query($conn, "DELETE FROM carts WHERE customer_id = $customer_id");

echo "Checkout berhasil! Order ID: $order_id";
header("Location: cart.php");
?>

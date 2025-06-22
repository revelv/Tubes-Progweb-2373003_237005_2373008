<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['kd_cs'])) {
    die("Anda harus login terlebih dahulu.");
}

$customer_id = $_SESSION['kd_cs'];

// Jika metode pembayaran = Transfer Bank (upload bukti)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay_bank'])) {

    // Ambil data cart
    $query = "SELECT c.*, p.harga, p.stok FROM carts c 
              JOIN products p ON c.product_id = p.product_id 
              WHERE c.customer_id = '$customer_id'";
    $result = mysqli_query($conn, $query);

    $total = 0;
    $cart_items = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $harga = $row['harga'];
        $subtotal = $harga * $row['jumlah_barang'];
        $total += $subtotal;

        if ($row['stok'] < $row['jumlah_barang']) {
            die("Stok produk {$row['product_id']} tidak cukup.");
        }

        $cart_items[] = [
            'product_id' => $row['product_id'],
            'jumlah' => $row['jumlah_barang'],
            'harga' => $harga,
            'subtotal' => $subtotal
        ];
    }

    $ongkir = $total * 0.01;
    $grand_total = $total + $ongkir;
    $tanggal = date("Y-m-d H:i:s");
    $status = 'pending';

    // Insert order
    $sql_order = "INSERT INTO orders (customer_id, tgl_order, total_harga, status) 
                  VALUES ('$customer_id', '$tanggal', '$grand_total', '$status')";
    mysqli_query($conn, $sql_order);
    $order_id = mysqli_insert_id($conn);

    // Insert order detail & update stok
    foreach ($cart_items as $item) {
        mysqli_query($conn, "INSERT INTO order_details 
            (order_id, product_id, jumlah, harga_satuan, subtotal) 
            VALUES ('$order_id', '{$item['product_id']}', '{$item['jumlah']}', '{$item['harga']}', '{$item['subtotal']}')");

        mysqli_query($conn, "UPDATE products 
            SET stok = stok - {$item['jumlah']} 
            WHERE product_id = '{$item['product_id']}'");
    }

    // Upload bukti pembayaran
    if (isset($_FILES['bukti']) && $_FILES['bukti']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "../payment_proofs/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        $file_ext = pathinfo($_FILES['bukti']['name'], PATHINFO_EXTENSION);
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array(strtolower($file_ext), $allowed_ext)) {
            die("Format file tidak didukung.");
        }

        if ($_FILES['bukti']['size'] > 2000000) { // 2MB
            die("Ukuran file maksimal 2MB.");
        }

        $new_filename = "proof_" . $order_id . "_" . time() . "." . $file_ext;
        $target_file = $target_dir . $new_filename;

        if (move_uploaded_file($_FILES['bukti']['tmp_name'], $target_file)) {
            mysqli_query($conn, "INSERT INTO payments 
                (order_id, metode, jumlah_dibayar, tanggal_bayar, payment_proof, payment_status) 
                VALUES ('$order_id', 'Transfer Bank', '$grand_total', '$tanggal', '$target_file', 'pending')");

            mysqli_query($conn, "DELETE FROM carts WHERE customer_id = '$customer_id'");

            echo "<script>
                alert('Pembayaran berhasil diupload! Order Anda akan diproses.');
                window.location.href = 'riwayat_belanja.php';
            </script>";
            exit;
        } else {
            die("Gagal upload bukti pembayaran.");
        }
    } else {
        die("Bukti pembayaran wajib diupload.");
    }
}
?>

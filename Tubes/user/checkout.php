<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['kd_cs'])) {
    die("Anda harus login terlebih dahulu.");
}

$customer_id = $_SESSION['kd_cs'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $payment_method = $_POST['metode'] ?? '';

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

    // VALIDASI dan SET nilai variabel penting
    if ($payment_method === 'Transfer' || $payment_method === 'QRIS') {
        $ongkir = $total * 0.01;
        $grand_total = $total + $ongkir;
        $tanggal = date("Y-m-d H:i:s");
        $status = ($payment_method === 'Transfer') ? 'pending' : 'proses';
    } else {
        die("Metode pembayaran tidak valid.");
    }

    // INSERT orders
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

    // === Handle metode Transfer Bank ===
    if ($payment_method === 'Transfer') {
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

            if ($_FILES['bukti']['size'] > 2000000) {
                die("Ukuran file maksimal 2MB.");
            }

            $new_filename = "proof_" . $order_id . "_" . time() . "." . $file_ext;
            $target_file = $target_dir . $new_filename;

            if (move_uploaded_file($_FILES['bukti']['tmp_name'], $target_file)) {
                mysqli_query($conn, "INSERT INTO payments 
                    (order_id, metode, jumlah_dibayar, tanggal_bayar, payment_proof, payment_status) 
                    VALUES ('$order_id', 'Transfer Bank', '$grand_total', '$tanggal', '$target_file', 'pending')");
            } else {
                die("Gagal upload bukti pembayaran.");
            }
        } else {
            die("Bukti pembayaran wajib diupload.");
        }
    }

    // === Handle metode QRIS ===
    elseif ($payment_method === 'QRIS') {
        $qris_code = $_POST['kode_transaksi'] ?? '';
        if (empty($qris_code)) {
            die("Kode transaksi QRIS wajib diisi.");
        }

        mysqli_query($conn, "INSERT INTO payments 
            (order_id, metode, jumlah_dibayar, tanggal_bayar, payment_proof, payment_status) 
            VALUES ('$order_id', 'QRIS', '$grand_total', '$tanggal', '$qris_code', 'proses')");
    }

    // Bersihkan cart
    mysqli_query($conn, "DELETE FROM carts WHERE customer_id = '$customer_id'");

    echo "<script>
        alert('Pembayaran berhasil diproses! Order Anda akan segera diproses.');
        window.location.href = 'riwayat_belanja.php';
    </script>";
    exit;
}

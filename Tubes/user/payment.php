<?php
session_start();
include '../koneksi.php';

// Handle payment proof upload if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['pay_bank'])) {
        // Bank transfer processing
        $customer_id = $_SESSION['kd_cs'];
        
        // Get cart items to calculate total
        $query = "SELECT carts.*, products.harga, products.harga_diskon, products.stok 
                FROM carts 
                JOIN products ON carts.product_id = products.product_id 
                WHERE carts.customer_id = '$customer_id'";
        $result = mysqli_query($conn, $query);

        $total = 0;
        $cart_items = [];
        
        while ($row = mysqli_fetch_assoc($result)) {
            $harga = ($row['harga_diskon'] > 0) ? $row['harga_diskon'] : $row['harga'];
            $subtotal = $harga * $row['jumlah_barang'];
            $total += $subtotal;
            
            // Check stock
            if ($row['stok'] < $row['jumlah_barang']) {
                die("Stok untuk produk {$row['product_id']} tidak mencukupi.");
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

        // Create order
        $tanggal = date("Y-m-d H:i:s");
        $status = 'pending';
        $sql_order = "INSERT INTO orders (customer_id, tgl_order, total_harga, status) 
                    VALUES ('$customer_id', '$tanggal', '$grand_total', '$status')";
        mysqli_query($conn, $sql_order);
        $order_id = mysqli_insert_id($conn);

        // Insert order details and update stock
        foreach ($cart_items as $item) {
            $sql_detail = "INSERT INTO order_details (order_id, product_id, jumlah, harga_satuan, subtotal) 
                        VALUES ('$order_id', '{$item['product_id']}', '{$item['jumlah']}', '{$item['harga']}', '{$item['subtotal']}')";
            mysqli_query($conn, $sql_detail);
            
            $sql_update = "UPDATE products SET stok = stok - {$item['jumlah']} WHERE product_id = '{$item['product_id']}'";
            mysqli_query($conn, $sql_update);
        }

        // Handle payment proof upload
        if (isset($_FILES['bukti']) && $_FILES['bukti']['error'] === UPLOAD_ERR_OK) {
            $target_dir = "../payment_proofs/";
            
            // Create directory if not exists
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0755, true);
            }
            
            // Generate unique filename
            $file_ext = pathinfo($_FILES['bukti']['name'], PATHINFO_EXTENSION);
            $new_filename = "proof_" . $order_id . "_" . time() . "." . $file_ext;
            $target_file = $target_dir . $new_filename;
            
            // Validate file
            $check = getimagesize($_FILES['bukti']['tmp_name']);
            if ($check === false) {
                die("File bukan gambar.");
            }
            
            // Check file size (max 2MB)
            if ($_FILES['bukti']['size'] > 2000000) {
                die("Ukuran file terlalu besar (max 2MB).");
            }
            
            // Allow only certain formats
            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array(strtolower($file_ext), $allowed_ext)) {
                die("Hanya format JPG, JPEG, PNG & GIF yang diperbolehkan.");
            }
            
            // Move file to directory
            if (move_uploaded_file($_FILES['bukti']['tmp_name'], $target_file)) {
                // Record payment
                $sql_payment = "INSERT INTO payments (order_id, metode, jumlah_dibayar, tanggal_bayar, payment_proof, payment_status) 
                                VALUES ('$order_id', 'Transfer Bank', '$grand_total', '$tanggal', '$target_file', 'pending')";
                mysqli_query($conn, $sql_payment);
                
                // Clear cart
                mysqli_query($conn, "DELETE FROM carts WHERE customer_id = '$customer_id'");
                
                // Show success message
                echo "<script>
                    alert('Upload bukti pembayaran berhasil! Pesanan Anda akan diproses setelah verifikasi.');
                    window.location.href = 'orders.php';
                </script>";
                exit();
            } else {
                die("Gagal mengupload bukti pembayaran.");
            }
        } else {
            die("Harap upload bukti pembayaran.");
        }
    }
}

// Normal page display
$customer_id = $_SESSION['kd_cs'];
$query = "SELECT carts.*, products.nama_produk, products.harga, products.harga_diskon, products.link_gambar 
          FROM carts 
          JOIN products ON carts.product_id = products.product_id 
          WHERE carts.customer_id = '$customer_id'";
$result = mysqli_query($conn, $query);

$total = 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Payment Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/payment.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="container mt-4">

    <!-- Back to Cart Button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="m-0">Checkout - Payment</h2>
        <a href="cart.php" class="btn btn-secondary">← Back to Cart</a>
    </div>

    <!-- Daftar Produk -->
    <div class="mb-4">
        <h4>Barang dalam Keranjang</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Gambar</th>
                    <th>Nama</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) :
                    $harga = ($row['harga_diskon'] > 0) ? $row['harga_diskon'] : $row['harga'];
                    $subtotal = $harga * $row['jumlah_barang'];
                    $total += $subtotal;
                ?>
                    <tr>
                        <td><img src="<?= $row['link_gambar']; ?>" width="80"></td>
                        <td><?= $row['nama_produk']; ?></td>
                        <td><?= $row['jumlah_barang']; ?></td>
                        <td>$ <?= number_format($harga, 0, ',', '.'); ?></td>
                        <td>$ <?= number_format($subtotal, 0, ',', '.'); ?></td>
                    </tr>
                <?php endwhile; ?>
                <?php
                $ongkir = $total * 0.01;
                $grand_total = $total + $ongkir;
                ?>
                <tr>
                    <td colspan="4" class="text-end"><strong>Ongkir (1%)</strong></td>
                    <td>$ <?= number_format($ongkir, 0, ',', '.'); ?></td>
                </tr>
                <tr>
                    <td colspan="4" class="text-end"><strong>Total</strong></td>
                    <td><strong>$ <?= number_format($grand_total, 0, ',', '.'); ?></strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pilihan Metode Pembayaran -->
    <div class="mb-4">
        <h4 class="mb-3 text-center">Pilih Metode Pembayaran</h4>
        <div class="payment-methods justify-content-center">
            <div class="payment-option">
                <input type="radio" name="metode" id="qris" value="QRIS" class="payment-input">
                <label for="qris" class="payment-label">
                    <div class="payment-content">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/d/d0/QR_code_for_mobile_English_Wikipedia.svg/1200px-QR_code_for_mobile_English_Wikipedia.svg.png" alt="QRIS" class="payment-icon">
                        <span>QRIS</span>
                    </div>
                </label>
            </div>

            <div class="payment-option">
                <input type="radio" name="metode" id="transfer" value="Transfer" class="payment-input" checked>
                <label for="transfer" class="payment-label">
                    <div class="payment-content">
                        <img src="./css/bca_logo.png" alt="Transfer Bank" class="payment-icon">
                        <span>Transfer Bank</span>
                    </div>
                </label>
            </div>
        </div>
    </div>

    <!-- Area Tampilan Pembayaran -->
    <div id="paymentContainer">
        <!-- Default Bank Transfer Form -->
        <div class="payment-box">
            <h5>Transfer Bank</h5>
            <p>Silakan transfer ke rekening:</p>
            <p><strong>BANK BCA 1234567890 a.n STYRK INDUSTRIES</strong></p>
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="bukti" class="form-label">Upload Bukti Transfer</label>
                    <input type="file" name="bukti" class="form-control" required accept="image/*">
                    <div class="form-text">Format: JPG, PNG (max 2MB)</div>
                </div>
                <button type="submit" name="pay_bank" class="btn btn-success w-100">
                    <i class="fas fa-upload me-2"></i> Upload & Cek Pembayaran
                </button>
            </form>
        </div>
    </div>

    <!-- Tombol Pay (Original Button) -->
    <div class="text-center mb-4">
        <button class="btn btn-lg btn-warning" onclick="mulaiPembayaran()">Pay</button>
    </div>

    <!-- Script -->
    <script>
        let qrTimer, qrContent = "", paymentChecked = false;
        const grandTotal = <?= $grand_total ?>;

        function mulaiPembayaran() {
            const metode = document.querySelector('input[name="metode"]:checked');
            const container = document.getElementById("paymentContainer");

            if (!metode) {
                alert("Silakan pilih metode pembayaran terlebih dahulu.");
                return;
            }

            let html = "";

            if (metode.value === "QRIS") {
                qrContent = generateQRContent();
                html += `
                <div class="payment-box">
                    <h5>QRIS</h5>
                    <img id="qrImage" src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${qrContent}" alt="QRIS"><br>
                    <div class="qris-timer" id="timer">02:00</div>
                    <form action="checkout.php" method="post">
                        <input type="hidden" name="metode" value="QRIS">
                        <input type="hidden" name="total" value="${grandTotal}">
                        <input type="hidden" name="kode_transaksi" value="${qrContent}">
                        <button type="submit" class="btn btn-primary mt-2">Cek Pembayaran</button>
                    </form>
                </div>`;
                container.innerHTML = html;
                startQRISTimer();
            } else if (metode.value === "Transfer") {
                html += `
                <div class="payment-box">
                    <h5>Transfer Bank</h5>
                    <p>Silakan transfer ke rekening:</p>
                    <p><strong>BANK BCA 1234567890 a.n STYRK INDUSTRIES</strong></p>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="bukti" class="form-label">Upload Bukti Transfer</label>
                            <input type="file" name="bukti" class="form-control" required accept="image/*">
                            <div class="form-text">Format: JPG, PNG (max 2MB)</div>
                        </div>
                        <button type="submit" name="pay_bank" class="btn btn-success w-100">
                            <i class="fas fa-upload me-2"></i> Upload & Cek Pembayaran
                        </button>
                    </form>
                </div>`;
                container.innerHTML = html;
            }
        }

        function generateQRContent() {
            const randomCode = "ORDER" + Date.now() + Math.floor(Math.random() * 1000);
            return encodeURIComponent(randomCode);
        }

        function startQRISTimer() {
            clearInterval(qrTimer);
            paymentChecked = false;

            let duration = 120;
            const timerDisplay = document.getElementById("timer");

            qrTimer = setInterval(() => {
                const minutes = Math.floor(duration / 60);
                const seconds = duration % 60;
                timerDisplay.textContent =
                    (minutes < 10 ? "0" : "") + minutes + ":" +
                    (seconds < 10 ? "0" : "") + seconds;

                if (--duration < 0) {
                    clearInterval(qrTimer);
                    qrContent = generateQRContent();
                    document.getElementById("qrImage").src =
                        "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" + qrContent;
                    startQRISTimer();
                    alert("QR baru telah digenerate karena belum ada konfirmasi pembayaran.");
                }
            }, 1000);
        }
    </script>
</body>
</html>
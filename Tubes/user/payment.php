<?php
session_start();
include '../koneksi.php';

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
                <input type="radio" name="metode" id="transfer" value="Transfer" class="payment-input">
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
    <div id="paymentContainer"></div>

    <!-- Tombol Pay -->
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
                    <form action="upload_bukti.php" method="post" enctype="multipart/form-data">
                        <label for="bukti">Upload Bukti Transfer</label>
                        <input type="file" name="bukti" class="form-control mb-2" required>
                        <button type="submit" class="btn btn-success">Upload & Cek Pembayaran</button>
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

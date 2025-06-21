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
    
    <style>
        .qris-timer {
            font-size: 1.2em;
            font-weight: bold;
            color: red;
        }

        .payment-box {
            border: 1px solid #ccc;
            padding: 15px;
            border-radius: 10px;
            margin-top: 15px;
        }
    </style>
</head>

<body class="container mt-4">

    <!-- Back to Cart Button -->
    <div class="mb-3">
        <a href="cart.php" class="btn btn-secondary">← Back to Cart</a>
    </div>

    <h2>Checkout - Payment</h2>

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
                        <td>Rp <?= number_format($harga, 0, ',', '.'); ?></td>
                        <td>Rp <?= number_format($subtotal, 0, ',', '.'); ?></td>
                    </tr>
                <?php endwhile; ?>
                <?php
                $ongkir = $total * 0.01;
                $grand_total = $total + $ongkir;
                ?>
                <tr>
                    <td colspan="4" class="text-end"><strong>Ongkir (1%)</strong></td>
                    <td>Rp <?= number_format($ongkir, 0, ',', '.'); ?></td>
                </tr>
                <tr>
                    <td colspan="4" class="text-end"><strong>Total</strong></td>
                    <td><strong>Rp <?= number_format($grand_total, 0, ',', '.'); ?></strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pilihan Metode Pembayaran -->
    <div class="mb-3">
        <h4>Pilih Metode Pembayaran</h4>
        <div>
            <input type="radio" name="metode" id="qris" value="QRIS">
            <label for="qris">QRIS</label>
        </div>
        <div>
            <input type="radio" name="metode" id="transfer" value="Transfer">
            <label for="transfer">Transfer Bank</label>
        </div>
    </div>

    <!-- Tombol Pay -->
    <div class="text-center mb-4">
        <button class="btn btn-lg btn-warning" onclick="mulaiPembayaran()">Pay</button>
    </div>

    <!-- Area Tampilan Pembayaran -->
    <div id="paymentContainer"></div>

    <!-- Script -->
    <script>
        let timerStarted = false;

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
      <button onclick="cekPembayaran('QRIS')" class="btn btn-primary mt-2">Cek Pembayaran</button>
    </div>
  `;
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
          </div>
        `;
                container.innerHTML = html;
            }
        }

        function startTimer() {
            if (timerStarted) return;
            timerStarted = true;

            let duration = 120;
            const timerDisplay = document.getElementById("timer");

            const countdown = setInterval(() => {
                const minutes = Math.floor(duration / 60);
                const seconds = duration % 60;
                timerDisplay.textContent =
                    (minutes < 10 ? "0" : "") + minutes + ":" +
                    (seconds < 10 ? "0" : "") + seconds;

                if (--duration < 0) {
                    clearInterval(countdown);
                    timerDisplay.textContent = "00:00";
                    alert("Waktu QRIS habis. Silakan ulangi pembayaran.");
                }
            }, 1000);
        }

        function cekPembayaran(metode) {
            alert("Cek status pembayaran metode: " + metode + " (simulasi)");
        }

        let qrTimer, qrContent = "",
            paymentChecked = false;

        function generateQRContent() {
            const randomCode = "ORDER" + Date.now() + Math.floor(Math.random() * 1000);
            return encodeURIComponent(randomCode);
        }

        function startQRISTimer() {
            clearInterval(qrTimer); // pastikan tidak double timer
            paymentChecked = false;
            timerStarted = true;

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

                    if (!paymentChecked) {
                        // Generate ulang QR
                        qrContent = generateQRContent();
                        document.getElementById("qrImage").src =
                            "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" + qrContent;

                        // Mulai ulang timer
                        startQRISTimer();
                        alert("QR baru telah digenerate karena belum ada konfirmasi pembayaran.");
                    }
                }
            }, 1000);
        }

        function cekPembayaran(metode) {
            paymentChecked = true;
            alert("Cek status pembayaran metode: " + metode + " (simulasi)");
        }
    </script>

</body>

</html>
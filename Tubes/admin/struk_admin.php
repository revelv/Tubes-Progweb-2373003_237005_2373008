<?php
include 'koneksi.php';
include 'header_admin.php';

// --- Filter pencarian ---
$search = $_GET['search'] ?? '';

// Query utama dengan filter status
$query = "
  SELECT p.*, o.tgl_order, o.total_harga, o.status,
         c.nama AS customer, c.alamat
  FROM payments p
  JOIN orders o ON p.order_id = o.order_id
  JOIN customer c ON o.customer_id = c.customer_id
  WHERE o.status IN ('proses', 'selesai')
";

if (!empty($search)) {
  $query .= " AND c.nama LIKE '%$search%'";
}
$query .= " ORDER BY p.tanggal_bayar DESC";

$result = mysqli_query($conn, $query);
$orders = mysqli_query($conn, "SELECT orders.order_id, customer.nama FROM orders JOIN customer ON orders.customer_id = customer.customer_id");
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <style>
  @media print {
    .watermark-print {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      display: flex;
      flex-wrap: wrap;
      justify-content: space-around;
      align-content: space-around;
      z-index: -1;
      opacity: 0.1;
      pointer-events: none;
    }

    .wm {
      font-size: 24px;
      transform: rotate(-30deg);
      width: 200px;
      text-align: center;
    }
  }
  </style>

  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>History Pembayaran - Stryk Industries</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <style>
    /* Gaya untuk menyembunyikan tombol saat cetak */
    @media print {
      .no-print,
      .no-print * {
        display: none !important;
      }
      
      .hide-on-print {
        display: none !important;
      }
      
      body {
        background-color: white !important;
        color: black !important;
        padding: 0 !important;
        margin: 0 !important;
      }
      
      .struk-card {
        page-break-after: always;
        border: 1px solid #d1d5db;
        margin: 0;
        box-shadow: none;
        width: 100%;
        max-width: 80mm;
        margin: 0 auto;
      }
      
      .bg-gray-800 {
        background-color: white !important;
        color: black !important;
      }
      
      .bg-yellow-500 {
        background-color: #000 !important;
        color: white !important;
      }
      
      .text-gray-400 {
        color: #666 !important;
      }
    }

    /* Gaya badge status */
    .status-badge {
      @apply px-2 py-1 rounded-full text-xs font-semibold;
    }

    .status-pending {
      @apply bg-yellow-500 text-gray-900;
    }

    .status-proses {
      @apply bg-blue-500 text-white;
    }

    .status-selesai {
      @apply bg-green-500 text-white;
    }
    
    /* Gaya khusus untuk struk */
    .struk-card {
      width: 100%;
      max-width: 80mm;
      margin: 0;
    }
    
    .struk-header {
      border-bottom: 2px dashed #000;
      padding-bottom: 10px;
      margin-bottom: 10px;
    }
    
    .struk-footer {
      border-top: 2px dashed #000;
      padding-top: 10px;
      margin-top: 10px;
      text-align: center;
      font-size: 0.8rem;
    }
    
    .struk-title {
      text-align: center;
      font-weight: bold;
      font-size: 1.2rem;
      margin-bottom: 5px;
    }
    
    .struk-detail {
      margin-bottom: 3px;
      font-size: 0.9rem;
    }
    
    .struk-detail-label {
      font-weight: bold;
      display: inline-block;
      width: 100px;
    }
    
    .total-amount {
      font-weight: bold;
      font-size: 1.1rem;
      text-align: right;
      margin-top: 10px;
    }
    
    /* Gaya untuk layout responsif */
    .struk-container {
      display: flex;
      flex-wrap: wrap;
      gap: 1rem;
      justify-content: flex-start;
    }
    
    @media (max-width: 768px) {
      .struk-container {
        flex-direction: column;
        align-items: center;
      }
    }
  </style>
</head>

<body class="bg-gray-900 text-white p-6">
  <h1 class="text-2xl font-bold text-yellow-400 mb-6 no-print">History Pembayaran</h1>

  <!-- Form Pencarian -->
  <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 no-print">
    <div class="md:col-span-3">
      <input type="text" name="search" class="w-full px-3 py-2 rounded bg-gray-700 text-white border border-gray-600" placeholder="Cari berdasarkan nama customer" value="<?= htmlspecialchars($search) ?>">
    </div>
    <div>
      <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-bold py-2 px-4 rounded">
        🔍 Cari
      </button>
    </div>
  </form>

  <!-- Daftar Struk -->
  <div class="struk-container">
    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
      <div class="bg-gray-800 rounded-lg shadow overflow-hidden struk-card" id="struk-<?= $row['payment_id'] ?>">
        <!-- Header Struk -->
        <div class="bg-yellow-500 text-gray-900 px-4 py-3 flex justify-between items-center no-print">
          <div>
            <strong>Struk ID: <?= $row['payment_id'] ?></strong> |
            <?= date('d/m/Y H:i', strtotime($row['tanggal_bayar'])) ?>
          </div>
          <button class="bg-gray-900 hover:bg-gray-700 text-yellow-400 px-3 py-1 rounded text-sm"
            onclick="printStruk('struk-<?= $row['payment_id'] ?>')">
            🖨 Cetak
          </button>
        </div>

        <!-- Isi Struk -->
        <div class="p-4">
          <div class="struk-header">
            <div class="struk-title">STRUK PEMBAYARAN Stryk Admin</div>
            <div class="text-center text-sm mb-2"><?= date('d/m/Y H:i', strtotime($row['tanggal_bayar'])) ?></div>
          </div>
          
          <div class="mb-4">
            <div class="struk-detail">
              <span class="struk-detail-label">No. Transaksi:</span> <?= $row['payment_id'] ?>
            </div>
            <div class="struk-detail">
              <span class="struk-detail-label">Order ID:</span> <?= $row['order_id'] ?>
            </div>
            <div class="struk-detail">
              <span class="struk-detail-label">Customer:</span> <?= $row['customer'] ?>
            </div>
            <div class="struk-detail">
              <span class="struk-detail-label">Alamat:</span> <?= $row['alamat'] ?>
            </div>
            <div class="struk-detail">
              <span class="struk-detail-label">Metode Bayar:</span> <?= $row['metode'] ?>
            </div>
            <!-- Status hanya ditampilkan di tampilan web, tidak di print -->
            <div class="struk-detail no-print">
              <span class="struk-detail-label">Status:</span>
              <span class="status-badge <?= $row['status'] === 'pending' ? 'status-pending' : ($row['status'] === 'proses' ? 'status-proses' : 'status-selesai') ?>">
                <?= ucfirst($row['status']) ?>
              </span>
            </div>
          </div>
          
          <div class="mb-4">
            <div class="struk-detail">
              <span class="struk-detail-label">Tgl. Order:</span> <?= date('d/m/Y H:i', strtotime($row['tgl_order'])) ?>
            </div>
            <div class="struk-detail">
              <span class="struk-detail-label">Jumlah Bayar:</span> $ <?= number_format($row['jumlah_dibayar'], 0, ',', '.') ?>
            </div>
            <div class="total-amount">
              <span class="struk-detail-label">Total Harga:</span> $ <?= number_format($row['total_harga'], 0, ',', '.') ?>
            </div>
          </div>
          
          <div class="struk-footer">
            Terima kasih atas pembayarannya<br>
            Stryk Industries &copy; <?= date('Y') ?>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>

  <!-- Script untuk Cetak Per Struk -->
  <script>
    function printStruk(id) {
      const struk = document.getElementById(id);
      const strukClone = struk.cloneNode(true);

      // Hapus elemen no-print
      const elementsToRemove = strukClone.querySelectorAll('.no-print, .hide-on-print');
      elementsToRemove.forEach(el => el.remove());

      // Generate watermark dengan pola staggered yang rapi
      let watermarkHTML = '<div class="watermark-print">';
      const columns = 4; // Jumlah kolom
      const rows = 8; // Jumlah baris
      const offset = 120; // Jarak offset untuk efek staggered
      
      for (let i = 0; i < rows; i++) {
        for (let j = 0; j < columns; j++) {
          // Hitung posisi x dengan offset bergantian
          const xPos = j * 200 + (i % 2 === 0 ? 0 : offset);
          // Hitung posisi y
          const yPos = i * 120;
          
          watermarkHTML += `
            <div class="wm" style="left:${xPos}px; top:${yPos}px">
              Stryk Industries
            </div>`;
        }
      }
      watermarkHTML += '</div>';

      const win = window.open('', '', 'width=350,height=600');
      win.document.write(`
        <html>
          <head>
            <title>Struk Pembayaran #${id.replace('struk-', '')}</title>
            <style>
              @page { 
                size: 80mm auto;
                margin: 0;
              }
              body { 
                font-family: Arial, sans-serif;
                font-size: 12px;
                padding: 5mm;
                margin: 0;
                width: 80mm;
                position: relative;
              }
              .struk-card {
                width: 100%;
                margin: 0 auto;
                position: relative;
                z-index: 1;
              }
              .watermark-print {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: -1;
                opacity: 0.1;
                pointer-events: none;
              }
              .wm {
                position: absolute;
                font-size: 24px;
                transform: rotate(-30deg);
                white-space: nowrap;
                width: 180px;
                text-align: center;
              }
              .struk-header {
                border-bottom: 2px dashed #000;
                padding-bottom: 5px;
                margin-bottom: 5px;
                text-align: center;
              }
              .struk-footer {
                border-top: 2px dashed #000;
                padding-top: 5px;
                margin-top: 5px;
                text-align: center;
                font-size: 10px;
              }
              .struk-title {
                font-weight: bold;
                font-size: 14px;
                margin-bottom: 3px;
              }
              .struk-detail {
                margin-bottom: 2px;
              }
              .struk-detail-label {
                font-weight: bold;
                display: inline-block;
                width: 80px;
              }
              .total-amount {
                font-weight: bold;
                text-align: right;
                margin-top: 5px;
              }
            </style>
          </head>
          <body onload="window.print(); setTimeout(() => window.close(), 500);">
            ${strukClone.innerHTML}
            ${watermarkHTML}
          </body>
        </html>
      `);
      win.document.close();
    }
  </script>
</body>
</html>
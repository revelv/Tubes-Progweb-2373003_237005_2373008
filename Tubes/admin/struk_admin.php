<?php
include 'koneksi.php';
include 'header_admin.php';

// --- Hapus pembayaran ---
if (isset($_GET['hapus'])) {
  $id = $_GET['hapus'];
  mysqli_query($conn, "DELETE FROM payments WHERE payment_id='$id'");
  echo "<script>alert('Struk berhasil dihapus'); window.location='struk_admin.php';</script>";
}

// --- Ambil data untuk edit ---
$edit = null;
if (isset($_GET['edit'])) {
  $id = $_GET['edit'];
  $res = mysqli_query($conn, "SELECT * FROM payments WHERE payment_id='$id'");
  $edit = mysqli_fetch_assoc($res);
}

// --- Simpan perubahan pembayaran ---
if (isset($_POST['update'])) {
  $id = $_POST['payment_id'];
  $order_id = $_POST['order_id'];
  $metode = $_POST['metode'];
  $jumlah = $_POST['jumlah_dibayar'];
  $tanggal = $_POST['tanggal_bayar'];

  mysqli_query($conn, "UPDATE payments SET 
    order_id='$order_id', 
    metode='$metode', 
    jumlah_dibayar='$jumlah', 
    tanggal_bayar='$tanggal' 
    WHERE payment_id='$id'");
  echo "<script>alert('Struk diperbarui'); window.location='struk_admin.php';</script>";
}

// --- Filter pencarian ---
$search = $_GET['search'] ?? '';

// Query utama
$query = "
  SELECT p.*, o.tgl_order, o.total_harga, o.status,
         c.nama AS customer
  FROM payments p
  JOIN orders o ON p.order_id = o.order_id
  JOIN customer c ON o.customer_id = c.customer_id
  WHERE 1=1
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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>History Pembayaran - Stryk Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <style>
    @media print {
      .no-print { display: none !important; }
      .struk-card { page-break-after: always; border: 1px solid #d1d5db; }
    }
    .status-badge {
      @apply px-2 py-1 rounded-full text-xs font-semibold;
    }
    .status-pending { @apply bg-yellow-500 text-gray-900; }
    .status-proses { @apply bg-blue-500 text-white; }
    .status-selesai { @apply bg-green-500 text-white; }
  </style>
</head>

<body class="bg-gray-900 text-white p-6">
  <h1 class="text-2xl font-bold text-yellow-400 mb-6">History Pembayaran</h1>

  <!-- Filter pencarian -->
  <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 no-print">
    <div class="col-span-2">
      <input type="text" name="search" class="w-full px-3 py-2 rounded bg-gray-700 text-white border border-gray-600" 
             placeholder="Cari berdasarkan nama customer" value="<?= htmlspecialchars($search) ?>">
    </div>
    <div class="grid grid-cols-2 gap-2">
      <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-bold py-2 px-4 rounded">
        🔍 Cari
      </button>
      <button type="button" onclick="window.print()" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
        🖨 Cetak Semua
      </button>
    </div>
  </form>

  <!-- Form Edit (Hanya muncul saat mode edit) -->
  <?php if ($edit): ?>
    <div class="bg-gray-800 rounded-lg shadow p-6 mb-8 no-print">
      <h2 class="text-xl font-semibold text-yellow-400 mb-4">Edit Struk ID: <?= $edit['payment_id'] ?></h2>
      
      <form method="POST" class="space-y-4">
        <input type="hidden" name="payment_id" value="<?= $edit['payment_id'] ?>">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-gray-300 mb-1">ID Order</label>
            <select name="order_id" required class="w-full px-3 py-2 rounded bg-gray-700 text-white border border-gray-600">
              <option value="">-- Pilih Order --</option>
              <?php mysqli_data_seek($orders, 0); while ($o = mysqli_fetch_assoc($orders)) : ?>
                <option value="<?= $o['order_id'] ?>" <?= ($edit['order_id'] == $o['order_id']) ? 'selected' : '' ?>>
                  <?= $o['order_id'] ?> - <?= $o['nama'] ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>
          
          <div>
            <label class="block text-gray-300 mb-1">Metode Pembayaran</label>
            <input type="text" name="metode" value="<?= $edit['metode'] ?>" required
                   class="w-full px-3 py-2 rounded bg-gray-700 text-white border border-gray-600">
          </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-gray-300 mb-1">Jumlah Dibayar</label>
            <input type="number" name="jumlah_dibayar" value="<?= $edit['jumlah_dibayar'] ?>" required
                   class="w-full px-3 py-2 rounded bg-gray-700 text-white border border-gray-600">
          </div>
          
          <div>
            <label class="block text-gray-300 mb-1">Tanggal Bayar</label>
            <input type="datetime-local" name="tanggal_bayar" 
                   value="<?= date('Y-m-d\TH:i', strtotime($edit['tanggal_bayar'])) ?>" required
                   class="w-full px-3 py-2 rounded bg-gray-700 text-white border border-gray-600">
          </div>
        </div>
        
        <div class="flex space-x-3 pt-2">
          <button type="submit" name="update" 
                  class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-bold py-2 px-6 rounded">
            Simpan Perubahan
          </button>
          <a href="struk_admin.php" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded">
            Batal
          </a>
        </div>
      </form>
    </div>
  <?php endif; ?>

  <!-- Daftar Struk (History) -->
  <div class="space-y-4">
    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
      <div class="bg-gray-800 rounded-lg shadow overflow-hidden struk-card" id="struk-<?= $row['payment_id'] ?>">
        <div class="bg-yellow-500 text-gray-900 px-4 py-3 flex justify-between items-center">
          <div>
            <strong>Struk ID: <?= $row['payment_id'] ?></strong> | 
            <?= date('d/m/Y H:i', strtotime($row['tanggal_bayar'])) ?>
          </div>
          <button class="bg-gray-900 hover:bg-gray-700 text-yellow-400 px-3 py-1 rounded text-sm no-print" 
                  onclick="printStruk('struk-<?= $row['payment_id'] ?>')">
            🖨 Cetak
          </button>
        </div>
        <div class="p-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <p class="mb-2"><span class="text-gray-400">Order ID:</span> <strong><?= $row['order_id'] ?></strong></p>
              <p class="mb-2"><span class="text-gray-400">Customer:</span> <?= $row['customer'] ?></p>
              <p class="mb-2"><span class="text-gray-400">Metode:</span> <?= $row['metode'] ?></p>
            </div>
            <div>
              <p class="mb-2"><span class="text-gray-400">Jumlah:</span> Rp <?= number_format($row['jumlah_dibayar'], 0, ',', '.') ?></p>
              <p class="mb-2"><span class="text-gray-400">Status:</span> 
                <span class="status-badge <?= $row['status'] === 'pending' ? 'status-pending' : ($row['status'] === 'proses' ? 'status-proses' : 'status-selesai') ?>">
                  <?= ucfirst($row['status']) ?>
                </span>
              </p>
              <p class="mb-2"><span class="text-gray-400">Order Date:</span> <?= date('d/m/Y H:i', strtotime($row['tgl_order'])) ?></p>
              <p class="mb-2"><span class="text-gray-400">Total:</span> Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></p>
            </div>
          </div>
          <div class="mt-4 flex space-x-2 no-print">
            <a href="struk_admin.php?edit=<?= $row['payment_id'] ?>" 
               class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
              Edit
            </a>
            <a href="struk_admin.php?hapus=<?= $row['payment_id'] ?>" 
               onclick="return confirm('Yakin hapus struk ini?')"
               class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
              Hapus
            </a>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>

  <!-- JS Print per struk -->
  <script>
  function printStruk(id) {
    const content = document.getElementById(id).innerHTML;
    const win = window.open('', '', 'width=800,height=600');
    win.document.write(`
      <html>
        <head>
          <title>Struk Pembayaran</title>
          <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
          <style>
            @page { size: auto; margin: 5mm; }
            body { background: white; color: black; }
            .status-badge { display: inline-block; padding: 0.25rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
            .status-pending { background-color: #eab308; color: #111827; }
            .status-proses { background-color: #3b82f6; color: white; }
            .status-selesai { background-color: #10b981; color: white; }
          </style>
        </head>
        <body onload="window.print(); setTimeout(function(){ window.close(); }, 100);">
          <div class="max-w-md mx-auto p-4">${content}</div>
        </body>
      </html>
    `);
    win.document.close();
  }
  </script>
</body>
</html>
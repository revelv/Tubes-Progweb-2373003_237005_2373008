<?php
include 'koneksi.php';
include 'header_admin.php';

// --- Hapus Order ---
if (isset($_GET['hapus'])) {
  $id = $_GET['hapus'];
  mysqli_query($conn, "DELETE FROM orders WHERE order_id='$id'");
  echo "<script>alert('Order berhasil dihapus'); window.location='order_admin.php';</script>";
}

// --- Ambil Data untuk Edit ---
$edit = null;
if (isset($_GET['edit'])) {
  $id = $_GET['edit'];
  $res = mysqli_query($conn, "SELECT * FROM orders WHERE order_id='$id'");
  $edit = mysqli_fetch_assoc($res);
}

// --- Simpan Perubahan Edit ---
if (isset($_POST['update'])) {
  $id = $_POST['order_id'];
  $customer_id = $_POST['customer_id'];
  $tgl_order = $_POST['tgl_order'];
  $total_harga = $_POST['total_harga'];
  $status = $_POST['status'];

  mysqli_query($conn, "UPDATE orders SET 
    customer_id='$customer_id',
    tgl_order='$tgl_order',
    total_harga='$total_harga',
    status='$status'
    WHERE order_id='$id'");
  echo "<script>alert('Order berhasil diperbarui'); window.location='order_admin.php';</script>";
}

// --- Tambah Order Baru ---
if (isset($_POST['insert'])) {
  $customer_id = $_POST['customer_id'];
  $tgl_order = $_POST['tgl_order'];
  $total_harga = $_POST['total_harga'];
  $status = $_POST['status'];

  mysqli_query($conn, "INSERT INTO orders (customer_id, tgl_order, total_harga, status) VALUES 
    ('$customer_id', '$tgl_order', '$total_harga', '$status')");
  echo "<script>alert('Order baru ditambahkan'); window.location='order_admin.php';</script>";
}

// --- Ubah Status Order ---
if (isset($_POST['update_status'])) {
  $order_id = $_POST['order_id'];
  $status = $_POST['status'];
  mysqli_query($conn, "UPDATE orders SET status='$status' WHERE order_id='$order_id'");
  echo "<script>alert('Status order diperbarui'); window.location='order_admin.php';</script>";
}

// --- Ambil Semua Customer untuk Dropdown ---
$customers = mysqli_query($conn, "SELECT * FROM customer ORDER BY nama");

// --- Filter Pencarian ---
$search = $_GET['search'] ?? '';
$search_by = $_GET['search_by'] ?? 'customer'; // Default search by customer name
$status_filter = $_GET['status'] ?? '';

// Query untuk mendapatkan semua order dengan filter
$query = "
  SELECT o.order_id, c.customer_id, c.nama AS customer, o.tgl_order, o.total_harga, o.status
  FROM orders o
  JOIN customer c ON o.customer_id = c.customer_id
  WHERE 1=1
";

if (!empty($search)) {
  switch ($search_by) {
    case 'customer':
      $query .= " AND c.nama LIKE '%$search%'";
      break;
    case 'order_id':
      $query .= " AND o.order_id LIKE '%$search%'";
      break;
    case 'total':
      $query .= " AND o.total_harga LIKE '%$search%'";
      break;
  }
}

if (!empty($status_filter)) {
  $query .= " AND o.status = '$status_filter'";
}

$query .= " ORDER BY o.tgl_order DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Data Order - Stryk Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-900 text-white p-6">

  <h1 class="text-2xl font-bold text-yellow-400 mb-6">Order Dashboard</h1>

  <!-- Filter Pencarian -->
  <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div>
      <input type="text" name="search" class="w-full px-3 py-2 rounded bg-gray-700 text-white border border-gray-600" 
             placeholder="Cari order..." value="<?= htmlspecialchars($search) ?>">
    </div>
    <div>
      <select name="search_by" class="w-full px-3 py-2 rounded bg-gray-700 text-white border border-gray-600">
        <option value="customer" <?= $search_by === 'customer' ? 'selected' : '' ?>>Customer</option>
        <option value="order_id" <?= $search_by === 'order_id' ? 'selected' : '' ?>>ID Order</option>
        <option value="total" <?= $search_by === 'total' ? 'selected' : '' ?>>Total Harga</option>
      </select>
    </div>
    <div>
      <select name="status" class="w-full px-3 py-2 rounded bg-gray-700 text-white border border-gray-600">
        <option value="">Semua Status</option>
        <option value="pending" <?= $status_filter === 'pending' ? 'selected' : '' ?>>Pending</option>
        <option value="proses" <?= $status_filter === 'proses' ? 'selected' : '' ?>>Proses</option>
        <option value="selesai" <?= $status_filter === 'selesai' ? 'selected' : '' ?>>Selesai</option>
      </select>
    </div>
    <div class="grid grid-cols-2 gap-2">
      <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-bold py-2 px-4 rounded">
        🔍 Cari
      </button>
      <a href="order_admin.php" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-center">
        🔄 Reset
      </a>
    </div>
  </form>


  <!-- Tabel Order -->
  <div class="bg-gray-800 rounded-lg shadow overflow-hidden">
    <table class="w-full">
      <thead class="bg-gray-700 text-yellow-400">
        <tr>
          <th class="py-3 px-4 text-left">ID Order</th>
          <th class="py-3 px-4 text-left">Customer</th>
          <th class="py-3 px-4 text-left">Tanggal Order</th>
          <th class="py-3 px-4 text-left">Total</th>
          <th class="py-3 px-4 text-left">Status</th>
          <th class="py-3 px-4 text-center">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-700">
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
          <tr class="hover:bg-gray-700">
            <td class="py-3 px-4"><?= $row['order_id'] ?></td>
            <td class="py-3 px-4"><?= htmlspecialchars($row['customer']) ?></td>
            <td class="py-3 px-4"><?= date('d-m-Y H:i', strtotime($row['tgl_order'])) ?></td>
            <td class="py-3 px-4">$ <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
            <td class="py-3 px-4">
              <form method="POST" class="flex items-center space-x-2">
                <input type="hidden" name="order_id" value="<?= $row['order_id'] ?>">
                <select name="status" class="px-3 py-1 rounded bg-gray-700 text-white border border-gray-600">
                  <option value="pending" <?= $row['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                  <option value="proses" <?= $row['status'] === 'proses' ? 'selected' : '' ?>>Proses</option>
                  <option value="selesai" <?= $row['status'] === 'selesai' ? 'selected' : '' ?>>Selesai</option>
                </select>
                <button type="submit" name="update_status" class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded text-sm">
                  Update
                </button>
              </form>
            </td>
            <td class="py-3 px-4 text-center">
  <div class="flex justify-center space-x-2">
    <?php if ($row['status'] === 'proses'): ?>
      <a href="tracking_admin.php?order_id=<?= $row['order_id'] ?>" 
         class="bg-purple-500 hover:bg-purple-600 text-white px-3 py-1 rounded text-sm">
        Tracking
      </a>
    <?php endif; ?>

    <a href="order_admin.php?hapus=<?= $row['order_id'] ?>" 
       onclick="return confirm('Yakin hapus order ini?')"
       class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
      Hapus
    </a>
  </div>
</td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
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
  $order_id = mysqli_real_escape_string($conn, $_POST['order_id']);
  $status = mysqli_real_escape_string($conn, $_POST['status']);

  // Mulai transaction
  mysqli_begin_transaction($conn);

  try {
    // Jika status diubah menjadi "proses", buat struk pembayaran
    if ($status == 'proses') {
      // Cek apakah sudah ada pembayaran untuk order ini
      $cek_payment = mysqli_query($conn, "SELECT * FROM payments WHERE order_id='$order_id'");
      if (!$cek_payment) throw new Exception(mysqli_error($conn));

      if (mysqli_num_rows($cek_payment) == 0) {
        // Jika belum ada, buat record pembayaran baru
        $order_data = mysqli_query($conn, "SELECT * FROM orders WHERE order_id='$order_id'");
        if (!$order_data) throw new Exception(mysqli_error($conn));

        $order = mysqli_fetch_assoc($order_data);
        $metode = 'QRIS'; // Default payment method
        $jumlah = $order['total_harga'];

        $insert = mysqli_query($conn, "INSERT INTO payments (order_id, metode, jumlah_dibayar, tanggal_bayar, payment_status) 
          VALUES ('$order_id', '$metode', '$jumlah', NOW(), 'pending')");
        if (!$insert) throw new Exception(mysqli_error($conn));
      }
    }

    $update = mysqli_query($conn, "UPDATE orders SET status='$status' WHERE order_id='$order_id'");
    if (!$update) throw new Exception(mysqli_error($conn));

    mysqli_commit($conn);
    echo "<script>alert('Status order diperbarui'); window.location='order_admin.php';</script>";
  } catch (Exception $e) {
    mysqli_rollback($conn);
    echo "<script>alert('Error: " . addslashes($e->getMessage()) . "'); window.location='order_admin.php';</script>";
  }
}

// --- Update Payment Status ---
// --- Update Payment Status ---
if (isset($_POST['update_payment_status'])) {
  $payment_id = $_POST['payment_id'];
  $status = $_POST['payment_status'];

  // Start transaction
  mysqli_begin_transaction($conn);

  try {
    // Update payment status
    mysqli_query($conn, "UPDATE payments SET payment_status='$status' WHERE payment_id='$payment_id'");

    // If status is rejected, cancel the order and restore stock
    if ($status == 'rejected') {
      // Get order ID from payment
      $payment_query = mysqli_query($conn, "SELECT order_id FROM payments WHERE payment_id='$payment_id'");
      $payment_data = mysqli_fetch_assoc($payment_query);
      $order_id = $payment_data['order_id'];

      // Update order status to 'batal'
      mysqli_query($conn, "UPDATE orders SET status='batal' WHERE order_id='$order_id'");

      // Get all products in this order
      $order_items = mysqli_query($conn, "SELECT product_id, jumlah FROM order_details WHERE order_id='$order_id'");

      // Restore stock for each product
      while ($item = mysqli_fetch_assoc($order_items)) {
        $product_id = $item['product_id'];
        $quantity = $item['jumlah'];

        mysqli_query($conn, "UPDATE products SET stok = stok + $quantity WHERE product_id='$product_id'");
      }

      // Add tracking record
      mysqli_query($conn, "INSERT INTO order_tracking (order_id, status, description) 
                          VALUES ('$order_id', 'batal', 'Pembayaran ditolak, silahkan belanja kembali.')");
    }

    mysqli_commit($conn);

    $redirect_url = 'order_admin.php';
    if (isset($_GET['view_payments'])) {
      $redirect_url .= '?view_payments=1';
    }
    echo "<script>alert('Status pembayaran diperbarui'); window.location='$redirect_url';</script>";
    exit();
  } catch (Exception $e) {
    mysqli_rollback($conn);
    echo "<script>alert('Error: " . addslashes($e->getMessage()) . "'); window.location='order_admin.php';</script>";
    exit();
  }
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

// Query untuk payment proofs jika diperlukan
if (isset($_GET['view_payments'])) {
  $payments_query = "
        SELECT p.*, c.nama AS customer 
        FROM payments p
        JOIN orders o ON p.order_id = o.order_id
        JOIN customer c ON o.customer_id = c.customer_id
        ORDER BY p.tanggal_bayar DESC
    ";
  $payments_result = mysqli_query($conn, $payments_query);
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Data Order - Stryk Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
          <option value="batal" <?= $row['status'] === 'batal' ? 'selected' : '' ?>>Batal</option>
      </select>
    </div>
    <div class="grid grid-cols-2 gap-2">
      <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-bold py-2 px-4 rounded">
        🔍 Cari
      </button>
      <a href="order_admin.php" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-center">
        🔄 Refresh
      </a>
    </div>
  </form>

  <!-- Tab Navigation -->
  <div class="flex border-b border-gray-700 mb-6">
    <a href="order_admin.php" class="px-4 py-2 <?= !isset($_GET['view_payments']) ? 'border-b-2 border-yellow-400 text-yellow-400' : 'text-gray-400' ?> font-medium">
      Orders
    </a>
    <a href="order_admin.php?view_payments=1" class="px-4 py-2 <?= isset($_GET['view_payments']) ? 'border-b-2 border-yellow-400 text-yellow-400' : 'text-gray-400' ?> font-medium">
      Payment Proofs
    </a>
  </div>

  <?php if (!isset($_GET['view_payments'])): ?>
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
                    <option value="batal" <?= $row['status'] === 'batal' ? 'selected' : '' ?>>Batal</option>
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

                  <a href="order_admin.php?view_payments=1&order_id=<?= $row['order_id'] ?>"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                    Payment
                  </a>

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
  <?php else: ?>
    <!-- Payment Proofs Section -->
    <div class="bg-gray-800 rounded-lg shadow overflow-hidden">
      <table class="w-full">
        <thead class="bg-gray-700 text-yellow-400">
          <tr>
            <th class="py-3 px-4 text-left">Order ID</th>
            <th class="py-3 px-4 text-left">Customer</th>
            <th class="py-3 px-4 text-left">Payment Method</th>
            <th class="py-3 px-4 text-left">Amount</th>
            <th class="py-3 px-4 text-left">Payment Date</th>
            <th class="py-3 px-4 text-left">Status</th>
            <th class="py-3 px-4 text-left">Proof</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-700">
          <?php
          $order_filter = isset($_GET['order_id']) ? "AND p.order_id = '" . $_GET['order_id'] . "'" : "";
          $payments_query = "
            SELECT p.*, c.nama AS customer, o.total_harga
            FROM payments p
            JOIN orders o ON p.order_id = o.order_id
            JOIN customer c ON o.customer_id = c.customer_id
            WHERE 1=1 $order_filter
            ORDER BY p.tanggal_bayar DESC
          ";
          $payments_result = mysqli_query($conn, $payments_query);


          while ($payment = mysqli_fetch_assoc($payments_result)):
          ?>
            <tr class="hover:bg-gray-700">
              <td class="py-3 px-4"><?= $payment['order_id'] ?></td>
              <td class="py-3 px-4"><?= htmlspecialchars($payment['customer']) ?></td>
              <td class="py-3 px-4"><?= $payment['metode'] ?></td>
              <td class="py-3 px-4">$ <?= number_format($payment['jumlah_dibayar'], 0, ',', '.') ?></td>
              <td class="py-3 px-4"><?= date('d-m-Y H:i', strtotime($payment['tanggal_bayar'])) ?></td>
              <td class="py-3 px-4">
                <form method="POST" class="flex items-center space-x-2">
                  <input type="hidden" name="payment_id" value="<?= $payment['payment_id'] ?>">
                  <select name="payment_status" class="px-3 py-1 rounded bg-gray-700 text-white border border-gray-600">
                    <option value="pending" <?= $payment['payment_status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="verified" <?= $payment['payment_status'] === 'verified' ? 'selected' : '' ?>>Verified</option>
                    <option value="rejected" <?= $payment['payment_status'] === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                  </select>
                  <button type="submit" name="update_payment_status" class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded text-sm">
                    Update
                  </button>
                </form>
              </td>
              <td class="py-3 px-4">
                <?php if ($payment['metode'] === 'Transfer Bank'): ?>
                  <?php if (!empty($payment['payment_proof']) && file_exists($payment['payment_proof'])): ?>
                    <a href="#" onclick="openModal('<?= $payment['payment_proof'] ?>')" class="text-blue-400 hover:text-blue-300">
                      View Proof
                    </a>
                  <?php else: ?>
                    No proof uploaded
                  <?php endif; ?>
                <?php elseif ($payment['metode'] === 'QRIS'): ?>
                  <span class="text-green-400 break-all"><?= htmlspecialchars($payment['payment_proof']) ?></span>
                <?php else: ?>
                  -
                <?php endif; ?>
              </td>


            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden">
      <div class="bg-gray-800 p-4 rounded-lg max-w-4xl max-h-screen">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-xl font-bold">Payment Proof</h3>
          <button onclick="closeModal()" class="text-gray-400 hover:text-white">
            <i class="fas fa-times"></i>
          </button>
        </div>
        <img id="modalImage" src="" alt="<?php $bukti ?>" class="max-w-full max-h-[80vh]">
      </div>
    </div>

    <script>
      function openModal(imageSrc) {
        document.getElementById('modalImage').src = imageSrc;
        document.getElementById('imageModal').classList.remove('hidden');
      }

      function closeModal() {
        document.getElementById('imageModal').classList.add('hidden');
      }
    </script>
  <?php endif; ?>
</body>

</html>
<?php
include 'koneksi.php';

$order_id = $_GET['order_id'] ?? 0;

// Ambil data order
$order_query = mysqli_query($conn, "SELECT o.*, c.nama AS customer_name 
                                   FROM orders o 
                                   JOIN customer c ON o.customer_id = c.customer_id 
                                   WHERE o.order_id = '$order_id'");
$order = mysqli_fetch_assoc($order_query);

// Jika tidak ditemukan, redirect
if (!$order) {
  header("Location: order_admin.php");
  exit();
}

// Update tracking info jika form disubmit
if (isset($_POST['update_tracking'])) {
  $tracking_info = $_POST['tracking_info'];
  mysqli_query($conn, "UPDATE orders SET tracking_info = '$tracking_info' WHERE order_id = '$order_id'");
  $order['tracking_info'] = $tracking_info;
  echo "<script>alert('Tracking info diperbarui');</script>";
}

// Default tracking steps
$tracking_steps = [
  'Pesanan diterima',
  'Pesanan diproses',
  'Pesanan dikemas',
  'Pesanan dikirim',
  'Pesanan sampai di gerai terdekat',
  'Pesanan diterima customer'
];

// Jika ada custom tracking info, gunakan itu
$current_tracking = $order['tracking_info'] ?? '';
$tracking_data = $current_tracking ? explode('|', $current_tracking) : [];
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tracking Order - Stryk Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-900 text-white p-6">
  <!-- NAV -->
  <nav class="bg-gray-800 shadow px-6 py-4 flex justify-between items-center mb-6">
    <h1 class="text-xl font-bold text-yellow-400">Stryk Admin</h1>
    <div class="space-x-6 text-sm text-white">
      <a href="index_admin.php" class="hover:text-yellow-400">Home</a>
      <a href="order_admin.php" class="hover:text-yellow-400">Kembali ke Order</a>
    </div>
  </nav>

  <div class="max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold text-yellow-400 mb-2">Tracking Order #<?= $order['order_id'] ?></h1>
    <p class="text-gray-400 mb-6">Customer: <?= htmlspecialchars($order['customer_name']) ?></p>

    <!-- Timeline Tracking -->
    <div class="bg-gray-800 rounded-lg shadow p-6 mb-8">
      <h2 class="text-xl font-semibold text-yellow-400 mb-4">Status Pengiriman</h2>
      
      <div class="space-y-4">
        <?php foreach ($tracking_steps as $index => $step): ?>
          <div class="flex items-start">
            <div class="flex-shrink-0 pt-1">
              <div class="<?= (count($tracking_data) > $index) ? 'bg-green-500' : 'bg-gray-600' ?> 
                           rounded-full h-4 w-4 flex items-center justify-center"></div>
              <?php if ($index < count($tracking_steps) - 1): ?>
                <div class="<?= (count($tracking_data) > $index) ? 'bg-green-500' : 'bg-gray-600' ?> 
                             h-8 w-0.5 mx-auto"></div>
              <?php endif; ?>
            </div>
            <div class="ml-4">
              <p class="<?= (count($tracking_data) > $index) ? 'text-green-400 font-medium' : 'text-gray-400' ?>">
                <?= $step ?>
              </p>
              <?php if (isset($tracking_data[$index])): ?>
                <p class="text-xs text-gray-500 mt-1"><?= htmlspecialchars($tracking_data[$index]) ?></p>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Form Update Tracking (Admin Only) -->
    <div class="bg-gray-800 rounded-lg shadow p-6">
      <h2 class="text-xl font-semibold text-yellow-400 mb-4">Update Tracking</h2>
      
      <form method="POST" class="space-y-4">
        <div>
          <label class="block text-gray-300 mb-1">Step Saat Ini</label>
          <select name="current_step" class="w-full px-3 py-2 rounded bg-gray-700 text-white border border-gray-600">
            <?php foreach ($tracking_steps as $index => $step): ?>
              <option value="<?= $index ?>" <?= (count($tracking_data) == $index + 1) ? 'selected' : '' ?>>
                <?= $step ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        
        <div>
          <label class="block text-gray-300 mb-1">Detail Lokasi/Info</label>
          <input type="text" name="tracking_info" 
                 value="<?= htmlspecialchars($current_tracking) ?>"
                 placeholder="Note Here"
                 class="w-full px-3 py-2 rounded bg-gray-700 text-white border border-gray-600">
          <p class="text-xs text-gray-500 mt-1">Pisahkan setiap update dengan tanda |</p>
        </div>
        
        <div class="flex space-x-3 pt-2">
          <button type="submit" name="update_tracking" 
                  class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-bold py-2 px-6 rounded">
            Update Tracking
          </button>
          <a href="order.php" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded">
            Kembali
          </a>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
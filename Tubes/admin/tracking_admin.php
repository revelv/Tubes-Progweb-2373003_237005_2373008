<?php
// Koneksi database
require_once __DIR__ . '/../koneksi.php';
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

$order_id = filter_input(INPUT_GET, 'order_id', FILTER_VALIDATE_INT);
if ($order_id === null || $order_id <= 0) {
    die("ID Order tidak valid");
}

// Definisi konstanta tracking steps
define('TRACKING_STEPS', [
    'Pesanan masuk',
    'Pesanan diproses',
    'Pesanan dikemas',
    'Pesanan dikirim ke gerai',
    'Pesanan sampai di gerai',
    'Pesanan keluar dari gerai',
    'Pesanan dikirim ke customer',
    'Pesanan diterima customer'
]);

// Inisialisasi variabel
$order_id = 0;
$order = null;
$tracking_history = [];
$next_statuses = [];
$error_message = '';

try {
    // Ambil order_id dari URL
    $order_id = intval($_GET['order_id'] ?? 0);

    if ($order_id <= 0) {
        throw new Exception("ID Order tidak valid");
    }

    // Validasi order
    $order_query = mysqli_prepare(
        $conn,
        "SELECT o.*, c.nama AS customer_name 
         FROM orders o 
         JOIN customer c ON o.customer_id = c.customer_id 
         WHERE o.order_id = ?"
    );

    if (!$order_query) {
        throw new Exception("Query error: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($order_query, "i", $order_id);
    mysqli_stmt_execute($order_query);
    $order = mysqli_stmt_get_result($order_query)->fetch_assoc();

    if (!$order) {
        throw new Exception("Order tidak ditemukan");
    }

    // Handle form submission
    if (isset($_POST['update_tracking'])) {
        $status = $_POST['status'] ?? '';
        $description = $_POST['description'] ?? null;

        if (empty($status)) {
            throw new Exception("Status tidak boleh kosong");
        }

        // Insert tracking data
        $insert_query = mysqli_prepare(
            $conn,
            "INSERT INTO order_tracking (order_id, status, description) 
             VALUES (?, ?, ?)"
        );

        if (!$insert_query) {
            throw new Exception("Prepare error: " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($insert_query, "iss", $order_id, $status, $description);

        if (!mysqli_stmt_execute($insert_query)) {
            throw new Exception("Execute error: " . mysqli_stmt_error($insert_query));
        }

        // Update order status if final step
        if ($status === 'Pesanan diterima customer') {
            mysqli_query(
                $conn,
                "UPDATE orders SET status = 'selesai' 
                 WHERE order_id = $order_id"
            );
        }

        // Redirect to avoid form resubmission
        header("Location: tracking_admin.php?order_id=$order_id&success=1");
        exit();
    }

    // Get tracking history
    $tracking_query = mysqli_prepare(
        $conn,
        "SELECT * FROM order_tracking 
         WHERE order_id = ? 
         ORDER BY timestamp ASC"
    );

    if (!$tracking_query) {
        throw new Exception("Query error: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($tracking_query, "i", $order_id);
    mysqli_stmt_execute($tracking_query);
    $tracking_history = mysqli_stmt_get_result($tracking_query)->fetch_all(MYSQLI_ASSOC);

    // Determine next available statuses
    $completed_steps = array_column($tracking_history, 'status');
    $last_status = end($completed_steps);

    if (empty($completed_steps)) {
        $next_statuses = ['Pesanan diterima'];
    } elseif ($last_status === 'Pesanan diterima') {
        $next_statuses = ['Pesanan diproses'];
    } elseif ($last_status === 'Pesanan diproses') {
        $next_statuses = ['Pesanan dikemas'];
    } elseif ($last_status === 'Pesanan dikemas') {
        $next_statuses = ['Pesanan dikirim ke gerai'];
    } elseif ($last_status === 'Pesanan dikirim ke gerai') {
        $next_statuses = ['Pesanan sampai di gerai'];
    } elseif ($last_status === 'Pesanan sampai di gerai') {
        $next_statuses = ['Pesanan keluar dari gerai'];
    } elseif ($last_status === 'Pesanan keluar dari gerai') {
        $next_statuses = [
            'Pesanan dikirim ke gerai',
            'Pesanan dikirim ke customer'
        ];
    } elseif ($last_status === 'Pesanan dikirim ke customer') {
        $next_statuses = ['Pesanan diterima customer'];
    } else {
        $next_statuses = [];
    }
} catch (Exception $e) {
    $error_message = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracking Order - Stryk Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .timeline-dot {
            @apply flex-shrink-0 w-4 h-4 rounded-full bg-gray-600;
        }

        .timeline-dot.active {
            @apply bg-green-500;
        }

        .timeline-connector {
            @apply flex-shrink-0 w-0.5 h-6 bg-gray-600 mx-auto;
        }

        .timeline-connector.active {
            @apply bg-green-500;
        }

        .form-input-dark {
            @apply bg-gray-700 border-gray-600 text-white focus:ring-yellow-500 focus:border-yellow-500;
        }
    </style>
</head>

<body class="bg-gray-900 text-gray-100">
    <div class="container mx-auto p-4 max-w-4xl">
        <?php if (!empty($error_message)) : ?>
            <div class="bg-red-800/50 border border-red-600 text-red-100 px-4 py-3 rounded-lg mb-6 flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <?= htmlspecialchars($error_message) ?>
            </div>
            <a href="order_admin.php" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Order
            </a>
        <?php elseif ($order) : ?>
            <!-- Header Card -->
            <div class="bg-gray-800 rounded-lg shadow-lg p-6 mb-6 border border-gray-700">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-bold text-yellow-400">
                            <i class="fas fa-truck mr-2"></i> Tracking Order #<?= $order_id ?>
                        </h1>
                        <div class="flex items-center mt-2 text-gray-400">
                            <span><i class="fas fa-user mr-1"></i> <?= htmlspecialchars($order['customer_name']) ?></span>
                            <span class="mx-3 text-gray-600">|</span>
                            <span class="font-medium <?=
                                                        $order['status'] === 'proses' ? 'text-blue-400' : ($order['status'] === 'selesai' ? 'text-green-400' : 'text-yellow-400')
                                                        ?>">
                                <i class="fas fa-<?=
                                                    $order['status'] === 'proses' ? 'cog' : ($order['status'] === 'selesai' ? 'check-circle' : 'clock')
                                                    ?> mr-1"></i>
                                <?= ucfirst($order['status']) ?>
                            </span>
                        </div>
                    </div>
                    <a href="order_admin.php" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                </div>
            </div>

            <!-- Success Notification -->
            <?php if (isset($_GET['success'])) : ?>
                <div class="bg-green-800/50 border border-green-600 text-green-100 px-4 py-3 rounded-lg mb-6 flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    Status tracking berhasil diperbarui!
                </div>
            <?php endif; ?>

            <!-- Timeline Section -->
            <div class="bg-gray-800 rounded-lg shadow-lg p-6 mb-6 border border-gray-700">
                <h2 class="text-xl font-semibold text-yellow-400 mb-4 flex items-center">
                    <i class="fas fa-history mr-2"></i> Riwayat Pengiriman
                </h2>

                <?php if (!empty($tracking_history)) : ?>
                    <div class="space-y-6 pl-2">
                        <?php foreach ($tracking_history as $index => $track) : ?>
                            <div class="flex group">
                                <div class="flex flex-col items-center mr-4">
                                    <div class="timeline-dot active group-hover:scale-125 transition-transform"></div>
                                    <?php if ($index < count($tracking_history) - 1) : ?>
                                        <div class="timeline-connector active"></div>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-grow pb-6 group-hover:bg-gray-700/50 rounded-lg px-3 py-2 transition-colors">
                                    <p class="font-medium text-white flex items-center">
                                        <i class="fas fa-<?=
                                                            strpos($track['status'], 'diterima') !== false ? 'check' : (strpos($track['status'], 'dikirim') !== false ? 'shipping-fast' : 'box')
                                                            ?> mr-2 text-gray-400"></i>
                                        <?= htmlspecialchars($track['status']) ?>
                                    </p>
                                    <p class="text-sm text-gray-400 mt-1 ml-6">
                                        <i class="far fa-clock mr-1"></i>
                                        <?= date('d/m/Y H:i', strtotime($track['timestamp'])) ?>
                                    </p>
                                    <?php if (!empty($track['description'])) : ?>
                                        <div class="mt-2 p-3 bg-gray-700 rounded-lg text-sm ml-6 border-l-2 border-yellow-500">
                                            <i class="fas fa-info-circle mr-2 text-yellow-400"></i>
                                            <?= nl2br(htmlspecialchars($track['description'])) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($next_statuses)) : ?>
                    <div class="flex items-center pt-2">
                        <div class="flex flex-col items-center mr-4">
                            <div class="timeline-dot pulse-animation"></div>
                        </div>
                        <div class="flex-grow">
                            <p class="text-gray-400 italic">
                                <i class="fas fa-arrow-right mr-2 text-yellow-400"></i>
                                Menunggu: <?= implode(', ', $next_statuses) ?>
                            </p>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="flex items-center pt-2">
                        <div class="flex flex-col items-center mr-4">
                            <div class="timeline-dot bg-green-500">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                        </div>
                        <div class="flex-grow">
                            <p class="text-green-400 font-medium">
                                <i class="fas fa-flag-checkered mr-2"></i>
                                Semua tahap pengiriman telah selesai
                            </p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (!empty($next_statuses)) : ?>
                <!-- Form Update -->
                <div class="bg-gray-800 rounded-lg shadow-lg p-6 border border-gray-700">
                    <h2 class="text-xl font-semibold text-yellow-400 mb-4 flex items-center">
                        <i class="fas fa-edit mr-2"></i> Update Status Tracking
                    </h2>

                    <form method="POST" class="space-y-4">
                        <div>
                            <label class="block text-gray-300 mb-2 flex items-center">
                                <i class="fas fa-tag mr-2"></i> Status
                            </label>
                            <select name="status" style="background-color: #2c3545; color: #f0f0f0; border: 1px solid #444;" required
                                class="w-full px-4 py-2 form-input-dark rounded-lg">
                                <option value="">-- Pilih Status --</option>
                                <?php foreach ($next_statuses as $status) : ?>
                                    <option value="<?= htmlspecialchars($status) ?>">
                                        <?= htmlspecialchars($status) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label class="block text-gray-300 mb-2 flex items-center">
                                <i class="fas fa-align-left mr-2"></i> Keterangan (Opsional)
                            </label>
                            <textarea name="description" style="background-color: #2c3545; color: #f0f0f0; border: 1px solid #444;" rows="3"
                                class="w-full px-4 py-2 form-input-dark rounded-lg"
                                placeholder=" "></textarea>
                        </div>

                        <div class="flex space-x-3 pt-2">
                            <button type="submit" name="update_tracking"
                                class="bg-yellow-600 hover:bg-yellow-500 text-white font-bold py-2 px-6 rounded-lg transition flex items-center">
                                <i class="fas fa-save mr-2"></i> Update Status
                            </button>
                            <button type="reset"
                                class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg transition flex items-center">
                                <i class="fas fa-undo mr-2"></i> Reset
                            </button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <style>
        .pulse-animation {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(74, 222, 128, 0.7);
            }

            70% {
                transform: scale(1);
                box-shadow: 0 0 0 10px rgba(74, 222, 128, 0);
            }

            100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(74, 222, 128, 0);
            }
        }
    </style>
</body>

</html>
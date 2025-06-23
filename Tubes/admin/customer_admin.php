<?php
include 'koneksi.php';
include 'header_admin.php';

// --- Filter Pencarian ---
$search = $_GET['search'] ?? '';
$search_by = $_GET['search_by'] ?? 'nama'; // Default search by name

// Query utama dengan filter pencarian
$query = "SELECT customer_id, nama, email, no_telepon, alamat FROM customer WHERE 1=1";
if (!empty($search)) {
  switch ($search_by) {
    case 'nama':
      $query .= " AND nama LIKE '%$search%'";
      break;
    case 'email':
      $query .= " AND email LIKE '%$search%'";
      break;
    case 'no_telepon':
      $query .= " AND no_telepon LIKE '%$search%'";
      break;
    case 'alamat':
      $query .= " AND alamat LIKE '%$search%'";
      break;
    default:
      $query .= " AND nama LIKE '%$search%'";
  }
}
$query .= " ORDER BY nama";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Data Customer - Stryk Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <style>
    .table-container {
      overflow-x: auto;
    }
    .truncate {
      max-width: 200px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
  </style>
</head>

<body class="bg-gray-900 text-white p-6">

  <h1 class="text-2xl font-bold text-yellow-400 mb-6">Customer Library</h1>

  <!-- Filter Pencarian -->
  <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="col-span-2">
      <input type="text" name="search" class="w-full px-3 py-2 rounded bg-gray-700 text-white border border-gray-600" 
             placeholder="Cari customer..." value="<?= htmlspecialchars($search) ?>">
    </div>
    <div>
      <select name="search_by" class="w-full px-3 py-2 rounded bg-gray-700 text-white border border-gray-600">
        <option value="nama" <?= $search_by === 'nama' ? 'selected' : '' ?>>Nama</option>
        <option value="email" <?= $search_by === 'email' ? 'selected' : '' ?>>Email</option>
        <option value="no_telepon" <?= $search_by === 'no_telepon' ? 'selected' : '' ?>>No. Telepon</option>
        <option value="alamat" <?= $search_by === 'alamat' ? 'selected' : '' ?>>Alamat</option>
      </select>
    </div>
    <div class="grid grid-cols-2 gap-2">
      <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-bold py-2 px-4 rounded">
        🔍 Cari
      </button>
      <a href="customer_admin.php" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-center">
        🔄 Refresh
      </a>
    </div>
  </form>

  <!-- Tabel Customer -->
  <div class="bg-gray-800 rounded-lg shadow overflow-hidden table-container">
    <table class="w-full">
      <thead class="bg-gray-700 text-yellow-400">
        <tr>
          <th class="py-3 px-4 text-left">ID</th>
          <th class="py-3 px-4 text-left">Nama</th>
          <th class="py-3 px-4 text-left">Email</th>
          <th class="py-3 px-4 text-left">No Telepon</th>
          <th class="py-3 px-4 text-left">Alamat</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-700">
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
          <tr class="hover:bg-gray-700">
            <td class="py-3 px-4"><?= $row['customer_id'] ?></td>
            <td class="py-3 px-4"><?= htmlspecialchars($row['nama']) ?></td>
            <td class="py-3 px-4"><?= htmlspecialchars($row['email']) ?></td>
            <td class="py-3 px-4"><?= htmlspecialchars($row['no_telepon']) ?></td>
            <td class="py-3 px-4 truncate" title="<?= htmlspecialchars($row['alamat']) ?>">
              <?= strlen($row['alamat']) > 30 ? substr(htmlspecialchars($row['alamat']), 0, 30).'...' : htmlspecialchars($row['alamat']) ?>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
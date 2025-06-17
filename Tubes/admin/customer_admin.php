<?php
include 'koneksi.php';
include 'header_admin.php';

// --- Hapus Customer ---
if (isset($_GET['hapus'])) {
  $id = $_GET['hapus'];
  mysqli_query($conn, "DELETE FROM customer WHERE customer_id='$id'");
  echo "<script>alert('Customer berhasil dihapus'); window.location='customer_admin.php';</script>";
}

// --- Ambil Data untuk Edit ---
$edit = null;
if (isset($_GET['edit'])) { 
  $id = $_GET['edit'];
  $res = mysqli_query($conn, "SELECT * FROM customer WHERE customer_id='$id'");
  $edit = mysqli_fetch_assoc($res);
}

// --- Simpan Perubahan Edit ---
if (isset($_POST['update'])) {
  $id = $_POST['customer_id'];
  $nama = $_POST['nama'];
  $email = $_POST['email'];
  $telp = $_POST['no_telepon'];
  $alamat = $_POST['alamat'];

  mysqli_query($conn, "UPDATE customer SET 
    nama='$nama', 
    email='$email', 
    no_telepon='$telp', 
    alamat='$alamat'
    WHERE customer_id='$id'");
  echo "<script>alert('Customer berhasil diperbarui'); window.location='customer_admin.php';</script>";
}

// --- Tambah Customer Baru ---
if (isset($_POST['insert'])) {
  $nama = $_POST['nama'];
  $email = $_POST['email'];
  $telp = $_POST['no_telepon'];
  $alamat = $_POST['alamat'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

  mysqli_query($conn, "INSERT INTO customer (nama, password, email, no_telepon, alamat) VALUES 
    ('$nama', '$password', '$email', '$telp', '$alamat')");
  echo "<script>alert('Customer baru ditambahkan'); window.location='customer_admin.php';</script>";
}

// --- Filter Pencarian ---
$search = $_GET['search'] ?? '';
$search_by = $_GET['search_by'] ?? 'nama'; // Default search by name

// Query utama dengan filter pencarian
$query = "SELECT * FROM customer WHERE 1=1";
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
</head>

<body class="bg-gray-900 text-white p-6">

  <h1 class="text-2xl font-bold text-yellow-400 mb-6">Customer Dashboard</h1>

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
      </select>
    </div>
    <div class="grid grid-cols-2 gap-2">
      <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-bold py-2 px-4 rounded">
        🔍 Cari
      </button>
      <a href="customer_admin.php" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-center">
        🔄 Reset
      </a>
    </div>
  </form>

  <!-- Form Tambah/Edit -->
  <div class="bg-gray-800 rounded-lg shadow p-6 mb-8">
    <h2 class="text-xl font-semibold text-yellow-400 mb-4">
      <?= $edit ? "Edit Customer ID: {$edit['customer_id']}" : 'Tambah Customer Baru' ?>
    </h2>
    
    <form method="POST" class="space-y-4">
      <?php if ($edit): ?>
        <input type="hidden" name="customer_id" value="<?= $edit['customer_id'] ?>">
      <?php endif; ?>
      
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-gray-300 mb-1">Nama</label>
          <input type="text" name="nama" value="<?= $edit['nama'] ?? '' ?>" required
                 class="w-full px-3 py-2 rounded bg-gray-700 text-white border border-gray-600">
        </div>
        
        <div>
          <label class="block text-gray-300 mb-1">Email</label>
          <input type="email" name="email" value="<?= $edit['email'] ?? '' ?>" required
                 class="w-full px-3 py-2 rounded bg-gray-700 text-white border border-gray-600">
        </div>
      </div>
      
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-gray-300 mb-1">No Telepon</label>
          <input type="text" name="no_telepon" value="<?= $edit['no_telepon'] ?? '' ?>" required
                 class="w-full px-3 py-2 rounded bg-gray-700 text-white border border-gray-600">
        </div>
        
        <?php if (!$edit): ?>
          <div>
            <label class="block text-gray-300 mb-1">Password</label>
            <input type="password" name="password" required
                   class="w-full px-3 py-2 rounded bg-gray-700 text-white border border-gray-600">
          </div>
        <?php endif; ?>
      </div>
      
      <div>
        <label class="block text-gray-300 mb-1">Alamat</label>
        <textarea name="alamat" required
                 class="w-full px-3 py-2 rounded bg-gray-700 text-white border border-gray-600"><?= $edit['alamat'] ?? '' ?></textarea>
      </div>
      
      <div class="flex space-x-3 pt-2">
        <button type="submit" name="<?= $edit ? 'update' : 'insert' ?>" 
                class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-bold py-2 px-6 rounded">
          <?= $edit ? 'Simpan Perubahan' : 'Tambah Customer' ?>
        </button>
        
        <?php if ($edit): ?>
          <a href="customer_admin.php" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded">
            Batal
          </a>
        <?php endif; ?>
      </div>
    </form>
  </div>

  <!-- Tabel Customer -->
  <div class="bg-gray-800 rounded-lg shadow overflow-hidden">
    <table class="w-full">
      <thead class="bg-gray-700 text-yellow-400">
        <tr>
          <th class="py-3 px-4 text-left">ID</th>
          <th class="py-3 px-4 text-left">Nama</th>
          <th class="py-3 px-4 text-left">Email</th>
          <th class="py-3 px-4 text-left">No Telepon</th>
          <th class="py-3 px-4 text-left">Alamat</th>
          <th class="py-3 px-4 text-center">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-700">
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
          <tr class="hover:bg-gray-700">
            <td class="py-3 px-4"><?= $row['customer_id'] ?></td>
            <td class="py-3 px-4"><?= htmlspecialchars($row['nama']) ?></td>
            <td class="py-3 px-4"><?= $row['email'] ?></td>
            <td class="py-3 px-4"><?= $row['no_telepon'] ?></td>
            <td class="py-3 px-4"><?= $row['alamat'] ?></td>
            <td class="py-3 px-4 text-center">
              <div class="flex justify-center space-x-2">
                <a href="customer_admin.php?edit=<?= $row['customer_id'] ?>" 
                   class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                  Edit
                </a>
                <a href="customer_admin.php?hapus=<?= $row['customer_id'] ?>" 
                   onclick="return confirm('Hapus customer ini?')"
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
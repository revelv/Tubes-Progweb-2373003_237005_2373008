<?php
include 'koneksi.php';
include 'header_admin.php';

// --- Hapus Produk ---
if (isset($_GET['hapus'])) {
  $id = $_GET['hapus'];
  mysqli_query($conn, "DELETE FROM products WHERE product_id='$id'");
  echo "<script>alert('Produk berhasil dihapus'); window.location='produk_admin.php';</script>";
}

// --- Ambil Produk untuk Edit ---
$edit = null;
if (isset($_GET['edit'])) {
  $id = $_GET['edit'];
  $res = mysqli_query($conn, "SELECT * FROM products WHERE product_id='$id'");
  $edit = mysqli_fetch_assoc($res);
}

// --- Simpan Perubahan Edit Produk ---
if (isset($_POST['update'])) {
  $id_lama = $_POST['old_product_id']; // hidden input
  $id_baru = $_POST['product_id'];
  $nama = $_POST['nama_produk'];
  $deskripsi_produk = $_POST['deskripsi_produk'];
  $harga = $_POST['harga'];
  $stok = $_POST['stok'];
  $kategori = $_POST['category'];
  $gambar = $_POST['link_gambar'];

  // ✅ Cek apakah nama produk di kategori ini sudah dipakai oleh produk lain
  $cek = mysqli_query($conn, "
    SELECT * FROM products 
    WHERE nama_produk = '" . mysqli_real_escape_string($conn, $nama) . "'
    AND category_id = " . intval($kategori) . "
    AND product_id != '" . mysqli_real_escape_string($conn, $id_lama) . "'
  ");

  if (mysqli_num_rows($cek) > 0) {
    echo "<script>alert('Nama produk sudah ada di kategori ini.'); window.location='produk_admin.php';</script>";
    exit;
  }

  // ✅ Update jika tidak duplikat
  $query = "UPDATE products SET 
    product_id = '" . mysqli_real_escape_string($conn, $id_baru) . "',
    nama_produk = '" . mysqli_real_escape_string($conn, $nama) . "',
    deskripsi_produk = '" . mysqli_real_escape_string($conn, $deskripsi_produk) . "',
    harga = '" . mysqli_real_escape_string($conn, $harga) . "',
    stok = " . intval($stok) . ",
    category_id = " . intval($kategori) . ",
    link_gambar = '" . mysqli_real_escape_string($conn, $gambar) . "'
    WHERE product_id = '" . mysqli_real_escape_string($conn, $id_lama) . "'";

  if (mysqli_query($conn, $query)) {
    echo "<script>alert('Produk berhasil diperbarui'); window.location='produk_admin.php';</script>";
  } else {
    echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
  }
}


// --- Tambah Produk Baru ---
if (isset($_POST['insert'])) {
  $id = $_POST['product_id'];
  $nama = $_POST['nama_produk'];
  $harga = $_POST['harga'];
  $deskripsi_produk = $_POST['deskripsi_produk'];
  $stok = $_POST['stok'];
  $kategori = intval($_POST['category']);
  $gambar = $_POST['link_gambar'];

  // Cek duplikat nama produk di kategori yang sama
  $cek = mysqli_query($conn, "SELECT * FROM products WHERE nama_produk = '" . mysqli_real_escape_string($conn, $nama) . "' AND category_id = " . intval($kategori));

  if (mysqli_num_rows($cek) > 0) {
    echo "<script>alert('Nama produk sudah ada di kategori ini.'); window.location='produk_admin.php';</script>";
    exit;
  }


  $query = "INSERT INTO products (
    product_id, nama_produk, deskripsi_produk, harga, stok, category_id, link_gambar
  ) VALUES (
    '" . mysqli_real_escape_string($conn, $id) . "',
    '" . mysqli_real_escape_string($conn, $nama) . "',
    '" . mysqli_real_escape_string($conn, $deskripsi_produk) . "',
    '" . mysqli_real_escape_string($conn, $harga) . "',
    " . intval($stok) . ",
    " . $kategori . ",
    '" . mysqli_real_escape_string($conn, $gambar) . "'
  )";

  mysqli_query($conn, $query);
  echo "<script>alert('Produk baru ditambahkan'); window.location='produk_admin.php';</script>";
}

// --- Filter Pencarian ---
$search = $_GET['search'] ?? '';
$category_filter = $_GET['category'] ?? '';

// Query utama dengan JOIN dan filter
$query = "SELECT products.*, category.category AS nama_kategori 
          FROM products 
          JOIN category ON products.category_id = category.category_id 
          WHERE 1=1";

if (!empty($search)) {
  $query .= " AND (nama_produk LIKE '%$search%' OR product_id LIKE '%$search%')";
}

if (!empty($category_filter)) {
  $query .= " AND products.category_id = " . intval($category_filter);
}

$query .= " ORDER BY category_id, nama_produk";
$result = mysqli_query($conn, $query);

// Untuk dropdown kategori
$all_categories = mysqli_query($conn, "SELECT * FROM category ORDER BY category");
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Manajemen Produk - Stryk Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-900 text-white p-6">

  <h1 class="text-2xl font-bold text-yellow-400 mb-6">Product Dashboard</h1>

  <!-- Filter Pencarian -->
  <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div>
      <input type="text" name="search" placeholder="Cari produk..." value="<?= htmlspecialchars($search) ?>"
        class="w-full px-3 py-2 rounded bg-gray-700 text-white border border-gray-600">
    </div>
    <div>
      <select name="category" class="w-full px-3 py-2 rounded bg-gray-700 text-white border border-gray-600">
        <option value="">Semua Kategori</option>
        <?php while ($cat = mysqli_fetch_assoc($all_categories)): ?>
          <option value="<?= $cat['category_id'] ?>" <?= $category_filter == $cat['category_id'] ? 'selected' : '' ?>>
            <?= $cat['category'] ?>
          </option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="grid grid-cols-2 gap-2">
      <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-bold py-2 px-4 rounded">
        🔍 Cari
      </button>
      <a href="produk_admin.php" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-center">
        Refresh
      </a>
    </div>
  </form>

  <!-- Form Tambah/Edit -->
  <div class="bg-gray-800 rounded-lg shadow p-6 mb-8">
    <h2 class="text-xl font-semibold text-yellow-400 mb-4">
      <?= $edit ? "Edit Produk ID: {$edit['product_id']}" : 'Tambah Produk Baru' ?>
    </h2>

     <form method="POST" class="space-y-4">
      <?php if ($edit): ?>
        <input type="hidden" name="old_product_id" value="<?= $edit['product_id'] ?>">
        <input type="hidden" name="product_id" value="<?= $edit['product_id'] ?>">
      <?php endif; ?>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-gray-300 mb-1">ID Produk</label>
          <input type="text" name="product_id" value="<?= $edit['product_id'] ?? '' ?>" <?= $edit ? 'readonly' : 'required' ?>
            class="w-full px-3 py-2 rounded bg-gray-700 text-white border border-gray-600">
        </div>

        <div>
          <label class="block text-gray-300 mb-1">Nama Produk</label>
          <input type="text" name="nama_produk" value="<?= $edit['nama_produk'] ?? '' ?>" required
            class="w-full px-3 py-2 rounded bg-gray-700 text-white border border-gray-600">
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-gray-300 mb-1">Harga</label>
          <input type="text" name="harga" value="<?= isset($edit['harga']) ? str_replace(['$', ','], '', $edit['harga']) : '' ?>" required
            class="w-full px-3 py-2 rounded bg-gray-700 text-white border border-gray-600">
        </div>

        <div>
          <label class="block text-gray-300 mb-1">Stok</label>
          <input type="number" name="stok" value="<?= $edit['stok'] ?? 0 ?>" required
            class="w-full px-3 py-2 rounded bg-gray-700 text-white border border-gray-600">
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-gray-300 mb-1">Kategori</label>
          <select name="category" class="w-full px-3 py-2 rounded bg-gray-700 text-white border border-gray-600">
            <?php
            $category_options = mysqli_query($conn, "SELECT * FROM category ORDER BY category");
            while ($cat = mysqli_fetch_assoc($category_options)): ?>
              <option value="<?= $cat['category_id'] ?>" <?= ($edit && $edit['category_id'] == $cat['category_id']) ? 'selected' : '' ?>>
                <?= $cat['category'] ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>

        <div>
          <label class="block text-gray-300 mb-1">Link Gambar</label>
          <input type="text" name="link_gambar" value="<?= $edit['link_gambar'] ?? '' ?>"
            class="w-full px-3 py-2 rounded bg-gray-700 text-white border border-gray-600">
        </div>
      </div>

      <div>
        <label class="block text-gray-300 mb-1">Deskripsi Produk</label>
        <input type="text" name="deskripsi_produk" value="<?= $edit['deskripsi_produk'] ?? '' ?>" required
          class="w-full px-3 py-2 rounded bg-gray-700 text-white border border-gray-600">
      </div>

      <div class="flex space-x-3 pt-2">
        <button type="submit" name="<?= $edit ? 'update' : 'insert' ?>"
          class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-bold py-2 px-6 rounded">
          <?= $edit ? 'Simpan Perubahan' : 'Tambah Produk' ?>
        </button>
        <?php if ($edit): ?>
          <a href="produk_admin.php" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Batal</a>
        <?php endif; ?>
      </div>
    </form>
  </div>

  <!-- Tabel Produk -->
  <div class="bg-gray-800 rounded-lg shadow overflow-hidden">
    <table class="w-full">
      <thead class="bg-gray-700 text-yellow-400">
        <tr>
          <th class="py-3 px-4 text-left">ID</th>
          <th class="py-3 px-4 text-left">Nama</th>
          <th class="py-3 px-4 text-left">Kategori</th>
          <th class="py-3 px-4 text-left">Deskripsi</th>
          <th class="py-3 px-4 text-left">Harga</th>
          <th class="py-3 px-4 text-left">Stok</th>
          <th class="py-3 px-4 text-center">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-700">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
          <tr class="hover:bg-gray-700">
            <td class="py-3 px-4"><?= $row['product_id'] ?></td>
            <td class="py-3 px-4"><?= htmlspecialchars($row['nama_produk']) ?></td>
            <td class="py-3 px-4"><?= htmlspecialchars($row['nama_kategori']) ?></td>
            <td class="py-3 px-4"><?= $row['deskripsi_produk'] ?></td>
            <td class="py-3 px-4"><?= '$' . number_format((float)$row['harga'], 0, ',', '.') ?></td>
            <td class="py-3 px-4"><?= $row['stok'] ?></td>
            <td class="py-3 px-4 text-center">
              <div class="flex justify-center space-x-2">
                <a href="produk_admin.php?edit=<?= $row['product_id'] ?>"
                  class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">Edit</a>
                <a href="produk_admin.php?hapus=<?= $row['product_id'] ?>" onclick="return confirm('Yakin hapus produk ini?')"
                  class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">Hapus</a>
              </div>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

</body>

</html>
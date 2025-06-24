<?php
session_start();
include 'koneksi.php';
// Tambahkan validasi admin jika diperlukan
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Stryk Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
  <style>
    .logout-btn {
      background-color: #ef4444;
      color: white;
      padding: 0.5rem 1rem;
      border-radius: 0.375rem;
      font-weight: bold;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
    }
    .logout-btn:hover {
      background-color: #dc2626;
      transform: translateY(-1px);
    }
    .logout-btn svg {
      margin-right: 0.5rem;
    }
  </style>
</head>
<body class="bg-gray-900 text-white font-sans">
  <nav class="bg-gray-800 px-6 py-4 flex justify-between items-center">
    <h1 class="text-xl font-bold text-yellow-400">Stryk Industries - Admin Panel</h1>
    <a href="logout_admin.php" class="logout-btn">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
      </svg>
      Logout
    </a>
  </nav>
  
  <main class="p-6">
    <h2 class="text-2xl text-yellow-400">Selamat datang di Admin Panel Stryk Industries</h2>
    <p class="mt-2 text-gray-300">Silakan pilih menu di bawah untuk mengelola data:</p>
    
    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
      <a href="produk_admin.php" class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-bold py-8 px-4 rounded-lg text-center transition duration-300 flex flex-col items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
        </svg>
        Kelola Produk
      </a>
      
      <a href="order_admin.php" class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-bold py-8 px-4 rounded-lg text-center transition duration-300 flex flex-col items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        Kelola Order
      </a>
      
      <a href="struk_admin.php" class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-bold py-8 px-4 rounded-lg text-center transition duration-300 flex flex-col items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        Lihat Struk
      </a>
      
      <a href="customer_admin.php" class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-bold py-8 px-4 rounded-lg text-center transition duration-300 flex flex-col items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
        Data Customer
      </a>
    </div>
  </main>
  
  <footer class="mt-10 text-center text-gray-500 text-sm p-4 border-t border-gray-800">
    <small>&copy; <?= date('Y') ?> Stryk Industries. All rights reserved.</small>
  </footer>
</body>
</html>
<?php
$conn = mysqli_connect("localhost", "root", "", "styrk_industries");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Set charset untuk menghindari masalah encoding
mysqli_set_charset($conn, "utf8mb4");
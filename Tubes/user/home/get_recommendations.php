<?php
// Database connection - diubah dari db_connection.php ke koneksi.php
require_once 'koneksi.php';

// Get 6 random products (adjust the number as needed)
$query = "SELECT * FROM products ORDER BY RAND() LIMIT 6";
$result = mysqli_query($conn, $query);

$recommendations = array();
while ($row = mysqli_fetch_assoc($result)) {
    $recommendations[] = $row;
}

// Return as JSON
header('Content-Type: application/json');
echo json_encode($recommendations);
?>
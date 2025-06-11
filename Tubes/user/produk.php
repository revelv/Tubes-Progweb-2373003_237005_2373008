<?php
include 'header.php';
?>

<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="./css/produk.css">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&display=swap" rel="stylesheet">
	<title>Styrk Industries</title>
</head>

<body>
	<div class="container">
		<h2 id="judul">Our Products</h2>
	</div>

	<div class="container">
		<div class="row">
			<?php
			$result = mysqli_query($conn, "SELECT * FROM products");
			while ($row = mysqli_fetch_assoc($result)) {
			?>
				<div class="col-sm-6 col-md-4">
					<div class="thumbnail">
						<a href="#"><img id="gambar" src="<?= $row['link_gambar']; ?>"> </a>
						<div class="caption">
							<h3><?= $row['nama_produk']; ?></h3>
							<h4><?= $row['harga']; ?></h4>
						</div>
						<div class="button">
							<?php if(isset($_SESSION['kd_cs'])){ ?>
								<div class="">
									<a href="Produk/add.php?produk=<?= $row['kode_produk']; ?>&kd_cs=<?= $kode_cs; ?>&hal=1" class="btn btn-success btn-block" role="button"><i class="glyphicon glyphicon-shopping-cart"></i>Add to carts</a>
								</div>
								<?php 
							}
							else{
								?>
								<div class="">
									<a href="#" class="btn btn-success btn-block" role="button"><i class="glyphicon glyphicon-shopping-cart"></i>Add to carts</a>
								</div>
								<?php 
							}
							?>
						</div>
					</div>
				</div>
			<?php
			}
			?>
		</div>
	</div>
</body>

</html>
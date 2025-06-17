<?php
include 'header.php';
?>

<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="./css/produk.css">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&display=swap" rel="stylesheet">
	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<!-- Bootstrap JS (Popper included) -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	<title>Styrk Industries</title>
</head>

<body>
	<div class="container_produk">
		<h2 id="judul">Our Products</h2>
	</div>

	<div class="container_produk">
		<div class="row">
			<?php
			$result = mysqli_query($conn, "SELECT * FROM products");
			while ($row = mysqli_fetch_assoc($result)) {
			?>
				<div class="col-sm-6 col-md-4">
					<div class="thumbnail">
						<a href="#"
							class="product-detail"
							data-bs-toggle="modal"
							data-bs-target="#detailModal"
							data-id="<?= $row['product_id']; ?>"
							data-nama="<?= htmlspecialchars($row['nama_produk'], ENT_QUOTES); ?>"
							data-harga="<?= $row['harga']; ?>"
							data-stok="<?= $row['stok']; ?>"
							data-kategori="<?= $row['category']; ?>"
							data-deskripsi="<?= htmlspecialchars($row['deskripsi_produk'] ?? ''); ?>"
							data-gambar="<?= $row['link_gambar']; ?>">
							<img id="gambar" src="<?= $row['link_gambar']; ?>" alt="<?= $row['nama_produk']; ?>">
						</a>



						<div class="caption">
							<h3><?= $row['nama_produk']; ?></h3>
							<h4>$<?= $row['harga']; ?></h4>
						</div>
						<div class="button">
							<?php
							$stok = (int)$row['stok'];

							if ($stok < 1) {
								// Jika stok habis
								echo '<div class=""><button class="btn btn-secondary btn-block" disabled>SOLD OUT</button></div>';
							} else {
								if (isset($_SESSION['kd_cs'])) {
									// Jika user login dan stok masih ada
									echo '<div class="">
											<a href="add_to_cart.php?product_id=' . $row['product_id'] . '" class="btn btn-success btn-block" role="button">
												<i class="glyphicon glyphicon-shopping-cart"></i> Add to cart
											</a>
			  								</div>';
								} else {
									// Jika belum login
									echo '<div class="">
											<a href="#" class="btn btn-success btn-block" role="button">
												<i class="glyphicon glyphicon-shopping-cart"></i> Login to Add
											</a>
			  							</div>';
								}
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

	<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="detailModalLabel">Detail Produk</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body d-flex flex-wrap">
					<div class="me-4">
						<img id="modal-gambar" src="" alt="gambar" style="max-width: 300px;">
					</div>
					<div>
						<h4 id="modal-nama"></h4>
						<p><strong>Harga:</strong> $<span id="modal-harga"></span></p>
						<p><strong>Stok:</strong> <span id="modal-stok"></span></p>
						<p id="modal-deskripsi"></p>
						<div id="modal-button-container" class="mt-3"></div>
					</div>
					<!-- Tombol Audio -->
					<!-- Audio controls gabungan -->
					<div id="modal-audio-container" class="mt-3" style="display: none;">
						<button id="toggle-audio" class="btn btn-primary">
							▶️ Play Sound
						</button>
						<audio id="product-audio" hidden></audio>
					</div>


				</div>
			</div>
		</div>
	</div>



	<script>
		document.querySelectorAll('.product-detail').forEach(el => {
			el.addEventListener('click', function() {
				const nama = this.dataset.nama;
				const harga = this.dataset.harga;
				const stok = this.dataset.stok;
				const deskripsi = this.dataset.deskripsi;
				const gambar = this.dataset.gambar;
				const id = this.dataset.id;
				const kategori = this.dataset.kategori;

				// Isi modal konten
				document.getElementById('modal-nama').textContent = nama;
				document.getElementById('modal-harga').textContent = harga;
				document.getElementById('modal-stok').textContent = stok;
				document.getElementById('modal-deskripsi').textContent = deskripsi;
				document.getElementById('modal-gambar').src = gambar;

				// Tombol Add/SOLD OUT
				const container = document.getElementById('modal-button-container');
				container.innerHTML = '';
				if (parseInt(stok) > 0) {
					container.innerHTML = `<a href="add_to_cart.php?product_id=${id}" class="btn btn-success">Add to Cart</a>`;
				} else {
					container.innerHTML = `<button class="btn btn-secondary" disabled>SOLD OUT</button>`;
				}

				// Audio kategori dan tombol play/pause
				const audioContainer = document.getElementById('modal-audio-container');
				const audioElement = document.getElementById('product-audio');
				const toggleButton = document.getElementById('toggle-audio');

				// Reset tombol
				toggleButton.textContent = '▶️ Play Sound';
				audioElement.pause();
				audioElement.currentTime = 0;

				if (kategori === 'Switch_kit') {
					audioElement.src = '../sounds/switch_sound.mp3';
					audioContainer.style.display = 'block';
				} else if (kategori === 'Keyboard') {
					audioElement.src = '../sounds/keyboard_sound.mp3';
					audioContainer.style.display = 'block';
				} else {
					audioElement.src = '';
					audioContainer.style.display = 'none';
				}
			});
		});

		// Toggle Play/Pause
		document.getElementById('toggle-audio').addEventListener('click', function() {
			const audio = document.getElementById('product-audio');
			const button = this;

			if (audio.paused) {
				audio.play();
				button.textContent = '⏸️ Pause Sound';
			} else {
				audio.pause();
				button.textContent = '▶️ Play Sound';
			}

			// Reset button text setelah audio selesai
			audio.onended = () => {
				button.textContent = '▶️ Play Sound';
			};
		});
	</script>




</body>

</html>
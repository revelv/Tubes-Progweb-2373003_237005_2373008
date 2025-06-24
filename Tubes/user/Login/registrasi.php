<?php
session_start();
$old = $_SESSION['form_data'] ?? [];
$error_message = $_SESSION['register_error'] ?? '';
unset($_SESSION['form_data'], $_SESSION['register_error']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        /* Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #121212;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            color: #e0e0e0;
            font-family: 'Montserrat', sans-serif;
            line-height: 1.6;
        }

        /* Container Styles */
        .container {
            background-color: #1e1e1e;
            border-radius: 12px;
            padding: 40px;
            width: 100%;
            max-width: 800px;
            border: 1px solid #333;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        /* Typography */
        h2 {
            color: #d4af37;
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: 600;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 20px;
        }

        label {
            color: #d4af37;
            font-weight: 500;
            margin-bottom: 8px;
            display: block;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            background-color: #2a2a2a;
            border: 2px solid #333;
            border-radius: 8px;
            color: #e0e0e0;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #d4af37;
            outline: none;
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.2);
        }

        /* Button Styles */
        .btn-success {
            background: linear-gradient(135deg, #d4af37 0%, #f9d423 100%);
            color: #1e1e1e;
            padding: 14px 30px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 16px;
            width: 100%;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.4);
        }

        .btn-success:active {
            transform: translateY(0);
        }

        /* Grid Layout */
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px;
        }

        .col-md-6 {
            padding: 0 10px;
            flex: 0 0 50%;
            max-width: 50%;
            margin-bottom: 15px;
        }

        /* Alert Message */
        .alert {
            background-color: #b91c1c;
            color: #fff;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            border: 1px solid #f87171;
            text-align: center;
        }

        /* Link Styles */
        .back-link {
            color: #b0b0b0;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
            text-align: center;
            width: 100%;
            transition: color 0.3s ease;
        }

        .back-link:hover {
            color: #d4af37;
            text-decoration: underline;
        }

        /* Button Container */
        .form-actions {
            margin-top: 20px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 30px 20px;
            }

            .col-md-6 {
                flex: 0 0 100%;
                max-width: 100%;
            }

            h2 {
                font-size: 24px;
            }
        }

        /* Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .container {
            animation: fadeIn 0.5s ease-out;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Form Registrasi</h2>

        <?php if ($error_message): ?>
            <div class="alert"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <form action="proses_registrasi.php" method="POST">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nama">Nama Lengkap</label>
                        <input type="text" id="nama" name="nama" class="form-control" required
                            value="<?= htmlspecialchars($old['nama'] ?? '') ?>" placeholder="Masukkan nama lengkap">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Alamat Email</label>
                        <input type="email" id="email" name="email" class="form-control" required
                            value="<?= htmlspecialchars($old['email'] ?? '') ?>" placeholder="contoh@email.com">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <input type="text" id="alamat" name="alamat" class="form-control" required
                            value="<?= htmlspecialchars($old['alamat'] ?? '') ?>" placeholder="Alamat lengkap">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="telp">Nomor Telepon</label>
                        <input type="text" id="telp" name="telp" class="form-control" required
                            value="<?= htmlspecialchars($old['telp'] ?? '') ?>" placeholder="+62">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required
                            placeholder="Minimal 8 karakter">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="konfirmasi">Konfirmasi Password</label>
                        <input type="password" id="konfirmasi" name="konfirmasi" class="form-control" required
                            placeholder="Ketik ulang password">
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-success">Daftar Sekarang</button>
                <a href="../produk.php" class="back-link">Kembali ke Halaman Produk</a>
            </div>
        </form>
    </div>
</body>

</html>
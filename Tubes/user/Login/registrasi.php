<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        /* Reset dan Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Montserrat', 'Segoe UI', sans-serif;
        }
        
        body {
            background-color: #121212;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            color: #e0e0e0;
        }
        
        /* Container Form */
        .container {
            background-color: #1e1e1e;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            padding: 40px;
            width: 100%;
            max-width: 800px;
            animation: fadeIn 0.5s ease-in-out;
            border: 1px solid #333;
        }
        
        /* Judul */
        h2 {
            color: #d4af37;
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: 600;
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
            text-align: center;
        }
        
        /* Form Group */
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #d4af37;
            font-weight: 500;
            font-size: 14px;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            background-color: #2a2a2a;
            border: 2px solid #333;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
            color: #e0e0e0;
        }
        
        .form-control:focus {
            border-color: #d4af37;
            outline: none;
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.2);
        }
        
        /* Grid System */
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -15px;
        }
        
        .col-md-6 {
            padding: 0 15px;
            flex: 0 0 50%;
            max-width: 50%;
        }
        
        /* Tombol Emas */
        .btn-success {
            background: linear-gradient(135deg, #d4af37 0%, #f9d423 100%);
            color: #1e1e1e;
            border: none;
            padding: 14px 30px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
            position: relative;
            overflow: hidden;
            display: inline-block;
            text-decoration: none;
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.4);
        }
        
        .btn-success:active {
            transform: translateY(0);
        }
        
        /* Efek kilau emas pada tombol */
        .btn-success::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -60%;
            width: 50%;
            height: 200%;
            background: rgba(255, 255, 255, 0.2);
            transform: rotate(30deg);
            transition: all 0.3s;
        }
        
        .btn-success:hover::after {
            left: 110%;
        }
        
        /* Link Kembali */
        a[href="../produk.php"] {
            display: inline-block;
            margin-left: 15px;
            margin-top: 20px;
            color: #b0b0b0;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }
        
        a[href="../produk.php"]:hover {
            color: #d4af37;
            text-decoration: underline;
        }
        
        /* Animasi */
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
        
        /* Responsif */
        @media (max-width: 768px) {
            .col-md-6 {
                flex: 0 0 100%;
                max-width: 100%;
            }
            
            .container {
                padding: 30px 20px;
            }
            
            .btn-success, a[href="../produk.php"] {
                width: 100%;
                margin-left: 0;
                text-align: center;
            }
            
            a[href="../produk.php"] {
                margin-top: 15px;
                display: block;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Form Registrasi</h2>
        <form action="proses_registrasi.php" method="POST">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" class="form-control" id="nama" placeholder="Nama Lengkap" name="nama" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" placeholder="Alamat Email" name="email" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <input type="text" class="form-control" id="alamat" placeholder="Alamat Lengkap" name="alamat" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="telp">No Telepon</label>
                        <input type="text" class="form-control" id="telp" placeholder="+62" name="telp" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" placeholder="Password (min 8 karakter)" name="password" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="konfirmasi">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="konfirmasi" placeholder="Ulangi Password" name="konfirmasi" required>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-success">Register</button>
            <a href="../produk.php">Kembali ke Produk</a>
        </form>
    </div>
</body>
</html>
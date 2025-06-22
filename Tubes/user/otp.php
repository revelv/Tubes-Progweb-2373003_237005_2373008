<!DOCTYPE html>
<html>
<head>
    <title>Verifikasi OTP</title>
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
        }
        
        /* Container Form */
        .otp-container {
            background-color: #1e1e1e;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            padding: 40px;
            width: 100%;
            max-width: 450px;
            text-align: center;
            animation: fadeIn 0.5s ease-in-out;
            border: 1px solid #333;
        }
        
        /* Judul */
        h2 {
            color: #d4af37; /* Warna emas */
            margin-bottom: 25px;
            font-size: 28px;
            font-weight: 600;
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
        }
        
        /* Deskripsi */
        .description {
            color: #b0b0b0;
            margin-bottom: 30px;
            line-height: 1.5;
        }
        
        /* Form Group */
        .form-group {
            margin-bottom: 25px;
            text-align: left;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #d4af37; /* Warna emas */
            font-weight: 500;
            font-size: 14px;
        }
        
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            background-color: #2a2a2a;
            border: 2px solid #333;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
            color: #e0e0e0;
        }
        
        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #d4af37;
            outline: none;
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.2);
        }
        
        /* Tombol Emas */
        button[type="submit"] {
            background: linear-gradient(135deg, #d4af37 0%, #f9d423 100%);
            color: #1e1e1e;
            border: none;
            padding: 14px 20px;
            width: 100%;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
            position: relative;
            overflow: hidden;
        }
        
        button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.4);
        }
        
        button[type="submit"]:active {
            transform: translateY(0);
        }
        
        /* Efek kilau emas pada tombol */
        button[type="submit"]::after {
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
        
        button[type="submit"]:hover::after {
            left: 110%;
        }
        
        /* Link Kembali */
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #b0b0b0;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }
        
        .back-link:hover {
            color: #d4af37;
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
        @media (max-width: 480px) {
            .otp-container {
                padding: 30px 20px;
            }
            
            h2 {
                font-size: 24px;
            }
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="otp-container">
        <h2>Verifikasi OTP</h2>
        <p class="description">Masukkan kode OTP yang dikirim ke email Anda dan password baru Anda.</p>
        
        <form method="POST" action="reset_password.php">
            <div class="form-group">
                <label for="otp">Kode OTP</label>
                <input type="text" id="otp" name="otp" required placeholder="Masukkan 6 digit kode">
            </div>
            
            <div class="form-group">
                <label for="new_password">Password Baru</label>
                <input type="password" id="new_password" name="new_password" required placeholder="Password minimal 8 karakter">
            </div>
            
            <button type="submit">Reset Password</button>
        </form>
        
        <a href="produk.php" class="back-link">Kembali ke Login</a>
    </div>
</body>
</html>
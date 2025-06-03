<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
    :root {
        --primary: #4361ee;
        --secondary: #3f37c9;
        --accent: #4895ef;
        --light: #f8f9fa;
        --dark: #212529;
        --success: #4cc9f0;
        --error: #f72585;
    }

    .container {
        max-width: 800px;
        margin: 50px auto;
        padding: 40px;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border-radius: 16px;
        box-shadow: 
            0 8px 32px rgba(31, 38, 135, 0.1),
            0 4px 8px rgba(0, 0, 0, 0.05);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        position: relative;
        overflow: hidden;
    }

    .container::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(67, 97, 238, 0.1) 0%, rgba(255,255,255,0) 70%);
        z-index: -1;
    }

    .form-group {
        margin-bottom: 24px;
        position: relative;
    }

    label {
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 8px;
        display: block;
        font-size: 14px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .form-control {
        height: 50px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 12px 20px;
        font-size: 15px;
        transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        background-color: rgba(255, 255, 255, 0.8);
        width: 100%;
    }

    .form-control:focus {
        border-color: var(--accent);
        box-shadow: 0 4px 12px rgba(67, 97, 238, 0.15);
        outline: none;
        transform: translateY(-2px);
    }

    .form-control:hover {
        border-color: #adb5bd;
    }

    .btn-success {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        border: none;
        padding: 14px 32px;
        font-size: 16px;
        font-weight: 600;
        border-radius: 8px;
        color: white;
        cursor: pointer;
        transition: all 0.4s ease;
        box-shadow: 0 4px 6px rgba(67, 97, 238, 0.1);
        display: inline-block;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-top: 20px;
        position: relative;
        overflow: hidden;
    }

    .btn-success::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, var(--secondary) 0%, var(--primary) 100%);
        opacity: 0;
        transition: opacity 0.4s ease;
        z-index: 1;
    }

    .btn-success:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(67, 97, 238, 0.2);
    }

    .btn-success:hover::after {
        opacity: 1;
    }

    .btn-success span {
        position: relative;
        z-index: 2;
    }

    .row {
        margin-bottom: 20px;
    }

    @media (max-width: 768px) {
        .container {
            padding: 30px;
            margin: 20px;
            border-radius: 12px;
        }
        
        .col-md-6 {
            margin-bottom: 20px;
        }

        .btn-success {
            width: 100%;
            padding: 16px;
        }
    }

    .container {
        animation: float 6s ease-in-out infinite;
    }

    .form-group:focus-within label {
        color: var(--primary);
        transform: translateY(-2px);
    }

</style>
</head>

<body>
    <div class="container" style="padding-bottom: 250px;">
        <form action="proses_registrasi.php" method="POST">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="exampleInputPassword1">Nama</label>
                        <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Nama" name="nama" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="exampleInputPassword1">Email</label>
                        <input type="email" class="form-control" id="exampleInputPassword1" placeholder="Email" name="email" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="exampleInputPassword1">Alamat</label>
                        <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Alamat" name="alamat" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="exampleInputPassword1">No Telepon</label>
                        <input type="text" class="form-control" id="exampleInputPassword1" placeholder="+62" name="telp" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="exampleInputPassword1">Password</label>
                        <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name="password" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="exampleInputPassword1">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Konfirmasi Password" name="konfirmasi" required>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Register</button>
        </form>
    </div>
</body>

</html>
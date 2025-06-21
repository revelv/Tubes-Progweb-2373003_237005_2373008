<?php
include 'header.php';

// Redirect ke login jika belum login
if (!isset($_SESSION['kd_cs'])) {
    header("Location: produk.php");
    exit();
}

$customer_id = $_SESSION['kd_cs'];

// Ambil data customer dari database
$query = "SELECT * FROM customer WHERE customer_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Customer not found.");
}

$customer = $result->fetch_assoc();
$stmt->close();

// Proses update data
$update_success = false;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $no_telepon = mysqli_real_escape_string($conn, $_POST['no_telepon']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);

    // Validasi input
    if (empty($nama)) {
        $errors[] = "Full name is required";
    }

    if (empty($no_telepon)) {
        $errors[] = "Phone number is required";
    } elseif (!preg_match('/^[0-9]{10,15}$/', $no_telepon)) {
        $errors[] = "Invalid phone number format";
    }

    if (empty($alamat)) {
        $errors[] = "Address is required";
    }

    // Jika tidak ada error, lakukan update
    if (empty($errors)) {
        $update_query = "UPDATE customer SET nama = ?, no_telepon = ?, alamat = ? WHERE customer_id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("sssi", $nama, $no_telepon, $alamat, $customer_id);

        if ($update_stmt->execute()) {
            $update_success = true;
            // Update session nama jika berhasil
            $_SESSION['nama'] = $nama;
            // Refresh data customer
            $customer['nama'] = $nama;
            $customer['no_telepon'] = $no_telepon;
            $customer['alamat'] = $alamat;
        } else {
            $errors[] = "Failed to update profile. Please try again.";
        }

        $update_stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Styrk Industries</title>

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            color: var(--black);
        }

        .profile-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 30px;
        }

        .profile-title {
            font-size: 28px;
            font-weight: 600;
            color: var(--primary-blue);
            margin: 0;
        }

        .btn-save {
            background-color: var(--primary-yellow);
            color: black;
            font-weight: 700;
        }

        .btn-save:hover {
            background-color: #e67e22;
            color: white;
        }

        .btn-cancel {
            background-color: red;
            color: white;
            font-weight: 700;
            margin-right: 10px;

        }

        .btn-cancel:hover {
            box-shadow: 0 0 10px rgba(231, 76, 60, 0.6);
            transform: scale(1.02);
        }

        .form-control:disabled {
            background-color: #f8f9fa;
            opacity: 1;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }

        .profile-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
    </style>
</head>

<body>
    <!-- Main Content -->
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="profile-container">
                    <div class="profile-header d-flex justify-content-between align-items-center mb-4">
                        <h1 class="profile-title">Edit Profile</h1>
                        <a href="produk.php" class="btn btn-cancel">
                            <i class="bi bi-arrow-left me-2"></i>Back
                        </a>
                    </div>

                    <?php if ($update_success): ?>
                        <div class="alert alert-success mb-4">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            Profile updated successfully!
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger mb-4">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <?php foreach ($errors as $error): ?>
                                <div><?= $error ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-4">
                            <label for="customer_id" class="form-label">Customer ID</label>
                            <input type="text" class="form-control" id="customer_id" value="<?= htmlspecialchars($customer['customer_id']) ?>" disabled>
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" value="<?= htmlspecialchars($customer['email']) ?>" disabled>
                        </div>

                        <div class="mb-4">
                            <label for="nama" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama" name="nama" value="<?= htmlspecialchars($customer['nama']) ?>" required>
                        </div>

                        <div class="mb-4">
                            <label for="no_telepon" class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="no_telepon" name="no_telepon" value="<?= htmlspecialchars($customer['no_telepon']) ?>" required>
                            <small class="text-muted">Format: 10-15 digits (e.g., 081234567890)</small>
                        </div>

                        <div class="mb-4">
                            <label for="alamat" class="form-label">Address <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3" required><?= htmlspecialchars($customer['alamat']) ?></textarea>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-save">
                                <i class="bi bi-save me-2"></i>Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Validasi nomor telepon sebelum submit
        document.querySelector('form').addEventListener('submit', function(e) {
            const phoneInput = document.getElementById('no_telepon');
            const phoneRegex = /^[0-9]{10,15}$/;

            if (!phoneRegex.test(phoneInput.value)) {
                e.preventDefault();
                alert('Please enter a valid phone number (10-15 digits)');
                phoneInput.focus();
            }
        });
    </script>
</body>

</html>
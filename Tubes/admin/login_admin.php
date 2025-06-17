<?php
require_once 'auth_admin.php';
include 'koneksi.php';

if (isLoggedIn()) {
    header("Location: index_admin.php");
    exit();
}
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (login($username, $password)) {
        header("Location: " . (isAdmin() ? "index_admin.php" : "employee_form.php"));
        exit();
    } else {
        $error = "Username atau password salah";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Styrk Industries</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #111827; /* Dark gray-900 */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: white;
        }

        .login-container {
            background-color: #1f2937; /* Gray-800 */
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            border: 1px solid #374151; /* Gray-700 */
        }

        h1 {
            color: #fbbf24; /* Yellow-400 */
            text-align: center;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .error-message {
            color: #f87171; /* Red-400 */
            text-align: center;
            margin-bottom: 1rem;
            padding: 0.5rem;
            background-color: #7f1d1d; /* Red-900 */
            border-radius: 0.25rem;
            font-size: 0.875rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
            color: #d1d5db; /* Gray-300 */
        }

        input[type="text"],
        input[type="password"] {
            width: 93%;
            padding: 0.75rem;
            background-color: #1f2937; /* Gray-800 */
            border: 1px solid #4b5563; /* Gray-600 */
            border-radius: 0.25rem;
            font-size: 1rem;
            color: white;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #fbbf24; /* Yellow-400 */
            box-shadow: 0 0 0 2px rgba(251, 191, 36, 0.2);
        }

        button {
            width: 100%;
            padding: 0.75rem;
            background-color: #fbbf24; /* Yellow-400 */
            color: #111827; /* Gray-900 */
            border: none;
            border-radius: 0.25rem;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #f59e0b; /* Yellow-500 */
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
            color: #9ca3af; /* Gray-400 */
            font-size: 0.875rem;
        }

        .register-link a {
            color: #fbbf24; /* Yellow-400 */
            text-decoration: none;
            font-weight: 500;
        }

        .register-link a:hover {
            text-decoration: underline;
            color: #f59e0b; /* Yellow-500 */
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h1>Welcome To Stryk Industries!</h1>

        <?php if ($error): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>

    </div>
</body>

</html>
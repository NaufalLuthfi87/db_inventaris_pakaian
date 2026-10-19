<?php
session_start();
include 'koneksi.php';

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    $result = mysqli_query($koneksi, "SELECT * FROM admin WHERE username='$username'");
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password']) || $password == $row['password']) {
            $_SESSION['status_login'] = true;
            $_SESSION['username'] = $row['username'];
            header("Location: index.php");
            exit;
        }
    }
    $error = true;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Toko Pakaian</title>
    <style>
        /* Animasi Latar Belakang & Kotak Login */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(-45deg, #d1ee52, #e7b13c, #23a6d5, #23d5ab);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Gaya Glassmorphism & Animasi Masuk */
        .login-card {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            padding: 40px;
            border-radius: 16px;
            width: 100%;
            max-width: 380px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.2);
            animation: fadeIn 0.8s ease-out forwards;
        }

        h2 {
            text-align: center;
            color: #ffffff;
            margin-bottom: 25px;
            font-weight: 600;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #ffffff;
            font-weight: 500;
            font-size: 14px;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 20px;
            border: 1px solid rgba(255, 255, 255, 0.5);
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            color: #fff;
            box-sizing: border-box;
            transition: all 0.3s ease;
        }

        input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        input:focus {
            outline: none;
            border-color: #ffffff;
            background: rgba(255, 255, 255, 0.3);
            box-shadow: 0 0 8px rgba(255, 255, 255, 0.5);
        }

        button {
            width: 100%;
            padding: 12px;
            background: #ffffff;
            color: #333;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        button:hover {
            background: #f8fafc;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }

        .error-msg {
            background: rgba(239, 68, 68, 0.3);
            border: 1px solid rgba(239, 68, 68, 0.5);
            color: #fee2e2;
            padding: 10px;
            border-radius: 6px;
            text-align: center;
            margin-bottom: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <h2>Login</h2>
        
        <?php if (isset($error)) { ?>
            <div class="error-msg">Username atau Password salah!</div>
        <?php } ?>

        <form method="POST">
            <label>Username</label>
            <input type="text" name="username" placeholder="Masukkan username..." required autocomplete="off">

            <label>Password</label>
            <input type="password" name="password" placeholder="Masukkan password..." required>

            <button type="submit" name="login">Masuk</button>
        </form>
    </div>

</body>
</html>
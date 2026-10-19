<?php
session_start();
if (!isset($_SESSION['status_login'])) { header("Location: login.php"); exit; }
include 'koneksi.php';

$id = $_GET['id'];
$data = mysqli_query($koneksi, "SELECT * FROM kategori WHERE id_kategori='$id'");
$row = mysqli_fetch_assoc($data);

if (!$row) {
    header("Location: kategori.php");
    exit;
}

if (isset($_POST['update'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_kategori']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);

    mysqli_query($koneksi, "UPDATE kategori SET nama_kategori='$nama', deskripsi='$deskripsi' WHERE id_kategori='$id'");
    header("Location: kategori.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Kategori</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(-45deg, #eebd52, #3ce797, #23a6d5, #23d5ab);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            color: #fff;
        }
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .container {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            padding: 30px;
            border-radius: 16px;
            max-width: 600px;
            margin: 40px auto;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.25);
        }
        h2 { border-bottom: 1px solid rgba(255,255,255,0.3); padding-bottom: 15px; margin-top: 0; }
        label { display: block; margin-bottom: 8px; font-weight: 500; }
        input[type="text"], textarea {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.4);
            background: rgba(255, 255, 255, 0.15);
            color: #fff;
            box-sizing: border-box;
            outline: none;
            font-family: inherit;
            font-size: 14px;
            transition: 0.3s;
        }
        input[type="text"]:focus, textarea:focus {
            background: rgba(255, 255, 255, 0.25);
            border-color: #fff;
        }
        textarea { resize: vertical; }
        button {
            background: #3b82f6;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
            transition: 0.3s;
        }
        button:hover { background: #2563eb; transform: translateY(-1px); }
        .btn-batal {
            background: rgba(0,0,0,0.2);
            color: #fff;
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            margin-left: 10px;
            transition: 0.3s;
        }
        .btn-batal:hover { background: rgba(0,0,0,0.3); }
        .form-group { margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit Kategori</h2>
    <form method="POST">
        <div class="form-group">
            <label>Nama Kategori:</label>
            <input type="text" name="nama_kategori" value="<?= htmlspecialchars($row['nama_kategori']); ?>" required>
        </div>

        <div class="form-group">
            <label>Deskripsi:</label>
            <textarea name="deskripsi" rows="4"><?= htmlspecialchars($row['deskripsi']); ?></textarea>
        </div>

        <button type="submit" name="update">Perbarui Kategori</button>
        <a href="kategori.php" class="btn-batal">Batal</a>
    </form>
</div>

</body>
</html>
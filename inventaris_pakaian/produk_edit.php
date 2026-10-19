<?php
session_start();
if (!isset($_SESSION['status_login'])) { header("Location: login.php"); exit; }
include 'koneksi.php';

if (isset($_POST['submit'])) {
    $id_kategori = $_POST['id_kategori'];
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_pakaian']);
    $ukuran = mysqli_real_escape_string($koneksi, $_POST['ukuran']);
    $warna = mysqli_real_escape_string($koneksi, $_POST['warna']);
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    mysqli_query($koneksi, "INSERT INTO pakaian (id_kategori, nama_pakaian, ukuran, warna, harga, stok) VALUES ('$id_kategori', '$nama', '$ukuran', '$warna', '$harga', '$stok')");
    header("Location: produk.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Pakaian</title>
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
        input[type="text"], input[type="number"], select {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.4);
            background: rgba(255, 255, 255, 0.15);
            color: #000; /* Atau #fff, pastikan kontras terbaca */
            box-sizing: border-box;
            outline: none;
            font-family: inherit;
            font-size: 14px;
            transition: 0.3s;
        }
        input::placeholder { color: rgba(255,255,255,0.7); }
        select option { background: #333; color: #fff; }
        input:focus, select:focus {
            background: rgba(255, 255, 255, 0.25);
            border-color: #fff;
        }
        button {
            background: #23d5ab;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(35, 213, 171, 0.4);
            transition: 0.3s;
        }
        button:hover { background: #1eb894; transform: translateY(-1px); }
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
    <h2>Tambah Data Pakaian</h2>
    <form method="POST">
        <div class="form-group">
            <label>Kategori:</label>
            <select name="id_kategori" required>
                <option value="">-- Pilih Kategori --</option>
                <?php
                $kat = mysqli_query($koneksi, "SELECT * FROM kategori");
                while ($k = mysqli_fetch_assoc($kat)) {
                    echo "<option value='".$k['id_kategori']."'>".$k['nama_kategori']."</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label>Nama Pakaian:</label>
            <input type="text" name="nama_pakaian" placeholder="Contoh: Kaos Polos Katun" required>
        </div>

        <div class="form-group">
            <label>Ukuran:</label>
            <input type="text" name="ukuran" placeholder="Contoh: M, L, XL">
        </div>

        <div class="form-group">
            <label>Warna:</label>
            <input type="text" name="warna" placeholder="Contoh: Hitam, Putih">
        </div>

        <div class="form-group">
            <label>Harga (Rp):</label>
            <input type="number" name="harga" placeholder="Contoh: 75000" required>
        </div>

        <div class="form-group">
            <label>Stok Awal:</label>
            <input type="number" name="stok" placeholder="Contoh: 20" required>
        </div>

        <button type="submit" name="submit">Simpan Pakaian</button>
        <a href="produk.php" class="btn-batal">Batal</a>
    </form>
</div>

</body>
</html>
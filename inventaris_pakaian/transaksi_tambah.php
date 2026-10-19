<?php
session_start();
if (!isset($_SESSION['status_login'])) { header("Location: login.php"); exit; }
include 'koneksi.php';

if (isset($_POST['submit'])) {
    $id_pakaian = $_POST['id_pakaian'];
    $jenis = $_POST['jenis_transaksi']; 
    $jumlah = $_POST['jumlah'];
    $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);

    // Ambil ID admin yang sedang login
    $username_aktif = $_SESSION['username'];
    $query_admin = mysqli_query($koneksi, "SELECT id_admin FROM admin WHERE username = '$username_aktif'");
    $data_admin = mysqli_fetch_assoc($query_admin);
    $id_admin = $data_admin['id_admin'];

    // 1. Simpan data ke tabel transaksi termasuk tanggal otomatis (NOW())
    mysqli_query($koneksi, "INSERT INTO transaksi (id_pakaian, id_admin, jenis_transaksi, jumlah, tanggal_transaksi, keterangan) VALUES ('$id_pakaian', '$id_admin', '$jenis', '$jumlah', NOW(), '$keterangan')");

    // 2. Update stok pakaian otomatis
    if ($jenis == 'Masuk') {
        mysqli_query($koneksi, "UPDATE pakaian SET stok = stok + $jumlah WHERE id_pakaian = '$id_pakaian'");
    } else {
        mysqli_query($koneksi, "UPDATE pakaian SET stok = stok - $jumlah WHERE id_pakaian = '$id_pakaian'");
    }

    header("Location: transaksi.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Catat Transaksi</title>
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
        input[type="number"], textarea, select {
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
        select option { background: #333; color: #fff; }
        input:focus, select:focus, textarea:focus {
            background: rgba(255, 255, 255, 0.25);
            border-color: #fff;
        }
        textarea { resize: vertical; }
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
    <h2>Catat Transaksi Barang</h2>
    <form method="POST">
        <div class="form-group">
            <label>Pilih Pakaian:</label>
            <select name="id_pakaian" required>
                <option value="">-- Pilih Barang & Lihat Stok --</option>
                <?php
                $p = mysqli_query($koneksi, "SELECT * FROM pakaian");
                while ($row = mysqli_fetch_assoc($p)) {
                    echo "<option value='".$row['id_pakaian']."'>".$row['nama_pakaian']." (Stok Saat Ini: ".$row['stok'].")</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label>Jenis Transaksi:</label>
            <select name="jenis_transaksi" required>
                <option value="Masuk">Masuk (Restock / Barang Masuk)</option>
                <option value="Keluar">Keluar (Terjual / Barang Keluar)</option>
            </select>
        </div>

        <div class="form-group">
            <label>Jumlah:</label>
            <input type="number" name="jumlah" min="1" placeholder="Masukkan jumlah..." required>
        </div>

        <div class="form-group">
            <label>Keterangan:</label>
            <textarea name="keterangan" rows="3" placeholder="Contoh: Penjualan kasir / Restock dari supplier..."></textarea>
        </div>

        <button type="submit" name="submit">Simpan Transaksi</button>
        <a href="transaksi.php" class="btn-batal">Batal</a>
    </form>
</div>

</body>
</html>
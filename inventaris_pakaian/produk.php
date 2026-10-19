<?php
session_start();
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true) {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Pakaian - Inventaris</title>
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
            max-width: 1000px;
            margin: 30px auto;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.25);
        }
        h2 { 
            margin-top: 0;
            border-bottom: 1px solid rgba(255,255,255,0.3); 
            padding-bottom: 15px; 
            font-weight: 600;
        }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 14px; }
        th, td { padding: 14px; text-align: left; border-bottom: 1px solid rgba(255,255,255,0.2); }
        th { background: rgba(0,0,0,0.15); font-weight: 600; text-transform: uppercase; font-size: 13px; letter-spacing: 0.5px; }
        tr:hover { background: rgba(255, 255, 255, 0.1); }
        .nav-link { margin-bottom: 20px; display: inline-block; color: #fff; text-decoration: none; font-weight: 500; background: rgba(0,0,0,0.1); padding: 8px 14px; border-radius: 8px; transition: 0.3s; }
        .nav-link:hover { background: rgba(0,0,0,0.2); }
        
        /* Tombol Modern */
        .btn-tambah { background: #23d5ab; color: white; padding: 10px 18px; border-radius: 8px; text-decoration: none; font-weight: bold; display: inline-block; margin-bottom: 15px; box-shadow: 0 4px 15px rgba(35, 213, 171, 0.4); transition: 0.3s; }
        .btn-tambah:hover { transform: translateY(-2px); background: #1eb894; }
        .btn-edit { background: #3b82f6; color: white; padding: 6px 14px; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: bold; margin-right: 5px; box-shadow: 0 2px 8px rgba(59, 130, 246, 0.4); transition: 0.3s; }
        .btn-edit:hover { background: #2563eb; }
        .btn-hapus { background: #ef4444; color: white; padding: 6px 14px; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: bold; box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4); transition: 0.3s; }
        .btn-hapus:hover { background: #dc2626; }
    </style>
</head>
<body>

<div class="container">
    <a href="index.php" class="nav-link">← Kembali ke Dashboard Utama</a>
    
    <h2>Data Inventaris Pakaian</h2>
    <a href="produk_tambah.php" class="btn-tambah">+ Tambah Pakaian Baru</a>
    
    <table>
        <tr>
            <th>ID</th>
            <th>Nama Pakaian</th>
            <th>Ukuran</th>
            <th>Warna</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
        <?php
        $query = mysqli_query($koneksi, "SELECT * FROM pakaian");
        if (mysqli_num_rows($query) > 0) {
            while ($row = mysqli_fetch_assoc($query)) {
                echo "<tr>";
                echo "<td>" . $row['id_pakaian'] . "</td>";
                echo "<td><strong>" . htmlspecialchars($row['nama_pakaian']) . "</strong></td>";
                echo "<td>" . htmlspecialchars($row['ukuran']) . "</td>";
                echo "<td>" . htmlspecialchars($row['warna']) . "</td>";
                echo "<td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>";
                echo "<td>" . $row['stok'] . "</td>";
                echo "<td>";
                echo "<a href='produk_edit.php?id=" . $row['id_pakaian'] . "' class='btn-edit'>Edit</a>";
                echo "<a href='produk_hapus.php?id=" . $row['id_pakaian'] . "' class='btn-hapus' onclick=\"return confirm('Yakin ingin menghapus pakaian ini?')\">Hapus</a>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7' style='text-align: center; font-style: italic;'>Belum ada data pakaian.</td></tr>";
        }
        ?>
    </table>
</div>

</body>
</html>
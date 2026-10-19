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
    <title>Manajemen Inventaris Pakaian</title>
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
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            padding: 30px;
            border-radius: 16px;
            max-width: 1100px;
            margin: 20px auto;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.2);
        }
        h2 { border-bottom: 1px solid rgba(255,255,255,0.3); padding-bottom: 10px; display: flex; justify-content: space-between; align-items: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 14px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid rgba(255,255,255,0.2); }
        th { background: rgba(0,0,0,0.1); }
        .nav-link { margin-bottom: 20px; display: inline-block; color: #fff; text-decoration: underline; }
        
        .badge-masuk { background: rgba(16, 185, 129, 0.9); color: white; padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: bold; }
        .badge-keluar { background: rgba(239, 68, 68, 0.9); color: white; padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: bold; }

        /* Tombol */
        .btn-tambah { background: #23d5ab; color: white; padding: 8px 14px; border-radius: 8px; text-decoration: none; font-weight: bold; display: inline-block; margin-bottom: 15px; font-size: 13px; }
        .btn-lihat { background: rgba(0,0,0,0.2); color: white; padding: 5px 10px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: bold; }
        .btn-lihat:hover { background: rgba(0,0,0,0.4); }
        .btn-edit { background: #3b82f6; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: bold; margin-right: 5px; display: inline-block; }
        .btn-edit:hover { background: #2563eb; }
        .btn-hapus { background: #ef4444; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: bold; display: inline-block; }
        .btn-hapus:hover { background: #dc2626; }
    </style>
</head>
<body>

<div class="container">
    <a href="logout.php" class="nav-link">Keluar dari Sistem (Logout)</a>
    
    <!-- ================= SECTION KATEGORI ================= -->
    <h2>
        <span>Data Kategori Pakaian</span>
    </h2>
    <a href="kategori_tambah.php" class="btn-tambah">+ Tambah Kategori</a>
    
    <table>
        <tr>
            <th>ID</th>
            <th>Nama Kategori</th>
            <th>Deskripsi</th>
            <th>Aksi</th>
        </tr>
        <?php
        $data_kategori = mysqli_query($koneksi, "SELECT * FROM kategori");
        while ($row = mysqli_fetch_assoc($data_kategori)) {
            echo "<tr>";
            echo "<td>" . $row['id_kategori'] . "</td>";
            echo "<td>" . htmlspecialchars($row['nama_kategori']) . "</td>";
            echo "<td>" . htmlspecialchars($row['deskripsi']) . "</td>";
            echo "<td>";
            echo "<a href='kategori_edit.php?id=" . $row['id_kategori'] . "' class='btn-edit'>Edit</a>";
            echo "<a href='kategori_hapus.php?id=" . $row['id_kategori'] . "' class='btn-hapus' onclick=\"return confirm('Yakin ingin menghapus kategori ini?')\">Hapus</a>";
            echo "</td>";
            echo "</tr>";
        }
        ?>
    </table>

    <br><br>

    <!-- ================= SECTION PAKAIAN ================= -->
    <h2>Data Pakaian</h2>
    <a href="produk_tambah.php" class="btn-tambah">+ Tambah Pakaian</a>
    <table>
        <tr>
            <th>ID</th>
            <th>Kategori</th> <!-- KOLOM KATEGORI DITAMBAHKAN -->
            <th>Nama Pakaian</th>
            <th>Ukuran</th>
            <th>Warna</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
        <?php
        // Menggunakan LEFT JOIN agar Nama Kategori ikut tampil
        $data_pakaian = mysqli_query($koneksi, "SELECT pakaian.*, kategori.nama_kategori 
                                                 FROM pakaian 
                                                 LEFT JOIN kategori ON pakaian.id_kategori = kategori.id_kategori");
        while ($row = mysqli_fetch_assoc($data_pakaian)) {
            echo "<tr>";
            echo "<td>" . $row['id_pakaian'] . "</td>";
            echo "<td>" . htmlspecialchars($row['nama_kategori'] ?? '-') . "</td>"; // Menampilkan nama kategori
            echo "<td>" . htmlspecialchars($row['nama_pakaian']) . "</td>";
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
        ?>
    </table>

    <br><br>

    <!-- ================= SECTION TRANSAKSI ================= -->
    <h2>
        <span>Data Transaksi Terbaru</span>
        <a href="transaksi.php" class="btn-lihat">Kelola Semua Transaksi →</a>
    </h2>
    <a href="transaksi_tambah.php" class="btn-tambah">+ Tambah Transaksi</a>
    <table>
        <tr>
            <th>ID</th>
            <th>Tanggal</th>
            <th>Nama Pakaian</th>
            <th>Admin</th>
            <th>Jenis</th>
            <th>Jumlah</th>
            <th>Keterangan</th>
            <th>Aksi</th>
        </tr>
        <?php
        $data_transaksi = mysqli_query($koneksi, "SELECT transaksi.*, pakaian.nama_pakaian, admin.username 
                                                 FROM transaksi 
                                                 JOIN pakaian ON transaksi.id_pakaian = pakaian.id_pakaian 
                                                 JOIN admin ON transaksi.id_admin = admin.id_admin 
                                                 ORDER BY transaksi.id_transaksi DESC LIMIT 5");
        
        if (mysqli_num_rows($data_transaksi) > 0) {
            while ($row = mysqli_fetch_assoc($data_transaksi)) {
                $badge = ($row['jenis_transaksi'] == 'Masuk') ? "<span class='badge-masuk'>Masuk</span>" : "<span class='badge-keluar'>Keluar</span>";
                echo "<tr>";
                echo "<td>" . $row['id_transaksi'] . "</td>";
                echo "<td>" . $row['tanggal_transaksi'] . "</td>";
                echo "<td><strong>" . htmlspecialchars($row['nama_pakaian']) . "</strong></td>";
                echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                echo "<td>" . $badge . "</td>";
                echo "<td>" . $row['jumlah'] . "</td>";
                echo "<td>" . htmlspecialchars($row['keterangan']) . "</td>";
                echo "<td>";
                echo "<a href='transaksi_edit.php?id=" . $row['id_transaksi'] . "' class='btn-edit'>Edit</a>";
                echo "<a href='transaksi_hapus.php?id=" . $row['id_transaksi'] . "' class='btn-hapus' onclick=\"return confirm('Yakin ingin menghapus transaksi ini? (Stok akan disesuaikan kembali)')\">Hapus</a>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8' style='text-align: center; font-style: italic;'>Belum ada data transaksi.</td></tr>";
        }
        ?>
    </table>

</div>

</body>
</html>
<?php
session_start();
if (!isset($_SESSION['status_login'])) { header("Location: login.php"); exit; }
include 'koneksi.php';

$id_transaksi = $_GET['id'];
$query_trx = mysqli_query($koneksi, "SELECT * FROM transaksi WHERE id_transaksi = '$id_transaksi'");
$trx = mysqli_fetch_assoc($query_trx);

if (!$trx) {
    header("Location: transaksi.php");
    exit;
}

if (isset($_POST['update'])) {
    $id_pakaian_baru = $_POST['id_pakaian'];
    $jenis_baru = $_POST['jenis_transaksi'];
    $jumlah_baru = $_POST['jumlah'];
    $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);

    // Data lama sebelum diubah
    $id_pakaian_lama = $trx['id_pakaian'];
    $jenis_lama = $trx['jenis_transaksi'];
    $jumlah_lama = $trx['jumlah'];

    // 1. Kembalikan stok pakaian ke kondisi sebelum transaksi ini dicatat
    if ($jenis_lama == 'Masuk') {
        mysqli_query($koneksi, "UPDATE pakaian SET stok = stok - $jumlah_lama WHERE id_pakaian = '$id_pakaian_lama'");
    } else {
        mysqli_query($koneksi, "UPDATE pakaian SET stok = stok + $jumlah_lama WHERE id_pakaian = '$id_pakaian_lama'");
    }

    // 2. Terapkan perubahan stok berdasarkan data baru
    if ($jenis_baru == 'Masuk') {
        mysqli_query($koneksi, "UPDATE pakaian SET stok = stok + $jumlah_baru WHERE id_pakaian = '$id_pakaian_baru'");
    } else {
        mysqli_query($koneksi, "UPDATE pakaian SET stok = stok - $jumlah_baru WHERE id_pakaian = '$id_pakaian_baru'");
    }

    // 3. Update data transaksi di database
    mysqli_query($koneksi, "UPDATE transaksi SET id_pakaian = '$id_pakaian_baru', jenis_transaksi = '$jenis_baru', jumlah = '$jumlah_baru', keterangan = '$keterangan' WHERE id_transaksi = '$id_transaksi'");

    header("Location: transaksi.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Transaksi</title>
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
    <h2>Edit Data Transaksi</h2>
    <form method="POST">
        <div class="form-group">
            <label>Pilih Pakaian:</label>
            <select name="id_pakaian" required>
                <?php
                $p = mysqli_query($koneksi, "SELECT * FROM pakaian");
                while ($row = mysqli_fetch_assoc($p)) {
                    $selected = ($row['id_pakaian'] == $trx['id_pakaian']) ? "selected" : "";
                    echo "<option value='".$row['id_pakaian']."' $selected>".$row['nama_pakaian']." (Stok: ".$row['stok'].")</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label>Jenis Transaksi:</label>
            <select name="jenis_transaksi" required>
                <option value="Masuk" <?= ($trx['jenis_transaksi'] == 'Masuk') ? 'selected' : ''; ?>>Masuk</option>
                <option value="Keluar" <?= ($trx['jenis_transaksi'] == 'Keluar') ? 'selected' : ''; ?>>Keluar</option>
            </select>
        </div>

        <div class="form-group">
            <label>Jumlah:</label>
            <input type="number" name="jumlah" min="1" value="<?= $trx['jumlah']; ?>" required>
        </div>

        <div class="form-group">
            <label>Keterangan:</label>
            <textarea name="keterangan" rows="3"><?= htmlspecialchars($trx['keterangan']); ?></textarea>
        </div>

        <button type="submit" name="update">Perbarui Transaksi</button>
        <a href="transaksi.php" class="btn-batal">Batal</a>
    </form>
</div>

</body>
</html>
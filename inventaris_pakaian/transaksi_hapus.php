<?php
session_start();
if (!isset($_SESSION['status_login'])) { header("Location: login.php"); exit; }
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id_transaksi = $_GET['id'];

    // Ambil data transaksi sebelum dihapus untuk menyesuaikan stok kembali
    $getData = mysqli_query($koneksi, "SELECT * FROM transaksi WHERE id_transaksi='$id_transaksi'");
    $trx = mysqli_fetch_assoc($getData);

    if ($trx) {
        $id_pakaian = $trx['id_pakaian'];
        $jenis = $trx['jenis_transaksi'];
        $jumlah = $trx['jumlah'];

        // Sesuaikan stok pakaian
        if ($jenis == 'Masuk') {
            mysqli_query($koneksi, "UPDATE pakaian SET stok = stok - $jumlah WHERE id_pakaian = '$id_pakaian'");
        } else {
            mysqli_query($koneksi, "UPDATE pakaian SET stok = stok + $jumlah WHERE id_pakaian = '$id_pakaian'");
        }

        // Hapus transaksi
        $hapus = mysqli_query($koneksi, "DELETE FROM transaksi WHERE id_transaksi='$id_transaksi'");
        
        if (!$hapus) {
            echo "Gagal menghapus database: " . mysqli_error($koneksi);
            exit;
        }
    }
}

header("Location: transaksi.php");
exit;
?>
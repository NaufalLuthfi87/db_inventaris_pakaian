<?php
session_start();
if (!isset($_SESSION['status_login'])) { header("Location: login.php"); exit; }
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    mysqli_query($koneksi, "DELETE FROM pakaian WHERE id_pakaian='$id'");
}

header("Location: produk.php");
exit;
?>
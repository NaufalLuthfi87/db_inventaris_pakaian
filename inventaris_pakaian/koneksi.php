<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_inventaris_pakaian"; // Sesuaikan dengan nama database Anda

// Membuat koneksi ke database
$koneksi = mysqli_connect($host, $user, $pass, $db);

// Cek apakah koneksi berhasil
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>
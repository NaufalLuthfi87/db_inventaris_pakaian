<?php
session_start();

// Hapus semua data session yang aktif
session_unset();
session_destroy();

// Alihkan kembali ke halaman login
header("Location: login.php");
exit;
?>
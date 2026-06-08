<?php
require 'koneksi.php';

// Cek apakah sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Lempar sesuai role
if ($_SESSION['role'] === 'admin') {
    header("Location: admindashboard.php");
} else {
    header("Location: catalog.php");
}
exit;
?>
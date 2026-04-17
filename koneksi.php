<?php
// Pengaturan Database
$host     = "localhost";
$user     = "root";
$pass     = "";
$db_name  = "db_alumni";

// Melakukan koneksi
$koneksi = mysqli_connect($host, $user, $pass, $db_name);

// Periksa koneksi
if (mysqli_connect_errno()) {
    // Gunakan mysqli_connect_errno() untuk deteksi error yang lebih akurat
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Set charset ke utf8 agar karakter khusus terbaca dengan benar
mysqli_set_charset($koneksi, "utf8");
?>
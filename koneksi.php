<?php
// Konfigurasi Database
$host     = "localhost";
$username = "root";
$password = ""; // Kosongkan jika menggunakan XAMPP default
$database = "db_inventaris";

// Perintah untuk mengoneksikan ke database
$koneksi = mysqli_connect($host, $username, $password, $database);

// Cek apakah koneksi berhasil
if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

// Jika berhasil (opsional, bisa dihapus jika sudah berfungsi)
// echo "Koneksi Berhasil!"; 
?>
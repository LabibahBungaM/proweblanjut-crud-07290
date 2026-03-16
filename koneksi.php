<?php
$host = "localhost";
$db   = "db_inventaris"; // Sesuaikan nama database kamu
$user = "root";
$pass = "";

try {
    // Membuat koneksi PDO
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    
    // Set error mode ke Exception agar mudah didebug
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch(PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?>
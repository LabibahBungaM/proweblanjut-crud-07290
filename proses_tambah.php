<?php
include 'koneksi.php';

if (isset($_POST['submit'])) {
    try {
        // Ambil data dari form
        $id     = $_POST['id'];
        $nama   = $_POST['nama_barang'];
        $jumlah = $_POST['jumlah'];
        $harga  = $_POST['harga'];

        // Persiapkan query dengan Placeholder (?) untuk keamanan (Prepared Statements)
        $sql = "INSERT INTO barang (id, nama_barang, jumlah, harga) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        
        // Eksekusi query dengan mengirimkan data dalam bentuk array
        $execute = $stmt->execute([$id, $nama, $jumlah, $harga]);

        if ($execute) {
            // Redirect ke index dengan status sukses
            header('Location: index.php?status=tambah-sukses');
        }
    } catch (PDOException $e) {
        // Jika ada error (misal ID duplikat), tangkap di sini
        echo "Gagal menambah data: " . $e->getMessage();
    }
} else {
    header('Location: tambah.php');
}
?>
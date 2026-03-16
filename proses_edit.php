<?php
include 'koneksi.php';

if (isset($_POST['update'])) {
    try {
        $id     = $_POST['id'];
        $nama   = $_POST['nama_barang'];
        $jumlah = $_POST['jumlah'];
        $harga  = $_POST['harga'];

        $sql = "UPDATE barang SET nama_barang = ?, jumlah = ?, harga = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        
        $execute = $stmt->execute([$nama, $jumlah, $harga, $id]);

        if ($execute) {
            header('Location: index.php?status=update-sukses');
        }
    } catch (PDOException $e) {
        echo "Gagal memperbarui data: " . $e->getMessage();
    }
} else {
    header('Location: index.php');
}
?>
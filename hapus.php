<?php
include 'koneksi.php';

// 1. Cek apakah ada ID yang dikirim lewat URL
if (isset($_GET['id'])) {
    try {
        $id = $_GET['id'];

        // 2. Gunakan Prepared Statement untuk menghapus
        // Sangat aman dari serangan SQL Injection
        $sql = "DELETE FROM barang WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $execute = $stmt->execute([$id]);

        // 3. Jika berhasil, arahkan kembali ke index dengan status
        if ($execute) {
            header('Location: index.php?status=hapus-berhasil');
            exit;
        }
    } catch (PDOException $e) {
        // Jika gagal karena masalah database
        echo "Gagal menghapus data: " . $e->getMessage();
    }
} else {
    // Jika akses file ini tanpa ID, lempar balik ke dashboard
    header('Location: index.php');
    exit;
}
?>
<?php
include 'koneksi.php';


if (isset($_GET['id'])) {
    try {
        $id = $_GET['id'];

        $sql = "DELETE FROM barang WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $execute = $stmt->execute([$id]);

        if ($execute) {
            header('Location: index.php?status=hapus-berhasil');
            exit;
        }
    } catch (PDOException $e) {

        echo "Gagal menghapus data: " . $e->getMessage();
    }
} else {
    header('Location: index.php');
    exit;
}
?>
<?php
include 'koneksi.php';

// 1. Ambil ID (Kode Barang) dari URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // 2. Query untuk menghapus data berdasarkan ID
    // Karena ID kita sekarang VARCHAR (seperti BRG001), pastikan pakai tanda kutip '$id'
    $sql = "DELETE FROM barang WHERE id = '$id'";
    $query = mysqli_query($koneksi, $sql);

    // 3. Cek apakah berhasil
    if ($query) {
        // Alihkan kembali ke index.php dengan status sukses
        header('Location: index.php?status=hapus-berhasil');
    } else {
        echo "Gagal menghapus data: " . mysqli_error($koneksi);
    }
} else {
    // Jika mencoba akses langsung tanpa ID
    header('Location: index.php');
}
?>
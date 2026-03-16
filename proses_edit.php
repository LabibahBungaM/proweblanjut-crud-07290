<?php
include 'koneksi.php';

if (isset($_POST['update'])) {
    // Ambil data dari form
    $id     = $_POST['id'];
    $nama   = mysqli_real_escape_string($koneksi, $_POST['nama_barang']);
    $jumlah = $_POST['jumlah'];
    $harga  = $_POST['harga'];

    // Query untuk update data berdasarkan ID
    $sql = "UPDATE barang SET 
            nama_barang = '$nama', 
            jumlah = '$jumlah', 
            harga = '$harga' 
            WHERE id = '$id'";

    $query = mysqli_query($koneksi, $sql);

    if ($query) {
        header('Location: index.php?status=update-sukses');
    } else {
        echo "Gagal memperbarui data: " . mysqli_error($koneksi);
    }
} else {
    header('Location: index.php');
}
?>
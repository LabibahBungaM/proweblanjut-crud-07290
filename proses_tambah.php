<?php
include 'koneksi.php';

if (isset($_POST['submit'])) {
    $id     = mysqli_real_escape_string($koneksi, $_POST['id']); // Ambil ID manual
    $nama   = mysqli_real_escape_string($koneksi, $_POST['nama_barang']);
    $jumlah = $_POST['jumlah'];
    $harga  = $_POST['harga'];

    // Masukkan $id ke dalam query INSERT
    $sql = "INSERT INTO barang (id, nama_barang, jumlah, harga) 
            VALUES ('$id', '$nama', '$jumlah', '$harga')";
    
    if (mysqli_query($koneksi, $sql)) {
        header('Location: index.php');
    } else {
        echo "Gagal: Kode Barang mungkin sudah ada!";
    }
}
?>
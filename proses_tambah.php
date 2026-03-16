<?php
include 'koneksi.php';

if (isset($_POST['submit'])) {
    try {
        $id     = $_POST['id'];
        $nama   = $_POST['nama_barang'];
        $jumlah = $_POST['jumlah'];
        $harga  = $_POST['harga'];

        // --- LANGKAH 1: CEK APAKAH ID SUDAH ADA ---
        $cekId = $pdo->prepare("SELECT COUNT(*) FROM barang WHERE id = ?");
        $cekId->execute([$id]);
        $jumlahId = $cekId->fetchColumn();

        if ($jumlahId > 0) {
            // Jika ID sudah ada, lempar balik ke tambah.php dengan status error
            header('Location: tambah.php?error=id-ada');
            exit;
        }
        // ------------------------------------------

        // --- LANGKAH 2: JIKA AMAN, LANJUT INSERT ---
        $sql = "INSERT INTO barang (id, nama_barang, jumlah, harga) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $execute = $stmt->execute([$id, $nama, $jumlah, $harga]);

        if ($execute) {
            header('Location: index.php?status=tambah-sukses');
            exit;
        }
    } catch (PDOException $e) {
        echo "Gagal menambah data: " . $e->getMessage();
    }
}
?>
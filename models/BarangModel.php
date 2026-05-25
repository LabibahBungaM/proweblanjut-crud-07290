<?php
class BarangModel {
    private $db;

    // Konstruktor untuk menerima koneksi PDO dari luar
    public function __construct($pdo) {
        $this->db = $pdo;
    }

    // 1. Mengambil semua data barang untuk halaman utama
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM barang ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Mengambil satu data barang spesifik berdasarkan ID (untuk edit)
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM barang WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 3. Memeriksa apakah Kode Barang sudah terdaftar (untuk validasi)
    public function cekDuplikasiId($id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM barang WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn();
    }

    // 4. Menambahkan data barang baru ke database
    public function tambah($id, $nama, $jumlah, $harga, $gambar) {
        $sql = "INSERT INTO barang (id, nama_barang, jumlah, harga, gambar) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id, $nama, $jumlah, $harga, $gambar]);
    }

    // 5. Memperbarui data barang yang sudah ada
    public function update($id, $nama, $jumlah, $harga, $gambar) {
        $sql = "UPDATE barang SET nama_barang = ?, jumlah = ?, harga = ?, gambar = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nama, $jumlah, $harga, $gambar, $id]);
    }

    // 6. Menghapus data barang dari database
    public function hapus($id) {
        $sql = "DELETE FROM barang WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}
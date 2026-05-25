<?php
class BarangController {
    private $model;

    // Konstruktor untuk menerima objek BarangModel
    public function __construct($barangModel) {
        $this->model = $barangModel;
    }

    // 1. Menampilkan Semua Data Barang 
    public function index() {
        $barang = $this->model->getAll();
        include 'views/index_view.php';
    }

    // 2. Menambah Data Barang 
    public function tambah() {
        $errors = [];

        if (isset($_POST['submit'])) {
            $id     = trim($_POST['id']);
            $nama   = trim($_POST['nama_barang']);
            $jumlah = $_POST['jumlah'];
            $harga  = $_POST['harga'];

            $namaFile   = $_FILES['gambar']['name'] ?? '';
            $ukuranFile = $_FILES['gambar']['size'] ?? 0;
            $tmpName    = $_FILES['gambar']['tmp_name'] ?? '';
            $errorImg   = $_FILES['gambar']['error'] ?? 4;

            if (empty($id)) $errors[] = "Kode Barang tidak boleh kosong.";
            if (empty($nama)) $errors[] = "Nama Barang tidak boleh kosong.";
            if (!is_numeric($jumlah) || $jumlah < 0) $errors[] = "Jumlah stok harus berupa angka.";
            if (!is_numeric($harga) || $harga < 0) $errors[] = "Harga harus berupa angka.";

            if ($errorImg === 4) {
                $errors[] = "Kamu harus mengunggah foto barang.";
            } else {
                $ekstensiValid  = ['jpg', 'jpeg', 'png'];
                $ekstensiGambar = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

                if (!in_array($ekstensiGambar, $ekstensiValid)) $errors[] = "Format gambar harus JPG, JPEG, atau PNG.";
                if ($ukuranFile > 2000000) $errors[] = "Ukuran gambar terlalu besar (Maksimal 2MB).";
            }

            if (empty($errors)) {
                if ($this->model->cekDuplikasiId($id) > 0) {
                    $errors[] = "Kode Barang '$id' sudah terdaftar di sistem.";
                }
            }

            if (empty($errors)) {
                $namaGambarBaru = uniqid() . '-' . $namaFile;
                move_uploaded_file($tmpName, 'uploads/' . $namaGambarBaru);

                $this->model->tambah($id, $nama, $jumlah, $harga, $namaGambarBaru);
                header('Location: index.php?status=tambah-sukses');
                exit;
            }
        }
        include 'views/tambah_view.php';
    }

    // 3. Mengubah Data Barang 
    public function edit() {
        $id_barang = $_GET['id'] ?? $_POST['id'] ?? null;
        if (!$id_barang) {
            header('Location: index.php');
            exit;
        }

        $data = $this->model->getById($id_barang);
        if (!$data) die("Data tidak ditemukan...");

        $errors = [];

        if (isset($_POST['update'])) {
            $id          = $_POST['id'];
            $nama        = trim($_POST['nama_barang']);
            $jumlah      = $_POST['jumlah'];
            $harga       = $_POST['harga'];
            $gambar_lama = $_POST['gambar_lama'];

            if (empty($nama)) $errors[] = "Nama Barang tidak boleh kosong.";
            if (!is_numeric($jumlah) || $jumlah < 0) $errors[] = "Jumlah stok harus berupa angka minimal 0.";
            if (!is_numeric($harga) || $harga < 0) $errors[] = "Harga satuan harus berupa angka minimal 0.";

            $namaGambar = $gambar_lama;

            if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] !== 4) {
                $namaFile   = $_FILES['gambar']['name'];
                $ukuranFile = $_FILES['gambar']['size'];
                $tmpName    = $_FILES['gambar']['tmp_name'];

                $ekstensiValid  = ['jpg', 'jpeg', 'png'];
                $ekstensiGambar = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

                if (!in_array($ekstensiGambar, $ekstensiValid)) $errors[] = "Format gambar baru harus JPG, JPEG, atau PNG.";
                if ($ukuranFile > 2000000) $errors[] = "Ukuran gambar baru maksimal 2MB.";

                if (empty($errors)) {
                    $namaGambar = uniqid() . '-' . $namaFile;
                    move_uploaded_file($tmpName, 'uploads/' . $namaGambar);

                    if (!empty($gambar_lama) && file_exists('uploads/' . $gambar_lama)) {
                        unlink('uploads/' . $gambar_lama);
                    }
                }
            }

            if (empty($errors)) {
                $this->model->update($id, $nama, $jumlah, $harga, $namaGambar);
                header('Location: index.php?status=update-sukses');
                exit;
            }
        }
        include 'views/edit_view.php';
    }

    // 4. Menghapus Data Barang
    public function hapus() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $data = $this->model->getById($id);
            if ($data) {
                if (!empty($data['gambar']) && file_exists('uploads/' . $data['gambar'])) {
                    unlink('uploads/' . $data['gambar']);
                }
                $this->model->hapus($id);
            }
            header('Location: index.php?status=hapus-berhasil');
            exit;
        }
        header('Location: index.php');
        exit;
    }
}
?>
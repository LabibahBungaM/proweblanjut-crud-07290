<?php
session_start();

// Proteksi Halaman (Tugas 5)
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

// ============================================================
// 1. BAGIAN LOGIKA PROSES (Tugas 1, 2, & 3)
// ============================================================
if (isset($_POST['submit'])) {
    try {
        $id     = trim($_POST['id']);
        $nama   = trim($_POST['nama_barang']);
        $jumlah = $_POST['jumlah'];
        $harga  = $_POST['harga'];

        // Ambil Data File Gambar (Tugas 3)
        $namaFile   = $_FILES['gambar']['name'];
        $ukuranFile = $_FILES['gambar']['size'];
        $tmpName    = $_FILES['gambar']['tmp_name'];
        $errorImg   = $_FILES['gambar']['error'];

        $errorMsg = ""; 

        // --- VALIDASI SISI SERVER (Tugas 1) ---
        if (empty($id)) $errorMsg .= "- Kode Barang tidak boleh kosong!\\n";
        if (empty($nama)) $errorMsg .= "- Nama Barang tidak boleh kosong!\\n";
        if (!is_numeric($jumlah) || $jumlah < 0) $errorMsg .= "- Stok harus angka minimal 0!\\n";
        if (!is_numeric($harga) || $harga < 0) $errorMsg .= "- Harga harus angka minimal 0!\\n";

        // --- VALIDASI GAMBAR (Tugas 3) ---
        if ($errorImg === 4) {
            $errorMsg .= "- Kamu harus mengunggah foto barang!\\n";
        } else {
            $ekstensiValid  = ['jpg', 'jpeg', 'png'];
            $ekstensiGambar = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

            if (!in_array($ekstensiGambar, $ekstensiValid)) {
                $errorMsg .= "- Format gambar harus JPG, JPEG, atau PNG!\\n";
            }
            if ($ukuranFile > 2000000) {
                $errorMsg .= "- Ukuran gambar terlalu besar (Maksimal 2MB)!\\n";
            }
        }

        // Jika ada error, tampilkan alert dan jangan lanjut ke DB
        if ($errorMsg !== "") {
            echo "<script>alert('Gagal Menambah Data:\\n$errorMsg'); window.history.back();</script>";
            exit;
        }

        // --- CEK DUPLIKASI ID (Tugas 2 - PDO) ---
        $cekId = $pdo->prepare("SELECT COUNT(*) FROM barang WHERE id = ?");
        $cekId->execute([$id]);
        if ($cekId->fetchColumn() > 0) {
            header('Location: tambah.php?error=id-ada');
            exit;
        }

        // --- PROSES UPLOAD (Tugas 3) ---
        $namaGambarBaru = uniqid() . '.' . $ekstensiGambar;
        move_uploaded_file($tmpName, 'uploads/' . $namaGambarBaru);

        // --- SIMPAN KE DATABASE (Tugas 2 - PDO) ---
        $sql = "INSERT INTO barang (id, nama_barang, jumlah, harga, gambar) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $execute = $stmt->execute([$id, $nama, $jumlah, $harga, $namaGambarBaru]);

        if ($execute) {
            header('Location: index.php?status=tambah-sukses');
            exit;
        }
    } catch (PDOException $e) {
        echo "<script>alert('Database Error: " . addslashes($e->getMessage()) . "');</script>";
    }
}

// ============================================================
// 2. BAGIAN TAMPILAN FORM
// ============================================================
include 'header.php'; 
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <?php if (isset($_GET['error']) && $_GET['error'] == 'id-ada') : ?>
            <div id="alert-notif" class="alert alert-danger border-0 shadow-sm mb-4 text-white" style="border-radius: 15px; background-color: var(--color-salmon);">
                ⚠️ Kode Barang tersebut sudah terdaftar di sistem!
            </div>
        <?php endif; ?>

        <div class="card shadow-sm border-0">
            <div class="card-header text-center py-3">
                <h4 class="mb-0 fw-bold">✨ Tambah Barang Baru</h4>
            </div>
            <div class="card-body p-4">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kode Barang</label>
                        <input type="text" name="id" class="form-control" placeholder="Contoh: BRG01" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Barang</label>
                        <input type="text" name="nama_barang" class="form-control" placeholder="Masukkan nama barang" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Stok</label>
                        <input type="number" name="jumlah" class="form-control" placeholder="0" min="0" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Harga</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">Rp</span>
                            <input type="number" name="harga" class="form-control" placeholder="0" min="0" required>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Foto Barang</label>
                        <input type="file" name="gambar" class="form-control" accept="image/png, image/jpeg, image/jpg" required>
                        <small class="text-muted">Hanya PNG/JPG/JPEG (Maks 2MB)</small>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" name="submit" class="btn btn-custom-add">Simpan Data</button>
                        <a href="index.php" class="btn btn-outline-secondary rounded-pill border">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
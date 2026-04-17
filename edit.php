<?php
session_start();

// Proteksi Halaman
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

// Pastikan ada parameter ID (bisa dari URL GET atau dari form POST saat error)
$id_barang = $_GET['id'] ?? $_POST['id'] ?? null;

if (!$id_barang) {
    header('Location: index.php');
    exit;
}

// Ambil data asli dari database untuk ditampilkan pertama kali
$stmt_get = $pdo->prepare("SELECT * FROM barang WHERE id = ?");
$stmt_get->execute([$id_barang]);
$data = $stmt_get->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    die("Data tidak ditemukan...");
}

// Siapkan Array untuk menampung error (Instruksi 4c)
$errors = [];

// ============================================================
// 1. BAGIAN PROSES UPDATE
// ============================================================
if (isset($_POST['update'])) {
    $id          = $_POST['id'];
    $nama        = trim($_POST['nama_barang']);
    $jumlah      = $_POST['jumlah'];
    $harga       = $_POST['harga'];
    $gambar_lama = $_POST['gambar_lama'];

    // --- LOGIKA VALIDASI SISI SERVER (Instruksi 4a & 4b) ---
    if (empty($nama)) {
        $errors[] = "Nama Barang tidak boleh kosong.";
    }
    if (!is_numeric($jumlah) || $jumlah < 0) {
        $errors[] = "Jumlah stok harus berupa angka minimal 0.";
    }
    if (!is_numeric($harga) || $harga < 0) {
        $errors[] = "Harga satuan harus berupa angka minimal 0.";
    }

    // --- LOGIKA UPLOAD GAMBAR (Instruksi 6) ---
    $namaGambar = $gambar_lama; // Default: pakai gambar lama jika tidak ada upload baru

    if ($_FILES['gambar']['error'] !== 4) { // 4 artinya tidak ada file yang dipilih
        $namaFile   = $_FILES['gambar']['name'];
        $ukuranFile = $_FILES['gambar']['size'];
        $tmpName    = $_FILES['gambar']['tmp_name'];

        $ekstensiValid  = ['jpg', 'jpeg', 'png'];
        $ekstensiGambar = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

        if (!in_array($ekstensiGambar, $ekstensiValid)) {
            $errors[] = "Format gambar baru harus JPG, JPEG, atau PNG.";
        }
        if ($ukuranFile > 2000000) {
            $errors[] = "Ukuran gambar baru maksimal 2MB.";
        }

        // Jika tidak ada error validasi, siapkan gambar baru
        if (empty($errors)) {
            $namaGambar = uniqid() . '-' . $namaFile;
            move_uploaded_file($tmpName, 'uploads/' . $namaGambar);

            // Hapus gambar lama dari folder agar tidak menumpuk
            if (!empty($gambar_lama) && file_exists('uploads/' . $gambar_lama)) {
                unlink('uploads/' . $gambar_lama);
            }
        }
    }

    // --- UPDATE DATABASE (Jika tidak ada error) ---
    if (empty($errors)) {
        try {
            $sql = "UPDATE barang SET nama_barang = ?, jumlah = ?, harga = ?, gambar = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nama, $jumlah, $harga, $namaGambar, $id]);

            header('Location: index.php?status=update-sukses');
            exit;
        } catch (PDOException $e) {
            $errors[] = "Database Error: " . $e->getMessage();
        }
    }
}

// ============================================================
// 2. BAGIAN TAMPILAN FORM
// ============================================================
include 'header.php'; 
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        
        <?php if (!empty($errors)) : ?>
            <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 15px;">
                <strong>⚠️ Peringatan:</strong>
                <ul class="mb-0 mt-2 ps-3">
                    <?php foreach ($errors as $err) : ?>
                        <li><?= $err; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm border-0">
            <div class="card-header text-center py-3">
                <h4 class="mb-0 fw-bold">✏️ Edit Data Barang</h4>
            </div>
            <div class="card-body p-4">
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($data['id']); ?>">
                    <input type="hidden" name="gambar_lama" value="<?= htmlspecialchars($data['gambar']); ?>">
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kode Barang</label>
                        <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($data['id']); ?>" readonly>
                        <small class="text-muted">*ID tidak dapat diubah</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Barang</label>
                        <input type="text" name="nama_barang" class="form-control" 
                               value="<?= isset($_POST['nama_barang']) ? htmlspecialchars($_POST['nama_barang']) : htmlspecialchars($data['nama_barang']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jumlah Stok</label>
                        <input type="number" name="jumlah" class="form-control" 
                               value="<?= isset($_POST['jumlah']) ? htmlspecialchars($_POST['jumlah']) : htmlspecialchars($data['jumlah']); ?>" min="0" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Harga Satuan</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">Rp</span>
                            <input type="number" name="harga" class="form-control" 
                                   value="<?= isset($_POST['harga']) ? htmlspecialchars($_POST['harga']) : (int)$data['harga']; ?>" min="0" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold d-block">Foto Barang</label>
                        <?php if(!empty($data['gambar'])) : ?>
                            <div class="mb-2">
                                <img src="uploads/<?= htmlspecialchars($data['gambar']); ?>" width="100" class="rounded shadow-sm">
                                <p class="small text-muted">Foto saat ini</p>
                            </div>
                        <?php endif; ?>
                        <input type="file" name="gambar" class="form-control" accept="image/png, image/jpeg, image/jpg">
                        <small class="text-muted">Pilih file baru jika ingin mengganti foto (Maks 2MB).</small>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" name="update" class="btn btn-custom-add">Simpan Perubahan</button>
                        <a href="index.php" class="btn btn-outline-secondary rounded-pill border">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
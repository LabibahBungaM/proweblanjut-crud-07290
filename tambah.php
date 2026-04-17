<?php
session_start();

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

// Siapkan Array untuk menampung error (Sesuai Instruksi 4.c)
$errors = [];

if (isset($_POST['submit'])) {
    // Tangkap input
    $id     = trim($_POST['id']);
    $nama   = trim($_POST['nama_barang']);
    $jumlah = $_POST['jumlah'];
    $harga  = $_POST['harga'];

    // Ambil Data File Gambar
    $namaFile   = $_FILES['gambar']['name'];
    $ukuranFile = $_FILES['gambar']['size'];
    $tmpName    = $_FILES['gambar']['tmp_name'];
    $errorImg   = $_FILES['gambar']['error'];

    // --- LOGIKA VALIDASI SERVER (Instruksi 4a & 4b) ---
    if (empty($id)) {
        $errors[] = "Kode Barang tidak boleh kosong.";
    }
    if (empty($nama)) {
        $errors[] = "Nama Barang tidak boleh kosong.";
    }
    if (!is_numeric($jumlah) || $jumlah < 0) {
        $errors[] = "Jumlah stok harus berupa angka.";
    }
    if (!is_numeric($harga) || $harga < 0) {
        $errors[] = "Harga harus berupa angka.";
    }

    // --- LOGIKA UPLOAD & VALIDASI GAMBAR (Instruksi 6a & 6b) ---
    if ($errorImg === 4) {
        $errors[] = "Kamu harus mengunggah foto barang.";
    } else {
        $ekstensiValid  = ['jpg', 'jpeg', 'png'];
        $ekstensiGambar = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

        if (!in_array($ekstensiGambar, $ekstensiValid)) {
            $errors[] = "Format gambar harus JPG, JPEG, atau PNG.";
        }
        if ($ukuranFile > 2000000) {
            $errors[] = "Ukuran gambar terlalu besar (Maksimal 2MB).";
        }
    }

    // --- CEK DUPLIKASI ID (Instruksi 5 - PDO) ---
    if (empty($errors)) {
        $cekId = $pdo->prepare("SELECT COUNT(*) FROM barang WHERE id = ?");
        $cekId->execute([$id]);
        if ($cekId->fetchColumn() > 0) {
            $errors[] = "Kode Barang '$id' sudah terdaftar di sistem.";
        }
    }

    // JIKA ARRAY ERROR KOSONG (Artinya semua validasi sukses)
    if (empty($errors)) {
        try {
            // (Instruksi 6c & 6d) Buat nama unik dan pindahkan file
            $namaGambarBaru = uniqid() . '-' . $namaFile;
            move_uploaded_file($tmpName, 'uploads/' . $namaGambarBaru);

            // (Instruksi 5 & 6e) Simpan ke Database
            $sql = "INSERT INTO barang (id, nama_barang, jumlah, harga, gambar) VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id, $nama, $jumlah, $harga, $namaGambarBaru]);

            header('Location: index.php?status=tambah-sukses');
            exit;
        } catch (PDOException $e) {
            $errors[] = "Database Error: " . $e->getMessage();
        }
    }
}

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
                <h4 class="mb-0 fw-bold">✨ Tambah Barang Baru</h4>
            </div>
            <div class="card-body p-4">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kode Barang</label>
                        <input type="text" name="id" class="form-control" value="<?= isset($_POST['id']) ? htmlspecialchars($_POST['id']) : ''; ?>" placeholder="Contoh: BRG01" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Barang</label>
                        <input type="text" name="nama_barang" class="form-control" value="<?= isset($_POST['nama_barang']) ? htmlspecialchars($_POST['nama_barang']) : ''; ?>" placeholder="Masukkan nama barang" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Stok</label>
                        <input type="number" name="jumlah" class="form-control" value="<?= isset($_POST['jumlah']) ? htmlspecialchars($_POST['jumlah']) : ''; ?>" min="0" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Harga</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">Rp</span>
                            <input type="number" name="harga" class="form-control" value="<?= isset($_POST['harga']) ? htmlspecialchars($_POST['harga']) : ''; ?>" min="0" required>
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
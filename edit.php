<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';


if (isset($_POST['update'])) {
    try {
        $id          = $_POST['id'];
        $nama        = trim($_POST['nama_barang']);
        $jumlah      = $_POST['jumlah'];
        $harga       = $_POST['harga'];
        $gambar_lama = $_POST['gambar_lama'];

        $error = ""; 

     
        if (empty($nama)) $error .= "- Nama Barang tidak boleh kosong!\\n";
        if (!is_numeric($jumlah) || $jumlah < 0) $error .= "- Stok harus angka minimal 0!\\n";
        if (!is_numeric($harga) || $harga < 0) $error .= "- Harga harus angka minimal 0!\\n";

   
        if ($_FILES['gambar']['error'] === 4) {
            $namaGambar = $gambar_lama; 
        } else {
            $namaFile   = $_FILES['gambar']['name'];
            $ukuranFile = $_FILES['gambar']['size'];
            $tmpName    = $_FILES['gambar']['tmp_name'];

            $ekstensiValid  = ['jpg', 'jpeg', 'png'];
            $ekstensiGambar = strtolower(end(explode('.', $namaFile)));

            if (!in_array($ekstensiGambar, $ekstensiValid)) {
                $error .= "- Format gambar baru tidak valid!\\n";
            }
            if ($ukuranFile > 2000000) {
                $error .= "- Ukuran gambar baru maksimal 2MB!\\n";
            }

            if ($error === "") {
                $namaGambar = uniqid() . '.' . $ekstensiGambar;
                move_uploaded_file($tmpName, 'uploads/' . $namaGambar);

                
                if (!empty($gambar_lama) && file_exists('uploads/' . $gambar_lama)) {
                    unlink('uploads/' . $gambar_lama);
                }
            }
        }

        
        if ($error !== "") {
            echo "<script>alert('Gagal Update:\\n$error'); window.history.back();</script>";
            exit;
        }

        
        $sql = "UPDATE barang SET nama_barang = ?, jumlah = ?, harga = ?, gambar = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $execute = $stmt->execute([$nama, $jumlah, $harga, $namaGambar, $id]);

        if ($execute) {
            header('Location: index.php?status=update-sukses');
            exit;
        }
    } catch (PDOException $e) {
        echo "<script>alert('Gagal memperbarui data: " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
        exit;
    }
}


if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id_get = $_GET['id'];
$stmt_get = $pdo->prepare("SELECT * FROM barang WHERE id = ?");
$stmt_get->execute([$id_get]);
$data = $stmt_get->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    die("Data tidak ditemukan...");
}

include 'header.php'; 
?>

<div class="row justify-content-center">
    <div class="col-md-6">
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
                        <input type="text" name="nama_barang" class="form-control" value="<?= htmlspecialchars($data['nama_barang']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jumlah Stok</label>
                        <input type="number" name="jumlah" class="form-control" value="<?= htmlspecialchars($data['jumlah']); ?>" min="0" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Harga Satuan</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">Rp</span>
                            <input type="number" name="harga" class="form-control" value="<?= (int)$data['harga']; ?>" min="0" required>
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
<?php
session_start();


if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php");
    exit;
}


?>
<?php
include 'koneksi.php';


$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM barang WHERE id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

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
                <form action="proses_edit.php" method="POST">
                    <input type="hidden" name="id" value="<?= $data['id']; ?>">
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kode Barang</label>
                        <input type="text" class="form-control bg-light" value="<?= $data['id']; ?>" readonly>
                        <small class="text-secondary text-italic">*Kode barang tidak dapat diubah.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Barang</label>
                        <input type="text" name="nama_barang" class="form-control" 
                               value="<?= $data['nama_barang']; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jumlah Stok</label>
                        <input type="number" name="jumlah" class="form-control" 
                               value="<?= $data['jumlah']; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Harga Satuan</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">Rp</span>
                            <input type="number" name="harga" class="form-control" 
                                   value="<?= (int)$data['harga']; ?>" required>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" name="update" class="btn btn-custom-add">Simpan Perubahan</button>
                        <a href="index.php" class="btn btn-outline-secondary rounded-pill">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php 

include 'footer.php'; 
?>
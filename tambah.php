<?php
session_start();


if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php");
    exit;
}


?>
<?php 
include 'koneksi.php';
include 'header.php'; 
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <?php if (isset($_GET['error'])) : ?>
            <div id="alert-notif" class="alert alert-danger border-0 shadow-sm mb-4 text-white" style="border-radius: 15px; background-color: var(--color-salmon);">
                ⚠️ Kode Barang sudah terdaftar!
            </div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-header text-center py-3">
                <h4 class="mb-0 fw-bold">✨ Tambah Barang</h4>
            </div>
            <div class="card-body p-4">
                <form action="proses_tambah.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Kode Barang</label>
                        <input type="text" name="id" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" name="nama_barang" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stok</label>
                        <input type="number" name="jumlah" class="form-control" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga</label>
                        <input type="number" name="harga" class="form-control" min="0" required>
                    </div>
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" name="submit" class="btn btn-custom-add">Simpan</button>
                        <a href="index.php" class="btn btn-light rounded-pill border">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var alertElement = document.getElementById('alert-notif');
        if (alertElement) {
            setTimeout(function() {
                var bsAlert = new bootstrap.Alert(alertElement);
                bsAlert.close();
            }, 2000); 
        }
    });
</script>
</body>
</html>
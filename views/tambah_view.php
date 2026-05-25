<?php include 'views/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        
        <?php if (!empty($errors)) : ?>
            <div id="alert-error" class="alert alert-danger border-0 shadow-sm mb-4 fade show" style="border-radius: 15px;">
                <strong>⚠️ Peringatan:</strong>
                <ul class="mb-0 mt-2 ps-3">
                    <?php foreach ($errors as $err) : ?>
                        <li><?= $err; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    setTimeout(function() {
                        var alertNode = document.getElementById('alert-error');
                        if(alertNode) {
                            var bsAlert = new bootstrap.Alert(alertNode);
                            bsAlert.close();
                        }
                    }, 4500); 
                });
            </script>
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

<?php include 'views/footer.php'; ?>
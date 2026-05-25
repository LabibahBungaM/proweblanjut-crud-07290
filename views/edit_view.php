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

<?php include 'views/footer.php'; ?>
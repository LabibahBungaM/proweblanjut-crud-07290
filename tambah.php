<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Barang Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header text-center">
                    <h4 class="mb-0">✨ Tambah Barang Baru</h4>
                </div>
                <div class="card-body p-4">
                    <form action="proses_tambah.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kode Barang</label>
                            <input type="text" name="id" class="form-control" placeholder="Contoh: BRG001" required>
                            <small class="text-muted">Gunakan format unik (Huruf & Angka).</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Barang</label>
                            <input type="text" name="nama_barang" class="form-control" placeholder="Masukkan nama barang" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jumlah Stok</label>
                            <input type="number" name="jumlah" class="form-control" placeholder="0" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Harga Satuan</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="harga" class="form-control" placeholder="0" required>
                            </div>
                        </div>
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" name="submit" class="btn btn-custom-add">Simpan Data</button>
                            <a href="index.php" class="btn btn-light">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
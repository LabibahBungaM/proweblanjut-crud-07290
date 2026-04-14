<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';
include 'header.php'; 

$stmt = $pdo->query("SELECT * FROM barang ORDER BY id DESC");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-custom-title">🍃 Inventaris Barang</h2>
        <p class="text-muted">Kelola stok</p>
    </div>
    <a href="tambah.php" class="btn btn-custom-add shadow-sm">
        + Tambah Barang
    </a>
</div>

<?php if (isset($_GET['status'])) : ?>
    <?php 
        $bg_color = ($_GET['status'] == 'hapus-berhasil') ? '#F48B94' : 'var(--color-mint)';
        $text_color = ($_GET['status'] == 'hapus-berhasil') ? 'white' : 'var(--color-dark)';
        $icon = ($_GET['status'] == 'hapus-berhasil') ? '🗑️' : '✨';
    ?>
    <div id="alert-notif" class="alert fade show border-0 shadow-sm mb-4" role="alert" 
         style="background-color: <?= $bg_color; ?>; color: <?= $text_color; ?>; border-radius: 15px; transition: opacity 0.5s ease;">
        <div class="d-flex align-items-center">
            <span style="font-size: 1.5rem; margin-right: 15px;"><?= $icon; ?></span>
            <div>
                <strong class="d-block">
                    <?= ($_GET['status'] == 'hapus-berhasil') ? 'Terhapus!' : 'Berhasil!'; ?>
                </strong>
                <span class="small">
                    <?php 
                        if($_GET['status'] == 'hapus-berhasil') echo "Barang telah dikeluarkan dari daftar.";
                        if($_GET['status'] == 'tambah-sukses') echo "Barang baru telah ditambahkan.";
                        if($_GET['status'] == 'update-sukses') echo "Perubahan data telah disimpan.";
                    ?>
                </span>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Foto</th> <th>Nama Barang</th>
                        <th>Stok</th>
                        <th>Harga Satuan</th>
                        <th>Tanggal Masuk</th> <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
                    <tr>
                        <td class="ps-4 text-muted">#<?= htmlspecialchars($row['id']); ?></td>
                        
                        <td>
                            <?php 
                            // Cek apakah data gambar ada dan tidak kosong
                            if(isset($row['gambar']) && !empty($row['gambar'])) : 
                            ?>
                                <img src="uploads/<?= htmlspecialchars($row['gambar']); ?>" alt="Foto" style="width: 60px; height: 60px; object-fit: cover; border-radius: 10px; border: 1px solid #eee;">
                            <?php else : ?>
                                <span class="badge bg-secondary" style="font-size: 10px;">No Image</span>
                            <?php endif; ?>
                        </td>

                        <td class="fw-semibold"><?= htmlspecialchars($row['nama_barang']); ?></td>
                        
                        <td>
                            <span class="badge <?= ($row['jumlah'] < 5) ? 'badge-low' : 'badge-enough'; ?> rounded-pill px-3 py-2">
                                <?= htmlspecialchars($row['jumlah']); ?> Unit
                            </span>
                        </td>
                        <td><span class="text-price fw-bold">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></span></td>
                        <td><small class="text-muted"><?= date('d M Y', strtotime($row['tanggal_masuk'])); ?></small></td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                                <a href="hapus.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin menghapus barang ini?')">Hapus</a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    
                    <?php if($stmt->rowCount() == 0) : ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">Belum ada koleksi barang.</td> </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'footer.php';  ?>
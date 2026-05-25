<?php
session_start();

// 1. Proteksi Halaman
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php");
    exit;
}

// 2. Panggil semua komponen MVC
require_once 'koneksi.php';
require_once 'models/BarangModel.php';
require_once 'controllers/BarangController.php';

// 3. Inisialisasi Model & Controller
$model = new BarangModel($pdo);
$controller = new BarangController($model);

// ==========================================================
// 4. ROUTER (Sistem Pengarah Jalan MVC)
// ==========================================================
// Cek apakah ada permintaan 'action' di URL (misal: index.php?action=tambah)
$action = $_GET['action'] ?? 'index';

// Arahkan ke fungsi di Controller yang sesuai
if ($action == 'tambah') {
    $controller->tambah();
} elseif ($action == 'edit') {
    $controller->edit();
} elseif ($action == 'hapus') {
    $controller->hapus();
} else {
    // Jika tidak ada action, tampilkan halaman utama
    $controller->index();
}
?>
<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

require_once '../koneksi.php';
require_once '../models/BarangModel.php';

$model = new BarangModel($pdo);

// Menerima input dari JSON (raw) ATAU x-www-form-urlencoded
$input = json_decode(file_get_contents("php://input"), true) ?: $_POST;

if (!empty($input['id']) && !empty($input['nama_barang']) && isset($input['jumlah']) && isset($input['harga'])) {
    
    // Cek apakah ID sudah ada
    if ($model->cekDuplikasiId($input['id']) > 0) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Kode Barang sudah terdaftar."]);
    } else {
        $gambar = $input['gambar'] ?? ''; // Gambar dikosongkan/diisi teks dari API
        $model->tambah($input['id'], $input['nama_barang'], $input['jumlah'], $input['harga'], $gambar);
        
        http_response_code(201);
        echo json_encode(["status" => "success", "message" => "Barang baru berhasil ditambahkan."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Data tidak lengkap."]);
}
?>
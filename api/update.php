<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, PUT");

require_once '../koneksi.php';
require_once '../models/BarangModel.php';

$model = new BarangModel($pdo);
$input = json_decode(file_get_contents("php://input"), true) ?: $_POST;

if (!empty($input['id']) && !empty($input['nama_barang']) && isset($input['jumlah']) && isset($input['harga'])) {
    
    // Pastikan barang yang mau diubah ada di database
    $cekData = $model->getById($input['id']);
    if ($cekData) {
        $gambar = $input['gambar'] ?? $cekData['gambar'];
        $model->update($input['id'], $input['nama_barang'], $input['jumlah'], $input['harga'], $gambar);
        
        echo json_encode(["status" => "success", "message" => "Data barang berhasil diperbarui."]);
    } else {
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "Barang tidak ditemukan."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Data tidak lengkap."]);
}
?>
<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, DELETE");

require_once '../koneksi.php';
require_once '../models/BarangModel.php';

$model = new BarangModel($pdo);
$input = json_decode(file_get_contents("php://input"), true) ?: $_POST;

// Tangkap ID dari body request JSON/form, atau dari URL (?id=...)
$id = $input['id'] ?? $_GET['id'] ?? null;

if ($id) {
    if ($model->getById($id)) {
        $model->hapus($id);
        echo json_encode(["status" => "success", "message" => "Barang berhasil dihapus."]);
    } else {
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "Barang tidak ditemukan."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "ID barang tidak diberikan."]);
}
?>
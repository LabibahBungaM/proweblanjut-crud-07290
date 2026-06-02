<?php
// RTM Langkah 3: Mengatur Header
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

// Memanggil Koneksi dan Model (path naik satu tingkat dengan '../')
require_once '../koneksi.php';
require_once '../models/BarangModel.php';

$model = new BarangModel($pdo);
$data = $model->getAll();

// Mengeluarkan respons JSON (RTM Langkah 2a)
echo json_encode([
    "status" => "success", 
    "data" => $data
]);
?>
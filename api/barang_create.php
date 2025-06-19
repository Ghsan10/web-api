<?php
require '../config/database.php';
include '../config/cors.php';
setCorsHeaders();
header("Content-Type: application/json");
// header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->api_key, $data->nama, $data->jumlah, $data->kategori_id)) {
    http_response_code(400);
    echo json_encode(["message" => "Data tidak lengkap"]);
    exit;
}

// Contoh validasi API key bisa kamu tambahkan di sini
// if ($data->api_key !== "xxx") { ... }

try {
    $stmt = $db->prepare("INSERT INTO barang (nama, jumlah, kategori_id) VALUES (:nama, :jumlah, :kategori_id)");
    $stmt->execute([
        ':nama' => $data->nama,
        ':jumlah' => $data->jumlah,
        ':kategori_id' => $data->kategori_id
    ]);
    echo json_encode(["message" => "Barang berhasil ditambahkan"]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["message" => "Gagal menambahkan", "error" => $e->getMessage()]);
}

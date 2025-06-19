<?php
require '../config/database.php';
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->api_key, $data->id, $data->nama, $data->jumlah)) {
    http_response_code(400);
    echo json_encode(["message" => "Data tidak lengkap"]);
    exit;
}

try {
    $stmt = $db->prepare("UPDATE barang SET nama = :nama, jumlah = :jumlah WHERE id = :id");
    $stmt->execute([
        ':nama' => $data->nama,
        ':jumlah' => $data->jumlah,
        ':id' => $data->id
    ]);
    echo json_encode(["message" => "Barang berhasil diupdate"]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["message" => "Gagal update", "error" => $e->getMessage()]);
}

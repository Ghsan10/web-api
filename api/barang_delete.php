<?php
require '../config/database.php';
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->api_key, $data->id)) {
    http_response_code(400);
    echo json_encode(["message" => "Data tidak lengkap"]);
    exit;
}

try {
    $stmt = $db->prepare("DELETE FROM barang WHERE id = :id");
    $stmt->execute([':id' => $data->id]);
    echo json_encode(["message" => "Barang berhasil dihapus"]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["message" => "Gagal menghapus", "error" => $e->getMessage()]);
}

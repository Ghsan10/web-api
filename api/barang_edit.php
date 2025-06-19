<?php
include '../config/database.php';
include '../config/cors.php';
setCorsHeaders();
header('Content-Type: application/json');

if (!isset($_POST['api_key'])) {
    http_response_code(400);
    echo json_encode(['message' => 'API Key diperlukan']);
    exit;
}

$apiKey = $_POST['api_key'];
$stmt = $db->prepare("SELECT id FROM users WHERE api_key = ?");
$stmt->execute([$apiKey]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    http_response_code(403);
    echo json_encode(['message' => 'API Key tidak valid']);
    exit;
}

$id = $_POST['id'] ?? '';
$nama = $_POST['nama'] ?? '';
$jumlah = $_POST['jumlah'] ?? '';

if (!$id || !$nama) {
    http_response_code(400);
    echo json_encode(['message' => 'ID dan nama harus diisi']);
    exit;
}

$stmt = $db->prepare("UPDATE barang SET nama = ?, jumlah = ? WHERE id = ?");
$stmt->execute([$nama, $jumlah, $id]);
echo json_encode(['message' => 'Barang berhasil diperbarui']);

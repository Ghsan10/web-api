<?php
header('Content-Type: application/json');

// Include koneksi database dari folder config
include '../config/database.php';

if (!isset($_GET['api_key'])) {
    http_response_code(400);
    echo json_encode(['message' => 'API Key diperlukan']);
    exit;
}

$apiKey = $_GET['api_key'];

// Validasi API Key
$stmt = $db->prepare("SELECT id FROM users WHERE api_key = ?");
$stmt->execute([$apiKey]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    http_response_code(403);
    echo json_encode(['message' => 'API Key tidak valid']);
    exit;
}

try {
    $stmt = $db->query("SELECT id, nama FROM kategori");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($data);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Gagal mengambil data kategori']);
}

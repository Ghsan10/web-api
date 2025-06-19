<?php
header("Content-Type: application/json");
include '../config/database.php';

if (!isset($_GET['api_key'])) {
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

// Ambil data barang berdasarkan kategori jika tersedia
if (isset($_GET['kategori_id'])) {
    $kategoriId = $_GET['kategori_id'];
    $stmt = $db->prepare("SELECT id, nama, jumlah FROM barang WHERE kategori_id = ?");
    $stmt->execute([$kategoriId]);
} else {
    $stmt = $db->query("SELECT id, nama, jumlah FROM barang");
}

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($data);

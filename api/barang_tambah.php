<?php
header('Content-Type: application/json');
include '../config/database.php';

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

$nama = $_POST['nama'] ?? '';
$jumlah = $_POST['jumlah'] ?? 0;
$kategori_id = $_POST['kategori_id'] ?? 0;

if (!$nama || !$kategori_id) {
    http_response_code(400);
    echo json_encode(['message' => 'Nama dan kategori harus diisi']);
    exit;
}

$stmt = $db->prepare("INSERT INTO barang (nama, jumlah, kategori_id) VALUES (?, ?, ?)");
$stmt->execute([$nama, $jumlah, $kategori_id]);
echo json_encode(['message' => 'Barang berhasil ditambahkan']);

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

if (!$id) {
    http_response_code(400);
    echo json_encode(['message' => 'ID harus disertakan']);
    exit;
}

$stmt = $db->prepare("DELETE FROM barang WHERE id = ?");
$stmt->execute([$id]);
echo json_encode(['message' => 'Barang berhasil dihapus']);

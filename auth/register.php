<?php
session_start();
require '../config/database.php';

$nama = $_POST['nama'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$api_key = bin2hex(random_bytes(16));

$stmt = $db->prepare("INSERT INTO users (nama, email, password, api_key) VALUES (?, ?, ?, ?)");
$stmt->execute([$nama, $email, $password, $api_key]);

$_SESSION['user'] = [
    'nama' => $nama,
    'api_key' => $api_key
];
$_SESSION['user'] = [
    'nama' => $nama,
    'api_key' => $api_key
];

// Jika request dari browser biasa, redirect
if (!empty($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'Postman') === false) {
    header("Location: ../dashboard.php");
    exit;
}

// Jika dari Postman/API, balas JSON
echo json_encode([
    'message' => 'Registrasi berhasil',
    'nama' => $nama,
    'api_key' => $api_key
]);
exit;

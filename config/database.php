<?php
// $host = 'localhost';
// $dbname = 'gudang_db';
// $username = 'root';
// $password = '';
$host = "nozomi.proxy.rlwy.net";
$dbname = "railway";
$username = "root";
$password = "YqgbKBTiDvxzMYjzAFxOnZFvNJoYQVet";
$port = 32871;

try {
    $db = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?>
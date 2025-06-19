<?php
// $host = 'localhost';
// $dbname = 'gudang_db';
// $username = 'root';
// $password = '';

$host = 'mysql.railway.internal';
$dbname = 'railway';
$username = 'root';
$password = 'YqgbKBTiDvxzMYjzAFxOnZFvNJoYQVet';
$port = 3306;

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?>
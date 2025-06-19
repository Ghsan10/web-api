<?php
session_start();
require_once '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['email'] = $user['email'];
        header("Location: ../dashboard.php");
        exit;
    } else {
        echo "<script>alert('Email atau password salah!'); window.location.href = '../index.html';</script>";
        exit;
    }
} else {
    header("Location: ../index.html");
    exit;
}
?>
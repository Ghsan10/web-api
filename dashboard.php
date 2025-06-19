<?php
require_once 'middleware/session.php';
require_once 'config/database.php';

if (!isset($_SESSION['email'])) {
    header("Location: index.html");
    exit;
}

$email = $_SESSION['email'];
$stmt = $db->prepare("SELECT nama, api_key FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Pengguna tidak ditemukan.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            background: #e3efff;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .dashboard-container {
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
            width: 400px;
        }
        .api-key {
            background: #f4f4f4;
            padding: 10px;
            border-radius: 5px;
            font-family: monospace;
            word-break: break-all;
            margin-top: 10px;
        }
        .copy-btn {
            margin-top: 10px;
            padding: 6px 12px;
            border: none;
            background-color: #4CAF50;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }
        .copy-btn:hover {
            background-color: #45a049;
        }
        a.logout {
            display: inline-block;
            margin-top: 20px;
            padding: 8px 12px;
            background: #ff4d4d;
            color: #fff;
            border-radius: 5px;
            text-decoration: none;
        }
        a.logout:hover {
            background: #cc0000;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h2>Selamat Datang, <?php echo htmlspecialchars($user['nama']); ?>!</h2>
        <p>API Key Anda:</p>
        <div class="api-key" id="apiKey"><?php echo htmlspecialchars($user['api_key']); ?></div>
        <button class="copy-btn" onclick="copyApiKey()">Salin API Key</button>
        <a class="logout" href="logout.php">Logout</a>
    </div>

    <script>
        function copyApiKey() {
            const apiKeyText = document.getElementById("apiKey").innerText;
            navigator.clipboard.writeText(apiKeyText).then(() => {
                alert("API Key berhasil disalin!");
            }).catch(err => {
                alert("Gagal menyalin API Key: " + err);
            });
        }
    </script>
</body>
</html>

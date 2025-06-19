<?php
echo json_encode([
    'php_working' => true,
    'method' => $_SERVER['REQUEST_METHOD'],
    'post_data' => $_POST,
    'timestamp' => date('Y-m-d H:i:s')
]);
?>
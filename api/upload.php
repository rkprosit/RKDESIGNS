<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$targetDir = __DIR__ . '/../uploads/';
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0755, true);
}

if (!isset($_FILES['image'])) {
    http_response_code(400);
    echo json_encode(['error' => 'No image uploaded']);
    exit;
}

$file = $_FILES['image'];
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$allowed = ['jpg', 'jpeg', 'png', 'webp'];

if (!in_array($ext, $allowed)) {
    http_response_code(400);
    echo json_encode(['error' => 'Only JPG, PNG, WEBP allowed']);
    exit;
}

$filename = uniqid() . '.' . $ext;
$dest = $targetDir . $filename;

if (move_uploaded_file($file['tmp_name'], $dest)) {
    echo json_encode(['url' => 'uploads/' . $filename]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Upload failed']);
}

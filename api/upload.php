<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/guard.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$targetDir = __DIR__ . '/../uploads/';
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0755, true);
}

if (!isset($_FILES['image']) || !is_array($_FILES['image'])) {
    http_response_code(400);
    echo json_encode(['error' => 'No image uploaded']);
    exit;
}

$file = $_FILES['image'];

if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] <= 0 || $file['size'] > 5 * 1024 * 1024) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid upload (max 5 MB)']);
    exit;
}

$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$allowed = ['jpg', 'jpeg', 'png', 'webp'];

if (!in_array($ext, $allowed, true)) {
    http_response_code(400);
    echo json_encode(['error' => 'Only JPG, PNG, WEBP allowed']);
    exit;
}

// Verify real file content matches allowed MIME types
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime  = $finfo->file($file['tmp_name']);
$allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];

if (!in_array($mime, $allowedMimes, true)) {
    http_response_code(400);
    echo json_encode(['error' => 'File content is not a valid image']);
    exit;
}

$filename = bin2hex(random_bytes(16)) . '.' . $ext;
$dest     = $targetDir . $filename;

if (move_uploaded_file($file['tmp_name'], $dest)) {
    @chmod($dest, 0644);
    echo json_encode(['url' => 'uploads/' . $filename]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Upload failed']);
}

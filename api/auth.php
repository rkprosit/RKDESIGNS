<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

$data = json_decode(file_get_contents('php://input'), true);

$valid_username = 'admin';
$valid_password = 'rkdesigns@2026';

if ($data['username'] === $valid_username && $data['password'] === $valid_password) {
    $_SESSION['admin'] = true;
    echo json_encode(['success' => true]);
} else {
    http_response_code(401);
    echo json_encode(['success' => false]);
}

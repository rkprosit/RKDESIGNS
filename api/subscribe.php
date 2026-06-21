<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo json_encode(['error' => 'Method not allowed']); exit; }

require_once __DIR__ . '/../config/database.php';

$data = json_decode(file_get_contents('php://input'), true);
$email = filter_var($data['email'] ?? '', FILTER_VALIDATE_EMAIL);

if (!$email) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid email']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO subscribers (email) VALUES (?)");
    $stmt->execute([$email]);
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    if ($e->getCode() == 23000) {
        echo json_encode(['error' => 'Already subscribed']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Server error']);
    }
}

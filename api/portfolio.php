<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../config/database.php';

$method = $_SERVER['REQUEST_METHOD'];

// Public: anyone may read the portfolio feed (used by the website)
if ($method === 'GET') {
    $stmt = $pdo->query("SELECT * FROM portfolio ORDER BY created_at DESC");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

// Admin-only mutations
require_once __DIR__ . '/guard.php';

switch ($method) {
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true) ?: [];
        $title       = trim((string)($data['title'] ?? ''));
        $description = trim((string)($data['description'] ?? ''));
        $imageUrl    = trim((string)($data['image_url'] ?? ''));
        $category    = trim((string)($data['category'] ?? ''));

        if ($title === '' || $imageUrl === '' || $category === '') {
            http_response_code(400);
            echo json_encode(['error' => 'Title, image_url and category are required']);
            exit;
        }
        if (mb_strlen($title) > 255 || mb_strlen($description) > 255 || mb_strlen($category) > 50 || mb_strlen($imageUrl) > 500) {
            http_response_code(400);
            echo json_encode(['error' => 'Field length exceeded']);
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO portfolio (title, description, image_url, category) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $description, $imageUrl, $category]);
        echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
        break;

    case 'DELETE':
        $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
        if ($id !== false && $id !== null) {
            $stmt = $pdo->prepare("DELETE FROM portfolio WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['success' => true]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'ID required']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
}

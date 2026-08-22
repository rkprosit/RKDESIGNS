<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    http_response_code(200);
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false]);
    exit;
}

$secrets = require __DIR__ . '/../config/secrets.php';

// Brute-force throttle: max 5 attempts per 10 minutes per session
$now          = time();
$_SESSION['failures'] = $_SESSION['failures'] ?? [];
$_SESSION['failures'] = array_values(array_filter($_SESSION['failures'], static fn($t) => $t > $now - 600));

if (count($_SESSION['failures']) >= 5) {
    http_response_code(429);
    echo json_encode(['success' => false, 'error' => 'Too many attempts. Try again later.']);
    exit;
}

$data     = json_decode(file_get_contents('php://input'), true) ?: [];
$username = (string)($data['username'] ?? '');
$password = (string)($data['password'] ?? '');

$storedHash = (string)$secrets['admin']['password_hash'];
$isBcrypt   = strncmp($storedHash, '$2y$', 4) === 0 || strncmp($storedHash, '$2a$', 4) === 0;

$usernameOk = hash_equals((string)$secrets['admin']['username'], $username);
$passwordOk = $isBcrypt ? password_verify($password, $storedHash) : hash_equals($storedHash, $password);

if ($usernameOk && $passwordOk) {
    session_regenerate_id(true);
    $_SESSION['admin']   = true;
    $_SESSION['failures'] = [];
    echo json_encode(['success' => true]);
} else {
    $_SESSION['failures'][] = $now;
    usleep(random_int(200000, 500000));
    http_response_code(401);
    echo json_encode(['success' => false]);
}

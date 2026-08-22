<?php
/**
 * Shared admin session guard for mutating API endpoints.
 * Call after session_start(); exits with 401 if not logged in.
 */
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

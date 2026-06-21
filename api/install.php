<?php
require_once __DIR__ . '/../config/database.php';

try {
    $sql = file_get_contents(__DIR__ . '/../config/schema.sql');
    $pdo->exec($sql);
    echo json_encode(['success' => 'Portfolio table created successfully']);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

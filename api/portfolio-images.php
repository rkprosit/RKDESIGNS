<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$baseDir = __DIR__ . '/../uploads/portfolio';
$result = [];

if (!is_dir($baseDir)) {
    echo json_encode([]);
    exit;
}

$dirs = new DirectoryIterator($baseDir);
$categories = [];
foreach ($dirs as $dir) {
    if ($dir->isDir() && !$dir->isDot()) {
        $categories[] = $dir->getFilename();
    }
}
sort($categories);

foreach ($categories as $category) {
    $catPath = $baseDir . '/' . $category;
    $files = new DirectoryIterator($catPath);
    $images = [];
    foreach ($files as $f) {
        if (!$f->isFile()) continue;
        $filename = $f->getFilename();
        if ($filename === '.gitkeep') continue;
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) continue;
        $images[] = [
            'image_url' => 'uploads/portfolio/' . $category . '/' . rawurlencode($filename),
            'title' => pathinfo($filename, PATHINFO_FILENAME),
            'category' => $category,
        ];
    }
    usort($images, function($a, $b) {
        return strcasecmp($a['title'], $b['title']);
    });
    $result = array_merge($result, $images);
}

echo json_encode($result);

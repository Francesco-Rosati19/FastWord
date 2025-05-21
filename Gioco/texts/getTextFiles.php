<?php
$lingua = $_GET['lingua'] ?? '';

$path = __DIR__ . "/$lingua";

if (!is_dir($path)) {
    http_response_code(400);
    echo json_encode(["error" => "Cartella non valida"]);
    exit;
}

$files = array_values(array_filter(scandir($path), function ($file) use ($path) {
    return is_file("$path/$file") && pathinfo($file, PATHINFO_EXTENSION) === 'txt';
}));

echo json_encode($files);

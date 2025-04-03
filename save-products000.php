<?php
header('Content-Type: application/json');

// Получаем данные из POST запроса
$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);

if ($data === null) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON data']);
    exit;
}

// Сохраняем данные в файл
try {
    $result = file_put_contents(
        'cart.json',
        json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
    );

    if ($result === false) {
        throw new Exception('Failed to write file');
    }

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
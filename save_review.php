<?php
// Разрешаем CORS для локальной разработки
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Проверяем метод запроса
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Метод не разрешен']);
    exit;
}

try {
    // Получаем данные из POST запроса
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    // Проверяем наличие всех необходимых данных
    if (!isset($data['productId']) || !isset($data['rating']) || !isset($data['comment'])) {
        throw new Exception('Отсутствуют необходимые данные');
    }
    
    // Читаем текущий JSON файл
    $jsonFile = 'cart.json';
    if (!file_exists($jsonFile)) {
        throw new Exception('Файл cart.json не найден');
    }
    
    $jsonContent = file_get_contents($jsonFile);
    if ($jsonContent === false) {
        throw new Exception('Ошибка чтения файла cart.json');
    }
    
    $cartData = json_decode($jsonContent, true);
    if ($cartData === null) {
        throw new Exception('Ошибка декодирования JSON');
    }
    
    // Находим нужный продукт
    $productFound = false;
    foreach ($cartData['products'] as &$product) {
        if ($product['id'] === intval($data['productId'])) {
            if (!isset($product['reviews'])) {
                $product['reviews'] = [];
            }
            
            // Добавляем новый отзыв
            $product['reviews'][] = [
                'rating' => intval($data['rating']),
                'comment' => strip_tags($data['comment']), // Защита от XSS
                'date' => date('c')
            ];
            $productFound = true;
            break;
        }
    }
    
    if (!$productFound) {
        throw new Exception('Продукт не найден');
    }
    
    // Сохраняем обновленный JSON
    $result = file_put_contents($jsonFile, json_encode($cartData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    if ($result === false) {
        throw new Exception('Ошибка записи в файл');
    }
    
    echo json_encode(['success' => true]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
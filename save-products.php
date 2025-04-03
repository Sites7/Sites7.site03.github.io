<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


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
    if ($input === false) {
        throw new Exception('Ошибка чтения входных данных');
    }

    $data = json_decode($input, true);
    if ($data === null) {
        throw new Exception('Некорректные JSON данные');
    }

    // Проверяем структуру данных
    if (!isset($data['categories']) || !isset($data['products']) || 
        !is_array($data['categories']) || !is_array($data['products'])) {
        throw new Exception('Неверная структура данных');
    }

    // Проверяем наличие нужных полей в каждом продукте
    foreach ($data['products'] as $product) {
        if (!isset($product['id']) || !isset($product['name']) || 
            !isset($product['category']) || !isset($product['price'])) {
            throw new Exception('Неверная структура продукта');
        }
    }

    // Читаем текущий файл для проверки
    $jsonFile = 'cart.json';
    if (!file_exists($jsonFile)) {
        throw new Exception('Файл cart.json не найден');
    }

    if (!is_writable($jsonFile)) {
        throw new Exception('Нет прав на запись в файл cart.json');
    }

    // Создаем резервную копию
    $backup = file_get_contents($jsonFile);
    if ($backup === false) {
        throw new Exception('Ошибка создания резервной копии');
    }

    $backupFile = $jsonFile . '.backup';
    if (file_put_contents($backupFile, $backup) === false) {
        throw new Exception('Ошибка сохранения резервной копии');
    }

    // Сохраняем новые данные
    $result = file_put_contents(
        $jsonFile,
        json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
    );

    if ($result === false) {
        // Восстанавливаем из резервной копии
        file_put_contents($jsonFile, $backup);
        throw new Exception('Ошибка записи в файл');
    }

    // Удаляем резервную копию после успешного сохранения
    unlink($backupFile);

    echo json_encode([
        'success' => true,
        'message' => 'Данные успешно сохранены'
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => $e->getMessage(),
        'details' => 'Произошла ошибка при сохранении данных'
    ]);
}
?>
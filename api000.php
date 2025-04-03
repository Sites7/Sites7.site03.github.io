<?php
// db_config.php - Файл конфигурации базы данных
$db_host = 'localhost';
$db_name = 'q967394v_qqq';
$db_user = 'q967394v_qqq';
$db_pass = 'Abz35907xyz'; // Укажите свой пароль, если он установлен

// api.php - Основной файл API
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

include('db_config.php');

try {
    $db = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'] ?? '', '/'));
$endpoint = $request[0] ?? '';

switch ($method) {
    case 'GET':
        handleGetRequest($db, $endpoint, $request);
        break;
    case 'POST':
        handlePostRequest($db, $endpoint);
        break;
    case 'PUT':
        handlePutRequest($db, $endpoint, $request);
        break;
    case 'DELETE':
        handleDeleteRequest($db, $endpoint, $request);
        break;
    default:
        echo json_encode(['error' => 'Invalid request method']);
        break;
}

function handleGetRequest($db, $endpoint, $request) {
    switch ($endpoint) {
        case 'products':
            getProducts($db);
            break;
        case 'orders':
            if (isset($request[1])) {
                getOrderDetails($db, $request[1]);
            } else {
                getOrders($db);
            }
            break;
        default:
            echo json_encode(['error' => 'Invalid endpoint']);
            break;
    }
}

function handlePostRequest($db, $endpoint) {
    $data = json_decode(file_get_contents('php://input'), true);
    
    switch ($endpoint) {
        case 'order':
            createOrder($db, $data);
            break;
        default:
            echo json_encode(['error' => 'Invalid endpoint']);
            break;
    }
}

function handlePutRequest($db, $endpoint, $request) {
    $data = json_decode(file_get_contents('php://input'), true);
    
    switch ($endpoint) {
        case 'order_status':
            if (isset($request[1])) {
                updateOrderStatus($db, $request[1], $data);
            } else {
                echo json_encode(['error' => 'Order ID is required']);
            }
            break;
        default:
            echo json_encode(['error' => 'Invalid endpoint']);
            break;
    }
}

function handleDeleteRequest($db, $endpoint, $request) {
    switch ($endpoint) {
        case 'order':
            if (isset($request[1])) {
                deleteOrder($db, $request[1]);
            } else {
                echo json_encode(['error' => 'Order ID is required']);
            }
            break;
        default:
            echo json_encode(['error' => 'Invalid endpoint']);
            break;
    }
}

function getProducts($db) {
    try {
        $stmt = $db->prepare("SELECT * FROM products");
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'products' => $products]);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Failed to get products: ' . $e->getMessage()]);
    }
}

function getOrders($db) {
    try {
        $stmt = $db->prepare("
            SELECT o.order_id, o.order_date, o.status, o.total_amount, 
                   c.first_name, c.last_name, c.email, c.phone
            FROM orders o
            JOIN customers c ON o.customer_id = c.customer_id
            ORDER BY o.order_date DESC
        ");
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'orders' => $orders]);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Failed to get orders: ' . $e->getMessage()]);
    }
}

function getOrderDetails($db, $orderId) {
    try {
        // Получаем информацию о заказе и клиенте
        $stmt = $db->prepare("
            SELECT o.order_id, o.order_date, o.status, o.total_amount, 
                   c.customer_id, c.first_name, c.last_name, c.email, c.phone, c.address
            FROM orders o
            JOIN customers c ON o.customer_id = c.customer_id
            WHERE o.order_id = ?
        ");
        $stmt->execute([$orderId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$order) {
            echo json_encode(['error' => 'Order not found']);
            return;
        }
        
        // Получаем товары из заказа
        $stmt = $db->prepare("
            SELECT oi.order_item_id, oi.product_id, oi.quantity, oi.price, oi.subtotal, 
                   p.name as product_name, p.image
            FROM order_items oi
            JOIN products p ON oi.product_id = p.product_id
            WHERE oi.order_id = ?
        ");
        $stmt->execute([$orderId]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $order['items'] = $items;
        
        echo json_encode(['success' => true, 'order' => $order]);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Failed to get order details: ' . $e->getMessage()]);
    }
}

function createOrder($db, $data) {
    try {
        $db->beginTransaction();
        
        // Проверяем, существует ли клиент
        $stmt = $db->prepare("SELECT customer_id FROM customers WHERE email = ?");
        $stmt->execute([$data['email']]);
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Если клиент не существует, создаем нового
        if (!$customer) {
            $stmt = $db->prepare("
                INSERT INTO customers (first_name, last_name, email, phone, address)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $data['firstName'],
                $data['lastName'],
                $data['email'],
                $data['phone'],
                $data['address'] ?? ''
            ]);
            $customerId = $db->lastInsertId();
        } else {
            $customerId = $customer['customer_id'];
        }
        
        // Создаем заказ
        $stmt = $db->prepare("
            INSERT INTO orders (customer_id, status, total_amount)
            VALUES (?, 'Новый', ?)
        ");
        $stmt->execute([$customerId, $data['totalAmount']]);
        $orderId = $db->lastInsertId();
        
        // Добавляем товары в заказ
        foreach ($data['items'] as $item) {
            $stmt = $db->prepare("
                INSERT INTO order_items (order_id, product_id, quantity, price, subtotal)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $orderId,
                $item['productId'],
                $item['quantity'],
                $item['price'],
                $item['subtotal']
            ]);
        }
        
        $db->commit();
        echo json_encode(['success' => true, 'orderId' => $orderId]);
    } catch (PDOException $e) {
        $db->rollBack();
        echo json_encode(['error' => 'Failed to create order: ' . $e->getMessage()]);
    }
}

function updateOrderStatus($db, $orderId, $data) {
    try {
        $stmt = $db->prepare("
            UPDATE orders
            SET status = ?
            WHERE order_id = ?
        ");
        $stmt->execute([$data['status'], $orderId]);
        
        $rowCount = $stmt->rowCount();
        if ($rowCount > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Order not found or status not changed']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Failed to update order status: ' . $e->getMessage()]);
    }
}

function deleteOrder($db, $orderId) {
    try {
        $db->beginTransaction();
        
        // Удаляем связанные товары в заказе
        $stmt = $db->prepare("DELETE FROM order_items WHERE order_id = ?");
        $stmt->execute([$orderId]);
        
        // Удаляем заказ
        $stmt = $db->prepare("DELETE FROM orders WHERE order_id = ?");
        $stmt->execute([$orderId]);
        
        $rowCount = $stmt->rowCount();
        if ($rowCount > 0) {
            $db->commit();
            echo json_encode(['success' => true]);
        } else {
            $db->rollBack();
            echo json_encode(['error' => 'Order not found']);
        }
    } catch (PDOException $e) {
        $db->rollBack();
        echo json_encode(['error' => 'Failed to delete order: ' . $e->getMessage()]);
    }
}
?>
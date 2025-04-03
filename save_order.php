<?php
// Include database connection
include 'conection_db.php';

// Set headers for JSON response
header('Content-Type: application/json');

// Get the JSON data from the request body
$json_data = file_get_contents('php://input');
$order_data = json_decode($json_data, true);

// Check if data was received and properly decoded
if (!$order_data) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid data format'
    ]);
    exit;
}

// Start transaction
$conn->begin_transaction();

try {
    // 1. Insert order into orders table
    $order_date = date('Y-m-d H:i:s');
    $status = 'new'; // Initial status for new orders
    
    $order_stmt = $conn->prepare("
        INSERT INTO orders 
        (customer_name, customer_phone, customer_email, delivery_address, customer_comment, order_date, status, total_amount) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $order_stmt->bind_param(
        "sssssssd",
        $order_data['customer']['name'],
        $order_data['customer']['phone'],
        $order_data['customer']['email'],
        $order_data['customer']['address'],
        $order_data['customer']['comment'],
        $order_date,
        $status,
        $order_data['total']
    );
    
    $order_stmt->execute();
    $order_id = $conn->insert_id;
    
    // 2. Insert order items into order_items table
    $item_stmt = $conn->prepare("
        INSERT INTO order_items 
        (order_id, product_id, product_name, quantity, price, total_price) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    
    foreach ($order_data['items'] as $item) {
        $item_total = $item['price'] * $item['quantity'];
        
        $item_stmt->bind_param(
            "iisidi",
            $order_id,
            $item['id'],
            $item['title'],
            $item['quantity'],
            $item['price'],
            $item_total
        );
        
        $item_stmt->execute();
    }
    
    // 3. Commit transaction
    $conn->commit();
    
    // 4. Generate unique order number (could be order_id with prefix)
    $order_number = 'ORD-' . str_pad($order_id, 6, '0', STR_PAD_LEFT);
    
    // Return success response
    echo json_encode([
        'success' => true,
        'order_id' => $order_number,
        'message' => 'Order successfully placed'
    ]);
    
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    
    // Log the error (to error log, not to response)
    error_log('Order save error: ' . $e->getMessage());
    
    // Return error response
    echo json_encode([
        'success' => false,
        'message' => 'Failed to save order'
    ]);
}

// Close the database connection
$conn->close();
?>
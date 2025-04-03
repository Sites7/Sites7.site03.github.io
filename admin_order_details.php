<?php
// Включаем файл с подключением к базе данных
include 'db_connection.php';

// Проверка авторизации администратора
session_start();
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit;
}

// Проверка наличия ID заказа
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: admin_orders.php');
    exit;
}

$order_id = (int)$_GET['id'];

// Получение информации о заказе
$stmt = $conn->prepare("
    SELECT * FROM orders
    WHERE order_id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_result = $stmt->get_result();

if ($order_result->num_rows === 0) {
    header('Location: admin_orders.php');
    exit;
}

$order = $order_result->fetch_assoc();

// Получение элементов заказа
$stmt = $conn->prepare("
    SELECT oi.*, p.product_id, p.title, pi.thumbnail
    FROM order_items oi
    LEFT JOIN products p ON oi.product_id = p.product_id
    LEFT JOIN (
        SELECT product_id, MIN(thumbnail) as thumbnail
        FROM product_images
        GROUP BY product_id
    ) pi ON p.product_id = pi.product_id
    WHERE oi.order_id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$items_result = $stmt->get_result();

// Включаем header админ-панели
include 'admin_header.php';
?>

<div class="admin-content">
    <div class="order-header">
        <h1>Заказ #<?php echo $order_id; ?></h1>
        <a href="admin_orders.php" class="btn btn-secondary">Назад к списку заказов</a>
    </div>
    
    <div class="order-details-container">
        <div class="order-info">
            <h2>Информация о заказе</h2>
            <div class="order-info-grid">
                <div class="info-item">
                    <span class="info-label">Статус:</span>
                    <span class="status-badge status-<?php echo $order['status']; ?>">
                        <?php 
                        $status_labels = [
                            'new' => 'Новый',
                            'processing' => 'В обработке',
                            'shipped' => 'Отправлен',
                            'delivered' => 'Доставлен',
                            'cancelled' => 'Отменен'
                        ];
                        echo $status_labels[$order['status']]; 
                        ?>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Дата создания:</span>
                    <span><?php echo date('d.m.Y H:i', strtotime($order['created_at'])); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Сумма заказа:</span>
                    <span class="order-total"><?php echo number_format($order['total_amount'], 2, '.', ' '); ?> руб.</span>
                </div>
            </div>
            
            <!-- Форма для обновления статуса -->
            <div class="status-update-form">
                <h3>Изменить статус</h3>
                <form method="post" action="admin_orders.php">
                    <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                    <div class="form-group">
                        <select name="status" class="form-control">
                            <option value="new" <?php echo ($order['status'] == 'new') ? 'selected' : ''; ?>>Новый</option>
                            <option value="processing" <?php echo ($order['status'] == 'processing') ? 'selected' : ''; ?>>В обработке</option>
                            <option value="shipped" <?php echo ($order['status'] == 'shipped') ? 'selected' : ''; ?>>Отправлен</option>
                            <option value="delivered" <?php echo ($order['status'] == 'delivered') ? 'selected' : ''; ?>>Доставлен</option>
                            <option value="cancelled" <?php echo ($order['status'] == 'cancelled') ? 'selected' : ''; ?>>Отменен</option>
                        </select>
                    </div>
                    <button type="submit" name="update_status" class="btn btn-primary">Обновить статус</button>
                </form>
            </div>
        </div>
        
        <div class="customer-info">
            <h2>Информация о клиенте</h2>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">ФИО:</span>
                    <span><?php echo htmlspecialchars($order['customer_name']); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Телефон:</span>
                    <span><?php echo htmlspecialchars($order['customer_phone']); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email:</span>
                    <span><?php echo htmlspecialchars($order['customer_email']); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Адрес доставки:</span>
                    <span><?php echo htmlspecialchars($order['delivery_address']); ?></span>
                </div>
                <?php if (!empty($order['comment'])): ?>
                <div class="info-item full-width">
                    <span class="info-label">Комментарий:</span>
                    <span><?php echo htmlspecialchars($order['comment']); ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="order-items">
        <h2>Товары в заказе</h2>
        <div class="table-responsive">
            <table class="admin-table items-table">
                <thead>
                    <tr>
                        <th>Товар</th>
                        <th>Наименование</th>
                        <th>Цена, руб.</th>
                        <th>Количество</th>
                        <th>Сумма, руб.</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($item = $items_result->fetch_assoc()): ?>
                        <tr>
                            <td class="product-image">
                                <?php if ($item['thumbnail']): ?>
                                    <img src="<?php echo htmlspecialchars($item['thumbnail']); ?>" alt="<?php echo htmlspecialchars($item['product_title']); ?>">
                                <?php else: ?>
                                    <img src="/api/placeholder/80/80" alt="Нет изображения">
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($item['product_title']); ?></td>
                            <td><?php echo number_format($item['price'], 2, '.', ' '); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td><?php echo number_format($item['subtotal'], 2, '.', ' '); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-right"><strong>Итого:</strong></td>
                        <td><strong><?php echo number_format($order['total_amount'], 2, '.', ' '); ?> руб.</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<?php include 'admin_footer.php'; ?>
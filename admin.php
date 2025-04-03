<?php
// Включаем файл с подключением к базе данных
include 'db_connection.php';

// Проверка авторизации администратора
session_start();
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit;
}

// Обработка изменения статуса заказа
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];
    
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
    $stmt->bind_param("si", $new_status, $order_id);
    $stmt->execute();
    
    // Сообщение об успешном обновлении
    $status_updated = true;
}

// Получение списка заказов с пагинацией
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

// Фильтр по статусу заказа, если указан
$status_filter = "";
$status_param = "";
if (isset($_GET['status']) && $_GET['status'] != 'all') {
    $status_filter = " WHERE status = ?";
    $status_param = $_GET['status'];
}

// Получаем общее количество заказов для пагинации
if (!empty($status_filter)) {
    $count_stmt = $conn->prepare("SELECT COUNT(*) FROM orders" . $status_filter);
    $count_stmt->bind_param("s", $status_param);
} else {
    $count_stmt = $conn->prepare("SELECT COUNT(*) FROM orders");
}
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_orders = $count_result->fetch_row()[0];
$total_pages = ceil($total_orders / $per_page);

// Получаем список заказов
if (!empty($status_filter)) {
    $stmt = $conn->prepare("
        SELECT * FROM orders" . $status_filter . "
        ORDER BY created_at DESC
        LIMIT ? OFFSET ?
    ");
    $stmt->bind_param("sii", $status_param, $per_page, $offset);
} else {
    $stmt = $conn->prepare("
        SELECT * FROM orders
        ORDER BY created_at DESC
        LIMIT ? OFFSET ?
    ");
    $stmt->bind_param("ii", $per_page, $offset);
}
$stmt->execute();
$orders = $stmt->get_result();

// Включаем header админ-панели
include 'admin_header.php';
?>

<div class="admin-content">
    <h1>Управление заказами</h1>
    
    <?php if (isset($status_updated)): ?>
    <div class="alert alert-success">
        Статус заказа успешно обновлен!
    </div>
    <?php endif; ?>
    
    <!-- Фильтры -->
    <div class="filters-container">
        <form method="get" action="" class="filters-form">
            <label for="status">Фильтр по статусу:</label>
            <select name="status" id="status" onchange="this.form.submit()">
                <option value="all" <?php echo (!isset($_GET['status']) || $_GET['status'] == 'all') ? 'selected' : ''; ?>>Все заказы</option>
                <option value="new" <?php echo (isset($_GET['status']) && $_GET['status'] == 'new') ? 'selected' : ''; ?>>Новые</option>
                <option value="processing" <?php echo (isset($_GET['status']) && $_GET['status'] == 'processing') ? 'selected' : ''; ?>>В обработке</option>
                <option value="shipped" <?php echo (isset($_GET['status']) && $_GET['status'] == 'shipped') ? 'selected' : ''; ?>>Отправлен</option>
                <option value="delivered" <?php echo (isset($_GET['status']) && $_GET['status'] == 'delivered') ? 'selected' : ''; ?>>Доставлен</option>
                <option value="cancelled" <?php echo (isset($_GET['status']) && $_GET['status'] == 'cancelled') ? 'selected' : ''; ?>>Отменен</option>
            </select>
        </form>
    </div>
    
    <!-- Таблица заказов -->
    <div class="table-responsive">
        <table class="admin-table orders-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Клиент</th>
                    <th>Контакты</th>
                    <th>Сумма</th>
                    <th>Статус</th>
                    <th>Дата</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($orders->num_rows > 0): ?>
                    <?php while ($order = $orders->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $order['order_id']; ?></td>
                            <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                            <td>
                                <div>Тел: <?php echo htmlspecialchars($order['customer_phone']); ?></div>
                                <div>Email: <?php echo htmlspecialchars($order['customer_email']); ?></div>
                            </td>
                            <td><?php echo number_format($order['total_amount'], 2, '.', ' '); ?> руб.</td>
                            <td>
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
                            </td>
                            <td><?php echo date('d.m.Y H:i', strtotime($order['created_at'])); ?></td>
                            <td>
                                <a href="admin_order_details.php?id=<?php echo $order['order_id']; ?>" class="btn btn-info btn-sm">
                                    Детали
                                </a>
                                <button class="btn btn-primary btn-sm" onclick="showStatusModal(<?php echo $order['order_id']; ?>, '<?php echo $order['status']; ?>')">
                                    Изменить статус
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">Заказы не найдены</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Пагинация -->
    <?php if ($total_pages > 1): ?>
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?php echo ($page - 1); ?><?php echo isset($_GET['status']) ? '&status=' . $_GET['status'] : ''; ?>" class="pagination-item">&laquo; Назад</a>
        <?php endif; ?>
        
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?php echo $i; ?><?php echo isset($_GET['status']) ? '&status=' . $_GET['status'] : ''; ?>" class="pagination-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>
        
        <?php if ($page < $total_pages): ?>
            <a href="?page=<?php echo ($page + 1); ?><?php echo isset($_GET['status']) ? '&status=' . $_GET['status'] : ''; ?>" class="pagination-item">Вперед &raquo;</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<!-- Модальное окно для изменения статуса -->
<div id="statusModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeStatusModal()">&times;</span>
        <h2>Изменить статус заказа</h2>
        <form method="post" action="">
            <input type="hidden" id="modal_order_id" name="order_id" value="">
            <div class="form-group">
                <label for="status_select">Новый статус:</label>
                <select id="status_select" name="status" class="form-control">
                    <option value="new">Новый</option>
                    <option value="processing">В обработке</option>
                    <option value="shipped">Отправлен</option>
                    <option value="delivered">Доставлен</option>
                    <option value="cancelled">Отменен</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" name="update_status" class="btn btn-success">Сохранить</button>
                <button type="button" class="btn btn-secondary" onclick="closeStatusModal()">Отмена</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Функции для работы с модальным окном
    function showStatusModal(orderId, currentStatus) {
        document.getElementById('modal_order_id').value = orderId;
        document.getElementById('status_select').value = currentStatus;
        document.getElementById('statusModal').style.display = 'block';
    }
    
    function closeStatusModal() {
        document.getElementById('statusModal').style.display = 'none';
    }
    
    // Закрытие модального окна при клике вне его
    window.onclick = function(event) {
        var modal = document.getElementById('statusModal');
        if (event.target == modal) {
            closeStatusModal();
        }
    }
</script>

<?php include 'admin_footer.php'; ?>
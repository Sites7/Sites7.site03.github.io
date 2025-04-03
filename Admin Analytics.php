<?php
// Включаем файл с подключением к базе данных
include 'db_connection.php';

// Проверка авторизации администратора
session_start();
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit;
}

// Пагинация
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 15;
$offset = ($page - 1) * $per_page;

// Получение информации о популярных товарах (по количеству добавлений в избранное)
$popular_products_sql = "
    SELECT 
        p.product_id,
        p.title,
        p.price,
        COUNT(f.favorite_id) as favorite_count,
        (SELECT pi.thumbnail FROM product_images pi WHERE pi.product_id = p.product_id ORDER BY pi.sort_order LIMIT 1) as thumbnail
    FROM 
        products p
    LEFT JOIN 
        favorites f ON p.product_id = f.product_id
    GROUP BY 
        p.product_id
    HAVING 
        favorite_count > 0
    ORDER BY 
        favorite_count DESC, p.title
    LIMIT ?, ?
";

$stmt = $conn->prepare($popular_products_sql);
$stmt->bind_param("ii", $offset, $per_page);
$stmt->execute();
$popular_products_result = $stmt->get_result();

// Получаем общее количество популярных товаров для пагинации
$count_stmt = $conn->prepare("
    SELECT COUNT(*) FROM (
        SELECT p.product_id, COUNT(f.favorite_id) as favorite_count
        FROM products p
        LEFT JOIN favorites f ON p.product_id = f.product_id
        GROUP BY p.product_id
        HAVING favorite_count > 0
    ) as popular_products
");
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_products = $count_result->fetch_row()[0];
$total_pages = ceil($total_products / $per_page);

// Получение пользователей с избранными товарами
$users_with_favorites_sql = "
    SELECT 
        u.user_id,
        u.name,
        u.email,
        COUNT(DISTINCT f.product_id) as favorites_count
    FROM 
        users u
    JOIN 
        favorites f ON u.user_id = f.user_id
    GROUP BY 
        u.user_id
    ORDER BY 
        favorites_count DESC
    LIMIT 10
";

$users_stmt = $conn->prepare($users_with_favorites_sql);
$users_stmt->execute();
$users_result = $users_stmt->get_result();

// Получение последних заказов
$recent_orders_sql = "
    SELECT 
        o.order_id,
        o.customer_name,
        o.customer_email,
        o.customer_phone,
        o.delivery_address,
        o.total_amount,
        o.status,
        o.created_at,
        COUNT(oi.order_item_id) as items_count
    FROM 
        orders o
    LEFT JOIN 
        order_items oi ON o.order_id = oi.order_id
    GROUP BY 
        o.order_id
    ORDER BY 
        o.created_at DESC
    LIMIT 10
";

$orders_stmt = $conn->prepare($recent_orders_sql);
$orders_stmt->execute();
$recent_orders_result = $orders_stmt->get_result();

// Включаем header админ-панели
include 'admin_header.php';
?>

<div class="admin-content">
    <h1>Аналитика избранных товаров и заказов</h1>
    
    <div class="admin-stats">
        <div class="stat-card">
            <h3>Общая статистика по избранным</h3>
            <div class="stat-content">
                <?php
                // Получаем общее количество избранных товаров
                $total_favorites_stmt = $conn->prepare("SELECT COUNT(*) FROM favorites");
                $total_favorites_stmt->execute();
                $total_favorites = $total_favorites_stmt->get_result()->fetch_row()[0];
                
                // Получаем количество пользователей с избранными товарами
                $users_with_favorites_stmt = $conn->prepare("SELECT COUNT(DISTINCT user_id) FROM favorites");
                $users_with_favorites_stmt->execute();
                $users_with_favorites = $users_with_favorites_stmt->get_result()->fetch_row()[0];
                
                // Среднее количество избранных на пользователя
                $avg_favorites = $users_with_favorites > 0 ? round($total_favorites / $users_with_favorites, 1) : 0;
                ?>
                <p><strong>Всего добавлений в избранное:</strong> <?php echo $total_favorites; ?></p>
                <p><strong>Пользователей с избранным:</strong> <?php echo $users_with_favorites; ?></p>
                <p><strong>Среднее количество избранных на пользователя:</strong> <?php echo $avg_favorites; ?></p>
            </div>
        </div>
        
        <div class="stat-card">
            <h3>Общая статистика по заказам</h3>
            <div class="stat-content">
                <?php
                // Получаем общее количество заказов
                $total_orders_stmt = $conn->prepare("SELECT COUNT(*) FROM orders");
                $total_orders_stmt->execute();
                $total_orders = $total_orders_stmt->get_result()->fetch_row()[0];
                
                // Получаем сумму всех заказов
                $total_sales_stmt = $conn->prepare("SELECT SUM(total_amount) FROM orders WHERE status != 'canceled'");
                $total_sales_stmt->execute();
                $total_sales = $total_sales_stmt->get_result()->fetch_row()[0];
                
                // Получаем среднюю сумму заказа
                $avg_order_stmt = $conn->prepare("SELECT AVG(total_amount) FROM orders WHERE status != 'canceled'");
                $avg_order_stmt->execute();
                $avg_order = $avg_order_stmt->get_result()->fetch_row()[0];
                ?>
                <p><strong>Всего заказов:</strong> <?php echo $total_orders; ?></p>
                <p><strong>Общая сумма продаж:</strong> <?php echo number_format($total_sales, 2, '.', ' '); ?> руб.</p>
                <p><strong>Средняя сумма заказа:</strong> <?php echo number_format($avg_order, 2, '.', ' '); ?> руб.</p>
            </div>
        </div>
    </div>
    
    <div class="admin-sections">
        <!-- Раздел с популярными товарами -->
        <div class="admin-section">
            <h2>Популярные товары в избранном</h2>
            
            <div class="table-responsive">
                <table class="admin-table favorites-table">
                    <thead>
                        <tr>
                            <th>Товар</th>
                            <th>Наименование</th>
                            <th>Цена, руб.</th>
                            <th>Добавлений в избранное</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($popular_products_result->num_rows > 0): ?>
                            <?php while ($product = $popular_products_result->fetch_assoc()): ?>
                                <tr>
                                    <td class="product-image">
                                        <?php if ($product['thumbnail']): ?>
                                            <img src="<?php echo htmlspecialchars($product['thumbnail']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>">
                                        <?php else: ?>
                                            <img src="/api/placeholder/80/80" alt="Нет изображения">
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($product['title']); ?></td>
                                    <td><?php echo number_format($product['price'], 2, '.', ' '); ?></td>
                                    <td><strong><?php echo $product['favorite_count']; ?></strong></td>
                                    <td>
                                        <a href="product_edit.php?id=<?php echo $product['product_id']; ?>" class="admin-action edit">Редактировать</a>
                                        <a href="favorite_users.php?product_id=<?php echo $product['product_id']; ?>" class="admin-action view">Пользователи</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="no-data">Нет данных о популярных товарах</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Пагинация -->
            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo $page - 1; ?>" class="page-link">Назад</a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <?php if ($i == $page): ?>
                            <span class="page-link active"><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="?page=<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                    
                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?php echo $page + 1; ?>" class="page-link">Вперед</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Раздел с пользователями -->
        <div class="admin-section">
            <h2>Пользователи с избранными товарами</h2>
            
            <div class="table-responsive">
                <table class="admin-table users-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Имя</th>
                            <th>Email</th>
                            <th>Товаров в избранном</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($users_result->num_rows > 0): ?>
                            <?php while ($user = $users_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $user['user_id']; ?></td>
                                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td><strong><?php echo $user['favorites_count']; ?></strong></td>
                                    <td>
                                        <a href="user_favorites.php?user_id=<?php echo $user['user_id']; ?>" class="admin-action view">Показать избранное</a>
                                        <a href="user_orders.php?user_id=<?php echo $user['user_id']; ?>" class="admin-action view">Заказы</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="no-data">Нет пользователей с избранными товарами</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Раздел с последними заказами -->
        <div class="admin-section">
            <h2>Последние заказы</h2>
            
            <div class="table-responsive">
                <table class="admin-table orders-table">
                    <thead>
                        <tr>
                            <th>№ заказа</th>
                            <th>Дата</th>
                            <th>Клиент</th>
                            <th>Сумма, руб.</th>
                            <th>Статус</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($recent_orders_result->num_rows > 0): ?>
                            <?php while ($order = $recent_orders_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $order['order_id']; ?></td>
                                    <td><?php echo date('d.m.Y H:i', strtotime($order['created_at'])); ?></td>
                                    <td>
                                        <div><strong><?php echo htmlspecialchars($order['customer_name']); ?></strong></div>
                                        <div><?php echo htmlspecialchars($order['customer_email']); ?></div>
                                        <div><?php echo htmlspecialchars($order['customer_phone']); ?></div>
                                    </td>
                                    <td><?php echo number_format($order['total_amount'], 2, '.', ' '); ?> руб.</td>
                                    <td>
                                        <span class="status-badge status-<?php echo strtolower($order['status']); ?>">
                                            <?php 
                                            $status_labels = [
                                                'new' => 'Новый',
                                                'processing' => 'Обработка',
                                                'shipping' => 'Доставка',
                                                'completed' => 'Выполнен',
                                                'canceled' => 'Отменен'
                                            ];
                                            echo isset($status_labels[$order['status']]) ? $status_labels[$order['status']] : $order['status']; 
                                            ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="order_details.php?id=<?php echo $order['order_id']; ?>" class="admin-action view">Детали</a>
                                        <a href="order_edit.php?id=<?php echo $order['order_id']; ?>" class="admin-action edit">Редактировать</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="no-data">Нет данных о заказах</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="view-all-link">
                <a href="orders.php" class="button9">Все заказы</a>
            </div>
        </div>
    </div>
</div>

<!-- Страница просмотра деталей заказа - создаем order_details.php -->
<?php
// Сохраним в отдельный файл order_details.php
/*
<?php
// Включаем файл с подключением к базе данных
include 'db_connection.php';

// Проверка авторизации администратора
session_start();
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit;
}

// Проверяем наличие ID заказа
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: orders.php');
    exit;
}

$order_id = (int)$_GET['id'];

// Получаем информацию о заказе
$order_sql = "
    SELECT 
        o.*,
        DATE_FORMAT(o.created_at, '%d.%m.%Y %H:%i') as formatted_date
    FROM 
        orders o
    WHERE 
        o.order_id = ?
";

$stmt = $conn->prepare($order_sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_result = $stmt->get_result();

if ($order_result->num_rows === 0) {
    header('Location: orders.php');
    exit;
}

$order = $order_result->fetch_assoc();

// Получаем товары в заказе
$order_items_sql = "
    SELECT 
        oi.*,
        p.title as product_title,
        p.product_id,
        (SELECT pi.thumbnail FROM product_images pi WHERE pi.product_id = p.product_id ORDER BY pi.sort_order LIMIT 1) as thumbnail
    FROM 
        order_items oi
    LEFT JOIN 
        products p ON oi.product_id = p.product_id
    WHERE 
        oi.order_id = ?
    ORDER BY 
        oi.order_item_id
";

$items_stmt = $conn->prepare($order_items_sql);
$items_stmt->bind_param("i", $order_id);
$items_stmt->execute();
$order_items_result = $items_stmt->get_result();

// Включаем header админ-панели
include 'admin_header.php';
?>

<div class="admin-content">
    <div class="admin-header-actions">
        <a href="orders.php" class="button9 back-button">Назад к списку заказов</a>
        <a href="order_edit.php?id=<?php echo $order_id; ?>" class="button9">Редактировать заказ</a>
    </div>

    <h1>Заказ №<?php echo $order_id; ?></h1>
    
    <div class="order-details-container">
        <div class="order-summary">
            <h2>Информация о заказе</h2>
            <div class="order-info-grid">
                <div class="info-row">
                    <div class="info-label">Дата оформления:</div>
                    <div class="info-value"><?php echo $order['formatted_date']; ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Статус заказа:</div>
                    <div class="info-value">
                        <span class="status-badge status-<?php echo strtolower($order['status']); ?>">
                            <?php 
                            $status_labels = [
                                'new' => 'Новый',
                                'processing' => 'Обработка',
                                'shipping' => 'Доставка',
                                'completed' => 'Выполнен',
                                'canceled' => 'Отменен'
                            ];
                            echo isset($status_labels[$order['status']]) ? $status_labels[$order['status']] : $order['status']; 
                            ?>
                        </span>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Сумма заказа:</div>
                    <div class="info-value"><?php echo number_format($order['total_amount'], 2, '.', ' '); ?> руб.</div>
                </div>
            </div>
        </div>
        
        <div class="customer-info">
            <h2>Информация о клиенте</h2>
            <div class="customer-info-grid">
                <div class="info-row">
                    <div class="info-label">ФИО:</div>
                    <div class="info-value"><?php echo htmlspecialchars($order['customer_name']); ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Email:</div>
                    <div class="info-value"><?php echo htmlspecialchars($order['customer_email']); ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Телефон:</div>
                    <div class="info-value"><?php echo htmlspecialchars($order['customer_phone']); ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Адрес доставки:</div>
                    <div class="info-value"><?php echo nl2br(htmlspecialchars($order['delivery_address'])); ?></div>
                </div>
                <?php if (!empty($order['customer_comment'])): ?>
                <div class="info-row">
                    <div class="info-label">Комментарий:</div>
                    <div class="info-value"><?php echo nl2br(htmlspecialchars($order['customer_comment'])); ?></div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="order-items">
            <h2>Товары в заказе</h2>
            <div class="table-responsive">
                <table class="admin-table order-items-table">
                    <thead>
                        <tr>
                            <th>Товар</th>
                            <th>Наименование</th>
                            <th>Цена, руб.</th>
                            <th>Количество</th>
                            <th>Стоимость, руб.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($order_items_result->num_rows > 0): ?>
                            <?php while ($item = $order_items_result->fetch_assoc()): ?>
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
                                    <td><?php echo number_format($item['price'] * $item['quantity'], 2, '.', ' '); ?></td>
                                </tr>
                            <?php endwhile; ?>
                            <tr class="order-total-row">
                                <td colspan="4" class="text-right">Итого:</td>
                                <td><?php echo number_format($order['total_amount'], 2, '.', ' '); ?></td>
                            </tr>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="no-data">В заказе нет товаров</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="order-status-history">
            <h2>История статусов</h2>
            <?php
            // Получаем историю статусов заказа
            $status_history_sql = "
                SELECT 
                    os.*,
                    DATE_FORMAT(os.created_at, '%d.%m.%Y %H:%i') as formatted_date,
                    u.name as admin_name
                FROM 
                    order_status_history os
                LEFT JOIN 
                    users u ON os.user_id = u.user_id
                WHERE 
                    os.order_id = ?
                ORDER BY 
                    os.created_at DESC
            ";
            
            $history_stmt = $conn->prepare($status_history_sql);
            $history_stmt->bind_param("i", $order_id);
            $history_stmt->execute();
            $history_result = $history_stmt->get_result();
            ?>
            
            <div class="status-history-timeline">
                <?php if ($history_result->num_rows > 0): ?>
                    <?php while ($status = $history_result->fetch_assoc()): ?>
                        <div class="timeline-item">
                            <div class="timeline-point status-<?php echo strtolower($status['status']); ?>"></div>
                            <div class="timeline-content">
                                <div class="timeline-time"><?php echo $status['formatted_date']; ?></div>
                                <div class="timeline-status">
                                    Статус: <span class="status-badge status-<?php echo strtolower($status['status']); ?>">
                                        <?php echo isset($status_labels[$status['status']]) ? $status_labels[$status['status']] : $status['status']; ?>
                                    </span>
                                </div>
                                <div class="timeline-admin">Администратор: <?php echo htmlspecialchars($status['admin_name']); ?></div>
                                <?php if (!empty($status['comment'])): ?>
                                    <div class="timeline-comment"><?php echo nl2br(htmlspecialchars($status['comment'])); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="no-data">Нет данных об изменении статуса</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
*/
?>

<!-- Страница для просмотра избранного у пользователя - создаем user_favorites.php -->
<?php
// Сохраним в отдельный файл user_favorites.php
/*
<?php
// Включаем файл с подключением к базе данных
include 'db_connection.php';

// Проверка авторизации администратора
session_start();
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit;
}

// Проверяем наличие ID пользователя
if (!isset($_GET['user_id']) || !is_numeric($_GET['user_id'])) {
    header('Location: admin_favorites.php');
    exit;
}

$user_id = (int)$_GET['user_id'];

// Получаем информацию о пользователе
$user_sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($user_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();

if ($user_result->num_rows === 0) {
    header('Location: admin_favorites.php');
    exit;
}

$user = $user_result->fetch_assoc();

// Пагинация
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 15;
$offset = ($page - 1) * $per_page;

// Получаем список избранных товаров пользователя
$favorites_sql = "
    SELECT 
        f.favorite_id,
        f.created_at,
        p.product_id,
        p.title,
        p.price,
        (SELECT pi.thumbnail FROM product_images pi WHERE pi.product_id = p.product_id ORDER BY pi.sort_order LIMIT 1) as thumbnail
    FROM 
        favorites f
    JOIN 
        products p ON f.product_id = p.product_id
    WHERE 
        f.user_id = ?
    ORDER BY 
        f.created_at DESC
    LIMIT ?, ?
";

$stmt = $conn->prepare($favorites_sql);
$stmt->bind_param("iii", $user_id, $offset, $per_page);
$stmt->execute();
$favorites_result = $stmt->get_result();

// Получаем общее количество избранных товаров для пагинации
$count_stmt = $conn->prepare("SELECT COUNT(*) FROM favorites WHERE user_id = ?");
$count_stmt->bind_param("i", $user_id);
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_favorites = $count_result->fetch_row()[0];
$total_pages = ceil($total_favorites / $per_page);

// Включаем header админ-панели
include 'admin_header.php';
?>

<div class="admin-content">
    <div class="admin-header-actions">
        <a href="admin_favorites.php" class="button9 back-button">Назад к аналитике</a>
        <a href="user_edit.php?id=<?php echo $user_id; ?>" class="button9">Редактировать пользователя</a>
        <a href="user_orders.php?user_id=<?php echo $user_id; ?>" class="button9">Заказы пользователя</a>
    </div>

    <h1>Избранные товары пользователя</h1>
    
    <div class="user-info-panel">
        <div class="user-info">
            <p><strong>Пользователь:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Телефон:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
            <p><strong>Дата регистрации:</strong> <?php echo date('d.m.Y', strtotime($user['created_at'])); ?></p>
        </div>
        <div class="user-stats">
            <p><strong>Всего в избранном:</strong> <?php echo $total_favorites; ?> товаров</p>
            <?php
            // Получаем количество заказов пользователя
            $orders_stmt = $conn->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ? OR customer_email = ?");
            $orders_stmt->bind_param("is", $user_id, $user['email']);
            $orders_stmt->execute();
            $orders_count = $orders_stmt->get_result()->fetch_row()[0];
            ?>
            <p><strong>Заказов:</strong> <?php echo $orders_count; ?></p>
        </div>
    </div>
    
    <div class="favorites-list">
        <div class="table-responsive">
            <table class="admin-table favorites-table">
                <thead>
                    <tr>
                        <th>Товар</th>
                        <th>Наименование</th>
                        <th>Цена, руб.</th>
                        <th>Дата добавления</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($favorites_result->num_rows > 0): ?>
                        <?php while ($favorite = $favorites_result->fetch_assoc()): ?>
                            <tr>
                                <td class="product-image">
                                    <?php if ($favorite['thumbnail']): ?>
                                        <img src="<?php echo htmlspecialchars($favorite['thumbnail']); ?>" alt="<?php echo htmlspecialchars($favorite['title']); ?>">
                                    <?php else: ?>
                                        <img src="/api/placeholder/80/80" alt="Нет изображения">
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($favorite['title']); ?></td>
                                <td><?php echo number_format($favorite['price'], 2, '.', ' '); ?></td>
                                <td><?php echo date('d.m.Y H:i', strtotime($favorite['created_at'])); ?></td>
                                <td>
                                    <a href="product_edit.php?id=<?php echo $favorite['product_id']; ?>" class="admin-action edit">Редактировать товар</a>
                                    <a href="#" class="admin-action remove" onclick="removeFavorite(<?php echo $favorite['favorite_id']; ?>, <?php echo $user_id; ?>); return false;">Удалить из избранного</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="no-data">У пользователя нет избранных товаров</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Пагинация -->
        <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?user_id=<?php echo $user_id; ?>&page=<?php echo $page - 1; ?>" class="page-link">Назад</a>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <?php if ($i == $page): ?>
                        <span class="page-link active"><?php echo $i; ?></span>
                    <?php else: ?>
                        <a href="?user_id=<?php echo $user_id; ?>&page=<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
                
                <?php if ($page < $total_pages): ?>
                    <a href="?user_id=<?php echo $user_id; ?>&page=<?php echo $page + 1; ?>" class="page-link">Вперед</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    // Функция удаления товара из избранного
    function removeFavorite(favoriteId, userId) {
        if (confirm('Вы действительно хотите удалить этот товар из избранного пользователя?')) {
            fetch('remove_favorite_ajax.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'favorite_id=' + favoriteId + '&user_id=' + userId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Ошибка при удалении товара из избранного: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Произошла ошибка при удалении товара из избранного');
            });
        }
    }
</script>

<?php include 'admin_footer.php'; ?>
*/
?>

<?php include 'admin_footer.php'; ?>
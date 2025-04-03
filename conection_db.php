<?php
// Database configuration
$db_host = 'localhost';     // Database host (usually localhost)
$db_user = 'root';      // Database username
$db_pass = 'root';      // Database password
$db_name = 'online_shop';       // Database name

// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    // Log error to file instead of displaying it to users
    error_log('Database connection failed: ' . $conn->connect_error);
    
    // You can display a user-friendly message
    die('К сожалению, сервис временно недоступен. Пожалуйста, попробуйте позже.');
}

// Set charset to ensure proper handling of Cyrillic and special characters
$conn->set_charset('utf8mb4');

// Optionally, you can set timezone for consistent datetime values
date_default_timezone_set('Europe/Moscow');
?>
<?php
require_once 'config/config.php';
$db = Database::getInstance();

echo "<h1>Order System Diagnostic</h1>";

// 1. Check Table
try {
    $count = $db->fetchOne("SELECT COUNT(*) as c FROM orders")['c'];
    echo "<div style='color:green'>Orders table accessible. Current count: $count</div>";
} catch (Exception $e) {
    echo "<div style='color:red'>Orders table error: " . $e->getMessage() . "</div>";
    // Attempt create
    try {
        $sql = "CREATE TABLE IF NOT EXISTS orders (
            id INT AUTO_INCREMENT PRIMARY KEY,
            order_number VARCHAR(50) UNIQUE NOT NULL,
            user_id INT NULL,
            customer_name VARCHAR(100) NOT NULL,
            customer_email VARCHAR(100) NOT NULL,
            customer_phone VARCHAR(20) NOT NULL,
            shipping_address TEXT NOT NULL,
            shipping_cost DECIMAL(10,2) DEFAULT 0.00,
            subtotal DECIMAL(10,2) NOT NULL,
            total DECIMAL(10,2) NOT NULL,
            payment_method VARCHAR(50) DEFAULT 'cod',
            payment_status VARCHAR(20) DEFAULT 'pending',
            order_status VARCHAR(20) DEFAULT 'pending',
            notes TEXT NULL,
            tracking_number VARCHAR(100) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        $db->query($sql);
        echo "<div style='color:blue'>Created orders table.</div>";
    } catch (Exception $ex) {
        echo "<div style='color:red'>Failed to create table: " . $ex->getMessage() . "</div>";
    }
}

// 2. Insert Dummy Order
try {
    $orderData = [
        'order_number' => 'TEST-' . time(),
        'customer_name' => 'Diagnostic User',
        'customer_email' => 'test@example.com',
        'customer_phone' => '123456',
        'shipping_address' => json_encode(['address_line1' => 'Test St']),
        'subtotal' => 100,
        'total' => 100,
        'payment_status' => 'pending',
        'payment_method' => 'cod'
    ];
    $db->insert('orders', $orderData);
    $newCount = $db->fetchOne("SELECT COUNT(*) as c FROM orders")['c'];
    echo "<div style='color:green'>Inserted test order. New count: $newCount</div>";
} catch (Exception $e) {
    echo "<div style='color:red'>Insert failed: " . $e->getMessage() . "</div>";
}
?>
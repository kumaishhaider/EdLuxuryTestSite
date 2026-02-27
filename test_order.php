<?php
require 'config/config.php';
$orderModel = new Order();
$db = Database::getInstance();

// Mock cart items
$items = [
    [
        'product_id' => 5,
        'name' => 'Test Product',
        'quantity' => 1,
        'price' => 100,
        'subtotal' => 100
    ]
];

$orderData = [
    'customer_name' => 'Test User',
    'customer_email' => 'test@example.com',
    'customer_phone' => '971501234567',
    'shipping_address' => json_encode([
        'address_line1' => 'Test St',
        'city' => 'Dubai',
        'emirate' => 'Dubai',
        'country' => 'UAE'
    ]),
    'subtotal' => 100,
    'shipping_cost' => 0,
    'total' => 100,
    'payment_method' => 'cod',
    'payment_status' => 'pending',
    'order_status' => 'pending',
    'notes' => 'Test note',
    'user_id' => null
];

$result = $orderModel->create($orderData, $items);
print_r($result);

if (!$result['success']) {
    // If it failed, let's try to see if there was a DB error recorded
    echo "Last error log entry might be relevant if you can access it.\n";
}

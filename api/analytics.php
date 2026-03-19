<?php
require_once '../config/config.php';

// Set response header
header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get JSON data
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['event'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$db = Database::getInstance();
$eventType = $input['event'];
$productId = $input['product_id'] ?? null;
$quantity = $input['quantity'] ?? 1;
$userId = $_SESSION['user_id'] ?? null;
$sessionId = session_id();
$url = $input['url'] ?? $_SERVER['HTTP_REFERER'] ?? null;

try {
    $db->insert('analytics_events', [
        'event_type' => $eventType,
        'product_id' => $productId,
        'quantity' => $quantity,
        'user_id' => $userId,
        'session_id' => $sessionId,
        'url' => $url
    ]);
    
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

<?php
/**
 * Cart API Endpoint
 * Handles AJAX cart operations
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../config/config.php';

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? $_GET['action'] ?? null;

$cart = new Cart();
$response = ['success' => false];

try {
    switch ($action) {
        case 'add':
            $productId = $input['product_id'] ?? null;
            $quantity = $input['quantity'] ?? 1;

            if (!$productId) {
                $response['message'] = 'Product ID is required';
                break;
            }

            $result = $cart->add($productId, $quantity);
            $response = $result;
            $response['cart_count'] = $cart->getCount();
            break;

        case 'update':
            $productId = $input['product_id'] ?? null;
            $quantity = $input['quantity'] ?? 1;

            if (!$productId) {
                $response['message'] = 'Product ID is required';
                break;
            }

            $result = $cart->update($productId, $quantity);
            $response = $result;
            $response['cart_count'] = $cart->getCount();
            break;

        case 'remove':
            $productId = $input['product_id'] ?? null;

            if (!$productId) {
                $response['message'] = 'Product ID is required';
                break;
            }

            $result = $cart->remove($productId);
            $response = $result;
            $response['cart_count'] = $cart->getCount();
            break;

        case 'get':
            $summary = $cart->getSummary();
            $response = [
                'success' => true,
                'cart' => $summary
            ];
            break;

        case 'clear':
            $result = $cart->clear();
            $response = $result;
            $response['cart_count'] = 0;
            break;

        default:
            $response['message'] = 'Invalid action';
    }
} catch (Exception $e) {
    $response['message'] = 'An error occurred: ' . $e->getMessage();
    error_log('Cart API Error: ' . $e->getMessage());
}

echo json_encode($response);

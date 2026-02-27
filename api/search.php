<?php
/**
 * Search API Endpoint
 * Handles product search
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../config/config.php';

$query = $_GET['q'] ?? '';
$response = ['success' => false, 'products' => []];

if (strlen($query) >= 2) {
    $productModel = new Product();
    $products = $productModel->search($query, 10);

    $response = [
        'success' => true,
        'products' => $products
    ];
}

echo json_encode($response);

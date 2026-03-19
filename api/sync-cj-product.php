<?php
/**
 * CJ Dropshipping Product Sync API
 * 
 * Fetches product data from CJ Dropshipping API using user's API Key
 */

require_once __DIR__ . '/../config/config.php';

// Disable HTML error display for API
ini_set('display_errors', 0);
error_reporting(E_ALL);

header('Content-Type: application/json');

// Security Check
if (!Security::isAdminLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$productUrl = $input['url'] ?? '';
$apiKey = $input['api_key'] ?? '';
$categoryId = !empty($input['category_id']) ? (int)$input['category_id'] : null;
$stockQty = !empty($input['stock_quantity']) ? (int)$input['stock_quantity'] : 100;

if (empty($productUrl)) {
    echo json_encode(['success' => false, 'message' => 'Product URL or ID is required']);
    exit;
}

if (empty($apiKey)) {
    echo json_encode(['success' => false, 'message' => 'CJ API Key is required for syncing from CJ Dropshipping.']);
    exit;
}

// Save API Key in session for convenience
$_SESSION['cj_api_key'] = $apiKey;

try {
    $db = Database::getInstance();

    // 1. Extract PID from URL or use as ID
    $pid = '';
    if (preg_match('/p-([A-Za-z0-9\-]+)\.html/', $productUrl, $matches)) {
        $pid = $matches[1];
    } else {
        // Assume the whole thing is the ID if no URL pattern found
        $pid = trim(strip_tags($productUrl));
    }

    if (empty($pid)) {
        throw new Exception('Could not identify CJ Product ID from the URL.');
    }

    // 2. Get Access Token
    $accessToken = getCJAccessToken($apiKey);
    if (!$accessToken) {
        throw new Exception('Failed to authenticate with CJ Dropshipping. Please check your API Key.');
    }

    // 3. Get Product Details
    $productData = getCJProductDetails($pid, $accessToken);
    if (!$productData || empty($productData['productNameEn'])) {
        throw new Exception('Failed to fetch product details from CJ. PID: ' . $pid);
    }

    // 4. Map Data
    $title = $productData['productNameEn'];
    $description = $productData['description'] ?? '';
    // CJ description is often HTML stored in a different field or needs to be fetched
    // Let's use the provided description
    
    $price = (float)($productData['sellPrice'] ?? 0);
    // CJ sellPrice is often in USD or wholesale. We might need to adjust or let user edit.
    
    $slug = Helpers::generateSlug($title);
    
    // Check if slug exists
    $count = $db->count('products', 'slug = ?', [$slug]);
    if ($count > 0) {
        $slug = $slug . '-' . time();
    }

    // 5. Insert Product
    $productId = $db->insert('products', [
        'category_id' => $categoryId,
        'name' => $title,
        'slug' => $slug,
        'description' => $description,
        'extended_description' => $description,
        'short_description' => Helpers::truncate(strip_tags($description), 150),
        'price' => $price,
        'compare_price' => $price * 1.5, // Estimate
        'sku' => $productData['productSku'] ?? 'CJ-' . $pid,
        'stock_quantity' => $stockQty,
        'status' => 'active',
        'featured' => 0,
        'is_winning' => 0,
        'badge' => 'none',
        'video_url' => $productData['productVideo'] ?? '',
        'highlights' => '',
        'show_stock_bar' => 0,
        'show_countdown' => 0,
        'custom_buy_button' => '',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ]);

    // 6. Handle Images
    $images = [];
    if (!empty($productData['productImage'])) {
        // productImages can be a comma separated string or array
        if (is_string($productData['productImage'])) {
            $images = explode(',', $productData['productImage']);
        } else {
            $images = (array)$productData['productImage'];
        }
    }

    $uploadDir = UPLOADS_PATH . '/products';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $successCount = 0;
    foreach ($images as $index => $imageUrl) {
        $imageUrl = trim($imageUrl);
        if (empty($imageUrl)) continue;
        
        if (downloadProductImage($imageUrl, $productId, $index === 0, $db, $uploadDir)) {
            $successCount++;
        }
        
        // Limit to 8 images to prevent timeout
        if ($successCount >= 8) break;
    }

    echo json_encode([
        'success' => true, 
        'product_id' => $productId,
        'product_name' => $title
    ]);

} catch (Throwable $e) {
    error_log("CJ Sync Fatal Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'System Error: ' . $e->getMessage()]);
}

/**
 * Get CJ Access Token using API Key
 */
function getCJAccessToken($apiKey) {
    $url = "https://developers.cjdropshipping.com/api2.0/v1/authentication/getAccessToken";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['apiToken' => $apiKey]));
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    
    $response = curl_exec($ch);
    $data = json_decode($response, true);
    curl_close($ch);
    
    if (isset($data['data']['accessToken'])) {
        return $data['data']['accessToken'];
    }
    
    return null;
}

/**
 * Get Product Details from CJ API
 */
function getCJProductDetails($pid, $token) {
    // There are multiple versions, let's try the newer one first
    $url = "https://developers.cjdropshipping.com/api2.0/v1/product/query?pid=" . $pid;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'CJ-Access-Token: ' . $token
    ]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    
    $response = curl_exec($ch);
    $data = json_decode($response, true);
    curl_close($ch);
    
    return $data['data'] ?? null;
}

/**
 * Download and Scale Image
 */
function downloadProductImage($url, $productId, $isPrimary, $db, $uploadDir) {
    $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $savePath = $uploadDir . '/' . $filename;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
    $content = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200 && !empty($content)) {
        file_put_contents($savePath, $content);
        
        $db->query("INSERT INTO product_images (product_id, image_path, is_primary) VALUES (?, ?, ?)", [
            $productId,
            'products/' . $filename,
            $isPrimary ? 1 : 0
        ]);
        
        return true;
    }
    return false;
}

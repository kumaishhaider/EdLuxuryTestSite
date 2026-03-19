<?php
/**
 * Shopify Product Sync API
 * Fetches product JSON from a Shopify URL and imports it.
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

// Handle empty inputs correctly
$categoryId = !empty($input['category_id']) ? (int)$input['category_id'] : null;
$stockQty = !empty($input['stock_quantity']) ? (int)$input['stock_quantity'] : 100;

if (empty($productUrl)) {
    echo json_encode(['success' => false, 'message' => 'Product URL is required']);
    exit;
}

// Clean URL for Shopify JSON
// If it's a Shopify URL like example.com/products/name, we append .json
if (strpos($productUrl, '?') !== false) {
    $cleanUrl = explode('?', $productUrl)[0];
} else {
    $cleanUrl = $productUrl;
}

$jsonUrl = rtrim($cleanUrl, '/') . '.json';

try {
    $db = Database::getInstance();
    $isShopify = false;
    $title = '';
    $description = '';
    $price = 0;
    $comparePrice = null;
    $sku = '';
    $images = [];

    // Attempt 1: Fetch JSON (Shopify)
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $jsonUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200 && !empty($response)) {
        $data = json_decode($response, true);
        if (isset($data['product'])) {
            $isShopify = true;
            $source = $data['product'];
            $title = $source['title'] ?? '';
            $description = $source['body_html'] ?? '';
            $description = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $description);
            $price = $source['variants'][0]['price'] ?? 0;
            $comparePrice = $source['variants'][0]['compare_at_price'] ?? null;
            $sku = $source['variants'][0]['sku'] ?? '';
            
            if (isset($source['images']) && is_array($source['images'])) {
                foreach ($source['images'] as $imgData) {
                    if (isset($imgData['src'])) {
                        $images[] = $imgData['src'];
                    }
                }
            }
        }
    }

    // Attempt 2: Universal HTML Meta Tag Scraping (from everywhere)
    if (!$isShopify) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $productUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
        $html = curl_exec($ch);
        $httpCodeHtml = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCodeHtml !== 200 || empty($html)) {
            throw new Exception("Failed to fetch product data from URL. Ensure the URL is valid. (HTTP: $httpCodeHtml)");
        }

        libxml_use_internal_errors(true);
        $doc = new DOMDocument();
        @$doc->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        $xpath = new DOMXPath($doc);
        
        $titleNode = $xpath->query('//meta[@property="og:title"]/@content')->item(0);
        $title = $titleNode ? $titleNode->nodeValue : ($doc->getElementsByTagName('title')->item(0)->nodeValue ?? '');
        
        $descNode = $xpath->query('//meta[@property="og:description"]/@content')->item(0) ?: $xpath->query('//meta[@name="description"]/@content')->item(0);
        $description = $descNode ? $descNode->nodeValue : '';
        
        // Add a generic image if OG tag is present
        $imgNode = $xpath->query('//meta[@property="og:image"]/@content')->item(0);
        if ($imgNode) {
            $imageUrl = trim($imgNode->nodeValue);
            // Fix up URL for images
            if (strpos($imageUrl, 'http') !== 0) {
                $parsed = parse_url($productUrl);
                $base = $parsed['scheme'] . '://' . $parsed['host'];
                $imageUrl = rtrim($base, '/') . '/' . ltrim($imageUrl, '/');
            }
            $images[] = $imageUrl;
        }

        $priceNode = $xpath->query('//meta[@property="product:price:amount"]/@content')->item(0);
        if ($priceNode) {
            $price = floatval($priceNode->nodeValue);
        } else {
            $price = 0;
        }
    }

    if (empty($title)) {
        throw new Exception("Could not find product information at this URL.");
    }
    
    $slug = Helpers::generateSlug($title);
    
    // Ensure unique slug
    $existing = $db->fetchOne("SELECT id FROM products WHERE slug = ?", [$slug]);
    if ($existing) {
        $slug .= '-' . rand(100, 999);
    }

    if (empty($sku)) {
        $sku = 'SYNC-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
    }

    // Insert Product
    $productId = $db->insert('products', [
        'category_id' => $categoryId,
        'name' => $title,
        'slug' => $slug,
        'description' => $description,
        'extended_description' => $description, // Use same for extended
        'short_description' => Helpers::truncate(strip_tags($description), 150),
        'price' => $price,
        'compare_price' => $comparePrice,
        'sku' => $sku,
        'stock_quantity' => $stockQty,
        'status' => 'active',
        'featured' => 0,
        'is_winning' => 0,
        'badge' => 'none',
        'video_url' => '',
        'highlights' => '',
        'show_stock_bar' => 0,
        'show_countdown' => 0,
        'custom_buy_button' => '',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ]);

    // Handle Images
    if (!empty($images)) {
        foreach ($images as $index => $imgUrl) {
            downloadAndSaveProductImage($imgUrl, $productId, ($index === 0));
        }
    }

    echo json_encode([
        'success' => true, 
        'product_id' => $productId,
        'product_name' => $title
    ]);

} catch (Throwable $e) {
    error_log("Shopify Sync Fatal Error: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());
    echo json_encode(['success' => false, 'message' => 'System Error: ' . $e->getMessage()]);
}

/**
 * Downloads an image from URL and saves it to products directory
 */
function downloadAndSaveProductImage($url, $productId, $isPrimary = false) {
    $db = Database::getInstance();
    $uploadDir = UPLOADS_PATH . '/products';
    
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Get extension
    $pathInfo = pathinfo(parse_url($url, PHP_URL_PATH));
    $extension = !empty($pathInfo['extension']) ? $pathInfo['extension'] : 'jpg';
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $savePath = $uploadDir . '/' . $filename;

    // Download using CURL for more reliability
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
        
        // Save to DB
        $db->query("INSERT INTO product_images (product_id, image_path, is_primary) VALUES (?, ?, ?)", [
            $productId,
            'products/' . $filename,
            $isPrimary ? 1 : 0
        ]);
        
        return true;
    } else {
        error_log("Failed to download image: $url (HTTP $httpCode)");
        return false;
    }
}

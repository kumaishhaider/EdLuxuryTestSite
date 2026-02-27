<?php
/**
 * Edluxury Configuration File
 * 
 * This file contains all configuration settings for the Edluxury eCommerce platform.
 * Update these settings according to your hosting environment.
 */

// Error Reporting (Set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('Asia/Dubai');
setlocale(LC_ALL, 'en_US.utf8');

// Language & RTL Configuration
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'en'; // Changed to English as per new international requirement
}

if (isset($_GET['lang'])) {
    $allowed_langs = ['en', 'ar'];
    if (in_array($_GET['lang'], $allowed_langs)) {
        $_SESSION['lang'] = $_GET['lang'];
    }
}

define('CURRENT_LANG', $_SESSION['lang']);
define('IS_RTL', (CURRENT_LANG === 'ar'));
define('DIR_ATTR', IS_RTL ? 'rtl' : 'ltr');
define('LANG_ATTR', CURRENT_LANG);


// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'database_schema');
define('DB_USER', 'edluxury_user');
define('DB_PASS', 'edluxury_password');
define('DB_CHARSET', 'utf8mb4');

// Site Configuration
define('SITE_NAME', 'EdLuxury');

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];

define('SITE_URL', $protocol . '://' . $host);
define('ADMIN_URL', SITE_URL . '/admin');
define('ASSETS_URL', SITE_URL . '/assets');
define('UPLOADS_URL', SITE_URL . '/uploads');

// Directory Paths
define('ROOT_PATH', dirname(__DIR__));
define('INCLUDES_PATH', ROOT_PATH . '/includes');
define('UPLOADS_PATH', ROOT_PATH . '/uploads');
define('ADMIN_PATH', ROOT_PATH . '/admin');

// Session Configuration
define('SESSION_NAME', 'edluxury_session');
define('SESSION_LIFETIME', 7200); // 2 hours

// Security Configuration
define('HASH_ALGO', PASSWORD_BCRYPT);
define('HASH_COST', 10);
define('CSRF_TOKEN_NAME', 'csrf_token');
define('CSRF_TOKEN_LIFETIME', 3600); // 1 hour

// Email Configuration
// To enable real email: Set SMTP_ENABLED to true and add your Gmail App Password
// Get App Password: Google Account > Security > 2-Step Verification > App Passwords
define('SMTP_ENABLED', true); // Gmail SMTP enabled
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'edluxury32@gmail.com');
define('SMTP_PASSWORD', 'hgwt qtwy fxyd byju'); // Replace with Gmail App Password
define('SMTP_ENCRYPTION', 'tls');
define('FROM_EMAIL', 'edluxury32@gmail.com');
define('FROM_NAME', 'Edluxury');
define('ADMIN_EMAIL', 'edluxury32@gmail.com');
define('WHATSAPP_NUMBER', '923491697043'); // WhatsApp notification number

// Payment Gateway Configuration
define('PAYMENT_COD_ENABLED', true);
define('PAYMENT_STRIPE_ENABLED', false);
define('STRIPE_PUBLIC_KEY', 'pk_test_xxxxxxxxxxxxx');
define('STRIPE_SECRET_KEY', 'sk_test_xxxxxxxxxxxxx');
define('PAYMENT_PAYPAL_ENABLED', false);
define('PAYPAL_CLIENT_ID', 'xxxxxxxxxxxxx');
define('PAYPAL_SECRET', 'xxxxxxxxxxxxx');
define('PAYPAL_MODE', 'sandbox'); // sandbox or live

// Shipping Configuration
define('FREE_SHIPPING_THRESHOLD', 0); // 0 means always free
define('FLAT_SHIPPING_RATE', 0);
define('DEFAULT_CURRENCY', 'AED');
define('CURRENCY_SYMBOL', 'AED');

// Image Upload Configuration
define('MAX_IMAGE_SIZE', 5242880); // 5MB in bytes
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
define('THUMBNAIL_WIDTH', 300);
define('THUMBNAIL_HEIGHT', 300);
define('MEDIUM_WIDTH', 600);
define('MEDIUM_HEIGHT', 600);
define('LARGE_WIDTH', 1200);
define('LARGE_HEIGHT', 1200);

// Pagination
define('PRODUCTS_PER_PAGE', 12);
define('ORDERS_PER_PAGE', 20);
define('REVIEWS_PER_PAGE', 10);

// Cache Configuration
define('CACHE_ENABLED', false);
define('CACHE_LIFETIME', 3600); // 1 hour

// Admin Default Credentials (Change after first login!)
define('DEFAULT_ADMIN_USERNAME', 'admin');
define('DEFAULT_ADMIN_PASSWORD', 'admin123'); // Password: admin123

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    session_start();
}

// Auto-load classes
spl_autoload_register(function ($class) {
    $paths = [
        INCLUDES_PATH . '/' . $class . '.php',
        INCLUDES_PATH . '/models/' . $class . '.php',
        ADMIN_PATH . '/controllers/' . $class . '.php',
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

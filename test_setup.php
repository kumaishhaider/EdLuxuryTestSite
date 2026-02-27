<?php
// Enable full error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Edluxury Diagnostic Tool</h1>";

// 1. Check Config File
echo "<h2>1. Configuration Check</h2>";
if (file_exists('config/config.php')) {
    echo "<p style='color:green'>✅ config/config.php found.</p>";
    require_once 'config/config.php';
} else {
    echo "<p style='color:red'>❌ config/config.php NOT found!</p>";
    die("Cannot proceed without config file.");
}

// 2. Check Database Connection
echo "<h2>2. Database Connection Check</h2>";
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p style='color:green'>✅ Connected to database successfully!</p>";
} catch (PDOException $e) {
    echo "<p style='color:red'>❌ Database Connection Failed: " . $e->getMessage() . "</p>";
    echo "<p><strong>Possible Fix:</strong> Check your database credentials in <code>config/config.php</code> and ensure the database <code>" . DB_NAME . "</code> exists in phpMyAdmin.</p>";
}

// 3. Check Database Tables
if (isset($pdo)) {
    echo "<h2>3. Table Check</h2>";
    $tables = ['products', 'users', 'orders', 'categories'];
    foreach ($tables as $table) {
        try {
            $stmt = $pdo->query("SELECT 1 FROM $table LIMIT 1");
            echo "<p style='color:green'>✅ Table '$table' exists.</p>";
        } catch (PDOException $e) {
            echo "<p style='color:red'>❌ Table '$table' MISSING!</p>";
        }
    }
}

// 4. Check URL Configuration
echo "<h2>4. URL Configuration</h2>";
echo "<p>Detected SITE_URL: " . SITE_URL . "</p>";
echo "<p>Current Script: " . $_SERVER['PHP_SELF'] . "</p>";

// 5. Check Permissions
echo "<h2>5. Directory Permissions</h2>";
$uploads = __DIR__ . '/uploads';
if (is_writable($uploads)) {
    echo "<p style='color:green'>✅ Uploads directory is writable.</p>";
} else {
    if (file_exists($uploads)) {
        echo "<p style='color:red'>❌ Uploads directory is NOT writable.</p>";
    } else {
        echo "<p style='color:orange'>⚠️ Uploads directory does not exist. Attempting to create...</p>";
        if (mkdir($uploads, 0755, true)) {
            echo "<p style='color:green'>✅ Uploads directory created.</p>";
        } else {
            echo "<p style='color:red'>❌ Failed to create uploads directory.</p>";
        }
    }
}

echo "<hr>";
echo "<p>If you see green checkmarks above, your site back-end is working.</p>";
echo "<p><a href='index.php'>Go to Homepage</a></p>";

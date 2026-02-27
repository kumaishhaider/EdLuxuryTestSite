<?php
/**
 * Migration: Add video_url column to products table
 * Run this once to add video support to products.
 */

require_once 'config/config.php';

$db = Database::getInstance();

try {
    $db->query("ALTER TABLE products ADD COLUMN video_url VARCHAR(500) DEFAULT NULL");
    echo "<h2 style='color:green;'>✅ SUCCESS: video_url column added to products table!</h2>";
} catch (Exception $e) {
    if (strpos($e->getMessage(), 'Duplicate column') !== false) {
        echo "<h2 style='color:blue;'>ℹ️ Column already exists. No changes needed.</h2>";
    } else {
        echo "<h2 style='color:red;'>❌ ERROR: " . htmlspecialchars($e->getMessage()) . "</h2>";
    }
}

echo "<br><a href='admin/products.php'>← Go to Admin Products</a>";

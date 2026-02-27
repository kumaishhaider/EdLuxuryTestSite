<?php
/**
 * Migration: Add Arabic Name column to products table
 * This adds bilingual support for the UAE market
 */
require_once 'config/config.php';
$db = Database::getInstance();

echo "=== Adding Arabic Name Support ===\n\n";

// Step 1: Add name_ar column if it doesn't exist
try {
    $cols = $db->fetchAll("SHOW COLUMNS FROM products LIKE 'name_ar'");
    if (empty($cols)) {
        $db->query("ALTER TABLE products ADD COLUMN `name_ar` VARCHAR(300) DEFAULT NULL AFTER `name`");
        echo "✓ Added 'name_ar' column to products table\n";
    } else {
        echo "• Column 'name_ar' already exists\n";
    }
} catch (Exception $e) {
    echo "✗ Error adding column: " . $e->getMessage() . "\n";
}

// Step 2: Pre-populate Arabic translations for existing products
$translations = [
    'yellow-gold' => 'ذهب أصفر',
    'electric-scrubber' => 'فرشاة تنظيف كهربائية',
    'multifunctional-peeling-knife' => 'سكين تقشير متعدد الوظائف',
    'multifuctional-steam-iron' => 'مكواة بخارية متعددة الوظائف',
    'neck-massager-shoulder' => 'جهاز تدليك الرقبة والكتف',
    'tech-gadgets' => 'ماكينة قهوة إسبريسو (محمولة)',
];

foreach ($translations as $slug => $nameAr) {
    try {
        $product = $db->fetchOne("SELECT id, name FROM products WHERE slug = ?", [$slug]);
        if ($product) {
            $db->query("UPDATE products SET name_ar = ? WHERE slug = ?", [$nameAr, $slug]);
            echo "✓ Updated '{$product['name']}' → '{$nameAr}'\n";
        }
    } catch (Exception $e) {
        echo "✗ Error updating {$slug}: " . $e->getMessage() . "\n";
    }
}

// Also handle the vegetable slicer which has a URL as its slug
try {
    $product = $db->fetchOne("SELECT id, name FROM products WHERE name LIKE '%Vegetable Slicer%'");
    if ($product) {
        $db->query("UPDATE products SET name_ar = ? WHERE id = ?", ['آلة تقطيع الخضروات', $product['id']]);
        echo "✓ Updated '{$product['name']}' → 'آلة تقطيع الخضروات'\n";
    }
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

echo "\n=== Migration Complete ===\n";
echo "Arabic name support is now active!\n";

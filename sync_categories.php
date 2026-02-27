<?php
/**
 * Extra Data Inserter (Categories & Collections)
 */

require_once __DIR__ . '/config/config.php';
$db = Database::getInstance();

$items = [
    [
        'name' => 'Household items',
        'slug' => 'household-items',
        'desc' => 'Essential items for your daily household needs.',
        'order' => 5
    ],
    [
        'name' => 'Car Accessories',
        'slug' => 'car-accessories',
        'desc' => 'Premium accessories to enhance your driving experience.',
        'order' => 6
    ],
    [
        'name' => 'Smart Electronics',
        'slug' => 'smart-electronics',
        'desc' => 'The latest smart gadgets and electronics for your home.',
        'order' => 7
    ]
];

echo "Synchronizing Categories and Collections...\n";

foreach ($items as $item) {
    try {
        // Add to Categories if not exists
        $checkCat = $db->fetchOne("SELECT id FROM categories WHERE slug = ?", [$item['slug']]);
        if (!$checkCat) {
            $db->query(
                "INSERT INTO categories (name, slug, description, status, sort_order) VALUES (?, ?, ?, 'active', ?)",
                [$item['name'], $item['slug'], $item['desc'], $item['order']]
            );
            echo "Added Category: " . $item['name'] . "\n";
        }

        // Add to Collections if not exists
        $checkCol = $db->fetchOne("SELECT id FROM collections WHERE slug = ?", [$item['slug']]);
        if (!$checkCol) {
            $db->query(
                "INSERT INTO collections (name, slug, description, status, sort_order) VALUES (?, ?, ?, 'active', ?)",
                [$item['name'], $item['slug'], $item['desc'], $item['order']]
            );
            echo "Added Collection: " . $item['name'] . "\n";
        }
    } catch (Exception $e) {
        echo "Error adding " . $item['name'] . ": " . $e->getMessage() . "\n";
    }
}

echo "Finished.\n";
unlink(__FILE__);
?>
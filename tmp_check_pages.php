<?php
require_once 'c:/xampp/htdocs/Edluxury/config/config.php';
$db = Database::getInstance();
$slugs = ['shipping-delivery', 'returns-refunds'];
foreach ($slugs as $slug) {
    $page = $db->fetchOne("SELECT * FROM pages WHERE slug = ?", [$slug]);
    if ($page) {
        echo "Slug: $slug\n";
        echo "Title: " . $page['title'] . "\n";
        echo "Status: " . $page['status'] . "\n";
        echo "Content Length: " . strlen($page['content']) . "\n";
        echo "-------------------\n";
    } else {
        echo "Slug: $slug NOT FOUND\n";
        echo "-------------------\n";
    }
}

<?php
require_once 'config/config.php';
$db = Database::getInstance();
$result = $db->query("UPDATE products SET featured = 1, stock_quantity = 10, badge = 'new' WHERE name = 'Lip Oil'");
if ($result) {
    echo "SYNC_SUCCESS: Lip Oil is now Featured and In Stock.";
} else {
    echo "SYNC_FAILED: Could not update product.";
}
?>
<?php
require 'config/config.php';
$db = Database::getInstance();
$tables_res = $db->fetchAll("SHOW TABLES");
$all_tables = [];
foreach ($tables_res as $r)
    $all_tables[] = current($r);
echo "All tables: " . implode(", ", $all_tables) . "\n\n";

$tables = ['orders', 'order_items', 'payments', 'email_templates', 'settings'];
foreach ($tables as $table) {
    if (!in_array($table, $all_tables)) {
        echo "--- $table (MISSING) ---\n\n";
        continue;
    }
    echo "--- $table ---\n";
    $res = $db->fetchAll("DESCRIBE $table");
    foreach ($res as $r) {
        echo $r['Field'] . " (" . $r['Type'] . ") | Null: " . $r['Null'] . " | Default: " . $r['Default'] . "\n";
    }
    echo "\n";
}

echo "--- email_templates keys ---\n";
$res = $db->fetchAll("SELECT template_key FROM email_templates");
foreach ($res as $r)
    echo $r['template_key'] . "\n";

echo "\n--- latest order ---\n";
$res = $db->fetchOne("SELECT * FROM orders ORDER BY id DESC LIMIT 1");
if ($res)
    print_r($res);
else
    echo "No orders found.\n";

echo "\n--- some products ---\n";
$res = $db->fetchAll("SELECT id, name, status FROM products LIMIT 5");
foreach ($res as $r)
    echo $r['id'] . " | " . $r['name'] . " (" . $r['status'] . ")\n";

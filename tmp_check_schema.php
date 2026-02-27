<?php
require_once 'c:/xampp/htdocs/Edluxury/config/config.php';
$db = Database::getInstance();
$result = $db->fetchAll("DESCRIBE pages");
foreach ($result as $row) {
    echo $row['Field'] . " (" . $row['Type'] . ")\n";
}

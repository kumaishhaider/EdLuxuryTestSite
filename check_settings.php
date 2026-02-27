<?php
require_once 'config/config.php';
$db = Database::getInstance();

echo "<h1>Theme Settings Diag</h1>";
$settings = $db->fetchAll("SELECT * FROM theme_settings");
echo "<pre>";
print_r($settings);
echo "</pre>";

echo "<h1>Site URL Info</h1>";
echo "SITE_URL: " . SITE_URL . "<br>";
echo "HTTP_HOST: " . $_SERVER['HTTP_HOST'] . "<br>";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "<br>";

<?php
require_once 'config/config.php';

// Generate hash for 'admin123'
$password = 'admin123';
$hash = password_hash($password, PASSWORD_BCRYPT);

echo "New hash for 'admin123': " . $hash . "\n";

$db = Database::getInstance();

// Check if admin exists
$admin = $db->fetchOne("SELECT * FROM admins WHERE username = 'admin'");

if ($admin) {
    // Update existing admin
    $sql = "UPDATE admins SET password = ?, status = 'active' WHERE username = 'admin'";
    $db->query($sql, [$hash]);
    echo "Admin 'admin' password updated successfully.\n";
} else {
    // Insert new admin
    $sql = "INSERT INTO admins (username, password, email, full_name, role, status) VALUES (?, ?, ?, ?, ?, ?)";
    $db->query($sql, ['admin', $hash, 'admin@edluxury.com', 'Super Admin', 'super_admin', 'active']);
    echo "Admin 'admin' created successfully.\n";
}

echo "You can now login with:\nUsername: admin\nPassword: admin123";
?>
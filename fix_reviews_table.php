<?php
/**
 * Quick fix script to create product_reviews table if it doesn't exist
 * Run this once and then delete the file
 */

require_once 'config/config.php';

$db = Database::getInstance();

try {
    // Check if table exists
    $result = $db->fetchOne("SHOW TABLES LIKE 'product_reviews'");

    if (!$result) {
        // Create the table
        $sql = "CREATE TABLE `product_reviews` (
          `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
          `product_id` INT(11) UNSIGNED NOT NULL,
          `user_id` INT(11) UNSIGNED DEFAULT NULL,
          `name` VARCHAR(100) NOT NULL,
          `email` VARCHAR(100) NOT NULL,
          `rating` TINYINT(1) NOT NULL,
          `title` VARCHAR(200) DEFAULT NULL,
          `review` TEXT NOT NULL,
          `status` ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
          `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`),
          KEY `product_id` (`product_id`),
          KEY `user_id` (`user_id`),
          KEY `status` (`status`),
          FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
          FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $db->query($sql);
        echo "<p style='color:green; font-weight:bold;'>✅ product_reviews table created successfully!</p>";
    } else {
        echo "<p style='color:blue;'>ℹ️ product_reviews table already exists.</p>";
    }

    echo "<p><a href='index.php'>Go to Homepage</a></p>";

} catch (Exception $e) {
    echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
}
?>
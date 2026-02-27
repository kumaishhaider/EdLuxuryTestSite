<?php
/**
 * Delete Product Script
 */

require_once __DIR__ . '/../config/config.php';
Security::requireAdminLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Security::validateCSRFToken($_POST['csrf_token'] ?? '')) {
        Helpers::setFlash('error', 'Invalid security token');
    } else {
        $id = $_POST['id'] ?? null;
        if ($id) {
            $db = Database::getInstance();
            // Delete images files would be better, but for now just DB records
            // In a real app, unlink() the files from uploads/ folder too.

            // Delete logic (Cascading deletes handle related records if foreign keys are set up correctly)
            // But let's be safe
            $db->query("DELETE FROM product_images WHERE product_id = ?", [$id]);
            $db->query("DELETE FROM products WHERE id = ?", [$id]);

            Helpers::setFlash('success', 'Product deleted successfully');
        }
    }
}

Helpers::redirect(ADMIN_URL . '/products.php');

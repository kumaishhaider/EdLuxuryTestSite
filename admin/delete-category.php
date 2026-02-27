<?php
/**
 * Admin Delete Category Script
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

            // Check if products exist in this category
            $count = $db->fetchOne("SELECT COUNT(*) as count FROM products WHERE category_id = ?", [$id])['count'];

            if ($count > 0) {
                // Option 1: Prevent delete
                // Helpers::setFlash('error', 'Cannot delete category with existing products. Please reassign products first.');

                // Option 2: Uncategorize products (Nullify category_id) - Already handled by ON DELETE SET NULL in SQL, but let's be explicit if needed or just rely on DB constraints
                // Since our schema has ON DELETE SET NULL, we can just delete the category.
                $db->query("DELETE FROM categories WHERE id = ?", [$id]);
                Helpers::setFlash('success', 'Category deleted successfully. Products were uncategorized.');
            } else {
                $db->query("DELETE FROM categories WHERE id = ?", [$id]);
                Helpers::setFlash('success', 'Category deleted successfully');
            }
        }
    }
}

Helpers::redirect(ADMIN_URL . '/categories.php');

<?php
/**
 * Admin Delete Collection Script
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
            $db->query("DELETE FROM collections WHERE id = ?", [$id]);
            Helpers::setFlash('success', 'Collection deleted successfully');
        }
    }
}

Helpers::redirect(ADMIN_URL . '/collections.php');

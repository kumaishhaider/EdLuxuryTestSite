<?php
/**
 * Admin Logout
 */

require_once __DIR__ . '/../config/config.php';

Security::logoutAdmin();
Helpers::redirect(ADMIN_URL . '/login.php');

<?php
/**
 * User Logout
 */
require_once __DIR__ . '/config/config.php';

// Logout user
Security::logout();

// Clear cart if desired, or keep it. Usually we keep it or clear based on business logic. 
// For now, let's just redirect home.

Helpers::setFlash('success', 'You have been logged out successfully.');
header('Location: ' . SITE_URL);
exit;

<?php
/**
 * Security Utilities Class
 * 
 * Handles CSRF protection, input sanitization, and security functions
 */

class Security
{

    /**
     * Generate CSRF token
     */
    public static function generateCSRFToken()
    {
        if (
            !isset($_SESSION[CSRF_TOKEN_NAME]) ||
            !isset($_SESSION[CSRF_TOKEN_NAME . '_time']) ||
            time() - $_SESSION[CSRF_TOKEN_NAME . '_time'] > CSRF_TOKEN_LIFETIME
        ) {

            $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
            $_SESSION[CSRF_TOKEN_NAME . '_time'] = time();
        }

        return $_SESSION[CSRF_TOKEN_NAME];
    }

    /**
     * Validate CSRF token
     */
    public static function validateCSRFToken($token)
    {
        if (
            !isset($_SESSION[CSRF_TOKEN_NAME]) ||
            !isset($_SESSION[CSRF_TOKEN_NAME . '_time'])
        ) {
            return false;
        }

        if (time() - $_SESSION[CSRF_TOKEN_NAME . '_time'] > CSRF_TOKEN_LIFETIME) {
            return false;
        }

        return hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
    }

    /**
     * Get CSRF token input field
     */
    public static function getCSRFInput()
    {
        $token = self::generateCSRFToken();
        return '<input type="hidden" name="' . CSRF_TOKEN_NAME . '" value="' . $token . '">';
    }

    /**
     * Sanitize input string
     */
    public static function sanitize($input)
    {
        if (is_array($input)) {
            return array_map([self::class, 'sanitize'], $input);
        }

        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Validate email
     */
    public static function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Validate phone number (basic)
     */
    public static function validatePhone($phone)
    {
        return preg_match('/^[\d\s\+\-\(\)]+$/', $phone);
    }

    /**
     * Hash password
     */
    public static function hashPassword($password)
    {
        return password_hash($password, HASH_ALGO, ['cost' => HASH_COST]);
    }

    /**
     * Verify password
     */
    public static function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }

    /**
     * Generate random token
     */
    public static function generateToken($length = 32)
    {
        return bin2hex(random_bytes($length));
    }

    /**
     * Sanitize filename
     */
    public static function sanitizeFilename($filename)
    {
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '', $filename);
        return substr($filename, 0, 255);
    }

    /**
     * Validate image file
     */
    public static function validateImage($file)
    {
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return ['valid' => false, 'error' => 'No file uploaded'];
        }

        if ($file['size'] > MAX_IMAGE_SIZE) {
            return ['valid' => false, 'error' => 'File size exceeds maximum allowed'];
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, ALLOWED_IMAGE_TYPES)) {
            return ['valid' => false, 'error' => 'Invalid file type'];
        }

        return ['valid' => true];
    }

    /**
     * Prevent XSS in output
     */
    public static function escape($string)
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Check if user is logged in (customer)
     */
    public static function isLoggedIn()
    {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    /**
     * Check if admin is logged in
     */
    public static function isAdminLoggedIn()
    {
        return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
    }

    /**
     * Require login (customer)
     */
    public static function requireLogin()
    {
        if (!self::isLoggedIn()) {
            header('Location: ' . SITE_URL . '/login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
            exit;
        }
    }

    /**
     * Require admin login
     */
    public static function requireAdminLogin()
    {
        if (!self::isAdminLoggedIn()) {
            header('Location: ' . ADMIN_URL . '/login.php');
            exit;
        }
    }

    /**
     * Get current user ID
     */
    public static function getUserId()
    {
        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Get current admin ID
     */
    public static function getAdminId()
    {
        return $_SESSION['admin_id'] ?? null;
    }

    /**
     * Logout user
     */
    public static function logout()
    {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
    }

    /**
     * Logout admin
     */
    public static function logoutAdmin()
    {
        unset($_SESSION['admin_id']);
        unset($_SESSION['admin_username']);
        unset($_SESSION['admin_role']);
    }
}

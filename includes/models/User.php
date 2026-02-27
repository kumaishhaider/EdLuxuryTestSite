<?php
/**
 * User Model
 * 
 * Handles customer account operations
 */

class User
{
    private $db;
    private $email;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->email = new Email();
    }

    /**
     * Register new user
     */
    public function register($data)
    {
        // Validate email
        if (!Security::validateEmail($data['email'])) {
            return ['success' => false, 'message' => 'Invalid email address'];
        }

        // Check if email already exists
        if ($this->emailExists($data['email'])) {
            return ['success' => false, 'message' => 'Email already registered'];
        }

        // Hash password
        $data['password'] = Security::hashPassword($data['password']);

        try {
            $userId = $this->db->insert('users', $data);
            return ['success' => true, 'user_id' => $userId, 'message' => 'Registration successful'];
        } catch (Exception $e) {
            error_log("User registration failed: " . $e->getMessage());
            return ['success' => false, 'message' => 'Registration failed'];
        }
    }

    /**
     * Login user
     */
    public function login($email, $password)
    {
        $user = $this->getByEmail($email);

        if (!$user) {
            return ['success' => false, 'message' => 'Invalid credentials'];
        }

        if ($user['status'] !== 'active') {
            return ['success' => false, 'message' => 'Account is inactive'];
        }

        if (!Security::verifyPassword($password, $user['password'])) {
            return ['success' => false, 'message' => 'Invalid credentials'];
        }

        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];

        return ['success' => true, 'user' => $user, 'message' => 'Login successful'];
    }

    /**
     * Logout user
     */
    public function logout()
    {
        Security::logout();
        return ['success' => true, 'message' => 'Logged out successfully'];
    }

    /**
     * Get user by ID
     */
    public function getById($id)
    {
        return $this->db->fetchOne("SELECT * FROM users WHERE id = ?", [$id]);
    }

    /**
     * Get user by email
     */
    public function getByEmail($email)
    {
        return $this->db->fetchOne("SELECT * FROM users WHERE email = ?", [$email]);
    }

    /**
     * Check if email exists
     */
    public function emailExists($email)
    {
        return $this->db->count('users', 'email = ?', [$email]) > 0;
    }

    /**
     * Update user profile
     */
    public function updateProfile($userId, $data)
    {
        // Remove password and email from update data
        unset($data['password']);
        unset($data['email']);

        try {
            $this->db->update('users', $data, 'id = ?', [$userId]);
            return ['success' => true, 'message' => 'Profile updated successfully'];
        } catch (Exception $e) {
            error_log("Profile update failed: " . $e->getMessage());
            return ['success' => false, 'message' => 'Update failed'];
        }
    }

    /**
     * Change password
     */
    public function changePassword($userId, $currentPassword, $newPassword)
    {
        $user = $this->getById($userId);

        if (!Security::verifyPassword($currentPassword, $user['password'])) {
            return ['success' => false, 'message' => 'Current password is incorrect'];
        }

        $hashedPassword = Security::hashPassword($newPassword);
        $this->db->update('users', ['password' => $hashedPassword], 'id = ?', [$userId]);

        return ['success' => true, 'message' => 'Password changed successfully'];
    }

    /**
     * Request password reset
     */
    public function requestPasswordReset($email)
    {
        $user = $this->getByEmail($email);

        if (!$user) {
            // Don't reveal if email exists
            return ['success' => true, 'message' => 'If the email exists, a reset link has been sent'];
        }

        $resetToken = Security::generateToken();
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $this->db->update('users', [
            'reset_token' => $resetToken,
            'reset_token_expires' => $expiresAt
        ], 'id = ?', [$user['id']]);

        // Send email
        $this->email->sendPasswordReset($email, $resetToken, $user['first_name']);

        return ['success' => true, 'message' => 'If the email exists, a reset link has been sent'];
    }

    /**
     * Reset password with token
     */
    public function resetPassword($token, $newPassword)
    {
        $user = $this->db->fetchOne(
            "SELECT * FROM users WHERE reset_token = ? AND reset_token_expires > NOW()",
            [$token]
        );

        if (!$user) {
            return ['success' => false, 'message' => 'Invalid or expired reset token'];
        }

        $hashedPassword = Security::hashPassword($newPassword);

        $this->db->update('users', [
            'password' => $hashedPassword,
            'reset_token' => null,
            'reset_token_expires' => null
        ], 'id = ?', [$user['id']]);

        return ['success' => true, 'message' => 'Password reset successful'];
    }

    /**
     * Get user addresses
     */
    public function getAddresses($userId)
    {
        return $this->db->fetchAll(
            "SELECT * FROM shipping_addresses WHERE user_id = ? ORDER BY is_default DESC, created_at DESC",
            [$userId]
        );
    }

    /**
     * Add address
     */
    public function addAddress($userId, $addressData)
    {
        $addressData['user_id'] = $userId;

        // If this is set as default, unset other defaults
        if (!empty($addressData['is_default'])) {
            $this->db->update('shipping_addresses', ['is_default' => 0], 'user_id = ?', [$userId]);
        }

        try {
            $addressId = $this->db->insert('shipping_addresses', $addressData);
            return ['success' => true, 'address_id' => $addressId];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Failed to add address'];
        }
    }

    /**
     * Update address
     */
    public function updateAddress($addressId, $userId, $addressData)
    {
        // Verify address belongs to user
        $address = $this->db->fetchOne(
            "SELECT * FROM shipping_addresses WHERE id = ? AND user_id = ?",
            [$addressId, $userId]
        );

        if (!$address) {
            return ['success' => false, 'message' => 'Address not found'];
        }

        // If this is set as default, unset other defaults
        if (!empty($addressData['is_default'])) {
            $this->db->update('shipping_addresses', ['is_default' => 0], 'user_id = ?', [$userId]);
        }

        $this->db->update('shipping_addresses', $addressData, 'id = ?', [$addressId]);

        return ['success' => true, 'message' => 'Address updated'];
    }

    /**
     * Delete address
     */
    public function deleteAddress($addressId, $userId)
    {
        $this->db->delete('shipping_addresses', 'id = ? AND user_id = ?', [$addressId, $userId]);
        return ['success' => true, 'message' => 'Address deleted'];
    }

    /**
     * Get all users (admin)
     */
    public function getAll($page = 1, $perPage = 20)
    {
        $offset = ($page - 1) * $perPage;
        $total = $this->db->count('users');

        $users = $this->db->fetchAll(
            "SELECT id, email, first_name, last_name, phone, status, created_at 
             FROM users 
             ORDER BY created_at DESC 
             LIMIT {$perPage} OFFSET {$offset}"
        );

        return [
            'users' => $users,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => ceil($total / $perPage)
        ];
    }
}

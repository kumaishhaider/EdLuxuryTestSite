<?php
/**
 * Admin Profile / Account Settings
 */

require_once __DIR__ . '/../config/config.php';
Security::requireAdminLogin();

$pageTitle = 'My Account';
require_once 'includes/header.php';

$db = Database::getInstance();
$adminId = $_SESSION['admin_id'];

// Get current admin data
$adminData = $db->fetchOne("SELECT * FROM admins WHERE id = ?", [$adminId]);

// Handle Profile Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    if (!Security::validateCSRFToken($_POST['csrf_token'] ?? '')) {
        Helpers::setFlash('error', 'Invalid security token');
    } else {
        $username = trim($_POST['username']);
        $fullName = trim($_POST['full_name']);
        $email = trim($_POST['email']);

        if (empty($username) || empty($fullName) || empty($email)) {
            Helpers::setFlash('error', 'All profile fields are required');
        } else {
            try {
                $db->query("UPDATE admins SET username = ?, full_name = ?, email = ? WHERE id = ?", [$username, $fullName, $email, $adminId]);
                $_SESSION['admin_name'] = $fullName; // Update session
                Helpers::setFlash('success', 'Profile updated successfully');
                $adminData = $db->fetchOne("SELECT * FROM admins WHERE id = ?", [$adminId]); // Refresh data
            } catch (Exception $e) {
                Helpers::setFlash('error', 'Error: Username or Email might already be in use.');
            }
        }
    }
}

// Handle Password Change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    if (!Security::validateCSRFToken($_POST['csrf_token'] ?? '')) {
        Helpers::setFlash('error', 'Invalid security token');
    } else {
        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        if (!password_verify($currentPassword, $adminData['password'])) {
            Helpers::setFlash('error', 'Current password is incorrect');
        } elseif ($newPassword !== $confirmPassword) {
            Helpers::setFlash('error', 'New passwords do not match');
        } elseif (strlen($newPassword) < 6) {
            Helpers::setFlash('error', 'New password must be at least 6 characters');
        } else {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $db->query("UPDATE admins SET password = ? WHERE id = ?", [$hashedPassword, $adminId]);
            Helpers::setFlash('success', 'Password updated successfully');
        }
    }
}
?>

<div class="row g-4 justify-content-center">
    <!-- Profile Settings -->
    <div class="col-lg-5">
        <div class="admin-card">
            <h5 class="fw-bold mb-4"><i class="bi bi-person-circle me-2"></i>Account Details</h5>
            <form method="POST">
                <?php echo Security::getCSRFInput(); ?>

                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control"
                        value="<?php echo Security::escape($adminData['username']); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="full_name" class="form-control"
                        value="<?php echo Security::escape($adminData['full_name']); ?>" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control"
                        value="<?php echo Security::escape($adminData['email']); ?>" required>
                </div>

                <button type="submit" name="update_profile" class="btn btn-primary w-100 py-2 fw-bold">Update
                    Profile</button>
            </form>
        </div>
    </div>

    <!-- Security Settings -->
    <div class="col-lg-5">
        <div class="admin-card">
            <h5 class="fw-bold mb-4"><i class="bi bi-shield-lock me-2"></i>Change Password</h5>
            <form method="POST">
                <?php echo Security::getCSRFInput(); ?>

                <div class="mb-3">
                    <label class="form-label">Current Password</label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <input type="password" name="new_password" class="form-control" required minlength="6">
                </div>

                <div class="mb-4">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" name="confirm_password" class="form-control" required minlength="6">
                </div>

                <button type="submit" name="change_password" class="btn btn-dark w-100 py-2 fw-bold">Update
                    Password</button>
            </form>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
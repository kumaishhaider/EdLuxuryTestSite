<?php
/**
 * Admin Theme Settings
 */

require_once __DIR__ . '/../config/config.php';
Security::requireAdminLogin();

$pageTitle = 'Theme Settings';
require_once 'includes/header.php';

$db = Database::getInstance();

// Handle Save
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (Security::validateCSRFToken($_POST['csrf_token'] ?? '')) {
        // Save Settings
        $settings = [
            'site_name' => $_POST['site_name'],
            'site_tagline' => $_POST['site_tagline'],
            'contact_email' => $_POST['contact_email'],
            'contact_phone' => $_POST['contact_phone'],
            'primary_color' => $_POST['primary_color'],
            'logo_url' => $_POST['logo_url'],
            'favicon_url' => $_POST['favicon_url'],
        ];

        foreach ($settings as $key => $value) {
            $db->query("INSERT INTO theme_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)", [$key, $value]);
        }

        Helpers::setFlash('success', 'Settings updated successfully');
    }
}

// Get Current Settings
$rows = $db->fetchAll("SELECT * FROM theme_settings");
$currentSettings = [];
foreach ($rows as $row) {
    $currentSettings[$row['setting_key']] = $row['setting_value'];
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Theme Settings</h1>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white fw-bold">General Settings</div>
            <div class="card-body">
                <form method="POST">
                    <?php echo Security::getCSRFInput(); ?>

                    <div class="mb-3">
                        <label class="form-label">Site Name</label>
                        <input type="text" name="site_name" class="form-control"
                            value="<?php echo Security::escape($currentSettings['site_name'] ?? 'Edluxury'); ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tagline (Homepage Subtitle)</label>
                        <input type="text" name="site_tagline" class="form-control"
                            value="<?php echo Security::escape($currentSettings['site_tagline'] ?? ''); ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Contact Email</label>
                        <input type="email" name="contact_email" class="form-control"
                            value="<?php echo Security::escape($currentSettings['contact_email'] ?? ''); ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Contact Phone</label>
                        <input type="text" name="contact_phone" class="form-control"
                            value="<?php echo Security::escape($currentSettings['contact_phone'] ?? ''); ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Primary Color</label>
                        <input type="color" name="primary_color" class="form-control form-control-color"
                            value="<?php echo Security::escape($currentSettings['primary_color'] ?? '#000000'); ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Logo URL</label>
                        <input type="text" name="logo_url" class="form-control"
                            value="<?php echo Security::escape($currentSettings['logo_url'] ?? ''); ?>"
                            placeholder="e.g. assets/images/logo.png">
                        <div class="form-text">Leave empty to use site name as text.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Favicon URL</label>
                        <input type="text" name="favicon_url" class="form-control"
                            value="<?php echo Security::escape($currentSettings['favicon_url'] ?? ''); ?>"
                            placeholder="e.g. assets/images/favicon.png">
                    </div>

                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white fw-bold">Note</div>
            <div class="card-body">
                <p>To change the <strong>Hero Image</strong> (Main Banner), please replace the file at:</p>
                <code>assets/images/hero-illustration.svg</code>
                <p class="mt-3">Or create a 'Banners' section (coming soon).</p>

                <h5 class="mt-4">How to set "Winning Product"</h5>
                <ol>
                    <li>Go to <strong>Products</strong>.</li>
                    <li>Add or Edit a product.</li>
                    <li>Set <strong>Badge</strong> to "Hot" or check "Featured".</li>
                    <li>Upload your high-quality image.</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
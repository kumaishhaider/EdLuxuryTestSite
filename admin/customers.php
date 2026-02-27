<?php
/**
 * Admin Customers (Stub)
 */
require_once __DIR__ . '/../config/config.php';
Security::requireAdminLogin();
$pageTitle = 'Customers';
require_once 'includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Customers</h1>
</div>
<div class="card">
    <div class="card-body">Feature currently under development.</div>
</div>
<?php require_once 'includes/footer.php'; ?>
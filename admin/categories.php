<?php
/**
 * Admin Categories List
 */

require_once __DIR__ . '/../config/config.php';
Security::requireAdminLogin();

$pageTitle = 'Categories';
require_once 'includes/header.php';

$db = Database::getInstance();
$categories = $db->fetchAll("SELECT * FROM categories ORDER BY sort_order ASC, created_at DESC");
?>

<div class="d-flex justify-content-end mb-4">
    <a href="<?php echo Helpers::adminUrl('category-form.php'); ?>"
        class="btn btn-primary btn-primary-admin fw-bold shadow-sm">
        <i class="bi bi-plus-lg me-2"></i>New Category
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Image</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th>Sort Order</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($categories)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <p class="text-muted mb-0">No categories found.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($categories as $category): ?>
                            <tr>
                                <td class="ps-4">
                                    <?php if ($category['image']): ?>
                                        <img src="<?php echo Helpers::upload($category['image']); ?>" alt=""
                                            style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                    <?php else: ?>
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                            style="width: 40px; height: 40px;">
                                            <i class="bi bi-tag text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="fw-bold"><?php echo Security::escape($category['name']); ?></div>
                                </td>
                                <td><code><?php echo Security::escape($category['slug']); ?></code></td>
                                <td>
                                    <span
                                        class="badge bg-<?php echo $category['status'] === 'active' ? 'success' : 'secondary'; ?>">
                                        <?php echo ucfirst($category['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo $category['sort_order']; ?></td>
                                <td class="text-end pe-4">
                                    <a href="<?php echo Helpers::adminUrl('category-form.php?id=' . $category['id']); ?>"
                                        class="btn btn-sm btn-outline-primary me-1">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button onclick="confirmDelete(<?php echo $category['id']; ?>)"
                                        class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<form id="deleteForm" action="<?php echo Helpers::adminUrl('delete-category.php'); ?>" method="POST"
    style="display: none;">
    <?php echo Security::getCSRFInput(); ?>
    <input type="hidden" name="id" id="deleteId">
</form>

<script>
    function confirmDelete(id) {
        if (confirm('Are you sure you want to delete this category? Products in this category will be uncategorized.')) {
            document.getElementById('deleteId').value = id;
            document.getElementById('deleteForm').submit();
        }
    }
</script>

<?php require_once 'includes/footer.php'; ?>
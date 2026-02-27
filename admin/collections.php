<?php
/**
 * Admin Collections List
 */

require_once __DIR__ . '/../config/config.php';
Security::requireAdminLogin();

$pageTitle = 'Collections';
require_once 'includes/header.php';

$db = Database::getInstance();
$collections = $db->fetchAll("SELECT * FROM collections ORDER BY sort_order ASC, created_at DESC");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Collections</h1>
    <a href="<?php echo Helpers::adminUrl('collection-form.php'); ?>" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i>Add Collection
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
                    <?php if (empty($collections)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <p class="text-muted mb-0">No collections found.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($collections as $collection): ?>
                            <tr>
                                <td class="ps-4">
                                    <?php if ($collection['image']): ?>
                                        <img src="<?php echo Helpers::upload($collection['image']); ?>" alt=""
                                            style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                                    <?php else: ?>
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                            style="width: 60px; height: 60px;">
                                            <i class="bi bi-collection text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="fw-bold"><?php echo Security::escape($collection['name']); ?></div>
                                    <small class="text-muted text-truncate" style="max-width: 200px; display: block;">
                                        <?php echo Security::escape($collection['description']); ?>
                                    </small>
                                </td>
                                <td><code><?php echo Security::escape($collection['slug']); ?></code></td>
                                <td>
                                    <span
                                        class="badge bg-<?php echo $collection['status'] === 'active' ? 'success' : 'secondary'; ?>">
                                        <?php echo ucfirst($collection['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo $collection['sort_order']; ?></td>
                                <td class="text-end pe-4">
                                    <a href="<?php echo Helpers::adminUrl('collection-form.php?id=' . $collection['id']); ?>"
                                        class="btn btn-sm btn-outline-primary me-1">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button onclick="confirmDelete(<?php echo $collection['id']; ?>)"
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

<form id="deleteForm" action="<?php echo Helpers::adminUrl('delete-collection.php'); ?>" method="POST"
    style="display: none;">
    <?php echo Security::getCSRFInput(); ?>
    <input type="hidden" name="id" id="deleteId">
</form>

<script>
    function confirmDelete(id) {
        if (confirm('Are you sure you want to delete this collection?')) {
            document.getElementById('deleteId').value = id;
            document.getElementById('deleteForm').submit();
        }
    }
</script>

<?php require_once 'includes/footer.php'; ?>
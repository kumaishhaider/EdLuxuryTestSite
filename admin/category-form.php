<?php
/**
 * Admin Category Form (Add/Edit)
 */

require_once __DIR__ . '/../config/config.php';
Security::requireAdminLogin();

$id = $_GET['id'] ?? null;
$category = null;
$db = Database::getInstance();

if ($id) {
    $category = $db->fetchOne("SELECT * FROM categories WHERE id = ?", [$id]);
    if (!$category) {
        Helpers::setFlash('error', 'Category not found');
        Helpers::redirect(ADMIN_URL . '/categories.php');
    }
    $pageTitle = 'Edit Category';
} else {
    $pageTitle = 'Add New Category';
}

require_once 'includes/header.php';

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Security::validateCSRFToken($_POST['csrf_token'] ?? '')) {
        Helpers::setFlash('error', 'Invalid security token');
    } else {
        $data = [
            'name' => $_POST['name'],
            'slug' => $_POST['slug'] ?: Helpers::generateSlug($_POST['name']),
            'description' => $_POST['description'],
            'status' => $_POST['status'],
            'sort_order' => $_POST['sort_order'] ?? 0
        ];

        try {
            if ($id) {
                // Update
                $sql = "UPDATE categories SET name=?, slug=?, description=?, status=?, sort_order=? WHERE id=?";
                $params = array_values($data);
                $params[] = $id;
                $db->query($sql, $params);

                // Handle Image Upload
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $upload = Helpers::uploadImage($_FILES['image'], 'categories');
                    if ($upload['success']) {
                        $db->query("UPDATE categories SET image = ? WHERE id = ?", [$upload['path'], $id]);
                    }
                }

                Helpers::setFlash('success', 'Category updated successfully');
            } else {
                // Create
                $sql = "INSERT INTO categories (name, slug, description, status, sort_order) VALUES (?, ?, ?, ?, ?)";
                $db->query($sql, array_values($data));
                $newId = $db->lastInsertId();

                // Handle Image Upload
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $upload = Helpers::uploadImage($_FILES['image'], 'categories');
                    if ($upload['success']) {
                        $db->query("UPDATE categories SET image = ? WHERE id = ?", [$upload['path'], $newId]);
                    }
                }

                Helpers::setFlash('success', 'Category created successfully');
                Helpers::redirect(ADMIN_URL . '/categories.php');
            }
        } catch (Exception $e) {
            Helpers::setFlash('error', 'Error saving category: ' . $e->getMessage());
        }
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>
        <?php echo $pageTitle; ?>
    </h1>
    <a href="<?php echo Helpers::adminUrl('categories.php'); ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
            <?php echo Security::getCSRFInput(); ?>

            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">Category Name *</label>
                        <input type="text" name="name" class="form-control" required
                            value="<?php echo Security::escape($category['name'] ?? ''); ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control"
                            rows="4"><?php echo Security::escape($category['description'] ?? ''); ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">URL Slug</label>
                            <input type="text" name="slug" class="form-control"
                                value="<?php echo Security::escape($category['slug'] ?? ''); ?>">
                            <div class="form-text">Leave empty to auto-generate.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control"
                                value="<?php echo $category['sort_order'] ?? 0; ?>">
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h5 class="card-title">Status & Image</h5>
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="active" <?php echo ($category && $category['status'] === 'active') ? 'selected' : ''; ?>>Active</option>
                                    <option value="inactive" <?php echo ($category && $category['status'] === 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Image</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                            </div>
                            <?php if ($category && $category['image']): ?>
                                <div class="mt-2">
                                    <img src="<?php echo Helpers::upload($category['image']); ?>"
                                        class="img-fluid rounded border">
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <hr>
            <div class="d-flex justify-content-end gap-2">
                <a href="<?php echo Helpers::adminUrl('categories.php'); ?>" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary px-4">Save Category</button>
            </div>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
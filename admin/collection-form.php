<?php
/**
 * Admin Collection Form (Add/Edit)
 */

require_once __DIR__ . '/../config/config.php';
Security::requireAdminLogin();

$id = $_GET['id'] ?? null;
$collection = null;
$db = Database::getInstance();
$allProducts = $db->fetchAll("SELECT id, name, sku, price, primary_image FROM products WHERE status = 'active' ORDER BY name ASC");
$collectionProducts = [];

if ($id) {
    // Fetch products currently in this collection
    $cp = $db->fetchAll("SELECT product_id FROM product_collections WHERE collection_id = ?", [$id]);
    $collectionProducts = array_column($cp, 'product_id');

    $collection = $db->fetchOne("SELECT * FROM collections WHERE id = ?", [$id]);
    if (!$collection) {
        Helpers::setFlash('error', 'Collection not found');
        Helpers::redirect(ADMIN_URL . '/collections.php');
    }
    $pageTitle = 'Edit Collection';
} else {
    $pageTitle = 'Add New Collection';
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
                $sql = "UPDATE collections SET name=?, slug=?, description=?, status=?, sort_order=? WHERE id=?";
                $params = array_values($data);
                $params[] = $id;
                $db->query($sql, $params);

                // Handle Image Upload
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $upload = Helpers::uploadImage($_FILES['image'], 'collections');
                    if ($upload['success']) {
                    if ($upload['success']) {
                        $db->query("UPDATE collections SET image = ? WHERE id = ?", [$upload['path'], $id]);
                    }
                }

                // Handle Products (Update)
                // Remove all existing
                $db->query("DELETE FROM product_collections WHERE collection_id = ?", [$id]);
                // Add selected
                if (isset($_POST['products']) && is_array($_POST['products'])) {
                    foreach ($_POST['products'] as $pId) {
                        $db->query("INSERT INTO product_collections (product_id, collection_id) VALUES (?, ?)", [$pId, $id]);
                    }
                }

                Helpers::setFlash('success', 'Collection updated successfully');
            } else {
                // Create
                $sql = "INSERT INTO collections (name, slug, description, status, sort_order) VALUES (?, ?, ?, ?, ?)";
                $db->query($sql, array_values($data));
                $newId = $db->lastInsertId();

                // Handle Image Upload
                    if ($upload['success']) {
                        $db->query("UPDATE collections SET image = ? WHERE id = ?", [$upload['path'], $newId]);
                    }
                }

                // Handle Products (Create)
                if (isset($_POST['products']) && is_array($_POST['products'])) {
                    foreach ($_POST['products'] as $pId) {
                        $db->query("INSERT INTO product_collections (product_id, collection_id) VALUES (?, ?)", [$pId, $newId]);
                    }
                }

                Helpers::setFlash('success', 'Collection created successfully');
                Helpers::redirect(ADMIN_URL . '/collections.php');
            }
        } catch (Exception $e) {
            Helpers::setFlash('error', 'Error saving collection: ' . $e->getMessage());
        }
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>
        <?php echo $pageTitle; ?>
    </h1>
    <a href="<?php echo Helpers::adminUrl('collections.php'); ?>" class="btn btn-outline-secondary">
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
                        <label class="form-label">Collection Name *</label>
                        <input type="text" name="name" class="form-control" required
                            value="<?php echo Security::escape($collection['name'] ?? ''); ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control"
                            rows="4"><?php echo Security::escape($collection['description'] ?? ''); ?></textarea>
                        <div class="form-text">A brief description shown on the collection card.</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">URL Slug</label>
                            <input type="text" name="slug" class="form-control"
                                value="<?php echo Security::escape($collection['slug'] ?? ''); ?>">
                            <div class="form-text">Leave empty to auto-generate.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control"
                                value="<?php echo $collection['sort_order'] ?? 0; ?>">
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
                                    <option value="active" <?php echo ($collection && $collection['status'] === 'active') ? 'selected' : ''; ?>>Active</option>
                                    <option value="inactive" <?php echo ($collection && $collection['status'] === 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Cover Image</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                            </div>
                            <?php if ($collection && $collection['image']): ?>
                                <div class="mt-2 text-center">
                                    <img src="<?php echo Helpers::upload($collection['image']); ?>"
                                        class="img-fluid rounded border font-monospace" style="max-height: 200px;">
                                    <div class="small text-muted mt-1">Current Image</div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                </div>

                <!-- Product Selection Section -->
                <div class="col-12 mt-4">
                    <div class="card">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Products in this Collection</h5>
                            <small class="text-muted">Select products to display in this collection.</small>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                <table class="table table-hover table-sm mb-0">
                                    <thead class="bg-light sticky-top">
                                        <tr>
                                            <th width="40" class="ps-3"><i class="bi bi-check-lg"></i></th>
                                            <th>Product</th>
                                            <th>SKU</th>
                                            <th>Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($allProducts)): ?>
                                            <tr><td colspan="4" class="text-center py-3">No products available.</td></tr>
                                        <?php else: ?>
                                            <?php foreach ($allProducts as $p): ?>
                                                <tr>
                                                    <td class="ps-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="products[]" 
                                                                   value="<?php echo $p['id']; ?>" 
                                                                   id="prod_<?php echo $p['id']; ?>"
                                                                   <?php echo in_array($p['id'], $collectionProducts) ? 'checked' : ''; ?>>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <label class="form-check-label d-flex align-items-center gap-2" for="prod_<?php echo $p['id']; ?>" style="cursor: pointer; width: 100%;">
                                                            <?php if ($p['primary_image']): ?>
                                                                <img src="<?php echo Helpers::upload($p['primary_image']); ?>" style="width: 32px; height: 32px; object-fit: cover;" class="rounded">
                                                            <?php else: ?>
                                                                <div class="bg-light rounded" style="width: 32px; height: 32px;"></div>
                                                            <?php endif; ?>
                                                            <?php echo Security::escape($p['name']); ?>
                                                        </label>
                                                    </td>
                                                    <td class="text-muted small"><?php echo $p['sku']; ?></td>
                                                    <td><?php echo Helpers::formatPrice($p['price']); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr>
            <div class="d-flex justify-content-end gap-2">
                <a href="<?php echo Helpers::adminUrl('collections.php'); ?>" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary px-4">Save Collection</button>
            </div>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
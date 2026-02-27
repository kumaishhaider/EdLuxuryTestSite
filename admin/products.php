<?php
/**
 * Admin Products List
 */

require_once __DIR__ . '/../config/config.php';
Security::requireAdminLogin();

$pageTitle = 'Products';
require_once 'includes/header.php';

$db = Database::getInstance();
$page = $_GET['page'] ?? 1;
$search = $_GET['search'] ?? '';

// Build query
$where = "1=1";
$params = [];

if ($search) {
    $where .= " AND (name LIKE ? OR sku LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

// Pagination
$total = $db->fetchOne("SELECT COUNT(*) as count FROM products WHERE $where", $params)['count'];
$perPage = 10;
$totalPages = ceil($total / $perPage);
$offset = ($page - 1) * $perPage;

// Get products
$sql = "SELECT p.*, c.name as category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        WHERE $where 
        ORDER BY p.created_at DESC 
        LIMIT $perPage OFFSET $offset";
$products = $db->fetchAll($sql, $params);
?>

<div class="d-flex justify-content-end mb-4">
    <a href="<?php echo Helpers::adminUrl('product-form.php'); ?>"
        class="btn btn-primary btn-primary-admin fw-bold shadow-sm">
        <i class="bi bi-plus-lg me-2"></i>New Product
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="d-flex gap-2">
            <input type="text" name="search" class="form-control" placeholder="Search by name or SKU..."
                value="<?php echo Security::escape($search); ?>">
            <button type="submit" class="btn btn-secondary">Search</button>
            <?php if ($search): ?>
                <a href="<?php echo Helpers::adminUrl('products.php'); ?>" class="btn btn-outline-secondary">Clear</a>
            <?php endif; ?>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Image</th>
                        <th>Name</th>
                        <th>SKU</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($products)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <p class="text-muted mb-0">No products found.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($products as $product):
                            // Get primary image
                            $image = $db->fetchOne("SELECT image_path FROM product_images WHERE product_id = ? AND is_primary = 1", [$product['id']]);
                            $imagePath = $image ? $image['image_path'] : null;
                            ?>
                            <tr>
                                <td class="ps-4">
                                    <?php if ($imagePath): ?>
                                        <img src="<?php echo Helpers::upload($imagePath); ?>" alt=""
                                            style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                    <?php else: ?>
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                            style="width: 40px; height: 40px;">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="fw-bold">
                                        <?php echo Security::escape($product['name']); ?>
                                    </div>
                                    <?php if ($product['featured']): ?>
                                        <span class="badge bg-warning text-dark" style="font-size: 0.65em;">Featured</span>
                                    <?php endif; ?>
                                </td>
                                <td><small class="text-muted">
                                        <?php echo Security::escape($product['sku']); ?>
                                    </small></td>
                                <td>
                                    <?php echo Helpers::formatPrice($product['price']); ?>
                                </td>
                                <td>
                                    <?php if ($product['stock_quantity'] <= 0): ?>
                                        <span class="text-danger">Out of Stock</span>
                                    <?php else: ?>
                                        <?php echo $product['stock_quantity']; ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php echo Security::escape($product['category_name'] ?? '-'); ?>
                                </td>
                                <td>
                                    <span
                                        class="badge bg-<?php echo $product['status'] === 'active' ? 'success' : 'secondary'; ?>">
                                        <?php echo ucfirst($product['status']); ?>
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="<?php echo Helpers::adminUrl('product-form.php?id=' . $product['id']); ?>"
                                        class="btn btn-sm btn-outline-primary me-1">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button onclick="confirmDelete(<?php echo $product['id']; ?>)"
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

    <?php if ($totalPages > 1): ?>
        <div class="card-footer">
            <?php echo Helpers::paginationLinks($page, $totalPages, Helpers::adminUrl('products.php')); ?>
        </div>
    <?php endif; ?>
</div>

<form id="deleteForm" action="<?php echo Helpers::adminUrl('delete-product.php'); ?>" method="POST"
    style="display: none;">
    <?php echo Security::getCSRFInput(); ?>
    <input type="hidden" name="id" id="deleteId">
</form>

<script>
    function confirmDelete(id) {
        if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
            document.getElementById('deleteId').value = id;
            document.getElementById('deleteForm').submit();
        }
    }
</script>

<?php require_once 'includes/footer.php'; ?>
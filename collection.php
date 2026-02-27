<?php
/**
 * Collection Page
 * Displays products in a specific collection
 */

require_once 'config/config.php';

// Get slug from URL
$slug = $_GET['slug'] ?? '';
$page = $_GET['page'] ?? 1;
$sort = $_GET['sort'] ?? 'p.created_at DESC';

if (empty($slug)) {
    header('Location: ' . SITE_URL);
    exit;
}

$db = Database::getInstance();
$collection = $db->fetchOne("SELECT * FROM collections WHERE slug = ? AND status = 'active'", [$slug]);

if (!$collection) {
    // 404 check must happen before header output
    require_once 'includes/header.php';
    echo '<div class="container my-5 text-center py-5">
            <i class="bi bi-exclamation-circle display-1 text-muted"></i>
            <h1 class="mt-4">' . (CURRENT_LANG === 'ar' ? 'المجموعة غير موجودة' : 'Collection Not Found') . '</h1>
            <p class="lead text-muted">' . (CURRENT_LANG === 'ar' ? 'المجموعة التي تبحث عنها غير موجودة في سجلاتنا.' : 'The collection you are looking for does not exist.') . '</p>
            <a href="' . Helpers::url() . '" class="btn btn-primary mt-3">' . Helpers::translate('home') . '</a>
          </div>';

    require_once 'includes/footer.php';
    exit;
}

$pageTitle = $collection['name'];
require_once 'includes/header.php';

// Add page specific CSS
echo '<link rel="stylesheet" href="' . Helpers::asset('css/products-grid.css') . '">';

// Get products in this collection
$productModel = new Product();
$productsData = $productModel->getAll([
    'collection_id' => $collection['id'],
    'sort' => $sort
], $page, 12);
?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>
                <?php echo Security::escape($collection['name']); ?>
            </h1>
            <?php if ($collection['description']): ?>
                <p class="lead text-muted">
                    <?php echo Security::escape($collection['description']); ?>
                </p>
            <?php endif; ?>
        </div>
        <div class="col-md-4 <?php echo IS_RTL ? 'text-start' : 'text-end'; ?>">
            <select class="form-select" onchange="window.location.href='?slug=<?php echo $slug; ?>&sort=' + this.value">
                <option value="p.created_at DESC" <?php echo $sort === 'p.created_at DESC' ? 'selected' : ''; ?>>
                    <?php echo CURRENT_LANG === 'ar' ? 'الأحدث أولاً' : 'Newest First'; ?>
                </option>
                <option value="p.price ASC" <?php echo $sort === 'p.price ASC' ? 'selected' : ''; ?>>
                    <?php echo CURRENT_LANG === 'ar' ? 'السعر: من الأقل للأعلى' : 'Price: Low to High'; ?>
                </option>
                <option value="p.price DESC" <?php echo $sort === 'p.price DESC' ? 'selected' : ''; ?>>
                    <?php echo CURRENT_LANG === 'ar' ? 'السعر: من الأعلى للأقل' : 'Price: High to Low'; ?>
                </option>
                <option value="p.name ASC" <?php echo $sort === 'p.name ASC' ? 'selected' : ''; ?>>
                    <?php echo CURRENT_LANG === 'ar' ? 'الاسم: أ-ي' : 'Name: A-Z'; ?>
                </option>
            </select>
        </div>

    </div>

    <?php if (empty($productsData['products'])): ?>
        <div class="text-center py-5">
            <i class="bi bi-inbox" style="font-size: 80px; color: var(--text-muted);"></i>
            <h3 class="mt-4"><?php echo CURRENT_LANG === 'ar' ? 'لا توجد منتجات' : 'No products found'; ?></h3>
            <p class="text-muted">
                <?php echo CURRENT_LANG === 'ar' ? 'تفضل بزيارتنا لاحقاً لمشاهدة الجديد!' : 'Check back later for new products!'; ?>
            </p>
        </div>

    <?php else: ?>
        <div class="row g-4 justify-content-center">
            <?php foreach ($productsData['products'] as $product): ?>
                <div class="col-md-6 col-lg-3">
                    <div class="product-card">
                        <div class="product-image">
                            <?php if ($product['primary_image']): ?>
                                <img src="<?php echo Helpers::upload($product['primary_image']); ?>"
                                    alt="<?php echo Security::escape($product['name']); ?>">
                            <?php else: ?>
                                <img src="<?php echo Helpers::asset('images/placeholder-product.jpg'); ?>"
                                    alt="<?php echo Security::escape($product['name']); ?>">
                            <?php endif; ?>

                            <?php if ($product['badge'] !== 'none'): ?>
                                <span class="product-badge badge-<?php echo $product['badge']; ?>">
                                    <?php echo ucfirst($product['badge']); ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <div class="product-info">
                            <h3 class="product-name">
                                <a href="<?php echo Helpers::url('product/' . $product['slug']); ?>">
                                    <?php echo Security::escape($product['name']); ?>
                                </a>
                            </h3>
                            <div class="product-category">
                                <?php echo Security::escape($product['category_name'] ?? 'Premium choice'); ?>
                            </div>
                            <div class="product-price">
                                <?php if ($product['compare_price'] && $product['compare_price'] > $product['price']): ?>
                                    <span class="original-price">
                                        <?php echo Helpers::formatPrice($product['compare_price']); ?>
                                    </span>
                                <?php endif; ?>
                                <span class="fw-bold">
                                    <?php echo Helpers::formatPrice($product['price']); ?>
                                </span>
                            </div>
                            <div class="product-rating">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($productsData['total_pages'] > 1): ?>
            <div class="mt-5">
                <?php echo Helpers::paginationLinks($productsData['page'], $productsData['total_pages'], Helpers::url('collection/' . $slug)); ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
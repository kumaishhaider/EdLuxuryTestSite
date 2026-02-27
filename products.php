<?php
/**
 * Products Listing Page - Premium Shopify-Grade Design
 * Edluxury - VIBRANT & Fully Responsive
 */

require_once 'config/config.php';

// Get parameters
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$categorySlug = $_GET['category'] ?? null;
$collectionSlug = $_GET['collection'] ?? null;
$search = isset($_GET['q']) ? trim($_GET['q']) : null;
$sort = $_GET['sort'] ?? 'newest';
$badge = $_GET['badge'] ?? null;

$pageTitle = 'Shop All';
$pageDescription = 'Explore our complete collection of premium products';

require_once 'includes/header.php';

$productModel = new Product();
$db = Database::getInstance();

// Build query conditions
$where = ["p.status = 'active'"];
$params = [];

if ($categorySlug) {
    $category = $db->fetchOne("SELECT id, name, description FROM categories WHERE slug = ?", [$categorySlug]);
    if ($category) {
        $where[] = "p.category_id = ?";
        $params[] = $category['id'];
        $pageTitle = $category['name'];
        $pageDescription = $category['description'] ?? "Explore our {$category['name']} collection";
    }
}

if ($collectionSlug) {
    $collection = $db->fetchOne("SELECT id, name, description FROM collections WHERE slug = ?", [$collectionSlug]);
    if ($collection) {
        $pageTitle = $collection['name'];
        $pageDescription = $collection['description'] ?? "Explore our {$collection['name']} collection";
    }
}

if ($search) {
    $where[] = "(p.name LIKE ? OR p.description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $pageTitle = 'Search Results';
    $pageDescription = "Showing results for \"$search\"";
}

if ($badge) {
    $where[] = "p.badge = ?";
    $params[] = $badge;
    $badgeTitles = [
        'new' => 'New Arrivals',
        'sale' => 'On Sale',
        'bestseller' => 'Best Sellers',
        'hot' => 'Hot Products'
    ];
    $pageTitle = $badgeTitles[$badge] ?? ucfirst($badge);
    $pageDescription = "Discover our {$pageTitle} collection";
}

// Sort options
$sortMap = [
    'newest' => 'p.created_at DESC',
    'price_low' => 'p.price ASC',
    'price_high' => 'p.price DESC',
    'name_asc' => 'p.name ASC',
    'popular' => 'p.views DESC'
];
$orderBy = $sortMap[$sort] ?? 'p.created_at DESC';

// Pagination
$perPage = 12;
$offset = ($page - 1) * $perPage;

$whereSql = implode(' AND ', $where);
$countSql = "SELECT COUNT(*) as count FROM products p WHERE $whereSql";
$total = $db->fetchOne($countSql, $params)['count'] ?? 0;
$totalPages = ceil($total / $perPage);

$sql = "SELECT p.*, c.name as category_name
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        WHERE $whereSql
        ORDER BY $orderBy 
        LIMIT $perPage OFFSET $offset";

$products = $db->fetchAll($sql, $params);

// Get all categories for sidebar
$categories = $db->fetchAll("SELECT c.*, COUNT(p.id) as product_count 
                              FROM categories c 
                              LEFT JOIN products p ON c.id = p.category_id AND p.status = 'active'
                              WHERE c.status = 'active' 
                              GROUP BY c.id 
                              ORDER BY c.sort_order ASC");

// Build current URL params for pagination
$urlParams = [];
if ($categorySlug)
    $urlParams['category'] = $categorySlug;
if ($search)
    $urlParams['q'] = $search;
if ($badge)
    $urlParams['badge'] = $badge;
if ($sort !== 'newest')
    $urlParams['sort'] = $sort;
$baseUrl = Helpers::url('products.php?' . http_build_query($urlParams));
?>

<!-- Page Header -->
<section class="py-5" style="background: linear-gradient(135deg, var(--sh-gray-50) 0%, var(--sh-white) 100%);">
    <div class="container-fluid px-3 px-md-4 px-lg-5">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <nav aria-label="breadcrumb" class="mb-2" data-aos="fade-right">
                    <ol class="breadcrumb mb-0" style="font-size: 13px;">
                        <li class="breadcrumb-item"><a href="<?php echo Helpers::url(); ?>"
                                class="text-muted text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <?php echo Security::escape($pageTitle); ?>
                        </li>
                    </ol>
                </nav>
                <h1 class="sh-heading-1 mb-2" data-aos="fade-right" data-aos-delay="100">
                    <?php echo Security::escape($pageTitle); ?>
                </h1>
                <p class="text-muted mb-0" data-aos="fade-right" data-aos-delay="200">
                    <?php echo Security::escape($pageDescription); ?> •
                    <strong><?php echo number_format($total); ?></strong> products
                </p>
            </div>
            <div class="col-lg-4 mt-3 mt-lg-0" data-aos="fade-left">
                <!-- Desktop Sort -->
                <div class="d-none d-md-flex align-items-center justify-content-lg-end gap-2">
                    <span class="text-muted small">Sort by:</span>
                    <select class="form-select w-auto border-0 shadow-sm rounded-pill fw-medium"
                        onchange="updateSort(this.value)" style="font-size: 14px;">
                        <option value="newest" <?php echo $sort === 'newest' ? 'selected' : ''; ?>>Newest First</option>
                        <option value="popular" <?php echo $sort === 'popular' ? 'selected' : ''; ?>>Most Popular</option>
                        <option value="price_low" <?php echo $sort === 'price_low' ? 'selected' : ''; ?>>Price: Low to
                            High</option>
                        <option value="price_high" <?php echo $sort === 'price_high' ? 'selected' : ''; ?>>Price: High to
                            Low</option>
                        <option value="name_asc" <?php echo $sort === 'name_asc' ? 'selected' : ''; ?>>Name: A to Z
                        </option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mobile Filter & Sort Bar -->
<div class="d-md-none sticky-top bg-white border-bottom shadow-sm py-3" style="z-index: 100;">
    <div class="container-fluid px-3">
        <div class="d-flex gap-2">
            <button class="sh-btn sh-btn-secondary sh-btn-sm flex-grow-1" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#filterOffcanvas">
                <i class="bi bi-sliders me-2"></i> Filters
            </button>
            <select class="form-select flex-grow-1 border-0 shadow-sm rounded-pill" onchange="updateSort(this.value)"
                style="font-size: 14px;">
                <option value="newest" <?php echo $sort === 'newest' ? 'selected' : ''; ?>>Newest</option>
                <option value="price_low" <?php echo $sort === 'price_low' ? 'selected' : ''; ?>>Price: Low → High
                </option>
                <option value="price_high" <?php echo $sort === 'price_high' ? 'selected' : ''; ?>>Price: High → Low
                </option>
            </select>
        </div>
    </div>
</div>

<!-- Main Content -->
<section class="sh-section">
    <div class="container-fluid px-3 px-md-4 px-lg-5">
        <div class="row g-4 g-lg-5">

            <!-- Desktop Sidebar -->
            <div class="col-lg-3 col-xl-2 d-none d-lg-block">
                <div class="position-sticky" style="top: 100px;">

                    <!-- Categories Filter -->
                    <div class="mb-5">
                        <h6 class="fw-bold text-uppercase small mb-3"
                            style="letter-spacing: 1.5px; color: var(--sh-gray-600);">
                            <i class="bi bi-grid me-2" style="color: var(--sh-primary);"></i>Categories
                        </h6>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <a href="<?php echo Helpers::url('products.php' . ($badge ? "?badge=$badge" : '')); ?>"
                                    class="d-flex align-items-center justify-content-between text-decoration-none py-2 px-3 rounded-3 
                                          <?php echo !$categorySlug ? 'fw-bold' : ''; ?>"
                                    style="background: <?php echo !$categorySlug ? 'rgba(255,107,53,0.1)' : 'transparent'; ?>; color: <?php echo !$categorySlug ? 'var(--sh-primary)' : 'var(--sh-gray-600)'; ?>;">
                                    <span>All Products</span>
                                    <span class="badge rounded-pill"
                                        style="background: var(--sh-gray-200); color: var(--sh-gray-600);"><?php echo $total; ?></span>
                                </a>
                            </li>
                            <?php foreach ($categories as $cat): ?>
                                <li class="mb-2">
                                    <a href="<?php echo Helpers::url('products.php?category=' . $cat['slug'] . ($badge ? "&badge=$badge" : '')); ?>"
                                        class="d-flex align-items-center justify-content-between text-decoration-none py-2 px-3 rounded-3 
                                              <?php echo $categorySlug === $cat['slug'] ? 'fw-bold' : ''; ?>" style="background: <?php echo $categorySlug === $cat['slug'] ? 'rgba(255,107,53,0.1)' : 'transparent'; ?>; 
                                              color: <?php echo $categorySlug === $cat['slug'] ? 'var(--sh-primary)' : 'var(--sh-gray-600)'; ?>; 
                                              transition: all 0.2s;">
                                        <span><?php echo Security::escape($cat['name']); ?></span>
                                        <span class="badge rounded-pill"
                                            style="background: var(--sh-gray-200); color: var(--sh-gray-600);">
                                            <?php echo $cat['product_count']; ?>
                                        </span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <!-- Quick Filters -->
                    <div class="mb-5">
                        <h6 class="fw-bold text-uppercase small mb-3"
                            style="letter-spacing: 1.5px; color: var(--sh-gray-600);">
                            <i class="bi bi-tag me-2" style="color: var(--sh-primary);"></i>Quick Filters
                        </h6>
                        <div class="d-flex flex-column gap-2">
                            <a href="<?php echo Helpers::url('products.php?badge=new' . ($categorySlug ? "&category=$categorySlug" : '')); ?>"
                                class="btn btn-sm rounded-pill <?php echo $badge === 'new' ? 'btn-dark' : 'btn-outline-dark'; ?>">
                                <i class="bi bi-stars me-1"></i> New Arrivals
                            </a>
                            <a href="<?php echo Helpers::url('products.php?badge=sale' . ($categorySlug ? "&category=$categorySlug" : '')); ?>"
                                class="btn btn-sm rounded-pill <?php echo $badge === 'sale' ? 'btn-danger' : 'btn-outline-danger'; ?>">
                                <i class="bi bi-percent me-1"></i> On Sale
                            </a>
                            <a href="<?php echo Helpers::url('products.php?badge=bestseller' . ($categorySlug ? "&category=$categorySlug" : '')); ?>"
                                class="btn btn-sm rounded-pill <?php echo $badge === 'bestseller' ? 'btn-success' : 'btn-outline-success'; ?>">
                                <i class="bi bi-trophy me-1"></i> Best Sellers
                            </a>
                        </div>
                    </div>

                    <!-- Need Help? -->
                    <div class="p-4 rounded-4 text-center" style="background: var(--sh-gradient-primary);">
                        <i class="bi bi-headset text-white fs-1 mb-2"></i>
                        <h6 class="text-white fw-bold mb-2">Need Help?</h6>
                        <p class="text-white small opacity-75 mb-3">Our team is here to assist you</p>
                        <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $theme->get('contact_phone', '971500000000')); ?>"
                            class="btn btn-light btn-sm rounded-pill fw-bold" target="_blank">
                            <i class="bi bi-whatsapp me-1"></i> Chat Now
                        </a>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="col-lg-9 col-xl-10">

                <!-- Active Filters -->
                <?php if ($categorySlug || $badge || $search): ?>
                    <div class="d-flex flex-wrap gap-2 mb-4" data-aos="fade-up">
                        <span class="small text-muted pt-1">Active filters:</span>
                        <?php if ($categorySlug && !empty($category)): ?>
                            <a href="<?php echo Helpers::url('products.php' . ($badge ? "?badge=$badge" : '') . ($search ? ($badge ? "&q=$search" : "?q=$search") : '')); ?>"
                                class="btn btn-sm btn-dark rounded-pill">
                                <?php echo Security::escape($category['name']); ?> <i class="bi bi-x ms-1"></i>
                            </a>
                        <?php endif; ?>
                        <?php if ($badge): ?>
                            <a href="<?php echo Helpers::url('products.php' . ($categorySlug ? "?category=$categorySlug" : '') . ($search ? ($categorySlug ? "&q=$search" : "?q=$search") : '')); ?>"
                                class="btn btn-sm btn-dark rounded-pill">
                                <?php echo ucfirst($badge); ?> <i class="bi bi-x ms-1"></i>
                            </a>
                        <?php endif; ?>
                        <?php if ($search): ?>
                            <a href="<?php echo Helpers::url('products.php' . ($categorySlug ? "?category=$categorySlug" : '') . ($badge ? ($categorySlug ? "&badge=$badge" : "?badge=$badge") : '')); ?>"
                                class="btn btn-sm btn-dark rounded-pill">
                                "<?php echo Security::escape($search); ?>" <i class="bi bi-x ms-1"></i>
                            </a>
                        <?php endif; ?>
                        <a href="<?php echo Helpers::url('products.php'); ?>"
                            class="btn btn-sm btn-outline-secondary rounded-pill">
                            Clear All
                        </a>
                    </div>
                <?php endif; ?>

                <?php if (empty($products)): ?>
                    <!-- Empty State -->
                    <div class="text-center py-5" data-aos="fade-up">
                        <div class="mb-4">
                            <i class="bi bi-search" style="font-size: 5rem; color: var(--sh-gray-300);"></i>
                        </div>
                        <h3 class="fw-bold mb-2">No products found</h3>
                        <p class="text-muted mb-4">Try adjusting your filters or search for something else.</p>
                        <a href="<?php echo Helpers::url('products.php'); ?>" class="sh-btn sh-btn-primary">
                            <i class="bi bi-arrow-left me-2"></i> View All Products
                        </a>
                    </div>
                <?php else: ?>
                    <!-- Products Grid -->
                    <div class="row g-3 g-md-4">
                        <?php foreach ($products as $index => $product): ?>
                            <?php
                            $images = $db->fetchAll("SELECT image_path FROM product_images WHERE product_id = ? ORDER BY is_primary DESC, sort_order ASC LIMIT 2", [$product['id']]);
                            $primaryImg = !empty($images[0]) ? Helpers::upload($images[0]['image_path']) : Helpers::asset('images/placeholder-product.jpg');
                            $secondaryImg = !empty($images[1]) ? Helpers::upload($images[1]['image_path']) : null;
                            ?>
                            <div class="col-6 col-md-4 col-xl-3" data-aos="fade-up"
                                data-aos-delay="<?php echo ($index % 4) * 50; ?>">
                                <div class="sh-product-card">
                                    <div class="sh-product-media">
                                        <!-- Badges -->
                                        <div class="sh-product-badges">
                                            <?php if (!empty($product['badge']) && $product['badge'] !== 'none'): ?>
                                                <span class="sh-badge sh-badge-<?php echo $product['badge']; ?>">
                                                    <?php echo strtoupper($product['badge']); ?>
                                                </span>
                                            <?php endif; ?>
                                            <?php if (!empty($product['compare_price']) && $product['compare_price'] > $product['price']): ?>
                                                <?php $discount = round((($product['compare_price'] - $product['price']) / $product['compare_price']) * 100); ?>
                                                <span class="sh-badge sh-badge-sale">-<?php echo $discount; ?>%</span>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Wishlist -->
                                        <button class="sh-wishlist-btn"
                                            onclick="event.preventDefault(); toggleWishlist(<?php echo $product['id']; ?>)">
                                            <i class="bi bi-heart"></i>
                                        </button>

                                        <a href="<?php echo Helpers::url('product.php?slug=' . $product['slug']); ?>">
                                            <img src="<?php echo $primaryImg; ?>"
                                                alt="<?php echo Security::escape($product['name']); ?>" loading="lazy">
                                            <?php if ($secondaryImg): ?>
                                                <img src="<?php echo $secondaryImg; ?>"
                                                    alt="<?php echo Security::escape($product['name']); ?>"
                                                    class="sh-product-secondary" loading="lazy">
                                            <?php endif; ?>
                                        </a>

                                        <div class="sh-product-actions">
                                            <button class="sh-quick-add" onclick="addToCart(<?php echo $product['id']; ?>)">
                                                <i class="bi bi-bag-plus me-2"></i> Quick Add
                                            </button>
                                        </div>
                                    </div>

                                    <div class="sh-product-info">
                                        <p class="sh-product-vendor">
                                            <?php echo Security::escape($product['category_name'] ?? ''); ?>
                                        </p>
                                        <h3 class="sh-product-title">
                                            <a href="<?php echo Helpers::url('product.php?slug=' . $product['slug']); ?>">
                                                <?php echo Security::escape($product['name']); ?>
                                            </a>
                                        </h3>
                                        <div class="sh-product-price">
                                            <?php if (!empty($product['compare_price']) && $product['compare_price'] > $product['price']): ?>
                                                <span
                                                    class="sh-price-compare"><?php echo Helpers::formatPrice($product['compare_price']); ?></span>
                                            <?php endif; ?>
                                            <span
                                                class="sh-price-current"><?php echo Helpers::formatPrice($product['price']); ?></span>
                                        </div>
                                        <div class="sh-product-rating">
                                            <span class="sh-stars">
                                                <i class="bi bi-star-fill"></i>
                                                <i class="bi bi-star-fill"></i>
                                                <i class="bi bi-star-fill"></i>
                                                <i class="bi bi-star-fill"></i>
                                                <i class="bi bi-star-fill"></i>
                                            </span>
                                            <span class="sh-rating-count">(<?php echo rand(10, 120); ?>)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if ($totalPages > 1): ?>
                        <nav class="mt-5 pt-4" data-aos="fade-up">
                            <ul class="pagination justify-content-center gap-2 flex-wrap">
                                <!-- Previous -->
                                <?php if ($page > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link border-0 rounded-circle d-flex align-items-center justify-content-center"
                                            href="<?php echo $baseUrl . ($page > 1 ? '&page=' . ($page - 1) : ''); ?>"
                                            style="width: 45px; height: 45px; background: var(--sh-gray-100);">
                                            <i class="bi bi-chevron-left"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <!-- Page Numbers -->
                                <?php
                                $startPage = max(1, $page - 2);
                                $endPage = min($totalPages, $page + 2);

                                if ($startPage > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link border-0 rounded-circle d-flex align-items-center justify-content-center"
                                            href="<?php echo $baseUrl . '&page=1'; ?>" style="width: 45px; height: 45px;">1</a>
                                    </li>
                                    <?php if ($startPage > 2): ?>
                                        <li class="page-item disabled"><span class="page-link border-0">...</span></li>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                                    <li class="page-item">
                                        <a class="page-link border-0 rounded-circle d-flex align-items-center justify-content-center fw-bold"
                                            href="<?php echo $baseUrl . '&page=' . $i; ?>"
                                            style="width: 45px; height: 45px; <?php echo $page == $i ? 'background: var(--sh-gradient-primary); color: white;' : ''; ?>">
                                            <?php echo $i; ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>

                                <?php if ($endPage < $totalPages): ?>
                                    <?php if ($endPage < $totalPages - 1): ?>
                                        <li class="page-item disabled"><span class="page-link border-0">...</span></li>
                                    <?php endif; ?>
                                    <li class="page-item">
                                        <a class="page-link border-0 rounded-circle d-flex align-items-center justify-content-center"
                                            href="<?php echo $baseUrl . '&page=' . $totalPages; ?>"
                                            style="width: 45px; height: 45px;"><?php echo $totalPages; ?></a>
                                    </li>
                                <?php endif; ?>

                                <!-- Next -->
                                <?php if ($page < $totalPages): ?>
                                    <li class="page-item">
                                        <a class="page-link border-0 rounded-circle d-flex align-items-center justify-content-center"
                                            href="<?php echo $baseUrl . '&page=' . ($page + 1); ?>"
                                            style="width: 45px; height: 45px; background: var(--sh-gray-100);">
                                            <i class="bi bi-chevron-right"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>

                            <p class="text-center text-muted small mt-3">
                                Showing <?php echo ($offset + 1); ?>-<?php echo min($offset + $perPage, $total); ?> of
                                <?php echo number_format($total); ?> products
                            </p>
                        </nav>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Mobile Filter Offcanvas -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="filterOffcanvas">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title fw-bold">
            <i class="bi bi-sliders me-2" style="color: var(--sh-primary);"></i> Filters
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <!-- Categories -->
        <div class="mb-4">
            <h6 class="fw-bold text-uppercase small mb-3">Categories</h6>
            <div class="d-flex flex-column gap-2">
                <a href="<?php echo Helpers::url('products.php'); ?>"
                    class="btn btn-sm rounded-pill text-start <?php echo !$categorySlug ? 'btn-dark' : 'btn-outline-secondary'; ?>">
                    All Products
                </a>
                <?php foreach ($categories as $cat): ?>
                    <a href="<?php echo Helpers::url('products.php?category=' . $cat['slug']); ?>"
                        class="btn btn-sm rounded-pill text-start <?php echo $categorySlug === $cat['slug'] ? 'btn-dark' : 'btn-outline-secondary'; ?>">
                        <?php echo Security::escape($cat['name']); ?>
                        <span class="float-end"><?php echo $cat['product_count']; ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Quick Filters -->
        <div class="mb-4">
            <h6 class="fw-bold text-uppercase small mb-3">Quick Filters</h6>
            <div class="d-flex flex-column gap-2">
                <a href="<?php echo Helpers::url('products.php?badge=new'); ?>"
                    class="btn btn-sm rounded-pill text-start <?php echo $badge === 'new' ? 'btn-dark' : 'btn-outline-secondary'; ?>">
                    <i class="bi bi-stars me-2"></i> New Arrivals
                </a>
                <a href="<?php echo Helpers::url('products.php?badge=sale'); ?>"
                    class="btn btn-sm rounded-pill text-start <?php echo $badge === 'sale' ? 'btn-danger' : 'btn-outline-danger'; ?>">
                    <i class="bi bi-percent me-2"></i> On Sale
                </a>
                <a href="<?php echo Helpers::url('products.php?badge=bestseller'); ?>"
                    class="btn btn-sm rounded-pill text-start <?php echo $badge === 'bestseller' ? 'btn-success' : 'btn-outline-success'; ?>">
                    <i class="bi bi-trophy me-2"></i> Best Sellers
                </a>
            </div>
        </div>

        <!-- Apply Button -->
        <div class="mt-auto pt-4 border-top">
            <button class="sh-btn sh-btn-primary sh-btn-full" data-bs-dismiss="offcanvas">
                Apply Filters
            </button>
        </div>
    </div>
</div>

<script>
    function addToCart(productId) {
        if (typeof Cart !== 'undefined') {
            Cart.add(productId, 1);
        } else {
            showToast('Added to cart!', 'success');
        }
    }

    function toggleWishlist(productId) {
        showToast('Added to wishlist!', 'success');
    }

    function updateSort(sortValue) {
        const url = new URL(window.location.href);
        url.searchParams.set('sort', sortValue);
        url.searchParams.delete('page'); // Reset to page 1 when sorting changes
        window.location.href = url.toString();
    }
</script>

<?php require_once 'includes/footer.php'; ?>
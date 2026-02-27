<?php
/**
 * Premium Header - Shopify-Grade Design
 * Edluxury - VIBRANT & Responsive
 */

require_once __DIR__ . '/../config/config.php';

$theme = Theme::getInstance();
$siteName = $theme->get('site_name', 'Edluxury');
$logoUrl = $theme->get('logo_url');

// Get cart count for badge
$cartCount = 0;
if (class_exists('Cart')) {
    $cart = Cart::getInstance();
    $cartCount = $cart->getItemCount();
}

// Get categories for mega menu
$db = Database::getInstance();
$categories = $db->fetchAll("SELECT * FROM categories WHERE status = 'active' ORDER BY sort_order ASC LIMIT 8");
?>
<!DOCTYPE html>
<html lang="<?php echo CURRENT_LANG; ?>" dir="<?php echo IS_RTL ? 'rtl' : 'ltr'; ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="<?php echo $theme->get('meta_description', 'Edluxury - Premium eCommerce Store in UAE'); ?>">
    <meta name="keywords" content="<?php echo $theme->get('meta_keywords', 'shop, ecommerce, uae, dubai, premium'); ?>">

    <title><?php echo isset($pageTitle) ? Security::escape($pageTitle) . ' | ' : ''; ?><?php echo $siteName; ?></title>

    <!-- Favicon -->
    <?php
    $faviconUrl = $theme->get('favicon_url');
    if ($faviconUrl) {
        $faviconPath = (strpos($faviconUrl, 'http') === 0) ? $faviconUrl : Helpers::asset($faviconUrl);
    } else {
        $faviconPath = Helpers::asset('images/favicon.png');
    }
    ?>
    <link rel="icon" type="image/png" href="<?php echo $faviconPath; ?>">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&family=Noto+Sans+Arabic:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Premium Design System -->
    <link href="<?php echo Helpers::asset('css/shopify-premium.css'); ?>" rel="stylesheet">

    <!-- Main Stylesheet -->
    <link href="<?php echo Helpers::asset('css/main.css'); ?>" rel="stylesheet">

    <?php if (IS_RTL): ?>
        <link href="<?php echo Helpers::asset('css/rtl.css'); ?>" rel="stylesheet">
    <?php endif; ?>

    <!-- Global JS Variables -->
    <script>
        const SITE_URL = '<?php echo SITE_URL; ?>';
        const CSRF_TOKEN = '<?php echo $_SESSION[CSRF_TOKEN_NAME] ?? ''; ?>';
    </script>
</head>

<body class="shopify-premium">

    <!-- 1. PREMIUM Announcement Bar -->
    <div class="sh-announcement-bar" style="background: var(--sh-gradient-gold);">
        <div class="container-fluid px-3 lg:px-5">
            <div class="d-none d-md-flex align-items-center">
                <a href="tel:<?php echo $theme->get('contact_phone', '+971500000000'); ?>"
                    class="sh-announcement-link small">
                    <i class="bi bi-telephone me-1"></i> <?php echo $theme->get('contact_phone', '+971 50 000 0000'); ?>
                </a>
            </div>
            <div class="sh-announcement-text overflow-hidden" style="height: 24px;">
                <div class="announcement-slider">
                    <div class="announcement-item">
                        <span>🔥 <strong>FREE SHIPPING</strong> on all orders across UAE! <a
                                href="<?php echo Helpers::url('products.php'); ?>"
                                class="text-white text-decoration-underline ms-1">Shop Now</a></span>
                    </div>
                    <div class="announcement-item">
                        <span>✨ <strong>100% AUTHENTIC</strong> Products Guaranteed! 🏆</span>
                    </div>
                    <div class="announcement-item">
                        <span>🚚 <strong>EXPRESS DELIVERY</strong> - Standard in 24-48h! 🇦🇪</span>
                    </div>
                    <div class="announcement-item">
                        <span>💰 <strong>CASH ON DELIVERY</strong> Available Everywhere! ✅</span>
                    </div>
                </div>
            </div>
            <div class="d-none d-md-flex align-items-center gap-3 invisible">
                <!-- Placeholder to keep center alignment -->
                <span class="small opacity-0">Hidden</span>
            </div>
        </div>
    </div>

    <!-- 2. Main Header -->
    <header class="sh-header-main" id="mainHeader">
        <div class="container-fluid px-3 px-lg-5">
            <div class="sh-header-grid">

                <!-- Left: Navigation (Desktop) -->
                <nav class="d-none d-lg-block">
                    <ul class="sh-nav-list">
                        <li><a href="<?php echo Helpers::url(); ?>" class="sh-nav-link">Home</a></li>
                        <li class="sh-dropdown">
                            <a href="<?php echo Helpers::url('products.php'); ?>" class="sh-nav-link">
                                Shop <i class="bi bi-chevron-down ms-1 small"></i>
                            </a>
                            <div class="sh-dropdown-menu">
                                <a href="<?php echo Helpers::url('products.php'); ?>" class="sh-dropdown-item">
                                    <i class="bi bi-grid me-2"></i> All Products
                                </a>
                                <a href="<?php echo Helpers::url('products.php?badge=new'); ?>"
                                    class="sh-dropdown-item">
                                    <i class="bi bi-stars me-2"></i> New Arrivals
                                </a>
                                <a href="<?php echo Helpers::url('products.php?badge=sale'); ?>"
                                    class="sh-dropdown-item">
                                    <i class="bi bi-tag me-2"></i> On Sale
                                </a>
                                <hr class="my-2">
                                <?php foreach (array_slice($categories, 0, 5) as $cat): ?>
                                    <a href="<?php echo Helpers::url('products.php?category=' . $cat['slug']); ?>"
                                        class="sh-dropdown-item">
                                        <?php echo Security::escape($cat['name']); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </li>
                        <li><a href="<?php echo Helpers::url('products.php?badge=new'); ?>" class="sh-nav-link">New</a>
                        </li>
                        <li><a href="<?php echo Helpers::url('page.php?slug=about-us'); ?>"
                                class="sh-nav-link">About</a></li>
                        <li><a href="<?php echo Helpers::url('page.php?slug=contact-us'); ?>"
                                class="sh-nav-link">Contact</a></li>
                    </ul>
                </nav>

                <!-- Mobile Menu Toggle (Left) -->
                <button class="btn d-lg-none p-0 border-0" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#mobileMenu" style="font-size: 24px;">
                    <i class="bi bi-list"></i>
                </button>

                <!-- Center: Logo -->
                <div class="text-center">
                    <a href="<?php echo Helpers::url(); ?>" class="sh-logo">
                        <?php if ($logoUrl): ?>
                            <img src="<?php echo $logoUrl; ?>" alt="<?php echo $siteName; ?>" style="max-height: 45px;">
                        <?php else: ?>
                            <?php echo $siteName; ?>
                        <?php endif; ?>
                    </a>
                </div>

                <!-- Right: Action Icons -->
                <div class="sh-header-actions">
                    <!-- Search -->
                    <button class="sh-action-btn d-none d-sm-flex" type="button" data-bs-toggle="modal"
                        data-bs-target="#searchModal">
                        <i class="bi bi-search"></i>
                    </button>

                    <!-- Account -->
                    <div class="sh-dropdown d-none d-md-block">
                        <button class="sh-action-btn">
                            <i class="bi bi-person"></i>
                        </button>
                        <div class="sh-dropdown-menu" style="right: 0; left: auto;">
                            <?php if (Security::isLoggedIn()): ?>
                                <a href="<?php echo Helpers::url('account.php'); ?>" class="sh-dropdown-item">
                                    <i class="bi bi-person-circle me-2"></i> My Account
                                </a>
                                <a href="<?php echo Helpers::url('orders.php'); ?>" class="sh-dropdown-item">
                                    <i class="bi bi-box-seam me-2"></i> My Orders
                                </a>
                                <hr class="my-2">
                                <a href="<?php echo Helpers::url('logout.php'); ?>" class="sh-dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </a>
                            <?php else: ?>
                                <a href="<?php echo Helpers::url('login.php'); ?>" class="sh-dropdown-item">
                                    <i class="bi bi-box-arrow-in-right me-2"></i> Login
                                </a>
                                <a href="<?php echo Helpers::url('register.php'); ?>" class="sh-dropdown-item">
                                    <i class="bi bi-person-plus me-2"></i> Create Account
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Wishlist -->
                    <a href="<?php echo Helpers::url('wishlist.php'); ?>" class="sh-action-btn d-none d-sm-flex">
                        <i class="bi bi-heart"></i>
                    </a>

                    <!-- Cart -->
                    <a href="<?php echo Helpers::url('cart.php'); ?>" class="sh-action-btn position-relative">
                        <i class="bi bi-bag"></i>
                        <?php if ($cartCount > 0): ?>
                            <span class="sh-cart-count" id="cartCount"><?php echo $cartCount; ?></span>
                        <?php else: ?>
                            <span class="sh-cart-count d-none" id="cartCount">0</span>
                        <?php endif; ?>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Search Modal -->
    <div class="modal fade" id="searchModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow-lg">
                <div class="modal-body p-4">
                    <form action="<?php echo Helpers::url('products.php'); ?>" method="GET">
                        <div class="position-relative">
                            <input type="text" name="q" id="searchInput"
                                class="form-control form-control-lg border-0 bg-light rounded-pill ps-4 pe-5"
                                placeholder="Search products..." autofocus style="font-size: 18px;">
                            <button type="submit"
                                class="btn position-absolute end-0 top-50 translate-middle-y me-2 rounded-circle"
                                style="width: 45px; height: 45px; background: var(--sh-gradient-primary); color: white;">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                    <div class="mt-4">
                        <p class="small text-muted mb-2 fw-bold">Popular Searches</p>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="<?php echo Helpers::url('products.php?q=new'); ?>"
                                class="btn btn-sm btn-outline-secondary rounded-pill">New Arrivals</a>
                            <a href="<?php echo Helpers::url('products.php?badge=sale'); ?>"
                                class="btn btn-sm btn-outline-secondary rounded-pill">Sale</a>
                            <a href="<?php echo Helpers::url('products.php?badge=bestseller'); ?>"
                                class="btn btn-sm btn-outline-secondary rounded-pill">Best Sellers</a>
                            <?php foreach (array_slice($categories, 0, 3) as $cat): ?>
                                <a href="<?php echo Helpers::url('products.php?category=' . $cat['slug']); ?>"
                                    class="btn btn-sm btn-outline-secondary rounded-pill">
                                    <?php echo Security::escape($cat['name']); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Menu Offcanvas -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenu">
        <div class="offcanvas-header border-bottom">
            <a href="<?php echo Helpers::url(); ?>" class="sh-logo"
                style="font-size: 24px;"><?php echo $siteName; ?></a>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <!-- Mobile Search -->
            <form action="<?php echo Helpers::url('products.php'); ?>" method="GET" class="mb-4">
                <div class="input-group">
                    <input type="text" name="q" class="form-control rounded-start-pill border-end-0"
                        placeholder="Search...">
                    <button type="submit" class="btn rounded-end-pill"
                        style="background: var(--sh-gradient-primary); color: white;">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>

            <!-- Mobile Navigation -->
            <nav class="mb-4">
                <ul class="list-unstyled">
                    <li class="border-bottom py-3">
                        <a href="<?php echo Helpers::url(); ?>"
                            class="text-dark text-decoration-none fw-bold d-flex align-items-center">
                            <i class="bi bi-house me-3 fs-5" style="color: var(--sh-primary);"></i> Home
                        </a>
                    </li>
                    <li class="border-bottom py-3">
                        <a href="<?php echo Helpers::url('products.php'); ?>"
                            class="text-dark text-decoration-none fw-bold d-flex align-items-center">
                            <i class="bi bi-grid me-3 fs-5" style="color: var(--sh-primary);"></i> All Products
                        </a>
                    </li>
                    <li class="border-bottom py-3">
                        <a href="<?php echo Helpers::url('products.php?badge=new'); ?>"
                            class="text-dark text-decoration-none fw-bold d-flex align-items-center">
                            <i class="bi bi-stars me-3 fs-5" style="color: var(--sh-primary);"></i> New Arrivals
                        </a>
                    </li>
                    <li class="border-bottom py-3">
                        <a href="<?php echo Helpers::url('products.php?badge=sale'); ?>"
                            class="text-dark text-decoration-none fw-bold d-flex align-items-center">
                            <i class="bi bi-tag me-3 fs-5" style="color: var(--sh-primary);"></i> On Sale
                        </a>
                    </li>

                    <!-- Categories -->
                    <li class="py-3">
                        <p class="text-muted small fw-bold mb-3 text-uppercase">Categories</p>
                        <?php foreach ($categories as $cat): ?>
                            <a href="<?php echo Helpers::url('products.php?category=' . $cat['slug']); ?>"
                                class="d-block text-muted text-decoration-none py-2">
                                <?php echo Security::escape($cat['name']); ?>
                            </a>
                        <?php endforeach; ?>
                    </li>
                </ul>
            </nav>

            <!-- Mobile Account Links -->
            <div class="border-top pt-4 mt-auto">
                <?php if (Security::isLoggedIn()): ?>
                    <a href="<?php echo Helpers::url('account.php'); ?>"
                        class="btn btn-outline-dark w-100 rounded-pill mb-2">
                        <i class="bi bi-person-circle me-2"></i> My Account
                    </a>
                    <a href="<?php echo Helpers::url('logout.php'); ?>" class="btn btn-link w-100 text-muted">
                        Logout
                    </a>
                <?php else: ?>
                    <a href="<?php echo Helpers::url('login.php'); ?>" class="sh-btn sh-btn-primary sh-btn-full mb-2">
                        Login
                    </a>
                    <a href="<?php echo Helpers::url('register.php'); ?>" class="btn btn-outline-dark w-100 rounded-pill">
                        Create Account
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Main Content Wrapper -->
    <main class="main-content">
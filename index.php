<?php
/**
 * Homepage - Premium Shopify-Grade Design
 * Edluxury - UAE's Premier eCommerce Experience
 * VIBRANT & ATTENTION-GRABBING DESIGN
 */

$pageTitle = 'Home';
require_once 'includes/header.php';

// Initialize models
$productModel = new Product();
$db = Database::getInstance();

// Get collections
$collections = $db->fetchAll("SELECT * FROM collections WHERE status = 'active' ORDER BY sort_order ASC LIMIT 6");

// Get featured products
$featuredProducts = $productModel->getAll(['featured' => true], 1, 8);

// Get new arrivals
$newArrivals = $productModel->getAll(['badge' => 'new'], 1, 4);

// Get hero banner
$heroBanners = $theme->get('hero_image') ?: 'assets/images/gpt-image-1.5_Edluxury_Premium_Collection_for_hero_section_in_a_decent_and_luxurious_style_-0.jpg';

// Get Winning Product (Single high-conversion item)
$winningProduct = $db->fetchOne("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.is_winning = 1 AND p.status = 'active' LIMIT 1");
if ($winningProduct) {
    $winningProduct['images'] = $db->fetchAll("SELECT image_path FROM product_images WHERE product_id = ? ORDER BY is_primary DESC", [$winningProduct['id']]);
}
?>

<!-- 🎞️ ULTRA-PREMIUM INTERACTIVE HERO (Vision System) -->
<section class="sh-hero-vision-container">
    <div id="visionHero" class="vision-hero">
        <!-- Slide 1: Modern Luxury -->
        <div class="vision-slide active" data-slide="1">
            <div class="vision-bg-wrapper">
                <img src="https://img.freepik.com/premium-photo/flat-lay-black-tech-gadgets-headphones-phone-laptop-black-background_14117-747545.jpg?semt=ais_user_personalization&w=740&q=80"
                    class="vision-img" alt="Luxury Lifestyle">
                <div class="vision-overlay"></div>
            </div>
            <div class="container-fluid px-3 px-md-4 px-lg-5 h-100">
                <div class="vision-content-row h-100 align-items-center row">
                    <div class="col-lg-7">
                        <div class="vision-text-reveal">
                            <span class="vision-subtitle" data-aos="fade-right">✨ PREMIUM SELECTIONS</span>
                            <h1 class="vision-title" data-aos="fade-up" data-aos-delay="200">
                                Elevating <span class="text-gold">Elegance</span><br>to New Heights
                            </h1>
                            <h1 class="vision-title-ar" dir="rtl" lang="ar" data-aos="fade-up" data-aos-delay="300">
                                نرفع معايير <span class="text-gold">الأناقة</span> لمستويات جديدة
                            </h1>
                            <p class="vision-description" data-aos="fade-up" data-aos-delay="400">
                                A curated fusion of traditional Emirati heritage and contemporary global minimalism.
                            </p>
                            <div class="vision-actions" data-aos="fade-up" data-aos-delay="500">
                                <a href="<?php echo Helpers::url('products.php'); ?>" class="vision-btn-primary">
                                    EXPLORE COLLECTION <i class="bi bi-arrow-right-short ms-2 fs-4"></i>
                                </a>
                                <a href="#featured" class="vision-link-scroll ms-md-4 mt-3 mt-md-0">
                                    <span class="scroll-dot"></span> SCROLL TO DISCOVER
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Floating Interactive Card -->
            <div class="vision-float-card" data-parallax="0.05">
                <div class="glass-inner">
                    <div class="d-flex align-items-center gap-3">
                        <div class="glass-icon"><i class="bi bi-patch-check-fill"></i></div>
                        <div>
                            <div class="glass-label">DUBAI QUALITY</div>
                            <div class="glass-value">100% Guaranteed</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 2: Signature Scents -->
        <div class="vision-slide" data-slide="2">
            <div class="vision-bg-wrapper">
                <img src="https://media.licdn.com/dms/image/v2/C561BAQFK0rr1xwLmlQ/company-background_10000/company-background_10000/0/1631089770214/french_tech_uae_cover?e=2147483647&v=beta&t=ke6BUiM8Xs_3kVmn0v9vX7gtYX9FAF1xA9H6PQJiWY4"
                    class="vision-img" alt="Luxury Perfumes">
                <div class="vision-overlay"></div>
            </div>
            <div class="container-fluid px-3 px-md-4 px-lg-5 h-100">
                <div class="vision-content-row h-100 align-items-center row">
                    <div class="col-lg-7">
                        <div class="vision-text-reveal">
                            <span class="vision-subtitle">🖤 EXCLUSIVE SCENTS</span>
                            <h1 class="vision-title">
                                The Essence of <span class="text-gold">Arabian</span><br>Luxury
                            </h1>
                            <h1 class="vision-title-ar" dir="rtl" lang="ar">
                                جوهر <span class="text-gold">الفخامة</span> العربية الأصيلة
                            </h1>
                            <p class="vision-description">
                                Discover scents that define your presence with our masterfully crafted private blends.
                            </p>
                            <div class="vision-actions">
                                <a href="<?php echo Helpers::url('products.php?category=fragrance'); ?>"
                                    class="vision-btn-primary">
                                    SHOP PRIVATE COLLECTION
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Vision Controls -->
    <div class="vision-controls">
        <div class="vision-progress-container">
            <div class="vision-progress-item active" onclick="goToSlide(1)">
                <div class="progress-label">01 // MODERN</div>
                <div class="progress-bar-bg">
                    <div class="progress-fill"></div>
                </div>
            </div>
            <div class="vision-progress-item" onclick="goToSlide(2)">
                <div class="progress-label">02 // SIGNATURE</div>
                <div class="progress-bar-bg">
                    <div class="progress-fill"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .sh-hero-vision-container {
        position: relative;
        height: 100vh;
        width: 100%;
        background: #000;
        overflow: hidden;
    }

    .vision-hero {
        height: 100%;
        width: 100%;
        position: relative;
    }

    .vision-slide {
        position: absolute;
        inset: 0;
        opacity: 0;
        visibility: hidden;
        transition: all 1.2s cubic-bezier(0.645, 0.045, 0.355, 1);
        z-index: 1;
    }

    .vision-slide.active {
        opacity: 1;
        visibility: visible;
        z-index: 2;
    }

    .vision-bg-wrapper {
        position: absolute;
        inset: 0;
        overflow: hidden;
        transform: scale(1.1);
        transition: transform 10s linear;
    }

    .vision-slide.active .vision-bg-wrapper {
        transform: scale(1);
    }

    .vision-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .vision-overlay {
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 20% 50%, rgba(0, 0, 0, 0.6) 0%, rgba(0, 0, 0, 0.2) 50%, rgba(0, 0, 0, 0.7) 100%);
    }

    /* Typography */
    .vision-title {
        font-size: clamp(3rem, 6vw, 5.5rem);
        font-weight: 900;
        color: white;
        line-height: 0.95;
        letter-spacing: -3px;
        margin-bottom: 20px;
        text-transform: uppercase;
    }

    .vision-title-ar {
        font-family: 'Noto Sans Arabic', sans-serif;
        font-size: clamp(2.5rem, 5vw, 4.5rem);
        font-weight: 700;
        color: white;
        margin-bottom: 30px;
        opacity: 0.9;
    }

    .text-gold {
        background: var(--sh-gradient-gold);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .vision-subtitle {
        color: var(--sh-gold);
        font-weight: 900;
        font-size: 13px;
        letter-spacing: 5px;
        display: block;
        margin-bottom: 25px;
    }

    .vision-description {
        font-size: 20px;
        color: rgba(255, 255, 255, 0.7);
        max-width: 600px;
        margin-bottom: 45px;
        font-weight: 300;
        line-height: 1.6;
    }

    /* Buttons & Links */
    .vision-btn-primary {
        display: inline-flex;
        align-items: center;
        background: white;
        color: black;
        padding: 22px 45px;
        border-radius: 100px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 14px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        text-decoration: none;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
    }

    .vision-btn-primary:hover {
        transform: translateY(-5px) scale(1.05);
        background: var(--sh-gold);
        color: white;
        box-shadow: 0 20px 50px rgba(212, 175, 55, 0.4);
    }

    .vision-link-scroll {
        color: white;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 2px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 15px;
        opacity: 0.6;
        transition: opacity 0.3s;
    }

    .vision-link-scroll:hover {
        opacity: 1;
    }

    .scroll-dot {
        width: 8px;
        height: 8px;
        background: var(--sh-gold);
        border-radius: 50%;
        box-shadow: 0 0 10px var(--sh-gold);
        animation: scroll-ping 1.5s infinite;
    }

    @keyframes scroll-ping {
        0% {
            transform: scale(1);
            opacity: 1;
        }

        100% {
            transform: scale(3);
            opacity: 0;
        }
    }

    /* Interactive Floating Elements */
    .vision-float-card {
        position: absolute;
        bottom: 15%;
        right: 10%;
        z-index: 10;
        pointer-events: none;
    }

    .glass-inner {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(25px);
        padding: 30px 40px;
        border-radius: 30px;
        border: 1px solid rgba(255, 255, 255, 0.15);
        display: flex;
        flex-direction: column;
        gap: 10px;
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.4);
    }

    .glass-icon {
        font-size: 35px;
        color: #4ade80;
    }

    .glass-label {
        font-size: 10px;
        letter-spacing: 2px;
        color: rgba(255, 255, 255, 0.5);
        font-weight: 800;
    }

    .glass-value {
        font-size: 20px;
        font-weight: 900;
        color: white;
    }

    /* Controls System */
    .vision-controls {
        position: absolute;
        bottom: 60px;
        left: 50%;
        transform: translateX(-50%);
        width: auto;
        z-index: 20;
    }

    .vision-progress-container {
        display: flex;
        gap: 30px;
    }

    .vision-progress-item {
        cursor: pointer;
        width: 150px;
    }

    .progress-label {
        color: white;
        font-size: 10px;
        font-weight: 800;
        margin-bottom: 10px;
        opacity: 0.5;
        transition: opacity 0.3s;
    }

    .vision-progress-item.active .progress-label {
        opacity: 1;
    }

    .progress-bar-bg {
        height: 3px;
        background: rgba(255, 255, 255, 0.1);
        width: 100%;
        border-radius: 5px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background: var(--sh-gold);
        width: 0%;
        transition: width 0.1s linear;
    }

    .active .progress-fill {
        /* This will be animated via JS */
    }

    @media (max-width: 991px) {
        .vision-float-card {
            display: none;
        }

        .sh-hero-vision-container {
            height: 85vh;
        }

        .vision-title {
            font-size: 3.5rem;
        }

        .vision-controls {
            display: none;
        }
    }
</style>

<!-- 🛡️ PREMIUM AUTHENTIC BANNER (Interactive Vision) -->
<section class="sh-section-sm bg-white position-relative"
    style="z-index: 10; margin-top: -60px; border-radius: 40px 40px 0 0; background: linear-gradient(to bottom, #ffffff, #f9fafb);">
    <div class="container-fluid px-3 px-md-4 px-lg-5">
        <div class="sh-benefits-slider-wrapper">
            <div class="sh-benefits-grid" id="benefitsInteractGrid">
                <!-- Item 1 -->
                <div class="sh-benefit-card-premium" data-aos="fade-up" data-aos-delay="0">
                    <div class="sh-benefit-inner">
                        <div class="sh-benefit-glare"></div>
                        <div class="sh-benefit-icon-v3" style="background: linear-gradient(135deg, #FFD700, #B8860B);">
                            <i class="bi bi-truck"></i>
                        </div>
                        <h6 class="sh-benefit-title-v3">UAE NEXT-DAY</h6>
                        <p class="sh-benefit-text-v3">Express delivery in 24 hours across Emirates</p>
                    </div>
                </div>
                <!-- Item 2 -->
                <div class="sh-benefit-card-premium" data-aos="fade-up" data-aos-delay="100">
                    <div class="sh-benefit-inner">
                        <div class="sh-benefit-glare"></div>
                        <div class="sh-benefit-icon-v3" style="background: linear-gradient(135deg, #121826, #1E293B);">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h6 class="sh-benefit-title-v3">100% AUTHENTIC</h6>
                        <p class="sh-benefit-text-v3">Directly from official premium partners</p>
                    </div>
                </div>
                <!-- Item 3 -->
                <div class="sh-benefit-card-premium" data-aos="fade-up" data-aos-delay="200">
                    <div class="sh-benefit-inner">
                        <div class="sh-benefit-glare"></div>
                        <div class="sh-benefit-icon-v3" style="background: linear-gradient(135deg, #D4AF37, #E8C96D);">
                            <i class="bi bi-stars"></i>
                        </div>
                        <h6 class="sh-benefit-title-v3">LUXURY CARE</h6>
                        <p class="sh-benefit-text-v3">Premium packaging & dedicated handling</p>
                    </div>
                </div>
                <!-- Item 4 -->
                <div class="sh-benefit-card-premium" data-aos="fade-up" data-aos-delay="300">
                    <div class="sh-benefit-inner">
                        <div class="sh-benefit-glare"></div>
                        <div class="sh-benefit-icon-v3" style="background: linear-gradient(135deg, #0d121c, #2D3436);">
                            <i class="bi bi-headset"></i>
                        </div>
                        <h6 class="sh-benefit-title-v3">24/7 SUPPORT</h6>
                        <p class="sh-benefit-text-v3">Dedicated concierge for your shopping needs</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    /* --- Premium Benefits Styling (Clarity Design) --- */
    .sh-benefits-slider-wrapper {
        overflow: hidden;
        padding: 10px 0 40px;
    }

    .sh-benefits-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        width: 100%;
        transition: transform 0.5s cubic-bezier(0.2, 0.8, 0.2, 1);
    }

    .sh-benefit-card-premium {
        perspective: 1000px;
        height: 100%;
    }

    .sh-benefit-inner {
        background: #ffffff;
        border: 1px solid rgba(0, 0, 0, 0.04);
        border-radius: 24px;
        padding: 35px 25px;
        text-align: center;
        height: 100%;
        position: relative;
        transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
        overflow: hidden;
        transform-style: preserve-3d;
    }

    .sh-benefit-inner:hover {
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.08);
        background: #fff;
        border-color: rgba(212, 175, 55, 0.2);
    }

    .sh-benefit-glare {
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at center, rgba(255, 255, 255, 0.8) 0%, transparent 70%);
        opacity: 0;
        transition: opacity 0.3s;
        pointer-events: none;
        z-index: 1;
    }

    .sh-benefit-icon-v3 {
        width: 64px;
        height: 64px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        color: #fff;
        font-size: 26px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        transform: translateZ(30px);
    }

    .sh-benefit-title-v3 {
        font-family: var(--sh-font-display);
        font-size: 14px;
        font-weight: 800;
        color: #121826;
        margin-bottom: 10px;
        letter-spacing: 1px;
        transform: translateZ(20px);
    }

    .sh-benefit-text-v3 {
        font-size: 13px;
        color: #64748b;
        line-height: 1.5;
        margin: 0;
        transform: translateZ(10px);
    }

    @media (max-width: 991px) {
        .sh-benefits-grid {
            display: flex;
            gap: 15px;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            scrollbar-width: none;
            padding-bottom: 20px;
        }

        .sh-benefits-grid::-webkit-scrollbar {
            display: none;
        }

        .sh-benefit-card-premium {
            flex: 0 0 280px;
            scroll-snap-align: center;
        }
    }
</style>

<!-- Collections Grid (Bilingual & Premium) -->
<?php if (!empty($collections)): ?>
    <section class="sh-section" style="background: var(--sh-gray-50);">
        <div class="container-fluid px-3 px-md-4 px-lg-5">
            <div class="sh-section-header" data-aos="fade-up">
                <span class="sh-section-subtitle">🛍️ <?php echo Helpers::translate('shop_category'); ?></span>
                <h2 class="sh-section-title">Our Premium Collections</h2>
            </div>

            <div class="row g-3 g-md-4">
                <?php foreach (array_slice($collections, 0, 3) as $index => $collection): ?>
                    <div class="col-md-4" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                        <a href="<?php echo Helpers::url('products.php?collection=' . $collection['slug']); ?>"
                            class="sh-collection-card premium-hover">
                            <img src="<?php echo Helpers::upload($collection['image']); ?>"
                                alt="<?php echo Security::escape($collection['name']); ?>" class="sh-collection-img"
                                onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&q=80&w=800'">

                            <div class="sh-collection-overlay-v2">
                                <div class="sh-collection-content">
                                    <h3 class="sh-collection-name-en"><?php echo Security::escape($collection['name']); ?></h3>
                                    <?php if (!empty($collection['name_ar'])): ?>
                                        <h3 class="sh-collection-name-ar" dir="rtl">
                                            <?php echo Security::escape($collection['name_ar']); ?>
                                        </h3>
                                    <?php endif; ?>
                                    <div class="sh-collection-btn-mini mt-3">
                                        <span><?php echo Helpers::translate('explore'); ?></span>
                                        <i class="bi bi-arrow-right-short ms-1 fs-5"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <style>
        .sh-collection-overlay-v2 {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, transparent 40%, rgba(0, 0, 0, 0.85) 100%);
            display: flex;
            align-items: flex-end;
            padding: 2.5rem;
            transition: all 0.4s ease;
        }

        .sh-collection-card:hover .sh-collection-overlay-v2 {
            background: linear-gradient(to bottom, transparent 20%, rgba(0, 0, 0, 0.95) 100%);
        }

        .sh-collection-name-en {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .sh-collection-name-ar {
            font-family: 'Noto Sans Arabic', sans-serif;
            font-size: 1.25rem;
            font-weight: 500;
            color: var(--sh-gold);
            margin: 4px 0 0 0;
            opacity: 0.9;
        }

        .sh-collection-btn-mini {
            display: inline-flex;
            align-items: center;
            color: white;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding-bottom: 4px;
            border-bottom: 2px solid var(--sh-gold);
            transform: translateY(10px);
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .sh-collection-card:hover .sh-collection-btn-mini {
            transform: translateY(0);
            opacity: 1;
        }

        .sh-collection-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.8s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .sh-collection-card:hover .sh-collection-img {
            transform: scale(1.08);
        }
    </style>
<?php endif; ?>

<!-- Featured Products Section -->
<?php if (!empty($featuredProducts['products'])): ?>
    <section class="sh-section">
        <div class="container-fluid px-3 px-md-4 px-lg-5">
            <div class="sh-section-header" data-aos="fade-up">
                <span class="sh-section-subtitle">⭐ Curated Selection</span>
                <h2 class="sh-section-title">Featured Products</h2>
            </div>

            <div class="row g-3 g-md-4">
                <?php foreach ($featuredProducts['products'] as $product): ?>
                    <?php
                    $images = $db->fetchAll("SELECT image_path FROM product_images WHERE product_id = ? ORDER BY is_primary DESC LIMIT 2", [$product['id']]);
                    $primaryImg = !empty($images[0]) ? Helpers::upload($images[0]['image_path']) : Helpers::asset('images/placeholder-product.jpg');
                    $secondaryImg = !empty($images[1]) ? Helpers::upload($images[1]['image_path']) : null;
                    ?>
                    <div class="col-6 col-md-4 col-lg-3" data-aos="fade-up">
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

                                <!-- Wishlist Button -->
                                <button class="sh-wishlist-btn" onclick="event.preventDefault();">
                                    <i class="bi bi-heart"></i>
                                </button>

                                <!-- Primary Image -->
                                <a href="<?php echo Helpers::url('product.php?slug=' . $product['slug']); ?>">
                                    <img src="<?php echo $primaryImg; ?>"
                                        alt="<?php echo Security::escape($product['name']); ?>">
                                    <?php if ($secondaryImg): ?>
                                        <img src="<?php echo $secondaryImg; ?>"
                                            alt="<?php echo Security::escape($product['name']); ?>" class="sh-product-secondary">
                                    <?php endif; ?>
                                </a>

                                <!-- Quick Add -->
                                <div class="sh-product-actions">
                                    <button class="sh-quick-add" onclick="addToCart(<?php echo $product['id']; ?>)">
                                        <i class="bi bi-bag-plus me-2"></i> Quick Add
                                    </button>
                                </div>
                            </div>

                            <div class="sh-product-info">
                                <p class="sh-product-vendor">
                                    <?php echo Security::escape($product['category_name'] ?? 'Edluxury'); ?>
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
                                    <span class="sh-price-current"><?php echo Helpers::formatPrice($product['price']); ?></span>
                                </div>
                                <div class="sh-product-rating">
                                    <span class="sh-stars">
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                    </span>
                                    <span class="sh-rating-count">(<?php echo rand(12, 95); ?>)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="text-center mt-5" data-aos="fade-up">
                <a href="<?php echo Helpers::url('products.php'); ?>" class="sh-btn sh-btn-secondary">
                    View All Products <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- 💎 PLATINUM PRODUCT SPOTLIGHT (The Diamond Reveal) -->
<?php if ($winningProduct): ?>
    <section class="platinum-spotlight overflow-hidden position-relative">
        <!-- Luxury Canvas Elements -->
        <div class="platinum-canvas">
            <div class="diamond-glow-1"></div>
            <div class="diamond-glow-2"></div>
            <div class="mesh-grid-bg"></div>
        </div>

        <div class="container-fluid px-3 px-md-4 px-lg-6 position-relative z-index-10">
            <div class="row align-items-center g-0">
                <!-- Left: The Narrative -->
                <div class="col-lg-6 py-5" data-aos="fade-right">
                    <div class="platinum-content-reveal pe-lg-5">
                        <div class="d-flex align-items-center gap-2 mb-4">
                            <span class="platinum-tag-aura">
                                <i class="bi bi-patch-check-fill me-2"></i>CURATED MASTERPIECE
                            </span>
                            <div class="live-pulse-container">
                                <span class="live-pulse-ping"></span>
                                <span class="live-pulse-text text-uppercase small fw-bold">14 People viewing now</span>
                            </div>
                        </div>

                        <div class="headline-reveal mb-4">
                            <h2 class="platinum-headline-en mb-2">
                                <?php echo Security::escape($winningProduct['name']); ?>
                            </h2>
                            <?php if (!empty($winningProduct['name_ar'])): ?>
                                <h3 class="platinum-headline-ar" dir="rtl" lang="ar">
                                    <?php echo Security::escape($winningProduct['name_ar']); ?>
                                </h3>
                            <?php endif; ?>
                        </div>

                        <div class="platinum-description-box mb-5">
                            <p class="lead text-platinum-muted">
                                <?php echo Helpers::truncate(strip_tags($winningProduct['description']), 200); ?>
                            </p>
                        </div>

                        <!-- Scarcity & Social Proof -->
                        <div class="platinum-stats-row mb-5">
                            <div class="stat-item">
                                <span class="stat-value text-gold">4.9/5</span>
                                <span class="stat-label text-uppercase">Rating</span>
                            </div>
                            <div class="stat-divider"></div>
                            <div class="stat-item">
                                <span class="stat-value text-white">500+</span>
                                <span class="stat-label text-uppercase">UAE Sales</span>
                            </div>
                            <div class="stat-divider"></div>
                            <div class="stat-item">
                                <span class="stat-value text-gold" id="stockStatus">Limited</span>
                                <span class="stat-label text-uppercase">Stock</span>
                            </div>
                        </div>

                        <!-- Pricing & CTA -->
                        <div class="platinum-action-panel p-4 p-md-5 rounded-5 glass-card mb-5">
                            <div class="row align-items-center g-4">
                                <div class="col-md-6">
                                    <div class="price-stack">
                                        <div class="current-price-v2 d-flex align-items-center gap-3">
                                            <span class="aed-label">AED</span>
                                            <span
                                                class="value"><?php echo number_format($winningProduct['price'], 2); ?></span>
                                        </div>
                                        <?php if ($winningProduct['compare_price'] > $winningProduct['price']): ?>
                                            <div class="saving-stack d-flex align-items-center gap-2">
                                                <del
                                                    class="old-price"><?php echo Helpers::formatPrice($winningProduct['compare_price']); ?></del>
                                                <span class="discount-pill">Exclusive
                                                    -<?php echo Helpers::discountPercentage($winningProduct['compare_price'], $winningProduct['price']); ?>%</span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <div class="countdown-dial d-flex justify-content-center justify-content-md-end gap-3">
                                        <div class="dial-unit"><span class="v" id="winH">00</span><span class="l">Hrs</span>
                                        </div>
                                        <div class="dial-sep">:</div>
                                        <div class="dial-unit"><span class="v" id="winM">00</span><span class="l">Min</span>
                                        </div>
                                        <div class="dial-sep">:</div>
                                        <div class="dial-unit"><span class="v" id="winS">00</span><span class="l">Sec</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4 border-white border-opacity-10">

                            <div class="d-flex gap-3 flex-column flex-sm-row">
                                <button onclick="addToCart(<?php echo $winningProduct['id']; ?>)"
                                    class="sh-btn platinum-btn-glow w-100 flex-grow-1 py-4 fs-5 fw-bold">
                                    <i class="bi bi-cart-plus me-2"></i> ADD TO SHOPPING BAG
                                </button>
                                <a href="<?php echo Helpers::url('product.php?slug=' . $winningProduct['slug']); ?>"
                                    class="sh-btn sh-btn-ghost w-100 flex-grow-1 py-4 fs-5 fw-bold">
                                    EXPERIENCE PRODUCT
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: The Visual Spotlight -->
                <div class="col-lg-6" data-aos="zoom-out">
                    <div class="platinum-visual-stage">
                        <div class="stage-platform"></div>
                        <div class="floating-luxury-elements">
                            <div class="lux-el el-1"><i class="bi bi-stars"></i></div>
                            <div class="lux-el el-2"><i class="bi bi-gem"></i></div>
                            <div class="lux-el el-3"><i class="bi bi-award"></i></div>
                        </div>

                        <?php
                        $winImg = !empty($winningProduct['images'][0]) ? Helpers::upload($winningProduct['images'][0]['image_path']) : 'assets/images/placeholder.jpg';
                        ?>
                        <div class="image-reveal-v2">
                            <img src="<?php echo $winImg; ?>" alt="Masterpiece" class="platinum-main-img">
                            <div class="image-reflection"></div>
                        </div>

                        <!-- Floating Live Evidence -->
                        <div class="platinum-proof-toast" id="platinumProof">
                            <div class="toast-indicator"></div>
                            <div class="d-flex align-items-center gap-3">
                                <div class="toast-avatar"><i class="bi bi-check-circle-fill"></i></div>
                                <div>
                                    <div class="toast-title">Recent Purchase</div>
                                    <div class="toast-desc">Confirmed by <span class="buyer-name">Customer</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .platinum-spotlight {
            background: #020202;
            padding: 100px 0;
            margin: 40px 15px;
            border-radius: 60px;
            color: #fff;
            box-shadow: 0 50px 100px rgba(0, 0, 0, 0.8);
        }

        .platinum-canvas {
            position: absolute;
            inset: 0;
            z-index: 0;
        }

        .diamond-glow-1 {
            position: absolute;
            top: -10%;
            right: -10%;
            width: 70%;
            height: 70%;
            background: radial-gradient(circle, rgba(212, 175, 55, 0.1) 0%, transparent 70%);
            filter: blur(80px);
            animation: pulse-glow 8s infinite alternate ease-in-out;
        }

        .diamond-glow-2 {
            position: absolute;
            bottom: -10%;
            left: -10%;
            width: 60%;
            height: 60%;
            background: radial-gradient(circle, rgba(255, 107, 53, 0.05) 0%, transparent 70%);
            filter: blur(80px);
        }

        .mesh-grid-bg {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 60px 60px;
            mask-image: radial-gradient(circle at center, black, transparent);
        }

        .platinum-tag-aura {
            background: rgba(212, 175, 55, 0.15);
            color: #D4AF37;
            padding: 8px 18px;
            border-radius: 100px;
            font-size: 11px;
            font-weight: 900;
            letter-spacing: 2px;
            border: 1px solid rgba(212, 175, 55, 0.3);
            text-transform: uppercase;
        }

        .live-pulse-container {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #4ade80;
        }

        .live-pulse-ping {
            width: 8px;
            height: 8px;
            background: #4ade80;
            border-radius: 50%;
            position: relative;
        }

        .live-pulse-ping::after {
            content: '';
            position: absolute;
            inset: -4px;
            background: inherit;
            border-radius: inherit;
            animation: ping 1.5s infinite;
        }

        .platinum-headline-en {
            font-size: clamp(2.5rem, 6rem, 5rem);
            font-weight: 900;
            line-height: 0.95;
            letter-spacing: -3px;
            background: linear-gradient(180deg, #FFFFFF 0%, #A0A0A0 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .platinum-headline-ar {
            font-family: 'Noto Sans Arabic', sans-serif;
            font-size: clamp(2rem, 4.5rem, 3.5rem);
            font-weight: 700;
            color: #D4AF37;
            margin-top: 10px;
            text-shadow: 0 10px 40px rgba(212, 175, 55, 0.3);
        }

        .text-platinum-muted {
            font-size: 19px;
            color: rgba(255, 255, 255, 0.5);
            font-weight: 300;
            line-height: 1.7;
            max-width: 550px;
        }

        .platinum-stats-row {
            display: flex;
            align-items: center;
            gap: 40px;
        }

        .stat-item {
            display: flex;
            flex-direction: column;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 900;
            line-height: 1;
        }

        .stat-label {
            font-size: 10px;
            color: rgba(255, 255, 255, 0.4);
            letter-spacing: 2px;
            margin-top: 5px;
        }

        .stat-divider {
            width: 1px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 40px 100px rgba(0, 0, 0, 0.4);
        }

        .aed-label {
            font-size: 20px;
            font-weight: 800;
            color: #D4AF37;
        }

        .current-price-v2 .value {
            font-size: 56px;
            font-weight: 900;
            letter-spacing: -2px;
            line-height: 1;
        }

        .old-price {
            font-size: 20px;
            color: rgba(255, 255, 255, 0.3);
            text-decoration: line-through;
        }

        .discount-pill {
            background: #ef4444;
            color: white;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 800;
        }

        /* Countdown Dials */
        .countdown-dial {
            display: flex;
            align-items: center;
        }

        .dial-unit {
            display: flex;
            flex-direction: column;
            align-items: center;
            min-width: 60px;
        }

        .dial-unit .v {
            font-size: 32px;
            font-weight: 900;
            color: #fff;
            line-height: 1;
        }

        .dial-unit .l {
            font-size: 9px;
            text-transform: uppercase;
            color: #D4AF37;
            letter-spacing: 1px;
            margin-top: 5px;
        }

        .dial-sep {
            font-size: 32px;
            font-weight: 900;
            color: rgba(255, 255, 255, 0.2);
            padding-bottom: 20px;
        }

        .platinum-btn-glow {
            background: linear-gradient(135deg, #FFD700, #B8860B);
            color: #000;
            border: none;
            box-shadow: 0 15px 40px rgba(212, 175, 55, 0.4);
        }

        .platinum-btn-glow:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 20px 60px rgba(212, 175, 55, 0.6);
            background: #fff;
            color: #000;
        }

        /* Visual Stage */
        .platinum-visual-stage {
            position: relative;
            padding: 60px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 600px;
        }

        .stage-platform {
            position: absolute;
            bottom: 10%;
            width: 80%;
            height: 40px;
            background: radial-gradient(ellipse at center, rgba(212, 175, 55, 0.2) 0%, transparent 70%);
            transform: rotateX(70deg);
        }

        .image-reveal-v2 {
            position: relative;
            z-index: 5;
            transition: transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .platinum-main-img {
            max-width: 100%;
            height: auto;
            border-radius: 40px;
            box-shadow:
                0 30px 60px rgba(0, 0, 0, 0.8),
                0 0 40px rgba(212, 175, 55, 0.1);
        }

        .image-reflection {
            position: absolute;
            bottom: -50px;
            left: 5%;
            width: 90%;
            height: 40px;
            background: radial-gradient(ellipse at center, rgba(0, 0, 0, 0.5) 0%, transparent 70%);
            z-index: 4;
        }

        .image-reveal-v2:hover {
            transform: translateY(-20px) rotateY(-5deg) rotateX(5deg);
        }

        /* Luxury Proof Toast */
        .platinum-proof-toast {
            position: absolute;
            top: 10%;
            right: 0;
            background: rgba(255, 255, 255, 0.95);
            color: #000;
            padding: 20px 30px;
            border-radius: 30px 30px 0 30px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.3);
            z-index: 100;
            opacity: 0;
            transform: translateX(50px) scale(0.8);
            transition: all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
            pointer-events: none;
        }

        .platinum-proof-toast.show {
            opacity: 1;
            transform: translateX(0) scale(1);
        }

        .toast-avatar {
            font-size: 32px;
            color: #D4AF37;
        }

        .toast-title {
            font-size: 12px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
        }

        .toast-desc {
            font-size: 15px;
            font-weight: 700;
        }

        .buyer-name {
            color: #D4AF37;
        }

        .lux-el {
            position: absolute;
            color: #D4AF37;
            opacity: 0.2;
            font-size: 40px;
            animation: float-lux 6s infinite ease-in-out;
        }

        .el-1 {
            top: 0;
            left: 0;
        }

        .el-2 {
            bottom: 20%;
            right: 10%;
            animation-delay: 1s;
        }

        .el-3 {
            top: 40%;
            left: -10%;
            animation-delay: 2s;
        }

        @keyframes float-lux {

            0%,
            100% {
                transform: translate(0, 0) rotate(0);
            }

            50% {
                transform: translate(20px, -40px) rotate(15deg);
            }
        }

        @keyframes ping {

            75%,
            100% {
                transform: scale(3);
                opacity: 0;
            }
        }

        @keyframes pulse-glow {
            from {
                transform: scale(1);
                opacity: 0.1;
            }

            to {
                transform: scale(1.2);
                opacity: 0.15;
            }
        }

        @media (max-width: 991px) {
            .platinum-spotlight {
                border-radius: 0;
                margin: 0;
                padding: 60px 0;
            }

            .platinum-visual-stage {
                min-height: 400px;
                padding: 20px;
            }

            .platinum-stats-row {
                gap: 20px;
                flex-wrap: wrap;
            }

            .platinum-headline-en {
                font-size: 3rem;
            }

            .current-price-v2 .value {
                font-size: 42px;
            }
        }
    </style>

    <script>
        // 💎 DIAMOND REVEAL TIMER ENGINE
        function initSpotlightTimer() {
            function tick() {
                const now = new Date();
                const end = new Date();
                end.setHours(23, 59, 59, 999);

                const diff = end - now;
                if (diff <= 0) return;

                const h = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const s = Math.floor((diff % (1000 * 60)) / 1000);

                const hEl = document.getElementById('winH');
                const mEl = document.getElementById('winM');
                const sEl = document.getElementById('winS');

                if (hEl) hEl.innerText = String(h).padStart(2, '0');
                if (mEl) mEl.innerText = String(m).padStart(2, '0');
                if (sEl) sEl.innerText = String(s).padStart(2, '0');
            }
            setInterval(tick, 1000);
            tick();
        }

        // 🏆 PLATINUM SOCIAL PROOF ENGINE
        function initProofEngine() {
            const buyers = ['Hamad', 'Salem', 'Aisha', 'Noora', 'Jassim', 'Reem', 'Abdulla'];
            const cities = ['Dubai', 'Abu Dhabi', 'Sharjah', 'Fujairah', 'Umm Al Quwain'];
            const toast = document.getElementById('platinumProof');

            if (!toast) return;

            function trigger() {
                const name = buyers[Math.floor(Math.random() * buyers.length)];
                const city = cities[Math.floor(Math.random() * cities.length)];

                toast.querySelector('.buyer-name').innerText = `${name} (${city})`;
                toast.classList.add('show');

                setTimeout(() => toast.classList.remove('show'), 6000);
            }

            setTimeout(trigger, 5000);
            setInterval(() => {
                if (Math.random() > 0.5) trigger();
            }, 18000);
        }

        document.addEventListener('DOMContentLoaded', () => {
            initSpotlightTimer();
            initProofEngine();
        });
    </script>
<?php endif; ?>


<!-- New Arrivals Section -->
<?php if (!empty($newArrivals['products'])): ?>
    <section class="sh-section">
        <div class="container-fluid px-3 px-md-4 px-lg-5">
            <div class="sh-section-header" data-aos="fade-up">
                <span class="sh-section-subtitle">✨ Just Arrived</span>
                <h2 class="sh-section-title">New Arrivals</h2>
            </div>

            <div class="row g-3 g-md-4">
                <?php foreach ($newArrivals['products'] as $product): ?>
                    <?php
                    $images = $db->fetchAll("SELECT image_path FROM product_images WHERE product_id = ? ORDER BY is_primary DESC LIMIT 1", [$product['id']]);
                    $primaryImg = !empty($images[0]) ? Helpers::upload($images[0]['image_path']) : Helpers::asset('images/placeholder-product.jpg');
                    ?>
                    <div class="col-6 col-md-3" data-aos="fade-up">
                        <div class="sh-product-card">
                            <div class="sh-product-media">
                                <div class="sh-product-badges">
                                    <span class="sh-badge sh-badge-new">NEW</span>
                                </div>
                                <button class="sh-wishlist-btn"><i class="bi bi-heart"></i></button>
                                <a href="<?php echo Helpers::url('product.php?slug=' . $product['slug']); ?>">
                                    <img src="<?php echo $primaryImg; ?>"
                                        alt="<?php echo Security::escape($product['name']); ?>">
                                </a>
                                <div class="sh-product-actions">
                                    <button class="sh-quick-add" onclick="addToCart(<?php echo $product['id']; ?>)">
                                        <i class="bi bi-bag-plus me-2"></i> Quick Add
                                    </button>
                                </div>
                            </div>
                            <div class="sh-product-info">
                                <h3 class="sh-product-title">
                                    <a href="<?php echo Helpers::url('product.php?slug=' . $product['slug']); ?>">
                                        <?php echo Security::escape($product['name']); ?>
                                    </a>
                                </h3>
                                <div class="sh-product-price">
                                    <span class="sh-price-current"><?php echo Helpers::formatPrice($product['price']); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Testimonials -->
<section class="sh-section" style="background: var(--sh-gray-50);">
    <div class="container-fluid px-3 px-md-4 px-lg-5">
        <div class="sh-section-header" data-aos="fade-up">
            <span class="sh-section-subtitle">💬 Customer Reviews</span>
            <h2 class="sh-section-title">What Our Clients Say</h2>
        </div>

        <div class="row g-4">
            <div class="col-md-4" data-aos="fade-up">
                <div class="sh-testimonial-card">
                    <div class="sh-testimonial-stars">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <p class="sh-testimonial-text">
                        "Exceptional quality and the fastest delivery I've experienced in Dubai.
                        The packaging was beautiful - will definitely order again!"
                    </p>
                    <div class="sh-testimonial-author">
                        <img src="https://ui-avatars.com/api/?name=Ahmed+K&background=FF6B35&color=fff&bold=true"
                            alt="Ahmed K." class="sh-testimonial-avatar">
                        <div>
                            <p class="sh-testimonial-name">Ahmed K.</p>
                            <p class="sh-testimonial-role">Dubai, UAE</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="sh-testimonial-card">
                    <div class="sh-testimonial-stars">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <p class="sh-testimonial-text">
                        "Finally, an online store that understands premium service.
                        The customer support team went above and beyond for my order."
                    </p>
                    <div class="sh-testimonial-author">
                        <img src="https://ui-avatars.com/api/?name=Sara+M&background=00B894&color=fff&bold=true"
                            alt="Sara M." class="sh-testimonial-avatar">
                        <div>
                            <p class="sh-testimonial-name">Sara M.</p>
                            <p class="sh-testimonial-role">Abu Dhabi, UAE</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="sh-testimonial-card">
                    <div class="sh-testimonial-stars">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <p class="sh-testimonial-text">
                        "Ordered a gift for my wife and it arrived beautifully packaged.
                        The attention to detail truly sets Edluxury apart."
                    </p>
                    <div class="sh-testimonial-author">
                        <img src="https://ui-avatars.com/api/?name=Mohamed+Z&background=6C5CE7&color=fff&bold=true"
                            alt="Mohamed Z." class="sh-testimonial-avatar">
                        <div>
                            <p class="sh-testimonial-name">Mohamed Z.</p>
                            <p class="sh-testimonial-role">Sharjah, UAE</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Brand Story (Interactive Edition) -->
<section class="sh-section premium-story overflow-hidden">
    <div class="container-fluid px-3 px-md-4 px-lg-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="zoom-in-right">
                <div class="story-image-container interactive-tilt-target" id="storyImageCard">
                    <div class="story-image-wrapper">
                        <img src="https://images.unsplash.com/photo-1556740738-b6a63e27c4df?auto=format&fit=crop&q=80&w=800"
                            alt="Our Story" class="img-fluid rounded-5 shadow-2xl main-story-img">
                        <div class="story-glare"></div>
                    </div>

                    <!-- Floating Dynamic Badge -->
                    <div class="story-floating-badge animate-float" id="excellenceBadge">
                        <div class="badge-content">
                            <h3 class="mb-0 fw-black display-4"><span class="counter-up" data-target="10">0</span>+</h3>
                            <p class="mb-0 small text-uppercase fw-bold opacity-80">Years of Luxury</p>
                        </div>
                        <div class="badge-glow"></div>
                    </div>

                    <!-- Decorative Elements -->
                    <div class="story-circle-decoration"></div>
                    <div class="story-dots-decoration"></div>
                </div>
            </div>

            <div class="col-lg-6" data-aos="fade-left">
                <div class="story-content-reveal">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="story-tag">📖 OUR JOURNEY</span>
                        <div class="story-line"></div>
                    </div>

                    <h2 class="sh-heading-1 mb-4 display-3 fw-black">
                        Crafting <span class="text-gradient-gold">Premium</span><br>Experiences for the UAE
                    </h2>

                    <p class="mb-5 lead text-muted-premium">
                        Founded in the heart of Dubai, <strong>Edluxury</strong> was born from a passion for
                        delivering authentic, premium products with world-class service. We bridge
                        the gap between global luxury brands and seamless local convenience.
                    </p>

                    <div class="row g-4 mb-5">
                        <div class="col-sm-6">
                            <div class="story-feature-item" data-aos="fade-up" data-aos-delay="100">
                                <div class="feature-icon-wrapper">
                                    <i class="bi bi-shield-check"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Authentic Products</h6>
                                    <p class="small text-muted mb-0">100% Genuine Guaranteed</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="story-feature-item" data-aos="fade-up" data-aos-delay="200">
                                <div class="feature-icon-wrapper">
                                    <i class="bi bi-geo-alt"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Local Expertise</h6>
                                    <p class="small text-muted mb-0">UAE Based Operations</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="story-feature-item" data-aos="fade-up" data-aos-delay="300">
                                <div class="feature-icon-wrapper">
                                    <i class="bi bi-box-seam"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Premium Packaging</h6>
                                    <p class="small text-muted mb-0">Unboxing Excellence</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="story-feature-item" data-aos="fade-up" data-aos-delay="400">
                                <div class="feature-icon-wrapper">
                                    <i class="bi bi-lightning-charge"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Fast Delivery</h6>
                                    <p class="small text-muted mb-0">Same-Day in Many Areas</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-4">
                        <a href="<?php echo Helpers::url('page.php?slug=about-us'); ?>" class="btn-premium-story">
                            <span>EXPLORE OUR STORY</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                        <div class="story-social-hint d-none d-sm-flex align-items-center gap-2">
                            <span class="small fw-bold text-muted">FOLLOW US</span>
                            <div class="d-flex gap-2">
                                <a href="#" class="social-dot-link"><i class="bi bi-instagram"></i></a>
                                <a href="#" class="social-dot-link"><i class="bi bi-facebook"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .premium-story {
        padding: 100px 0;
        background: radial-gradient(circle at 100% 0%, rgba(212, 175, 55, 0.03) 0%, transparent 40%);
    }

    /* Image Container & 3D Effect */
    .story-image-container {
        position: relative;
        perspective: 2000px;
        z-index: 1;
    }

    .story-image-wrapper {
        position: relative;
        border-radius: 40px;
        overflow: hidden;
        transition: transform 0.1s ease-out;
        transform-style: preserve-3d;
    }

    .main-story-img {
        width: 100%;
        height: auto;
        transform: scale(1);
        transition: transform 0.8s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .story-image-wrapper:hover .main-story-img {
        transform: scale(1.05);
    }

    .story-glare {
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 50% 50%, rgba(255, 255, 255, 0.2) 0%, transparent 60%);
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.3s;
    }

    /* Floating Badge */
    .story-floating-badge {
        position: absolute;
        bottom: -30px;
        right: -10px;
        background: var(--sh-gradient-primary);
        color: white;
        padding: 40px;
        border-radius: 40px;
        box-shadow: 0 40px 80px rgba(0, 0, 0, 0.3);
        z-index: 5;
        min-width: 220px;
        text-align: center;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .badge-glow {
        position: absolute;
        inset: -20px;
        background: var(--sh-primary);
        filter: blur(40px);
        opacity: 0.2;
        z-index: -1;
    }

    /* Animations */
    @keyframes float {

        0%,
        100% {
            transform: translateY(0) rotate(0);
        }

        50% {
            transform: translateY(-20px) rotate(2deg);
        }
    }

    .animate-float {
        animation: float 6s infinite ease-in-out;
    }

    /* Decorations */
    .story-circle-decoration {
        position: absolute;
        top: -40px;
        left: -40px;
        width: 200px;
        height: 200px;
        border: 2px dashed rgba(212, 175, 55, 0.2);
        border-radius: 50%;
        z-index: -1;
        animation: spin 30s linear infinite;
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    /* Typography & Content */
    .text-gradient-gold {
        background: var(--sh-gradient-gold);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .text-muted-premium {
        color: #666;
        line-height: 1.8;
        font-size: 1.1rem;
    }

    .story-tag {
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 2px;
        color: var(--sh-primary);
        background: rgba(255, 107, 53, 0.1);
        padding: 5px 15px;
        border-radius: 50px;
    }

    .story-line {
        height: 2px;
        width: 40px;
        background: var(--sh-gold);
    }

    /* Features */
    .story-feature-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px;
        background: white;
        border-radius: 20px;
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }

    .story-feature-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        border-color: #eee;
    }

    .feature-icon-wrapper {
        width: 45px;
        height: 45px;
        background: #f8f9fa;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: var(--sh-primary);
        transition: all 0.3s ease;
    }

    .story-feature-item:hover .feature-icon-wrapper {
        background: var(--sh-gradient-primary);
        color: white;
    }

    /* Button */
    .btn-premium-story {
        display: inline-flex;
        align-items: center;
        gap: 15px;
        background: #000;
        color: #fff;
        padding: 18px 35px;
        border-radius: 100px;
        text-decoration: none;
        font-weight: 800;
        font-size: 13px;
        letter-spacing: 1px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .btn-premium-story:hover {
        transform: scale(1.05) translateX(5px);
        background: var(--sh-primary);
        box-shadow: 0 15px 40px rgba(255, 107, 53, 0.3);
        color: white;
    }

    .social-dot-link {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: 1px solid #ddd;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #666;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .social-dot-link:hover {
        background: black;
        color: white;
        border-color: black;
    }

    @media (max-width: 991px) {
        .story-floating-badge {
            position: relative;
            bottom: 0;
            right: 0;
            margin-top: -50px;
            margin-left: 20px;
            padding: 30px;
        }

        .premium-story {
            padding: 60px 0;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. 3D Tilt Effect for Story Card
        const storyCard = document.getElementById('storyImageCard');
        if (storyCard) {
            const wrapper = storyCard.querySelector('.story-image-wrapper');
            const glare = storyCard.querySelector('.story-glare');

            storyCard.addEventListener('mousemove', (e) => {
                const rect = storyCard.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;

                const centerX = rect.width / 2;
                const centerY = rect.height / 2;

                const rotateX = (centerY - y) / 15;
                const rotateY = (x - centerX) / 15;

                wrapper.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;

                // Show/Move Glare
                glare.style.opacity = '1';
                const glareX = (x / rect.width) * 100;
                const glareY = (y / rect.height) * 100;
                glare.style.background = `radial-gradient(circle at ${glareX}% ${glareY}%, rgba(255,255,255,0.3) 0%, transparent 60%)`;
            });

            storyCard.addEventListener('mouseleave', () => {
                wrapper.style.transform = `perspective(1000px) rotateX(0deg) rotateY(0deg)`;
                glare.style.opacity = '0';
            });
        }

        // 2. Counter Animation
        const counter = document.querySelector('.counter-up');
        if (counter) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const target = parseInt(counter.dataset.target);
                        let count = 0;
                        const duration = 2000;
                        const increment = target / (duration / 16);

                        const updateCounter = () => {
                            count += increment;
                            if (count < target) {
                                counter.innerText = Math.ceil(count);
                                requestAnimationFrame(updateCounter);
                            } else {
                                counter.innerText = target;
                            }
                        };
                        updateCounter();
                        observer.unobserve(counter);
                    }
                });
            }, { threshold: 0.5 });
            observer.observe(counter);
        }

        // 3. Parallax Floating Badge on Scroll
        window.addEventListener('scroll', () => {
            const badge = document.getElementById('excellenceBadge');
            if (badge) {
                const scrolled = window.pageYOffset;
                const rect = badge.getBoundingClientRect();
                if (rect.top < window.innerHeight && rect.bottom > 0) {
                    const move = (scrolled * 0.05);
                    badge.style.transform = `translateY(${move}px)`;
                }
            }
        });
    });
</script>


<!-- 📸 PREMIUM INTERACTIVE INSTAGRAM FEED (Infinity Marquee) -->
<section class="sh-section overflow-hidden" style="background: white;">
    <div class="container-fluid px-0">
        <div class="sh-section-header mb-5" data-aos="fade-up">
            <div class="d-flex justify-content-center mb-3">
                <span class="badge rounded-pill px-4 py-2"
                    style="background: rgba(255, 107, 53, 0.1); color: var(--sh-primary); font-size: 11px; letter-spacing: 2px;">
                    <i class="bi bi-instagram me-2"></i> @EDLUXURY.SHOP
                </span>
            </div>
            <h2 class="sh-section-title">Follow Our Journey</h2>
            <p class="text-muted small mt-2">Join 50k+ fashion enthusiasts on Instagram</p>
        </div>

        <!-- Infinite Scrolling Track -->
        <div class="instagram-marquee-container">
            <div class="instagram-track">
                <?php
                $instagramImages = [
                    'https://uaegadgets.com/cdn/shop/files/111.webp?v=1741520636&width=533',
                    'https://media.istockphoto.com/id/531786318/photo/top-view-of-female-fashion-accessories.jpg?s=612x612&w=0&k=20&c=kA9wOhgfDQiz7RO6GoEztqlPNGaTxZyFwf14991aMM0=',
                    'https://thumbs.dreamstime.com/b/women-s-accessories-costume-jewelry-fashion-scarves-handbag-bags-sunglasses-glasses-cases-cosmetics-summer-rings-earrings-bracelet-145826654.jpg',
                    'https://cdn.shopify.com/s/files/1/0553/8142/6241/files/SS24_Accessorize_Sparse_10_118_480x480.jpg?v=1720772886',
                    'https://img.freepik.com/free-photo/female-workspace-with-laptop-white-background_23-2147924075.jpg?semt=ais_hybrid&w=740&q=80',
                    'https://www.luxurylifestylemag.co.uk/wp-content/uploads/2021/04/bigstock-Nail-Art-And-Design-Beautiful-357297281.jpg',

                ];
                // Duplicate images to create a seamless infinite loop
                $fullList = array_merge($instagramImages, $instagramImages);
                foreach ($fullList as $index => $img): ?>
                    <div class="insta-item">
                        <a href="https://www.instagram.com/_edluxury?igsh=c2ZiaTM0ZjgxcG5y" target="_blank"
                            class="insta-card">
                            <div class="insta-image-wrapper">
                                <img src="<?php echo $img; ?>" alt="Instagram Feed" loading="lazy">
                                <!-- Interactive Social Overlay -->
                                <div class="insta-overlay-premium">
                                    <div class="social-stats">
                                        <span><i class="bi bi-heart-fill me-1"></i> <?php echo rand(100, 999); ?></span>
                                        <span><i class="bi bi-chat-fill ms-3 me-1"></i> <?php echo rand(10, 50); ?></span>
                                    </div>
                                    <div class="insta-glare"></div>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<style>
    .instagram-marquee-container {
        width: 100%;
        overflow: hidden;
        padding: 40px 0;
        cursor: grab;
        background: #fdfdfd;
        border-top: 1px solid #f0f0f0;
        border-bottom: 1px solid #f0f0f0;
    }

    .instagram-marquee-container:active {
        cursor: grabbing;
    }

    .instagram-track {
        display: flex;
        width: max-content;
        gap: 20px;
        animation: marquee 40s linear infinite;
        padding: 0 10px;
    }

    .instagram-marquee-container:hover .instagram-track {
        animation-play-state: paused;
    }

    @keyframes marquee {
        0% {
            transform: translateX(0);
        }

        100% {
            transform: translateX(calc(-50% - 10px));
        }
    }

    .insta-item {
        width: 300px;
        flex-shrink: 0;
        perspective: 1000px;
    }

    .insta-card {
        display: block;
        border-radius: 20px;
        overflow: hidden;
        position: relative;
        background: #fff;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        transition: transform 0.1s ease-out;
        transform-style: preserve-3d;
    }

    .insta-image-wrapper {
        position: relative;
        aspect-ratio: 1;
        overflow: hidden;
    }

    .insta-image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .insta-overlay-premium {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: all 0.4s ease;
        backdrop-filter: blur(2px);
    }

    .insta-card:hover .insta-overlay-premium {
        opacity: 1;
    }

    .social-stats {
        color: white;
        font-weight: 700;
        font-size: 16px;
        transform: translateY(20px);
        transition: transform 0.4s ease;
    }

    .insta-card:hover .social-stats {
        transform: translateY(0);
    }

    /* 3D Interactive Glare */
    .insta-glare {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.3) 0%, transparent 50%, rgba(255, 255, 255, 0.1) 100%);
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .insta-card:hover .insta-glare {
        opacity: 1;
    }

    @media (max-width: 768px) {
        .insta-item {
            width: 220px;
        }

        .instagram-track {
            animation-duration: 25s;
        }
    }
</style>

<script>
    // 🎞️ ULTRA-PREMIUM VISION HERO ENGINE
    let currentVisionSlide = 1;
    let visionAutoPlay;
    const visionDuration = 8000;
    let visionStartTime;
    const slides = document.querySelectorAll('.vision-slide');
    const progressItems = document.querySelectorAll('.vision-progress-item');

    function updateVisionSlide() {
        slides.forEach((slide, i) => {
            slide.classList.toggle('active', (i + 1) === currentVisionSlide);
        });
        progressItems.forEach((item, i) => {
            item.classList.toggle('active', (i + 1) === currentVisionSlide);
            if (i + 1 !== currentVisionSlide) {
                item.querySelector('.progress-fill').style.width = '0%';
            }
        });
        visionStartTime = Date.now();
    }

    function animateProgress() {
        if (!visionStartTime) return;
        const elapsed = Date.now() - visionStartTime;
        const percentage = Math.min((elapsed / visionDuration) * 100, 100);

        const activeItem = document.querySelector('.vision-progress-item.active .progress-fill');
        if (activeItem) activeItem.style.width = percentage + '%';

        if (percentage >= 100) {
            nextVisionSlide();
        }
        requestAnimationFrame(animateProgress);
    }

    function nextVisionSlide() {
        if (slides.length === 0) return;
        currentVisionSlide = currentVisionSlide === slides.length ? 1 : currentVisionSlide + 1;
        updateVisionSlide();
    }

    window.goToSlide = function (num) {
        currentVisionSlide = num;
        updateVisionSlide();
    };

    if (slides.length > 0) {
        updateVisionSlide();
        requestAnimationFrame(animateProgress);
    }

    // 🖱️ DYNAMIC PARALLAX & CURSOR TRACKING
    const heroContainer = document.querySelector('.sh-hero-vision-container');
    if (heroContainer) {
        heroContainer.addEventListener('mousemove', e => {
            const { clientX, clientY } = e;
            const centerX = window.innerWidth / 2;
            const centerY = window.innerHeight / 2;

            const moveX = (clientX - centerX) / 50;
            const moveY = (clientY - centerY) / 100; // Reduced vertical intensity

            // Apply movement to background
            const activeImg = document.querySelector('.vision-slide.active .vision-img');
            if (activeImg) activeImg.style.transform = `scale(1.1) translate(${moveX}px, ${moveY}px)`;

            // Apply 3D movement to float card
            const floatCard = document.querySelector('.vision-slide.active .vision-float-card');
            if (floatCard) {
                const parallax = floatCard.dataset.parallax || 0.1;
                floatCard.style.transform = `translate(${moveX * 2}px, ${moveY * 2}px) rotateY(${moveX / 2}deg) rotateX(${-moveY / 2}deg)`;
            }

            // Apply movement to text
            const visionContent = document.querySelector('.vision-slide.active .vision-text-reveal');
            if (visionContent) {
                visionContent.style.transform = `translate(${-moveX / 2}px, ${-moveY / 2}px)`;
            }
        });

        heroContainer.addEventListener('mouseleave', () => {
            const activeImg = document.querySelector('.vision-slide.active .vision-img');
            if (activeImg) activeImg.style.transform = `scale(1.1) translate(0, 0)`;

            const floatCard = document.querySelector('.vision-slide.active .vision-float-card');
            if (floatCard) floatCard.style.transform = `translate(0, 0) rotateY(0) rotateX(0)`;
        });
    }

    function addToCart(productId) {
        if (typeof Cart !== 'undefined') {
            Cart.add(productId, 1);
        } else {
            console.error('Cart module not loaded');
        }
    }

    // 🚀 ADVANCED 3D INTERACTIVE INSTAGRAM CARDS
    document.querySelectorAll('.insta-card').forEach(card => {
        card.addEventListener('mousemove', e => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const centerX = rect.width / 2;
            const centerY = rect.height / 2;

            const rotateX = (y - centerY) / 10;
            const rotateY = (centerX - x) / 10;

            // Apply 3D rotation
            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.05, 1.05, 1.05)`;

            // Move glare effect based on cursor
            const glare = card.querySelector('.insta-glare');
            const glareX = (x / rect.width) * 100;
            const glareY = (y / rect.height) * 100;
            glare.style.background = `radial-gradient(circle at ${glareX}% ${glareY}%, rgba(255,255,255,0.4) 0%, transparent 60%)`;
        });

        card.addEventListener('mouseleave', () => {
            card.style.transform = `perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)`;
            const glare = card.querySelector('.insta-glare');
            glare.style.background = `linear-gradient(135deg, rgba(255,255,255,0.3) 0%, transparent 50%)`;
        });
    });

    // Handle Drag-to-Scroll (Mobile Friendly)
    const marquee = document.querySelector('.instagram-marquee-container');
    const track = document.querySelector('.instagram-track');
    let isDown = false;
    let startX;
    let scrollLeft;

    marquee.addEventListener('mousedown', (e) => {
        isDown = true;
        track.style.animationPlayState = 'paused';
        startX = e.pageX - marquee.offsetLeft;
        scrollLeft = marquee.scrollLeft;
    });

    marquee.addEventListener('mouseleave', () => {
        isDown = false;
        track.style.animationPlayState = 'running';
    });

    marquee.addEventListener('mouseup', () => {
        isDown = false;
        track.style.animationPlayState = 'running';
    });

    marquee.addEventListener('mousemove', (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - marquee.offsetLeft;
        const walk = (x - startX) * 2;
        marquee.scrollLeft = scrollLeft - walk;
    });

    // 🏆 PREMIUM BENEFITS 3D INTERACTION & SLIDER ENGINE
    document.querySelectorAll('.sh-benefit-card-premium').forEach(card => {
        const inner = card.querySelector('.sh-benefit-inner');
        const glare = card.querySelector('.sh-benefit-glare');

        card.addEventListener('mousemove', e => {
            const rect = inner.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const centerX = rect.width / 2;
            const centerY = rect.height / 2;

            const rotateX = (centerY - y) / 10;
            const rotateY = (x - centerX) / 10;

            inner.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.02, 1.02, 1.02)`;

            glare.style.opacity = '1';
            glare.style.background = `radial-gradient(circle at ${x}px ${y}px, rgba(255,255,255,0.5) 0%, transparent 80%)`;
        });

        card.addEventListener('mouseleave', () => {
            inner.style.transform = `perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)`;
            glare.style.opacity = '0';
        });
    });

    // Mobile Slider Touch Interaction (Horizontal Scroll)
    const benefitsGrid = document.querySelector('.sh-benefits-grid');
    if (benefitsGrid && window.innerWidth < 992) {
        let isMoving = false;
        let startPos = 0;
        let scrollStart = 0;

        benefitsGrid.addEventListener('touchstart', (e) => {
            isMoving = true;
            startPos = e.touches[0].pageX - benefitsGrid.offsetLeft;
            scrollStart = benefitsGrid.scrollLeft;
        });

        benefitsGrid.addEventListener('touchend', () => isMoving = false);
        benefitsGrid.addEventListener('touchcancel', () => isMoving = false);

        benefitsGrid.addEventListener('touchmove', (e) => {
            if (!isMoving) return;
            const x = e.touches[0].pageX - benefitsGrid.offsetLeft;
            const walk = (x - startPos);
            benefitsGrid.scrollLeft = scrollStart - walk;
        });
    }
</script>

<?php require_once 'includes/footer.php'; ?>
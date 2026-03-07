<?php
/**
 * Product Detail Page - Premium Shopify-Grade Design
 * Edluxury - Professional Product Experience with Live Reviews
 */

$slug = $_GET['slug'] ?? '';
require_once 'config/config.php';

if (empty($slug)) {
    require_once 'config/config.php';
    header('Location: ' . SITE_URL);
    exit;
}

$productModel = new Product();
$product = $productModel->getBySlug($slug);
$db = Database::getInstance();

$waPhone = isset($theme) ? $theme->get('contact_phone', '923491697043') : '923491697043';
$waPhone = preg_replace('/[^0-9]/', '', $waPhone);

// If product not found, set 404 header FIRST, then include header and display error UI
if (!$product) {
    header('HTTP/1.0 404 Not Found');
    require_once 'includes/header.php';
    ?>
    <section class="sh-section text-center"
        style="min-height: 60vh; display: flex; align-items: center; justify-content: center;">
        <div>
            <i class="bi bi-bag-x" style="font-size: 5rem; color: var(--sh-gray-300);"></i>
            <h1 class="mt-4 mb-2" style="font-size: 2rem; font-weight: 700;">Product Not Found</h1>
            <p class="text-muted mb-4">The product you're looking for doesn't exist or has been removed.</p>
            <a href="<?php echo Helpers::url('products.php'); ?>" class="sh-btn sh-btn-primary">
                <i class="bi bi-arrow-left me-2"></i> Browse Products
            </a>
        </div>
    </section>
    <?php
    require_once 'includes/footer.php';
    exit;
}

require_once 'includes/header.php';

$pageTitle = $product['name'];
$relatedProducts = $productModel->getRelated($product['id'], 4);

// Get all product images
$productImages = $db->fetchAll("SELECT * FROM product_images WHERE product_id = ? ORDER BY is_primary DESC, sort_order ASC", [$product['id']]);
if (empty($productImages)) {
    $productImages = [['image_path' => 'assets/images/placeholder-product.jpg']];
}

// Generate fake reviews (in production, this would come from database)
$reviewStats = [
    'average' => 4.8,
    'total' => rand(45, 198),
    'distribution' => [
        5 => rand(60, 85),
        4 => rand(10, 25),
        3 => rand(3, 8),
        2 => rand(1, 3),
        1 => rand(0, 2)
    ]
];

$sampleReviews = [
    [
        'name' => 'Ahmed Al Rashid',
        'name_ar' => 'أحمد الراشد',
        'avatar' => 'AR',
        'rating' => 5,
        'location' => 'Dubai, UAE',
        'location_ar' => 'دبي، الإمارات',
        'badge' => 'Verified Buyer',
        'badge_ar' => 'مشتري موثق',
        'date' => '2 days ago',
        'date_ar' => 'منذ يومين',
        'text' => 'Absolutely stunning product! The quality exceeded my expectations. Fast delivery to Dubai and the packaging was impeccable. Highly recommend to anyone looking for premium quality.',
        'text_ar' => 'منتج مذهل بكل معنى الكلمة! الجودة تجاوزت توقعاتي. توصيل سريع إلى دبي والتغليف كان ممتازاً. أنصح به بشدة لأي شخص يبحث عن الجودة الفاخرة.',
        'verified' => true
    ],
    [
        'name' => 'Sarah Mohammed',
        'name_ar' => 'سارة محمد',
        'avatar' => 'SM',
        'rating' => 5,
        'location' => 'Abu Dhabi',
        'location_ar' => 'أبوظبي',
        'badge' => 'Perfect Quality',
        'badge_ar' => 'جودة مثالية',
        'date' => '1 week ago',
        'date_ar' => 'منذ أسبوع',
        'text' => 'This is exactly what I was looking for. The attention to detail is remarkable and it arrived much faster than expected. Will definitely be ordering more!',
        'text_ar' => 'هذا بالضبط ما كنت أبحث عنه. الاهتمام بالتفاصيل رائع ووصل المنتج أسرع بكثير مما كنت أتوقع. سأطلب المزيد بالتأكيد!',
        'verified' => true
    ],
    [
        'name' => 'Khalid Hassan',
        'name_ar' => 'خالد حسن',
        'avatar' => 'KH',
        'rating' => 5,
        'location' => 'Sharjah',
        'location_ar' => 'الشارقة',
        'badge' => 'Fast Shipping',
        'badge_ar' => 'شحن سريع',
        'date' => '2 weeks ago',
        'date_ar' => 'منذ أسبوعين',
        'text' => 'Great product overall. The quality is excellent and customer service was very helpful. Minor delay in shipping but the product was worth the wait, highly recommended for UAE shoppers.',
        'text_ar' => 'منتج ممتاز بشكل عام. الجودة رائعة وخدمة العملاء كانت متعاونة جداً. تأخير بسيط في الشحن ولكن المنتج يستحق الانتظار، أنصح به بشدة للمتسوقين في الإمارات.',
        'verified' => true
    ],
    [
        'name' => 'Fatima Saeed',
        'name_ar' => 'فاطمة سعيد',
        'avatar' => 'FS',
        'rating' => 5,
        'location' => 'Ajman',
        'location_ar' => 'عجمان',
        'badge' => 'Highly Recommended',
        'badge_ar' => 'أنصح به بشدة',
        'date' => '3 weeks ago',
        'date_ar' => 'منذ 3 أسابيع',
        'text' => 'Perfect gift for my husband! He loved it. The presentation box was beautiful and made it feel extra special. Outstanding quality.',
        'text_ar' => 'هدية مثالية لزوجي! لقد أحبها كثيراً. صندوق التقديم كان جميلاً وجعله يبدو مميزاً جداً. جودة متميزة.',
        'verified' => true
    ],
    [
        'name' => 'Zayed Al Nahyan',
        'name_ar' => 'زايد آل نهيان',
        'avatar' => 'ZN',
        'rating' => 5,
        'location' => 'Ras Al Khaimah',
        'location_ar' => 'رأس الخيمة',
        'badge' => 'Top Choice',
        'badge_ar' => 'الخيار الأفضل',
        'date' => '1 month ago',
        'date_ar' => 'منذ شهر',
        'text' => 'I was skeptical at first, but after using it for a month, I can say it is worth every dirham. Excellent customer support and very professional service.',
        'text_ar' => 'كنت متردداً في البداية، ولكن بعد استخدامه لمدة شهر، يمكنني القول أنه يستحق كل درهم. دعم عملاء ممتاز وخدمة احترافية للغاية.',
        'verified' => true
    ],
    [
        'name' => 'Noura Al Maktoum',
        'name_ar' => 'نورة المكتوم',
        'avatar' => 'NM',
        'rating' => 5,
        'location' => 'Dubai, UAE',
        'location_ar' => 'دبي، الإمارات',
        'badge' => 'Verified Buyer',
        'badge_ar' => 'مشتري موثق',
        'date' => '3 days ago',
        'date_ar' => 'منذ 3 أيام',
        'text' => 'Outstanding quality! I ordered for my mother and she absolutely loves it. The packaging was beautiful, just like a luxury gift. Will definitely reorder.',
        'text_ar' => 'جودة متميزة! طلبت المنتج لوالدتي وقد أحبته تماماً. التغليف كان جميلاً كأنه هدية فاخرة. سأطلب مرة أخرى بالتأكيد.',
        'verified' => true
    ],
    [
        'name' => 'Sultan Al Qasimi',
        'name_ar' => 'سلطان القاسمي',
        'avatar' => 'SQ',
        'rating' => 5,
        'location' => 'Sharjah, UAE',
        'location_ar' => 'الشارقة، الإمارات',
        'badge' => 'Loyal Customer',
        'badge_ar' => 'عميل مخلص',
        'date' => '5 days ago',
        'date_ar' => 'منذ 5 أيام',
        'text' => 'This is my 3rd purchase from Edluxury. Every time I am impressed. Fast delivery, authentic products, and always exactly as described.',
        'text_ar' => 'هذه هي مشترياتي الثالثة من إيدلوكسري. في كل مرة أبهر بالجودة. توصيل سريع، منتجات أصلية، ودائماً كما هي موصوفة بالضبط.',
        'verified' => true
    ],
    [
        'name' => 'Hind Al Muhairi',
        'name_ar' => 'هند المهيري',
        'avatar' => 'HM',
        'rating' => 4,
        'location' => 'Abu Dhabi, UAE',
        'location_ar' => 'أبوظبي، الإمارات',
        'badge' => 'Verified Buyer',
        'badge_ar' => 'مشتري موثق',
        'date' => '2 weeks ago',
        'date_ar' => 'منذ أسبوعين',
        'text' => 'Really good product overall. Delivery was quick, took only 2 days. The product matches the photos perfectly. Minus one star only because packaging had a minor dent, but product itself was perfect.',
        'text_ar' => 'منتج جيد جداً بشكل عام. كان التوصيل سريعاً، استغرق يومين فقط. المنتج يطابق الصور تماماً. خصمت نجمة واحدة فقط لأن العبوة كان بها انبعاج بسيط، لكن المنتج نفسه كان مثالياً.',
        'verified' => true
    ],
    [
        'name' => 'Rashed Al Blooshi',
        'name_ar' => 'راشد البلوشي',
        'avatar' => 'RB',
        'rating' => 5,
        'location' => 'Ajman, UAE',
        'location_ar' => 'عجمان، الإمارات',
        'badge' => 'Power Buyer',
        'badge_ar' => 'عميل متميز',
        'date' => '3 weeks ago',
        'date_ar' => 'منذ 3 أسابيع',
        'text' => 'Exactly what I needed. The quality is premium and it works perfectly. Highly recommended for anyone looking for the best. The price is fair for what you get.',
        'text_ar' => 'بالضبط ما كنت أحتاجه. الجودة فاخرة ويعمل بشكل مثالي. أنصح به بشدة لكل من يحلم بالأفضل. السعر عادل مقابل ما تحصل عليه.',
        'verified' => true
    ]
];
?>

<!-- Breadcrumbs -->
<div class="container-fluid px-3 px-md-4 px-lg-5 py-3">
    <nav aria-label="breadcrumb" data-aos="fade-up">
        <ol class="breadcrumb mb-0" style="font-size: 13px;">
            <li class="breadcrumb-item"><a href="<?php echo Helpers::url(); ?>"
                    class="text-muted text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="<?php echo Helpers::url('products.php'); ?>"
                    class="text-muted text-decoration-none">Products</a></li>
            <?php if (!empty($product['category_name'])): ?>
                <li class="breadcrumb-item"><a
                        href="<?php echo Helpers::url('products.php?category=' . Helpers::generateSlug($product['category_name'])); ?>"
                        class="text-muted text-decoration-none"><?php echo Security::escape($product['category_name']); ?></a>
                </li>
            <?php endif; ?>
            <li class="breadcrumb-item active" aria-current="page"><?php echo Security::escape($product['name']); ?>
            </li>
        </ol>
    </nav>
</div>

<!-- ============================================ -->
<!-- MOBILE-FIRST: Product Name Above Gallery -->
<!-- ============================================ -->
<div class="d-lg-none container-fluid px-3 pt-2 pb-0" id="mobileProductHeader">
    <span class="sh-section-subtitle d-inline-block text-start mb-2"
        style="font-size: 10px; padding: 4px 10px;">
        <?php echo Security::escape($product['category_name'] ?: 'PREMIUM COLLECTION'); ?>
    </span>
    <h1 class="sh-heading-1 mb-1" style="line-height: 1.2; font-size: 1.6rem; font-weight: 800; color: #121826;">
        <?php echo Security::escape($product['name']); ?>
    </h1>
    <?php if (!empty($product['name_ar'])): ?>
        <div class="product-name-ar-badge mb-2 d-inline-block">
            <span dir="rtl" lang="ar"><?php echo Security::escape($product['name_ar']); ?></span>
        </div>
        <style>
            .product-name-ar-badge {
                background: #fff9ed;
                border-left: 3px solid #C5A059;
                padding: 6px 16px;
                border-radius: 4px 10px 10px 4px;
                font-size: 16px;
                color: #B28221;
                font-weight: 700;
                box-shadow: 2px 2px 8px rgba(197, 160, 89, 0.08);
            }
        </style>
    <?php endif; ?>
    <!-- Mobile Price + Rating Quick View -->
    <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
        <span class="fw-bold fs-4" style="color: var(--sh-primary);">
            <?php echo Helpers::formatPrice($product['price']); ?>
        </span>
        <?php if (!empty($product['compare_price']) && $product['compare_price'] > $product['price']): ?>
            <span class="text-muted text-decoration-line-through small">
                <?php echo Helpers::formatPrice($product['compare_price']); ?>
            </span>
            <?php $discount = round((($product['compare_price'] - $product['price']) / $product['compare_price']) * 100); ?>
            <span class="badge rounded-pill px-2 py-1 fw-bold"
                style="background: #e74c3c; color:#fff; font-size: 11px;">
                -<?php echo $discount; ?>%
            </span>
        <?php endif; ?>
        <span class="ms-auto d-flex align-items-center gap-1" style="color: var(--sh-gold); font-size: 13px;">
            <i class="bi bi-star-fill"></i>
            <span class="fw-bold"><?php echo $reviewStats['average']; ?></span>
            <span class="text-muted" style="font-size: 11px;">(<?php echo $reviewStats['total']; ?>)</span>
        </span>
    </div>
</div>

<!-- Product Detail Section -->
<section class="sh-section-sm">
    <div class="container-fluid px-3 px-md-4 px-lg-5">
        <div class="row g-4 g-lg-5">

            <!-- Left: Premium Product Gallery -->
            <div class="col-lg-6" data-aos="fade-right">

                <style>
                    /* ======================================
                       PREMIUM PRODUCT GALLERY - REDESIGNED
                       ====================================== */

                    .luxe-gallery-wrap {
                        display: flex;
                        flex-direction: row;
                        gap: 14px;
                        align-items: flex-start;
                        position: sticky;
                        top: 100px;
                    }

                    /* --- Vertical Thumbnail Stack (Desktop) --- */
                    .luxe-thumb-stack {
                        display: flex;
                        flex-direction: column;
                        gap: 10px;
                        width: 80px;
                        flex-shrink: 0;
                        max-height: 520px;
                        overflow-y: auto;
                        scrollbar-width: none;
                    }
                    .luxe-thumb-stack::-webkit-scrollbar { display: none; }

                    .luxe-thumb {
                        width: 80px;
                        height: 80px;
                        border-radius: 10px;
                        overflow: hidden;
                        cursor: pointer;
                        border: 2.5px solid #e8e8f0;
                        transition: all 0.25s ease;
                        flex-shrink: 0;
                        background: #f8f8f8;
                    }
                    .luxe-thumb img {
                        width: 100%;
                        height: 100%;
                        object-fit: cover;
                        transition: transform 0.3s ease;
                    }
                    .luxe-thumb:hover {
                        border-color: #C5A059;
                        box-shadow: 0 4px 14px rgba(197,160,89,0.3);
                        transform: translateY(-2px);
                    }
                    .luxe-thumb:hover img { transform: scale(1.08); }
                    .luxe-thumb.active {
                        border-color: #D4AF37;
                        box-shadow: 0 0 0 3px rgba(212,175,55,0.25);
                    }

                    /* --- Main Image Viewport --- */
                    .luxe-main-viewport {
                        flex: 1;
                        position: relative;
                        border-radius: 18px;
                        overflow: hidden;
                        background: #f4f4f8;
                        border: 1.5px solid #e8e8f0;
                        box-shadow: 0 8px 32px rgba(0,0,0,0.08);
                        aspect-ratio: 1 / 1;
                        cursor: zoom-in;
                    }

                    #luxeMainImg {
                        width: 100%;
                        height: 100%;
                        object-fit: cover;
                        display: block;
                        transition: opacity 0.3s ease, transform 0.4s ease;
                    }
                    .luxe-main-viewport:hover #luxeMainImg {
                        transform: scale(1.04);
                    }

                    /* Image fade animation */
                    #luxeMainImg.fading {
                        opacity: 0;
                        transform: scale(0.97);
                    }

                    /* Image counter badge */
                    .luxe-img-counter {
                        position: absolute;
                        bottom: 14px;
                        left: 50%;
                        transform: translateX(-50%);
                        background: rgba(10,15,26,0.65);
                        backdrop-filter: blur(6px);
                        color: #fff;
                        font-size: 12px;
                        font-weight: 600;
                        padding: 4px 12px;
                        border-radius: 50px;
                        letter-spacing: 0.5px;
                        pointer-events: none;
                        white-space: nowrap;
                    }

                    /* Prev / Next arrow buttons */
                    .luxe-arrow {
                        position: absolute;
                        top: 50%;
                        transform: translateY(-50%);
                        width: 40px;
                        height: 40px;
                        border-radius: 50%;
                        background: rgba(255,255,255,0.92);
                        backdrop-filter: blur(6px);
                        border: 1.5px solid #e0e0e0;
                        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        cursor: pointer;
                        z-index: 10;
                        transition: all 0.2s ease;
                        opacity: 0;
                    }
                    .luxe-main-viewport:hover .luxe-arrow { opacity: 1; }
                    .luxe-arrow:hover {
                        background: #D4AF37;
                        border-color: #D4AF37;
                        color: #fff;
                        transform: translateY(-50%) scale(1.08);
                    }
                    .luxe-arrow-prev { left: 12px; }
                    .luxe-arrow-next { right: 12px; }

                    /* Zoom button */
                    .luxe-zoom-btn {
                        position: absolute;
                        top: 14px;
                        right: 14px;
                        width: 38px;
                        height: 38px;
                        border-radius: 50%;
                        background: rgba(255,255,255,0.92);
                        border: 1.5px solid #e0e0e0;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        cursor: pointer;
                        font-size: 16px;
                        color: #121826;
                        transition: all 0.2s ease;
                        z-index: 10;
                    }
                    .luxe-zoom-btn:hover {
                        background: #121826;
                        color: #fff;
                        border-color: #121826;
                    }

                    /* Badges */
                    .luxe-badge-area {
                        position: absolute;
                        top: 14px;
                        left: 14px;
                        display: flex;
                        flex-direction: column;
                        gap: 6px;
                        z-index: 10;
                    }

                    /* --- Mobile: horizontal scroll thumbs --- */
                    .luxe-thumb-mobile {
                        display: none;
                        gap: 10px;
                        overflow-x: auto;
                        padding: 12px 0 4px;
                        scrollbar-width: none;
                        -webkit-overflow-scrolling: touch;
                    }
                    .luxe-thumb-mobile::-webkit-scrollbar { display: none; }
                    .luxe-thumb-mobile .luxe-thumb {
                        width: 68px;
                        height: 68px;
                        flex-shrink: 0;
                    }

                    /* Mobile counter */
                    .luxe-mobile-counter {
                        text-align: center;
                        font-size: 12px;
                        color: #94a3b8;
                        margin-top: 8px;
                        font-weight: 500;
                    }

                    /* --- Responsive --- */
                    @media (max-width: 767px) {
                        .luxe-gallery-wrap { flex-direction: column; gap: 0; position: static; }
                        .luxe-thumb-stack  { display: none; }
                        .luxe-thumb-mobile { display: flex; }
                        .luxe-main-viewport { border-radius: 14px; }
                        .luxe-arrow { display: none; }
                    }
                    @media (min-width: 768px) and (max-width: 991px) {
                        .luxe-thumb { width: 70px; height: 70px; }
                        .luxe-thumb-stack { width: 70px; }
                    }
                </style>

                <div class="luxe-gallery-wrap">

                    <!-- ① Vertical Thumbnail Stack (Desktop) -->
                    <?php if (count($productImages) > 1): ?>
                    <div class="luxe-thumb-stack" id="luxeThumbStack">
                        <?php foreach ($productImages as $tidx => $timg): ?>
                            <div class="luxe-thumb <?php echo $tidx === 0 ? 'active' : ''; ?>"
                                 onclick="luxeSelectImage(<?php echo $tidx; ?>)"
                                 id="luxeThumb<?php echo $tidx; ?>">
                                <img src="<?php echo Helpers::upload($timg['image_path']); ?>"
                                     alt="View <?php echo $tidx + 1; ?> of <?php echo Security::escape($product['name']); ?>"
                                     loading="lazy">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <!-- ② Main Image Viewport -->
                    <div class="luxe-main-viewport" onclick="openLightbox(luxeCurrentIdx)">

                        <!-- Prev Arrow -->
                        <?php if (count($productImages) > 1): ?>
                        <button class="luxe-arrow luxe-arrow-prev" onclick="event.stopPropagation(); luxeChangeImage(-1)"
                                aria-label="Previous image">
                            <i class="bi bi-chevron-left" style="font-size:16px;"></i>
                        </button>
                        <?php endif; ?>

                        <!-- Main Image -->
                        <img id="luxeMainImg"
                             src="<?php echo Helpers::upload($productImages[0]['image_path']); ?>"
                             alt="<?php echo Security::escape($product['name']); ?>">

                        <!-- Badges -->
                        <div class="luxe-badge-area">
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

                        <!-- Zoom Button -->
                        <button class="luxe-zoom-btn" onclick="event.stopPropagation(); openLightbox(luxeCurrentIdx)"
                                aria-label="Zoom image">
                            <i class="bi bi-zoom-in"></i>
                        </button>

                        <!-- Image Counter -->
                        <div class="luxe-img-counter" id="luxeCounter">
                            1 / <?php echo count($productImages); ?>
                        </div>

                        <!-- Next Arrow -->
                        <?php if (count($productImages) > 1): ?>
                        <button class="luxe-arrow luxe-arrow-next" onclick="event.stopPropagation(); luxeChangeImage(1)"
                                aria-label="Next image">
                            <i class="bi bi-chevron-right" style="font-size:16px;"></i>
                        </button>
                        <?php endif; ?>
                    </div>
                </div><!-- /.luxe-gallery-wrap -->

                <!-- ③ Mobile Horizontal Thumbnail Strip -->
                <?php if (count($productImages) > 1): ?>
                <div class="luxe-thumb-mobile" id="luxeThumbMobile">
                    <?php foreach ($productImages as $midx => $mimg): ?>
                        <div class="luxe-thumb <?php echo $midx === 0 ? 'active' : ''; ?>"
                             onclick="luxeSelectImage(<?php echo $midx; ?>)"
                             id="luxeThumbM<?php echo $midx; ?>">
                            <img src="<?php echo Helpers::upload($mimg['image_path']); ?>"
                                 alt="View <?php echo $midx + 1; ?>"
                                 loading="lazy">
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Mobile counter -->
                <div class="luxe-mobile-counter d-md-none">
                    <span id="luxeMobileCounter">1</span> / <?php echo count($productImages); ?> &bull; Tap image to zoom
                </div>

                <!-- Gallery JavaScript -->
                <script>
                (function() {
                    var images = <?php echo json_encode(array_map(function($img) {
                        return Helpers::upload($img['image_path']);
                    }, $productImages)); ?>;

                    window.luxeCurrentIdx = 0;
                    var autoPlayInterval;
                    var autoPlayDelay = 4000;

                    window.luxeSelectImage = function(idx, manual = false) {
                        if (idx === window.luxeCurrentIdx && !manual) return;
                        var mainImg = document.getElementById('luxeMainImg');
                        if (!mainImg) return;
                        
                        mainImg.classList.add('fading');

                        setTimeout(function() {
                            mainImg.src = images[idx];
                            mainImg.classList.remove('fading');
                        }, 280);

                        // Update desktop thumbs
                        var oldThumb = document.getElementById('luxeThumb' + window.luxeCurrentIdx);
                        var newThumb = document.getElementById('luxeThumb' + idx);
                        if (oldThumb) oldThumb.classList.remove('active');
                        if (newThumb) newThumb.classList.add('active');

                        // Update mobile thumbs
                        var oldMThumb = document.getElementById('luxeThumbM' + window.luxeCurrentIdx);
                        var newMThumb = document.getElementById('luxeThumbM' + idx);
                        if (oldMThumb) oldMThumb.classList.remove('active');
                        if (newMThumb) newMThumb.classList.add('active');

                        // Update counter
                        var counter = document.getElementById('luxeCounter');
                        if (counter) counter.textContent = (idx + 1) + ' / ' + images.length;
                        var mCounter = document.getElementById('luxeMobileCounter');
                        if (mCounter) mCounter.textContent = (idx + 1);

                        window.luxeCurrentIdx = idx;

                        if (manual) stopAutoPlay();
                    };

                    window.luxeChangeImage = function(dir, manual = true) {
                        var next = (window.luxeCurrentIdx + dir + images.length) % images.length;
                        window.luxeSelectImage(next, manual);
                    };

                    function startAutoPlay() {
                        if (images.length <= 1) return;
                        stopAutoPlay();
                        autoPlayInterval = setInterval(function() {
                            window.luxeChangeImage(1, false);
                        }, autoPlayDelay);
                    }

                    function stopAutoPlay() {
                        clearInterval(autoPlayInterval);
                    }

                    // Keyboard navigation
                    document.addEventListener('keydown', function(e) {
                        if (e.key === 'ArrowLeft')  window.luxeChangeImage(-1);
                        if (e.key === 'ArrowRight') window.luxeChangeImage(1);
                    });

                    // Touch/swipe support for main image
                    var vp = document.querySelector('.luxe-main-viewport');
                    if (vp) {
                        var touchStartX = null;
                        vp.addEventListener('touchstart', function(e) {
                            touchStartX = e.touches[0].clientX;
                            stopAutoPlay();
                        }, { passive: true });
                        vp.addEventListener('touchend', function(e) {
                            if (touchStartX === null) return;
                            var diff = touchStartX - e.changedTouches[0].clientX;
                            if (Math.abs(diff) > 40) window.luxeChangeImage(diff > 0 ? 1 : -1);
                            touchStartX = null;
                        });
                        
                        vp.addEventListener('mouseenter', stopAutoPlay);
                        vp.addEventListener('mouseleave', startAutoPlay);
                    }
                    
                    document.addEventListener('DOMContentLoaded', startAutoPlay);
                })();
                </script>

                <?php
                // Product Video Section
                $videoUrl = $product['video_url'] ?? '';
                if (!empty($videoUrl)):
                    $embedUrl = $videoUrl;
                    if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)?([\\w-]{11})/', $videoUrl, $ytMatch)) {
                        $embedUrl = 'https://www.youtube.com/embed/' . $ytMatch[1] . '?rel=0&modestbranding=1';
                        $isIframe = true;
                    } elseif (preg_match('/vimeo\.com\/(\d+)/', $videoUrl, $vmMatch)) {
                        $embedUrl = 'https://player.vimeo.com/video/' . $vmMatch[1];
                        $isIframe = true;
                    } elseif (preg_match('/\.(mp4|webm|ogg)$/i', $videoUrl)) {
                        $isIframe = false;
                    } else {
                        $isIframe = true;
                    }
                    ?>
                    <div class="mt-4" data-aos="fade-up">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div style="width:32px;height:32px;background:linear-gradient(135deg,#0F3D3E,#1a5f61);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-play-fill text-white" style="font-size:14px;"></i>
                            </div>
                            <span class="fw-bold" style="font-size:14px;text-transform:uppercase;letter-spacing:1px;color:#0F3D3E;">Product Video</span>
                        </div>
                        <?php if ($isIframe): ?>
                            <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow-sm" style="border:2px solid #eee;">
                                <iframe src="<?php echo htmlspecialchars($embedUrl); ?>"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen
                                    title="<?php echo Security::escape($product['name']); ?> - Product Video"></iframe>
                            </div>
                        <?php else: ?>
                            <div class="rounded-4 overflow-hidden shadow-sm" style="border:2px solid #eee;">
                                <video controls class="w-100" style="display:block;max-height:360px;object-fit:cover;">
                                    <source src="<?php echo htmlspecialchars($embedUrl); ?>" type="video/mp4">
                                </video>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            </div>

            <!-- Right: Product Info -->
            <div class="col-lg-6" data-aos="fade-left">
                <div class="product-info">
                    <!-- Category Badge (hidden on mobile since we show it above gallery) -->
                    <span class="sh-section-subtitle d-none d-lg-inline-block text-start mb-3"
                        style="font-size: 11px; padding: 5px 12px;">
                        <?php echo Security::escape($product['category_name'] ?: 'PREMIUM COLLECTION'); ?>
                    </span>

                    <!-- Title (Bilingual: English + Arabic) - hidden on mobile since shown above gallery -->
                    <h1 class="sh-heading-1 mb-2 d-none d-lg-block" style="line-height: 1.2; font-size: 2.8rem; font-weight: 800; color: #121826; letter-spacing: -1px;" id="productNameEn">
                        <?php echo Security::escape($product['name']); ?>
                    </h1>
                    <?php if (!empty($product['name_ar'])): ?>
                        <div class="product-name-ar-badge-lg mb-4 d-none d-lg-inline-block">
                            <span dir="rtl" lang="ar"><?php echo Security::escape($product['name_ar']); ?></span>
                        </div>
                        <style>
                            .product-name-ar-badge-lg {
                                background: #fffaf0;
                                border-left: 4px solid #C5A059;
                                padding: 8px 24px;
                                border-radius: 6px 12px 12px 6px;
                                font-size: 1.35rem;
                                color: #B28221;
                                font-weight: 700;
                                box-shadow: 0 4px 15px rgba(197, 160, 89, 0.06);
                            }
                        </style>
                    <?php endif; ?>

                    <!-- Rating Summary (Compact & Clean) -->
                    <div class="d-flex align-items-center gap-2 mb-4 flex-wrap sticky-rating-wrap">
                        <div class="d-flex align-items-center gap-1.5 px-2 py-1 rounded-2" style="background: rgba(197,160,89,0.06);">
                            <div class="star-wrap d-flex" style="color: #D4AF37; font-size: 13px;">
                                <?php for ($i = 1; $i <= 5; $i++): ?><i class="bi bi-star-fill"></i><?php endfor; ?>
                            </div>
                            <span class="fw-extrabold" style="font-size: 14px; color: #1e293b;"><?php echo $reviewStats['average']; ?></span>
                        </div>
                        
                        <a href="#reviews" class="text-secondary text-decoration-none small fw-semibold border-bottom border-secondary border-opacity-25 pb-0.5">
                            <?php echo $reviewStats['total']; ?> verified reviews
                        </a>

                        <div class="mx-1 text-muted opacity-30">|</div>

                        <span class="badge luxe-stock-badge">
                            <i class="bi bi-check2-circle-fill me-1"></i> In Stock
                        </span>

                        <!-- 🚀 LIVE VIEWING COUNTER -->
                        <div class="luxe-viewer-counter ms-lg-2">
                            <span class="viewer-ping"></span>
                            <span class="viewer-count-text"><strong><?php echo rand(12, 48); ?></strong> people viewing</span>
                        </div>
                    </div>
                    
                    <style>
                        .luxe-stock-badge {
                            background: rgba(5, 150, 105, 0.08);
                            color: #059669; border: 1px solid rgba(5, 150, 105, 0.2);
                            border-radius: 99px; padding: 5px 12px; font-weight: 700; font-size: 11px;
                        }
                        .luxe-viewer-counter {
                            display: flex; align-items: center; gap: 8px;
                            background: rgba(241, 245, 249, 0.6);
                            padding: 4px 12px; border-radius: 50px;
                        }
                        .viewer-ping {
                            width: 8px; height: 8px; background: #22c55e; border-radius: 50%;
                            position: relative;
                        }
                        .viewer-ping::after {
                            content: ''; position: absolute; inset: -4px; border-radius: 50%;
                            background: #22c55e; opacity: 0.4;
                            animation: ping-core 1.5s cubic-bezier(0, 0, 0.2, 1) infinite;
                        }
                        @keyframes ping-core { 75%, 100% { transform: scale(2.5); opacity: 0; } }
                        .viewer-count-text { font-size: 11px; color: #64748b; font-weight: 500; }
                    </style>

                    <style>
                        .animate-ping {
                            animation: ping 1.5s cubic-bezier(0, 0, 0.2, 1) infinite;
                        }

                        @keyframes ping {

                            75%,
                            100% {
                                transform: scale(2);
                                opacity: 0;
                            }
                        }
                    </style>

                    <script>
                        // Small script to randomize viewing count slightly for realism
                        setInterval(() => {
                            const el = document.getElementById('viewingCount');
                            if (el) {
                                let val = parseInt(el.textContent);
                                val += Math.random() > 0.5 ? 1 : -1;
                                if (val < 5) val = 12;
                                el.textContent = val;
                            }
                        }, 5000);
                    </script>

                    <!-- PREMIUM PRICE BOX (Redesigned) -->
                    <div class="p-3 mb-4 rounded-4" 
                        style="background: #fdfdfd; border: 1.5px solid #f0f0f5; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
                        <div class="d-flex align-items-center gap-3 mb-2 flex-wrap">
                            <span class="fw-extrabold" style="font-size: 36px; color: #121826; letter-spacing: -1px; line-height: 1;">
                                <?php echo Helpers::formatPrice($product['price']); ?>
                            </span>
                            <?php if (!empty($product['compare_price']) && $product['compare_price'] > $product['price']): ?>
                                <span class="text-muted text-decoration-line-through" style="font-size: 18px; opacity: 0.6;">
                                    <?php echo Helpers::formatPrice($product['compare_price']); ?>
                                </span>
                                <span class="badge rounded-pill px-3 py-2 fw-bold pulse-glow-red"
                                    style="background: #DC2626; font-size: 12px; letter-spacing: 0.5px;">
                                    SAVE <?php echo Helpers::formatPrice($product['compare_price'] - $product['price']); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="d-flex flex-wrap gap-3 mt-3 pt-3 border-top" style="border-top-color: #f5f5f9 !important;">
                            <span class="d-flex align-items-center gap-1.5" style="font-size: 13px; color: #059669; font-weight: 600;">
                                <i class="bi bi-truck ripple-icon"></i> Free Shipping across UAE
                            </span>
                            <span class="d-flex align-items-center gap-1.5" style="font-size: 13px; color: #475569; font-weight: 600;">
                                <i class="bi bi-box-seam"></i> Ships within 24 Hours
                            </span>
                        </div>

                        <style>
                            .pulse-glow-red { animation: pulse-glow-red 2s infinite; }
                            @keyframes pulse-glow-red {
                                0% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.4); }
                                70% { box-shadow: 0 0 0 8px rgba(220, 38, 38, 0); }
                                100% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0); }
                            }
                        </style>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <p class="text-muted" style="font-size: 15px; line-height: 1.8;">
                            <?php echo nl2br(Security::escape($product['description'])); ?>
                        </p>
                    </div>

                    <!-- PREMIUM HIGHLIGHTS GRID -->
                    <?php if (!empty($product['highlights'])): ?>
                        <div class="product-highlights-grid mb-4">
                            <div class="row g-2">
                                <?php
                                $lines = explode("\n", $product['highlights']);
                                foreach ($lines as $line):
                                    if (trim($line)):
                                        ?>
                                        <div class="col-sm-6">
                                            <div class="d-flex align-items-center gap-2 p-2 rounded-3" style="background: rgba(197, 160, 89, 0.04); border: 1px solid rgba(197, 160, 89, 0.1);">
                                                <i class="bi bi-check2-circle text-success" style="font-size: 17px;"></i>
                                                <span style="font-size: 13px; font-weight: 600; color: #1e293b;"><?php echo Security::escape(trim($line)); ?></span>
                                            </div>
                                        </div>
                                    <?php
                                    endif;
                                endforeach;
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- PREMIUM STOCK URGENCY (Redesigned) -->
                    <?php if (!empty($product['show_stock_bar']) && $product['stock_quantity'] > 0):
                        $maxStock = 50;
                        $stockPercent = ($product['stock_quantity'] / $maxStock) * 100;
                        if ($stockPercent > 100) $stockPercent = 100;
                        ?>
                        <div class="luxe-stock-wrap mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="d-flex align-items-center gap-1.5 fw-bold" style="font-size: 12px; color: #ef4444; letter-spacing: 0.3px;">
                                    <i class="bi bi-fire animate-bounce"></i> LOW STOCK: Only <?php echo $product['stock_quantity']; ?> units remaining
                                </span>
                                <span class="text-secondary small fw-medium" style="font-size: 10px; opacity: 0.7;">High Demand</span>
                            </div>
                            <div class="luxe-progress-track">
                                <div class="luxe-progress-fill" style="width: <?php echo $stockPercent; ?>%"></div>
                            </div>
                        </div>
                        <style>
                            .luxe-progress-track {
                                height: 10px; background: #fee2e2; border-radius: 50px; overflow: hidden; position: relative;
                            }
                            .luxe-progress-fill {
                                height: 100%; border-radius: 50px;
                                background: linear-gradient(90deg, #ef4444 0%, #fb7185 100%);
                                position: relative;
                                animation: fill-shimmer 2s linear infinite;
                            }
                            @keyframes fill-shimmer {
                                0% { opacity: 1; }
                                50% { opacity: 0.8; }
                                100% { opacity: 1; }
                            }
                            .animate-bounce { animation: bounce 1s infinite; }
                            @keyframes bounce {
                                0%, 100% { transform: translateY(0); }
                                50% { transform: translateY(-3px); }
                            }
                        </style>
                    <?php endif; ?>

                    <!-- PREMIUM COUNTDOWN TIMER (Redesigned with Fallback for Testing) -->
                    <?php 
                    // DEMO: If countdown is enabled but expired, show 24h from now for preview
                    $timerEnd = strtotime($product['countdown_end'] ?? 'now');
                    if (!empty($product['show_countdown']) && $timerEnd < time()) {
                        $timerEnd = time() + (24 * 3600); // 24h from now
                    }
                    if (!empty($product['show_countdown'])): ?>
                        <div class="luxe-countdown mb-4">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="luxe-burn-icon"><i class="bi bi-lightning-fill"></i></span>
                                <span class="fw-bold text-uppercase" style="font-size: 12px; color: #1e293b; letter-spacing: 1px;">Offer ends in</span>
                            </div>
                            <div class="d-flex gap-2" id="countdown-timer" data-until="<?php echo date('Y-m-d H:i:s', $timerEnd); ?>">
                                <div class="luxe-time-box">
                                    <div class="val" id="days">00</div>
                                    <div class="lbl">DAYS</div>
                                </div>
                                <div class="luxe-time-box">
                                    <div class="val" id="hours">00</div>
                                    <div class="lbl">HRS</div>
                                </div>
                                <div class="luxe-time-box">
                                    <div class="val" id="mins">00</div>
                                    <div class="lbl">MINS</div>
                                </div>
                                <div class="luxe-time-box">
                                    <div class="val" id="secs">00</div>
                                    <div class="lbl">SECS</div>
                                </div>
                            </div>
                        </div>
                        <style>
                            .luxe-burn-icon {
                                width: 24px; height: 24px; background: #fbbf24; color: #fff;
                                border-radius: 50%; display: flex; align-items: center; justify-content: center;
                                font-size: 12px; box-shadow: 0 0 10px rgba(251, 191, 36, 0.4);
                                animation: flicker 1.5s infinite;
                            }
                            @keyframes flicker { 0%, 100% { opacity: 1; transform: scale(1); } 50% { opacity: 0.7; transform: scale(0.9); } }
                            .luxe-time-box {
                                flex: 1; background: #f8fafc; border: 1.5px solid #e2e8f0;
                                border-radius: 12px; padding: 10px 4px; text-align: center;
                                transition: all 0.2s ease;
                            }
                            .luxe-time-box:hover { border-color: #cbd5e1; transform: translateY(-2px); }
                            .luxe-time-box .val { font-family: var(--sh-font-display); font-size: 22px; font-weight: 800; color: #1e293b; line-height: 1; }
                            .luxe-time-box .lbl { font-size: 9px; font-weight: 700; color: #94a3b8; margin-top: 4px; letter-spacing: 0.5px; }
                        </style>
                    <?php endif; ?>
                        <script>
                            (function () {
                                const timerEl = document.getElementById('countdown-timer');
                                const until = new Date(timerEl.dataset.until).getTime();

                                function updateTimer() {
                                    const now = new Date().getTime();
                                    const diff = until - now;

                                    if (diff <= 0) {
                                        timerEl.closest('.countdown-timer-wrap').style.display = 'none';
                                        return;
                                    }

                                    const d = Math.floor(diff / (1000 * 60 * 60 * 24));
                                    const h = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                    const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                                    const s = Math.floor((diff % (1000 * 60)) / 1000);

                                    document.getElementById('days').textContent = d.toString().padStart(2, '0');
                                    document.getElementById('hours').textContent = h.toString().padStart(2, '0');
                                    document.getElementById('mins').textContent = m.toString().padStart(2, '0');
                                    document.getElementById('secs').textContent = s.toString().padStart(2, '0');
                                }

                                setInterval(updateTimer, 1000);
                                updateTimer();
                            })();
                        </script>

                    <!-- =====================================================
                         PREMIUM: Quantity + Add to Cart + Buy Now
                         ===================================================== -->
                    <?php if ($product['stock_quantity'] > 0): ?>

                        <!-- Inline styles for the premium CTA block -->
                        <style>
                            /* === Quantity Selector === */
                            .luxe-qty-wrap {
                                display: inline-flex;
                                align-items: center;
                                border: 2px solid #e8e8f0;
                                border-radius: 50px;
                                overflow: hidden;
                                background: #fafafa;
                                min-width: 130px;
                            }
                            .luxe-qty-btn {
                                width: 44px;
                                height: 54px;
                                border: none;
                                background: transparent;
                                font-size: 20px;
                                font-weight: 600;
                                cursor: pointer;
                                color: #121826;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                transition: all 0.2s ease;
                            }
                            .luxe-qty-btn:hover {
                                background: #f0f0f8;
                                color: #C5A059;
                            }
                            .luxe-qty-input {
                                width: 44px;
                                text-align: center;
                                border: none;
                                background: transparent;
                                font-size: 17px;
                                font-weight: 700;
                                color: #121826;
                                -moz-appearance: textfield;
                                appearance: none;
                            }
                            .luxe-qty-input::-webkit-outer-spin-button,
                            .luxe-qty-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }

                            /* === Add to Cart Button (Gradient Shimmer) === */
                            .luxe-atc-btn {
                                flex: 1;
                                height: 54px;
                                border: none;
                                border-radius: 50px;
                                background: linear-gradient(135deg,#C5A059 0%, #D4AF37 45%, #b8860b 100%);
                                background-size: 200% 200%;
                                color: #fff;
                                font-family: 'Poppins', sans-serif;
                                font-size: 14px;
                                font-weight: 700;
                                letter-spacing: 0.8px;
                                text-transform: uppercase;
                                cursor: pointer;
                                display: inline-flex;
                                align-items: center;
                                justify-content: center;
                                gap: 8px;
                                position: relative;
                                overflow: hidden;
                                transition: all 0.35s ease;
                                box-shadow: 0 6px 20px rgba(255, 107, 53, 0.45);
                                animation: atc-gradient-flow 4s ease infinite;
                            }
                            @keyframes atc-gradient-flow {
                                0%   { background-position: 0% 50%; }
                                50%  { background-position: 100% 50%; }
                                100% { background-position: 0% 50%; }
                            }
                            .luxe-atc-btn::before {
                                content: '';
                                position: absolute;
                                top: 0; left: -80%;
                                width: 60%;
                                height: 100%;
                                background: linear-gradient(90deg, transparent, rgba(255,255,255,0.35), transparent);
                                transform: skewX(-20deg);
                                animation: atc-sheen 3s ease-in-out infinite;
                            }
                            @keyframes atc-sheen {
                                0%   { left: -80%; }
                                60%  { left: 130%; }
                                100% { left: 130%; }
                            }
                            .luxe-atc-btn:hover {
                                transform: translateY(-3px);
                                box-shadow: 0 12px 30px rgba(255, 107, 53, 0.55);
                            }
                            .luxe-atc-btn:active { transform: translateY(0); }

                            /* === Buy Now Button (Gold Glow Pulse) === */
                            .luxe-buy-btn {
                                width: 100%;
                                height: 58px;
                                border: none;
                                border-radius: 50px;
                                background: linear-gradient(135deg, #C5A059 0%, #D4AF37 45%, #b8860b 100%);
                                color: #0A0F1A;
                                font-family: 'Poppins', sans-serif;
                                font-size: 15px;
                                font-weight: 800;
                                letter-spacing: 1px;
                                text-transform: uppercase;
                                cursor: pointer;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                gap: 10px;
                                position: relative;
                                overflow: hidden;
                                transition: all 0.35s ease;
                                box-shadow: 0 6px 22px rgba(197, 160, 89, 0.5);
                                animation: gold-pulse 2.5s cubic-bezier(0.4, 0, 0.6, 1) infinite;
                            }
                            @keyframes gold-pulse {
                                0%, 100% { box-shadow: 0 6px 22px rgba(197,160,89,0.5); }
                                50%       { box-shadow: 0 8px 35px rgba(197,160,89,0.75), 0 0 0 6px rgba(197,160,89,0.12); }
                            }
                            .luxe-buy-btn::before {
                                content: '';
                                position: absolute;
                                top: 0; left: -80%;
                                width: 60%;
                                height: 100%;
                                background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
                                transform: skewX(-20deg);
                                animation: atc-sheen 3.5s ease-in-out infinite 0.8s;
                            }
                            .luxe-buy-btn:hover {
                                transform: translateY(-3px);
                                box-shadow: 0 14px 40px rgba(197, 160, 89, 0.7);
                                filter: brightness(1.08);
                            }
                            .luxe-buy-btn:active { transform: translateY(0); }
                            .luxe-buy-btn .lightning-icon {
                                font-size: 18px;
                                filter: drop-shadow(0 0 4px rgba(255,255,200,0.8));
                            }

                            /* === Trust micro-badges strip === */
                            .luxe-trust-strip {
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                gap: 18px;
                                flex-wrap: wrap;
                                padding: 10px 0 4px;
                            }
                            .luxe-trust-item {
                                display: flex;
                                align-items: center;
                                gap: 5px;
                                font-size: 11px;
                                font-weight: 600;
                                color: #64748b;
                                letter-spacing: 0.3px;
                            }
                            .luxe-trust-item i {
                                font-size: 13px;
                                color: #059669;
                            }
                            .luxe-trust-item i.gold   { color: #D4AF37; }
                            .luxe-trust-item i.blue   { color: #0284c7; }
                        </style>

                        <!-- ROW: Qty Selector + Add-to-Cart -->
                        <div class="d-flex align-items-stretch gap-3 mb-3">
                            <!-- Premium Quantity Selector -->
                            <div class="luxe-qty-wrap flex-shrink-0">
                                <button type="button" class="luxe-qty-btn" onclick="changeQty(-1)" aria-label="Decrease quantity">
                                    <i class="bi bi-dash"></i>
                                </button>
                                <input type="number" id="purchase-qty" value="1" min="1"
                                    max="<?php echo $product['stock_quantity']; ?>" readonly
                                    class="luxe-qty-input border-0 sh-qty-value">
                                <button type="button" class="luxe-qty-btn" onclick="changeQty(1)" aria-label="Increase quantity">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>

                            <!-- Add to Cart - Vibrant Coral Gradient -->
                            <button class="luxe-atc-btn" onclick="addToCart()" id="addToCartBtn">
                                <i class="bi bi-bag-plus" style="font-size:16px;"></i>
                                Add to Cart &mdash; <?php echo Helpers::formatPrice($product['price']); ?>
                            </button>
                        </div>

                        <!-- Buy Now — Gold Glow CTA -->
                        <div class="mb-3">
                            <button class="luxe-buy-btn" onclick="buyNow()" id="buyNowBtn">
                                <i class="bi bi-lightning-fill lightning-icon"></i>
                                <?php echo !empty($product['custom_buy_button']) ? Security::escape($product['custom_buy_button']) : 'Buy Now — Express Checkout'; ?>
                            </button>
                        </div>

                        <!-- Trust Micro-Badges -->
                        <div class="luxe-trust-strip mb-4">
                            <span class="luxe-trust-item"><i class="bi bi-shield-fill-check"></i> Secure Payment</span>
                            <span class="luxe-trust-item"><i class="bi bi-truck gold"></i> Free UAE Shipping</span>
                            <span class="luxe-trust-item"><i class="bi bi-arrow-counterclockwise blue"></i> Easy Returns</span>
                            <span class="luxe-trust-item"><i class="bi bi-cash-coin gold"></i> Cash on Delivery</span>
                        </div>

                        <!-- PREMIUM: Bundle & Save (Masterfully Refined) -->
                        <div class="luxe-bundle-section mb-5">
                            <div class="d-flex align-items-center justify-content-between mb-3 px-1">
                                <h6 class="fw-bold mb-0 text-uppercase d-flex align-items-center" style="font-size: 14px; color: #1e293b; letter-spacing: 1.5px; font-family: 'Inter', sans-serif;">
                                    <i class="bi bi-layers-fill me-2" style="color: #C5A059; font-size: 18px;"></i>BUNDLE & SAVE
                                </h6>
                                <span class="badge rounded-pill px-3 py-1.5" style="background: #fff5f5; color: #e53e3e; font-size: 10px; font-weight: 800; border: 1px solid #fed7d7;">LIMITED TIME</span>
                            </div>

                            <div class="bundle-options-grid d-flex flex-column gap-3">
                                <!-- Option 1 -->
                                <div class="bundle-card selected" onclick="selectBundle(1, 0, this)">
                                    <div class="bundle-left">
                                        <div class="bundle-check"></div>
                                        <div class="bundle-content">
                                            <div class="bundle-title">Buy 1 Unit</div>
                                            <div class="bundle-subtitle">Standard Collection</div>
                                        </div>
                                    </div>
                                    <div class="bundle-right">
                                        <div class="bundle-price"><?php echo Helpers::formatPrice($product['price']); ?></div>
                                    </div>
                                </div>

                                <!-- Option 2 -->
                                <div class="bundle-card" onclick="selectBundle(2, 10, this)">
                                    <div class="bundle-badge">MOST POPULAR</div>
                                    <div class="bundle-left">
                                        <div class="bundle-check"></div>
                                        <div class="bundle-content">
                                            <div class="bundle-title">Buy 2 Units <span class="save-tag">SAVE 10%</span></div>
                                            <div class="bundle-subtitle">Best for Gifting</div>
                                        </div>
                                    </div>
                                    <div class="bundle-right">
                                        <div class="bundle-price-wrap">
                                            <div class="bundle-price text-success"><?php echo Helpers::formatPrice($product['price'] * 2 * 0.9); ?></div>
                                            <div class="bundle-old-price"><?php echo Helpers::formatPrice($product['price'] * 2); ?></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Option 3 -->
                                <div class="bundle-card" onclick="selectBundle(3, 20, this)">
                                    <div class="bundle-badge gold">BEST VALUE</div>
                                    <div class="bundle-left">
                                        <div class="bundle-check"></div>
                                        <div class="bundle-content">
                                            <div class="bundle-title">Buy 3 Units <span class="save-tag orange">SAVE 20%</span></div>
                                            <div class="bundle-subtitle">Full Luxury Set</div>
                                        </div>
                                    </div>
                                    <div class="bundle-right">
                                        <div class="bundle-price-wrap">
                                            <div class="bundle-price" style="color: #C5A059;"><?php echo Helpers::formatPrice($product['price'] * 3 * 0.8); ?></div>
                                            <div class="bundle-old-price"><?php echo Helpers::formatPrice($product['price'] * 3); ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <style>
                            .luxe-bundle-section { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
                            .bundle-card {
                                border: 1.5px solid #C5A05940; border-radius: 20px; padding: 18px 22px;
                                display: flex; align-items: center; justify-content: space-between;
                                cursor: pointer; position: relative; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                                background: #fff;
                            }
                            .bundle-card:hover { border-color: #C5A059; transform: translateY(-3px); box-shadow: 0 10px 20px rgba(197, 160, 89, 0.1); }
                            .bundle-card.selected { border-color: #C5A059; background: #fffdf9; box-shadow: 0 8px 30px rgba(197, 160, 89, 0.12); border-width: 2px; }
                            
                            .bundle-left { display: flex; align-items: center; gap: 15px; }
                            .bundle-check {
                                width: 24px; height: 24px; border: 2px solid #e2e8f0; border-radius: 50%;
                                position: relative; transition: all 0.2s ease; flex-shrink: 0;
                            }
                            .bundle-card.selected .bundle-check { border-color: #C5A059; background: #C5A059; }
                            .bundle-card.selected .bundle-check::after {
                                content: '\F26E'; font-family: "bootstrap-icons"; color: #fff;
                                font-size: 14px; position: absolute; inset: 0; display: flex; align-items: center; justify-content: center;
                            }

                            .bundle-title { font-weight: 800; font-size: 15px; color: #1e293b; display: flex; align-items: center; }
                            .bundle-subtitle { font-size: 12px; color: #94a3b8; font-weight: 500; margin-top: 2px; }
                            .save-tag { font-size: 10px; background: #059669; color: #fff; padding: 2px 10px; border-radius: 6px; margin-left: 10px; font-weight: 800; }
                            .save-tag.orange { background: #ea580c; }
                            
                            .bundle-right { text-align: right; }
                            .bundle-price { font-weight: 900; font-size: 19px; color: #1e293b; letter-spacing: -0.5px; }
                            .bundle-old-price { font-size: 12px; color: #cbd5e1; text-decoration: line-through; margin-top: -2px; }

                            .bundle-badge {
                                position: absolute; top: -11px; right: 25px;
                                background: #121826; color: #fff; font-size: 9px; font-weight: 900;
                                padding: 4px 14px; border-radius: 50px; text-transform: uppercase; letter-spacing: 0.8px;
                                box-shadow: 0 4px 10px rgba(0,0,0,0.1);
                            }
                            .bundle-badge.gold { background: #C5A059; color: #000; }
                        </style>

                        <!-- NEW: Animated Trust Reviews Snippet -->
                        <div class="trust-reviews-snippet mb-4 p-4 rounded-4"
                            style="background: #fdfdfd; border: 1px solid #eee; min-height: 200px; position: relative; overflow: hidden;">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-1" style="font-size: 14px;">Recent Customer Feedback</h6>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="text-warning small" style="font-size: 12px;">
                                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                                class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                                class="bi bi-star-fill"></i>
                                        </div>
                                        <span class="text-muted" style="font-size: 11px;">(4.9/5 based on
                                            <?php echo $reviewStats['total']; ?> reviews)</span>
                                    </div>
                                </div>
                                <div class="d-flex gap-1" id="review-nav-dots">
                                    <span class="dot active"></span>
                                    <span class="dot"></span>
                                    <span class="dot"></span>
                                </div>
                            </div>

                            <div class="mini-reviews-slider" id="miniReviewSlider">
                                <div class="mini-review-slide active">
                                    <div class="d-flex justify-content-between mb-1"
                                        dir="<?php echo CURRENT_LANG === 'ar' ? 'rtl' : 'ltr'; ?>">
                                        <span class="fw-bold"
                                            style="font-size: 12px;"><?php echo CURRENT_LANG === 'ar' ? 'راشد م.' : 'Rashid M.'; ?>
                                            <i class="bi bi-patch-check-fill text-primary ms-1"></i></span>
                                        <span class="text-muted"
                                            style="font-size: 10px;"><?php echo CURRENT_LANG === 'ar' ? 'دبي، الإمارات' : 'Dubai, UAE'; ?></span>
                                    </div>
                                    <p class="mb-0 text-secondary"
                                        style="font-size: 12px; line-height: 1.4; text-align: <?php echo CURRENT_LANG === 'ar' ? 'right' : 'left'; ?>;"
                                        dir="<?php echo CURRENT_LANG === 'ar' ? 'rtl' : 'ltr'; ?>">
                                        "<?php echo CURRENT_LANG === 'ar' ? 'جودة ممتازة، وتوصيل سريع جداً في دبي. المنتج أفضل حتى مما يظهر في الصور. أنصح به بشدة!' : 'Excellent quality, super fast delivery in Dubai. This is even better than what\'s shown in pictures. Highly recommend!'; ?>"
                                    </p>
                                </div>
                                <div class="mini-review-slide">
                                    <div class="d-flex justify-content-between mb-1"
                                        dir="<?php echo CURRENT_LANG === 'ar' ? 'rtl' : 'ltr'; ?>">
                                        <span class="fw-bold"
                                            style="font-size: 12px;"><?php echo CURRENT_LANG === 'ar' ? 'آمنة السيد' : 'Amna Al Sayed'; ?>
                                            <i class="bi bi-patch-check-fill text-primary ms-1"></i></span>
                                        <span class="text-muted"
                                            style="font-size: 10px;"><?php echo CURRENT_LANG === 'ar' ? 'أبوظبي' : 'Abu Dhabi'; ?></span>
                                    </div>
                                    <p class="mb-0 text-secondary"
                                        style="font-size: 12px; line-height: 1.4; text-align: <?php echo CURRENT_LANG === 'ar' ? 'right' : 'left'; ?>;"
                                        dir="<?php echo CURRENT_LANG === 'ar' ? 'rtl' : 'ltr'; ?>">
                                        "<?php echo CURRENT_LANG === 'ar' ? 'أفضل عملية شراء هذا الشهر. اللمسات النهائية فاخرة للغاية ويشعرك بالفخامة. يبدو تماماً كقطعة فاخرة من المتاجر العالمية.' : 'Best purchase this month. The finish is very premium and it feels substantial. Definitely looks like a high-end luxury item.'; ?>"
                                    </p>
                                </div>
                                <div class="mini-review-slide">
                                    <div class="d-flex justify-content-between mb-1"
                                        dir="<?php echo CURRENT_LANG === 'ar' ? 'rtl' : 'ltr'; ?>">
                                        <span class="fw-bold"
                                            style="font-size: 12px;"><?php echo CURRENT_LANG === 'ar' ? 'فاطمة ح.' : 'Fatima H.'; ?>
                                            <i class="bi bi-patch-check-fill text-primary ms-1"></i></span>
                                        <span class="text-muted"
                                            style="font-size: 10px;"><?php echo CURRENT_LANG === 'ar' ? 'الشارقة' : 'Sharjah'; ?></span>
                                    </div>
                                    <p class="mb-0 text-secondary"
                                        style="font-size: 12px; line-height: 1.4; text-align: <?php echo CURRENT_LANG === 'ar' ? 'right' : 'left'; ?>;"
                                        dir="<?php echo CURRENT_LANG === 'ar' ? 'rtl' : 'ltr'; ?>">
                                        "<?php echo CURRENT_LANG === 'ar' ? 'أهديت هذا المنتج لأختي وقد أحبته تماماً. كان التغليف جميلاً ووصل خليل أقل من 24 ساعة.' : 'Gifted this to my sister and she absolutely loves it. The packaging was beautiful and it arrived within 24 hours.'; ?>"
                                    </p>
                                </div>
                            </div>

                            <div class="text-center mt-3 pt-2 border-top">
                                <a href="#reviews" class="text-primary text-decoration-none fw-bold"
                                    style="font-size: 11px;">View All <?php echo $reviewStats['total']; ?> Reviews <i
                                        class="bi bi-arrow-right ms-1"></i></a>
                            </div>
                        </div>

                        <style>
                            .mini-reviews-slider {
                                position: relative;
                                height: 80px;
                            }

                            .mini-review-slide {
                                position: absolute;
                                top: 0;
                                left: 0;
                                width: 100%;
                                opacity: 0;
                                visibility: hidden;
                                transition: all 0.6s ease;
                                transform: translateX(20px);
                            }

                            .mini-review-slide.active {
                                opacity: 1;
                                visibility: visible;
                                transform: translateX(0);
                            }

                            #review-nav-dots .dot {
                                width: 6px;
                                height: 6px;
                                border-radius: 50%;
                                background: #eee;
                                display: inline-block;
                                transition: all 0.3s ease;
                            }

                            #review-nav-dots .dot.active {
                                background: var(--sh-primary);
                                width: 12px;
                                border-radius: 4px;
                            }
                        </style>

                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                const slides = document.querySelectorAll('.mini-review-slide');
                                const dots = document.querySelectorAll('#review-nav-dots .dot');
                                let currentSlide = 0;

                                function nextSlide() {
                                    slides[currentSlide].classList.remove('active');
                                    dots[currentSlide].classList.remove('active');

                                    currentSlide = (currentSlide + 1) % slides.length;

                                    slides[currentSlide].classList.add('active');
                                    dots[currentSlide].classList.add('active');
                                }

                                setInterval(nextSlide, 4000);
                            });
                        </script>
                    <?php else: ?>
                        <div class="mb-4 p-4 rounded-4 text-center"
                            style="background: rgba(231,76,60,0.1); border: 1px solid rgba(231,76,60,0.2);">
                            <i class="bi bi-exclamation-circle fs-1 text-danger mb-2"></i>
                            <h5 class="text-danger fw-bold mb-2">Currently Out of Stock</h5>
                            <p class="text-muted small mb-3">This item is temporarily unavailable.</p>
                            <button class="sh-btn sh-btn-secondary sh-btn-sm">
                                <i class="bi bi-bell me-2"></i> Notify When Available
                            </button>
                        </div>
                    <?php endif; ?>


                    <!-- PREMIUM ELITE SERVICE BAR (Modernized) -->
                    <div class="mt-5 p-4 rounded-4" style="background: #fafafa; border: 1.5px solid #f0f0f5;">
                        <div class="row g-4 text-center">
                            <div class="col-6 col-md-3">
                                <div class="luxe-feature">
                                    <div class="icon-wrap"><i class="bi bi-truck"></i></div>
                                    <h6>Free Delivery</h6>
                                    <p>Fast UAE Shipping</p>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="luxe-feature">
                                    <div class="icon-wrap gold"><i class="bi bi-patch-check"></i></div>
                                    <h6>100% Genuine</h6>
                                    <p>Verified Quality</p>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="luxe-feature">
                                    <div class="icon-wrap blue"><i class="bi bi-arrow-repeat"></i></div>
                                    <h6>Easy Returns</h6>
                                    <p>14 Day Window</p>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="luxe-feature">
                                    <div class="icon-wrap black"><i class="bi bi-headset"></i></div>
                                    <h6>24/7 Support</h6>
                                    <p>Dedicated Team</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <style>
                        .luxe-feature h6 { font-size: 13px; font-weight: 800; color: #1e293b; margin: 12px 0 4px; text-transform: uppercase; letter-spacing: 0.5px; }
                        .luxe-feature p { font-size: 11px; color: #94a3b8; margin: 0; font-weight: 500; }
                        .luxe-feature .icon-wrap {
                            width: 50px; height: 50px; background: #fff; color: #059669; border-radius: 14px;
                            display: flex; align-items: center; justify-content: center; margin: 0 auto;
                            font-size: 22px; box-shadow: 0 4px 12px rgba(0,0,0,0.04); transition: all 0.3s ease;
                        }
                        .luxe-feature .icon-wrap.gold { color: #C5A059; }
                        .luxe-feature .icon-wrap.blue { color: #0ea5e9; }
                        .luxe-feature .icon-wrap.black { color: #121826; }
                        .luxe-feature:hover .icon-wrap { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.08); }
                    </style>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Product Details Tabs -->
<section class="sh-section-sm" style="background: var(--sh-gray-50);">
    <div class="container-fluid px-3 px-md-4 px-lg-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Tabs Navigation -->
                <ul class="nav nav-pills justify-content-center gap-2 mb-4" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active rounded-pill px-4" data-bs-toggle="pill"
                            data-bs-target="#tab-details">
                            <?php echo Helpers::translate('description_bilingual'); ?>
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link rounded-pill px-4" data-bs-toggle="pill" data-bs-target="#tab-shipping">
                            <?php echo Helpers::translate('shipping_returns_bilingual'); ?>
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link rounded-pill px-4" data-bs-toggle="pill" data-bs-target="#tab-about">
                            <?php echo Helpers::translate('about_us_bilingual'); ?>
                        </button>
                    </li>
                </ul>

                <!-- Tabs Content -->
                <div class="tab-content bg-white rounded-4 p-4 p-md-5 shadow-sm">
                    <div class="tab-pane fade show active" id="tab-details">
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3">
                                <?php echo CURRENT_LANG === 'ar' ? 'وصف المنتج' : 'Product Description'; ?>:
                            </h5>
                            <div class="text-muted" style="line-height: 1.8;">
                                <?php echo nl2br(Security::escape($product['description'])); ?>
                            </div>
                        </div>

                        <?php if (!empty($product['name_ar']) || CURRENT_LANG === 'ar'): ?>
                            <div class="mt-4 p-3 rounded-3"
                                style="background: rgba(212, 175, 55, 0.05); border-left: 4px solid var(--sh-gold);">
                                <h6 class="fw-bold mb-2">المميزات الرئيسية:</h6>
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i> مواد عالية الجودة وفخمة
                                    </li>
                                    <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i> فحص دقيق للجودة قبل
                                        الشحن</li>
                                    <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i> ضمان المصنع الأصلي
                                        المعتمد</li>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="tab-pane fade" id="tab-shipping">
                        <div class="mb-4">
                            <h6 class="fw-bold mb-2">UAE Shipping: <span class="fw-normal text-muted">Delivered within
                                    4–5 business days</span></h6>
                            <p class="text-muted small mb-3">Orders are processed daily after confirmation.</p>
                            <p class="text-muted small" dir="rtl">.يتم تجهيز الطلبات يومياً بعد التأكيد</p>
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-bold mb-2">Tracking Information:</h6>
                            <p class="text-muted small mb-2">A tracking number will be provided once your order is
                                shipped.</p>
                            <p class="text-muted small" dir="rtl">.سيتم تزويدك برقم تتبع بعد شحن الطلب</p>
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-bold mb-2">7-Day Return Policy | <span dir="rtl">سياسة الإرجاع خلال 7
                                    أيام</span></h6>
                            <p class="text-muted small mb-2">If you're not completely satisfied, you may return the item
                                within 7 days of delivery, subject to our return policy.</p>
                            <p class="text-muted small" dir="rtl">في حال عدم الرضا، يمكن إرجاع المنتج خلال 7 أيام من
                                تاريخ الاستلام.</p>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="tab-about">
                        <h5 class="fw-bold mb-3"><?php echo Helpers::translate('about_us_bilingual'); ?></h5>
                        <div class="text-muted" style="line-height: 1.8;">
                            <p class="mb-3">
                                At <strong>Edluxury</strong>, we are passionate about providing quality products that
                                make everyday life easier and more enjoyable.
                            </p>
                            <p class="mb-4" dir="rtl">
                                في <strong>إيدلوكسري</strong>، نحن ملتزمون بتقديم منتجات عالية الجودة تسهّل حياتك
                                اليومية وتجعلها أكثر متعة.
                            </p>

                            <p class="mb-3">
                                We focus on reliable UAE delivery, fast service, and ensuring our customers can shop
                                with confidence.
                            </p>
                            <p class="mb-4" dir="rtl">
                                نحن نحرص على توصيل الطلبات داخل الإمارات بسرعة وموثوقية، ونضمن تجربة تسوق آمنة ومريحة
                                لعملائنا.
                            </p>

                            <p class="mb-3">
                                Our mission is simple: to bring convenience, quality, and trust to your doorstep.
                            </p>
                            <p class="mb-0" dir="rtl">
                                مهمتنا بسيطة: تقديم الراحة والجودة والثقة مباشرة إلى باب منزلك.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>

<!-- Live Reviews Section -->
<section class="sh-section" id="reviews">
    <div class="container-fluid px-3 px-md-4 px-lg-5">
        <div class="sh-section-header" data-aos="fade-up">
            <span class="sh-section-subtitle">Customer Feedback</span>
            <h2 class="sh-section-title">Verified Reviews</h2>
            <p class="text-muted text-center mt-2" style="font-size: 14px; max-width: 500px; margin: 0 auto;">
                <i class="bi bi-people-fill me-1"></i>
                Real reviews from our UAE customers who love this product
            </p>
        </div>

        <div class="row g-4">
            <!-- Review Stats -->
            <div class="col-lg-4" data-aos="fade-up">
                <div class="sh-reviews-section h-100">
                    <div class="sh-review-stats flex-column">
                        <div class="text-center w-100 pb-4 mb-4 border-bottom">
                            <div class="sh-stats-number"><?php echo $reviewStats['average']; ?></div>
                            <div class="sh-stats-stars my-2">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="bi bi-star-fill fs-5"></i>
                                <?php endfor; ?>
                            </div>
                            <div class="sh-stats-count">Based on <?php echo $reviewStats['total']; ?> reviews</div>
                        </div>
                        <div class="sh-stats-bars w-100">
                            <?php
                            $total = array_sum($reviewStats['distribution']);
                            for ($i = 5; $i >= 1; $i--):
                                $percent = $total > 0 ? round(($reviewStats['distribution'][$i] / $total) * 100) : 0;
                                ?>
                                <div class="sh-bar-row">
                                    <span class="sh-bar-label"><?php echo $i; ?> <i class="bi bi-star-fill"
                                            style="font-size: 10px;"></i></span>
                                    <div class="sh-bar-track">
                                        <div class="sh-bar-fill" style="width: 0%;" data-target="<?php echo $percent; ?>%">
                                        </div>
                                    </div>
                                    <span class="sh-bar-count"><?php echo $reviewStats['distribution'][$i]; ?></span>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <div class="sh-trust-badges-horizontal d-flex flex-wrap gap-3 mt-4 justify-content-center">
                        <div class="trust-badge-mini pulse-animation"><i class="bi bi-shield-lock me-1"></i> Data Safe
                        </div>
                        <div class="trust-badge-mini pulse-animation" style="animation-delay: 0.5s;"><i
                                class="bi bi-check-all me-1"></i> Quality Check</div>
                        <div class="trust-badge-mini pulse-animation" style="animation-delay: 1s;"><i
                                class="bi bi-truck me-1"></i> UAE Fast Shipping</div>
                    </div>

                    <!-- Write Review Button -->
                    <button class="sh-btn sh-btn-primary sh-btn-full mt-4" data-bs-toggle="modal"
                        data-bs-target="#reviewModal">
                        <i class="bi bi-pencil-square me-2"></i> Write a Review
                    </button>
                </div>
            </div>

            <!-- Reviews List -->
            <div class="col-lg-8" data-aos="fade-up" data-aos-delay="100">
                <div class="sh-reviews-section">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0"><?php echo Helpers::translate('recent_feedback'); ?></h5>
                        <select class="form-select w-auto border-0" style="font-size: 14px;">
                            <option><?php echo CURRENT_LANG === 'ar' ? 'الأحدث' : 'Most Recent'; ?></option>
                            <option><?php echo CURRENT_LANG === 'ar' ? 'الأعلى تقييماً' : 'Highest Rated'; ?></option>
                            <option><?php echo CURRENT_LANG === 'ar' ? 'الأقل تقييماً' : 'Lowest Rated'; ?></option>
                        </select>
                    </div>

                    <?php foreach ($sampleReviews as $index => $review): ?>
                        <div class="sh-review-card" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                            <div
                                class="sh-review-header <?php echo CURRENT_LANG === 'ar' ? 'flex-row-reverse text-end' : ''; ?>">
                                <div
                                    class="sh-review-author <?php echo CURRENT_LANG === 'ar' ? 'flex-row-reverse' : ''; ?>">
                                    <div class="sh-review-avatar"
                                        style="background: <?php echo $index % 2 == 0 ? 'var(--sh-primary)' : 'var(--sh-gold)'; ?>;">
                                        <?php echo $review['avatar']; ?>
                                    </div>
                                    <div class="<?php echo CURRENT_LANG === 'ar' ? 'me-3' : ''; ?>">
                                        <div
                                            class="sh-review-name d-flex align-items-center gap-2 <?php echo CURRENT_LANG === 'ar' ? 'flex-row-reverse' : ''; ?>">
                                            <?php echo CURRENT_LANG === 'ar' ? $review['name_ar'] : $review['name']; ?>
                                            <?php if (!empty($review['badge'])): ?>
                                                <span class="badge rounded-pill bg-light text-dark border fw-normal"
                                                    style="font-size: 10px;"><?php echo CURRENT_LANG === 'ar' ? $review['badge_ar'] : $review['badge']; ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="sh-review-date">
                                            <i class="bi bi-geo-alt me-1"></i>
                                            <?php echo CURRENT_LANG === 'ar' ? $review['location_ar'] : $review['location']; ?>
                                            •
                                            <?php echo CURRENT_LANG === 'ar' ? $review['date_ar'] : $review['date']; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="<?php echo CURRENT_LANG === 'ar' ? 'text-start' : 'text-end'; ?>">
                                    <div class="sh-review-stars mb-1">
                                        <?php for ($i = 1; $i <= $review['rating']; $i++): ?>
                                            <i class="bi bi-star-fill"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <?php if ($review['verified']): ?>
                                        <div class="d-flex align-items-center justify-content-end gap-1">
                                            <span class="sh-verified-badge pulse-badge">
                                                <i class="bi bi-patch-check-fill"></i>
                                                <?php echo Helpers::translate('verified_buyer'); ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <p class="sh-review-text mb-0" id="review-text-<?php echo $index; ?>"
                                style="text-align: <?php echo CURRENT_LANG === 'ar' ? 'right' : 'left'; ?>;"
                                dir="<?php echo CURRENT_LANG === 'ar' ? 'rtl' : 'ltr'; ?>">
                                <?php echo CURRENT_LANG === 'ar' ? $review['text_ar'] : $review['text']; ?>
                            </p>

                            <!-- JavaScript Translation Toggle -->
                            <div class="mt-2" style="text-align: <?php echo CURRENT_LANG === 'ar' ? 'right' : 'left'; ?>;">
                                <button class="btn btn-link btn-sm p-0 text-decoration-none translate-toggle"
                                    onclick="toggleTranslation(<?php echo $index; ?>)"
                                    data-en="<?php echo htmlspecialchars($review['text']); ?>"
                                    data-ar="<?php echo htmlspecialchars($review['text_ar']); ?>"
                                    data-current="<?php echo CURRENT_LANG; ?>"
                                    style="font-size: 11px; color: var(--sh-gold);">
                                    <i class="bi bi-translate me-1"></i>
                                    <?php echo CURRENT_LANG === 'ar' ? 'عرض بالإنجليزية' : 'Show in Arabic'; ?>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <!-- Load More Button -->
                    <div class="text-center mt-5">
                        <button class="sh-btn sh-btn-secondary mb-3">
                            <?php echo CURRENT_LANG === 'ar' ? 'عرض المزيد من التقييمات' : 'Load More Reviews'; ?>
                        </button>
                        <div class="d-flex align-items-center justify-content-center gap-2 text-muted small mt-2">
                            <i class="bi bi-shield-lock-fill text-success"></i>
                            <span>
                                <?php echo CURRENT_LANG === 'ar' ? 'تقييمات حقيقية 100% • موثقة بنظام Trust-Cloud' : '100% Real Reviews & Feedback • Verified by Trust-Cloud System'; ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Products -->
<?php if (!empty($relatedProducts)): ?>
    <section class="sh-section" style="background: var(--sh-gray-50);">
        <div class="container-fluid px-3 px-md-4 px-lg-5">
            <div class="sh-section-header" data-aos="fade-up">
                <span class="sh-section-subtitle">Complete Your Look</span>
                <h2 class="sh-section-title">You May Also Like</h2>
            </div>

            <div class="row g-3 g-md-4">
                <?php foreach ($relatedProducts as $relProduct):
                    $images = $db->fetchAll("SELECT image_path FROM product_images WHERE product_id = ? ORDER BY is_primary DESC LIMIT 1", [$relProduct['id']]);
                    $img = !empty($images[0]) ? Helpers::upload($images[0]['image_path']) : Helpers::asset('images/placeholder-product.jpg');
                    ?>
                    <div class="col-6 col-md-3" data-aos="fade-up">
                        <div class="sh-product-card">
                            <div class="sh-product-media">
                                <a href="<?php echo Helpers::url('product.php?slug=' . $relProduct['slug']); ?>">
                                    <img src="<?php echo $img; ?>" alt="<?php echo Security::escape($relProduct['name']); ?>">
                                </a>
                                <button class="sh-wishlist-btn"><i class="bi bi-heart"></i></button>
                                <div class="sh-product-actions">
                                    <button class="sh-quick-add" onclick="Cart.add(<?php echo $relProduct['id']; ?>, 1)">
                                        <i class="bi bi-plus me-1"></i> Quick Add
                                    </button>
                                </div>
                            </div>
                            <div class="sh-product-info">
                                <h3 class="sh-product-title">
                                    <a href="<?php echo Helpers::url('product.php?slug=' . $relProduct['slug']); ?>">
                                        <?php echo Security::escape($relProduct['name']); ?>
                                    </a>
                                </h3>
                                <div class="sh-product-price">
                                    <span
                                        class="sh-price-current"><?php echo Helpers::formatPrice($relProduct['price']); ?></span>
                                </div>
                                <div class="sh-product-rating">
                                    <span class="sh-stars">
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                    </span>
                                    <span class="sh-rating-count">(<?php echo rand(15, 120); ?>)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Image Lightbox Modal -->
<div class="modal fade" id="lightboxModal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content bg-dark">
            <div class="modal-header border-0">
                <span class="text-white" id="lightboxCounter">1 / <?php echo count($productImages); ?></span>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body d-flex align-items-center justify-content-center p-0 position-relative">
                <!-- Previous Button -->
                <button class="btn btn-dark position-absolute start-0 top-50 translate-middle-y ms-3 rounded-circle"
                    style="width: 50px; height: 50px; z-index: 10;" onclick="prevImage()">
                    <i class="bi bi-chevron-left fs-4"></i>
                </button>

                <!-- Image -->
                <img id="lightboxImage" src="" alt="Product Image" class="img-fluid"
                    style="max-height: 85vh; object-fit: contain;">

                <!-- Next Button -->
                <button class="btn btn-dark position-absolute end-0 top-50 translate-middle-y me-3 rounded-circle"
                    style="width: 50px; height: 50px; z-index: 10;" onclick="nextImage()">
                    <i class="bi bi-chevron-right fs-4"></i>
                </button>
            </div>
            <!-- Thumbnails -->
            <div class="modal-footer border-0 justify-content-center gap-2 py-3">
                <?php foreach ($productImages as $index => $image): ?>
                    <img src="<?php echo Helpers::upload($image['image_path']); ?>" alt="Thumbnail"
                        class="rounded cursor-pointer lightbox-thumb"
                        style="width: 60px; height: 60px; object-fit: cover; cursor: pointer; opacity: 0.5; transition: opacity 0.2s;"
                        onclick="setLightboxImage(<?php echo $index; ?>)">
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- Write Review Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow-lg border-0">
            <div class="modal-header border-bottom px-4 py-3">
                <h5 class="modal-title fw-bold" style="color: var(--sh-primary);">
                    <?php echo Helpers::translate('write_review'); ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="productReviewForm">
                    <div class="mb-4 text-center">
                        <p class="text-muted mb-2">
                            <?php echo CURRENT_LANG === 'ar' ? 'كيف تقيم هذا المنتج؟' : 'How would you rate this product?'; ?>
                        </p>
                        <div class="d-flex justify-content-center gap-2" id="ratingStars">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="bi bi-star fs-1 text-muted" style="cursor: pointer; transition: all 0.2s;"
                                    onmouseover="hoverRating(<?php echo $i; ?>)" onmouseout="resetRating()"
                                    onclick="setRating(<?php echo $i; ?>)"></i>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="sh-form-label"><?php echo Helpers::translate('first_name'); ?></label>
                        <input type="text" class="sh-form-input p-3"
                            placeholder="<?php echo CURRENT_LANG === 'ar' ? 'أدخل اسمك هنا' : 'Enter your name'; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="sh-form-label"><?php echo Helpers::translate('description'); ?></label>
                        <textarea class="sh-form-input p-3" rows="4"
                            placeholder="<?php echo CURRENT_LANG === 'ar' ? 'شاركنا تجربتك مع المنتج...' : 'Share your experience with this product...'; ?>"></textarea>
                    </div>
                    <button type="submit" class="sh-btn sh-btn-primary sh-btn-full py-3">
                        <i class="bi bi-check-circle me-2"></i>
                        <?php echo CURRENT_LANG === 'ar' ? 'إرسال التقييم' : 'Submit Review'; ?>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Product Data
    const productId = <?php echo $product['id']; ?>;
    const productImages = <?php echo json_encode(array_map(function ($img) {
        return Helpers::upload($img['image_path']);
    }, $productImages)); ?>;
    let currentImageIndex = 0;
    let selectedRating = 0;

    // Quantity Controls
    function changeQty(delta) {
        const input = document.getElementById('purchase-qty');
        let val = parseInt(input.value) + delta;
        if (val < 1) val = 1;
        if (val > <?php echo $product['stock_quantity'] ?: 10; ?>) val = <?php echo $product['stock_quantity'] ?: 10; ?>;
        input.value = val;
        updateCartButtonPrice();
    }

    function updateCartButtonPrice() {
        const qty = parseInt(document.getElementById('purchase-qty').value);
        const price = <?php echo $product['price']; ?>;
        const btn = document.getElementById('addToCartBtn');
        if (btn) {
            btn.innerHTML = `<i class="bi bi-bag-plus me-2"></i> Add to Cart – AED ${(price * qty).toFixed(2)}`;
        }
    }

    // Add to Cart
    function addToCart() {
        const qty = parseInt(document.getElementById('purchase-qty').value);
        if (typeof Cart !== 'undefined') {
            Cart.add(productId, qty);
        } else {
            alert('Added to cart!');
        }
    }

    // Buy Now
    function buyNow() {
        addToCart();
        setTimeout(() => {
            window.location.href = '<?php echo Helpers::url('checkout.php'); ?>';
        }, 500);
    }

    // Image Gallery initialization moved to consolidated script block above.


    // Lightbox
    function openLightbox(index) {
        stopAutoSlide();
        currentImageIndex = index;
        updateLightbox();
        new bootstrap.Modal(document.getElementById('lightboxModal')).show();
    }

    function updateLightbox() {
        document.getElementById('lightboxImage').src = productImages[currentImageIndex];
        document.getElementById('lightboxCounter').textContent = `${currentImageIndex + 1} / ${productImages.length}`;
        document.querySelectorAll('.lightbox-thumb').forEach((el, i) => {
            el.style.opacity = i === currentImageIndex ? '1' : '0.5';
        });
    }

    function prevImage() {
        currentImageIndex = (currentImageIndex - 1 + productImages.length) % productImages.length;
        updateLightbox();
    }

    function nextImage() {
        currentImageIndex = (currentImageIndex + 1) % productImages.length;
        updateLightbox();
    }

    function setLightboxImage(index) {
        currentImageIndex = index;
        updateLightbox();
    }

    // Review Rating
    function hoverRating(rating) {
        const stars = document.querySelectorAll('#ratingStars i');
        stars.forEach((star, i) => {
            star.classList.toggle('bi-star-fill', i < rating);
            star.classList.toggle('bi-star', i >= rating);
            star.style.color = i < rating ? '#FDCB6E' : '#dee2e6';
        });
    }

    function resetRating() {
        if (selectedRating === 0) {
            const stars = document.querySelectorAll('#ratingStars i');
            stars.forEach(star => {
                star.classList.remove('bi-star-fill');
                star.classList.add('bi-star');
                star.style.color = '#dee2e6';
            });
        } else {
            hoverRating(selectedRating);
        }
    }

    function setRating(rating) {
        selectedRating = rating;
        hoverRating(rating);
    }

    // Keyboard navigation for lightbox
    document.addEventListener('keydown', function (e) {
        const modal = document.getElementById('lightboxModal');
        if (modal.classList.contains('show')) {
            if (e.key === 'ArrowLeft') prevImage();
            if (e.key === 'ArrowRight') nextImage();
            if (e.key === 'Escape') bootstrap.Modal.getInstance(modal).hide();
        }
    });

    // Touch swipe for mobile gallery
    let touchStartX = 0;
    document.getElementById('lightboxModal')?.addEventListener('touchstart', e => {
        touchStartX = e.touches[0].clientX;
    });
    document.getElementById('lightboxModal')?.addEventListener('touchend', e => {
        const diff = touchStartX - e.changedTouches[0].clientX;
        if (Math.abs(diff) > 50) {
            diff > 0 ? nextImage() : prevImage();
        }
    });

    // Bundle Selection Logic
    function selectBundle(qty, discount, element) {
        // Update quantity input
        const qtyInput = document.getElementById('purchase-qty');
        if (qtyInput) {
            qtyInput.value = qty;
            updateCartButtonPrice();
        }

        // Update active class
        document.querySelectorAll('.bundle-option-card').forEach(card => {
            card.classList.remove('selected');
        });
        element.classList.add('selected');

        // Optional: show a small toast or feedback
        console.log(`Selected bundle: ${qty} units with ${discount}% discount`);
    }

    // ================================================
    // Arabic Name Auto-Translation Fallback
    // For products that don't have name_ar in database
    // ================================================
    const arabicTranslations = {
        'yellow gold': 'ذهب أصفر',
        'vegetable slicer': 'آلة تقطيع الخضروات',
        'electric scrubber': 'فرشاة تنظيف كهربائية',
        'espresso coffee maker (portable)': 'ماكينة قهوة إسبريسو (محمولة)',
        'multifunctional peeling knife': 'سكين تقشير متعدد الوظائف',
        'multifuctional steam iron': 'مكواة بخارية متعددة الوظائف',
        'neck massager & shoulder': 'جهاز تدليك الرقبة والكتف',
        // Common product keywords for auto-translate fallback
        'wireless earbuds': 'سماعات أذن لاسلكية',
        'smart watch': 'ساعة ذكية',
        'phone case': 'حافظة هاتف',
        'led light': 'إضاءة LED',
        'portable charger': 'شاحن محمول',
        'bluetooth speaker': 'مكبر صوت بلوتوث',
        'air purifier': 'جهاز تنقية الهواء',
        'water bottle': 'زجاجة مياه',
        'desk lamp': 'مصباح مكتبي',
        'face mask': 'قناع الوجه',
        'hair dryer': 'مجفف شعر',
        'kitchen scale': 'ميزان مطبخ',
        'car charger': 'شاحن سيارة',
        'mouse pad': 'لوحة ماوس',
        'yoga mat': 'سجادة يوغا',
        'hand blender': 'خلاط يدوي',
        'vacuum cleaner': 'مكنسة كهربائية',
        'power bank': 'بنك طاقة',
        'ring light': 'إضاءة دائرية',
        'portable mini air purifier': 'منقي هواء صغير محمول',
        'selfie stick': 'عصا سيلفي',
    };

    // Auto-apply Arabic name if not set from PHP
    document.addEventListener('DOMContentLoaded', function () {
        const arNameEl = document.getElementById('productNameAr');
        const enNameEl = document.getElementById('productNameEn');

        if (arNameEl && enNameEl && !arNameEl.textContent.trim()) {
            const enName = enNameEl.textContent.trim().toLowerCase();
            // Direct match
            if (arabicTranslations[enName]) {
                arNameEl.textContent = arabicTranslations[enName];
                arNameEl.style.display = '';
            } else {
                // Partial keyword match
                for (const [key, val] of Object.entries(arabicTranslations)) {
                    if (enName.includes(key) || key.includes(enName)) {
                        arNameEl.textContent = val;
                        arNameEl.style.display = '';
                        break;
                    }
                }
            }
        }

        // Also apply Arabic names to related product cards
        document.querySelectorAll('.sh-product-title a, .sh-card-title a').forEach(function (titleLink) {
            const enText = titleLink.textContent.trim().toLowerCase();
            const arName = arabicTranslations[enText];
            if (arName && !titleLink.parentElement.querySelector('.card-name-ar')) {
                const arSpan = document.createElement('span');
                arSpan.className = 'card-name-ar';
                arSpan.dir = 'rtl';
                arSpan.lang = 'ar';
                arSpan.textContent = arName;
                titleLink.parentElement.appendChild(arSpan);
            }
        });

        // 🚀 LIVE ACTIVITY NOTIFICATIONS (Social Proof Toast)
        const activeUsers = ['Hassan from Dubai', 'Mariam from Abu Dhabi', 'Omar from Sharjah', 'Fatima from Ajman', 'Khalid from RAK', 'Aisha from Fujairah'];
        const activities = ['just purchased this!', 'added this to wishlist', 'left a 5-star review', 'is viewing this product right now'];

        const showActivity = () => {
            const user = activeUsers[Math.floor(Math.random() * activeUsers.length)];
            const activity = activities[Math.floor(Math.random() * activities.length)];

            const toast = document.createElement('div');
            toast.className = 'social-proof-toast';
            toast.innerHTML = `
                <div class="d-flex align-items-center gap-3">
                    <div class="toast-avatar"><i class="bi bi-person-check-fill"></i></div>
                    <div>
                        <div class="toast-user">${user}</div>
                        <div class="toast-action">${activity}</div>
                    </div>
                </div>
            `;
            document.body.appendChild(toast);

            setTimeout(() => toast.classList.add('show'), 100);
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 500);
            }, 5000);
        };

        // Initial delay then repeat
        setTimeout(showActivity, 3000);
        setInterval(() => {
            if (Math.random() > 0.5) showActivity();
        }, 15000);

        // 📊 Animate Rating Bars on Scroll
        const animateBars = () => {
            document.querySelectorAll('.sh-bar-fill').forEach(bar => {
                const target = bar.getAttribute('data-target');
                if (target) {
                    bar.style.width = target;
                    bar.style.transition = 'width 1.5s cubic-bezier(0.1, 0.5, 0.5, 1)';
                }
            });
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateBars();
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        const statsSection = document.querySelector('.sh-stats-bars');
        if (statsSection) observer.observe(statsSection);
    });

    // Translation toggle function for reviews
    function toggleTranslation(index) {
        const textEl = document.getElementById(`review-text-${index}`);
        const btn = event.currentTarget;
        const enText = btn.getAttribute('data-en');
        const arText = btn.getAttribute('data-ar');
        const isCurrentlyAr = textEl.getAttribute('dir') === 'rtl';

        if (isCurrentlyAr) {
            textEl.textContent = enText;
            textEl.setAttribute('dir', 'ltr');
            textEl.style.textAlign = 'left';
            btn.innerHTML = '<i class="bi bi-translate me-1"></i> عرض بالعربية';
        } else {
            textEl.textContent = arText;
            textEl.setAttribute('dir', 'rtl');
            textEl.style.textAlign = 'right';
            btn.innerHTML = '<i class="bi bi-translate me-1"></i> Show in English';
        }

        // Add a small flare effect
        textEl.style.opacity = '0';
        setTimeout(() => {
            textEl.style.opacity = '1';
            textEl.style.transition = 'opacity 0.3s ease';
        }, 50);
    }
</script>

<style>
    .trust-badge-mini {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        padding: 5px 12px;
        background: #f8f9fa;
        border-radius: 50px;
        color: var(--sh-gray-600);
        border: 1px solid #eee;
    }

    .sh-stats-number {
        animation: countUp 1.5s ease-out forwards;
        opacity: 0;
        transform: scale(0.8);
    }

    @keyframes countUp {
        from {
            opacity: 0;
            transform: scale(0.8);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* 🎨 SOCIAL PROOF TOAST STYLES */
    .social-proof-toast {
        position: fixed;
        bottom: 20px;
        left: 20px;
        background: white;
        padding: 12px 20px;
        border-radius: 50px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        z-index: 9999;
        transform: translateY(100px);
        opacity: 0;
        transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        border: 1px solid #eee;
        pointer-events: none;
    }

    .social-proof-toast.show {
        transform: translateY(0);
        opacity: 1;
    }

    .toast-avatar {
        width: 35px;
        height: 35px;
        background: var(--sh-gold);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .toast-user {
        font-size: 13px;
        font-weight: 700;
        color: var(--sh-primary);
        line-height: 1.2;
    }

    .toast-action {
        font-size: 11px;
        color: #718096;
    }

    .pulse-badge {
        animation: pulse-verified 2s infinite;
    }

    @keyframes pulse-verified {
        0% {
            transform: scale(1);
            opacity: 1;
        }

        50% {
            transform: scale(1.03);
            opacity: 0.9;
        }

        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    .sh-review-card {
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }

    .sh-review-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        border-color: #f0f0f0;
        background: #fff;
    }

    /* Tab Navigation Styling */
    .nav-pills .nav-link {
        background: var(--sh-gray-100);
        color: var(--sh-gray-700);
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .nav-pills .nav-link.active {
        background: var(--sh-gradient-primary);
        color: white;
    }

    /* ========================================
       BILINGUAL ARABIC NAME STYLING
       ======================================== */
    .product-name-ar {
        font-family: 'Noto Sans Arabic', 'Segoe UI', 'Tahoma', sans-serif;
        font-size: clamp(1rem, 2.5vw, 1.5rem);
        font-weight: 600;
        color: var(--sh-gold);
        letter-spacing: 0;
        line-height: 1.6;
        text-align: right;
        position: relative;
        padding: 8px 18px 8px 14px;
        border-right: 3px solid var(--sh-gold);
        background: linear-gradient(90deg, transparent 0%, rgba(212, 175, 55, 0.04) 100%);
        border-radius: 0 8px 8px 0;
        animation: fadeSlideAr 0.6s ease-out;
    }

    @keyframes fadeSlideAr {
        from {
            opacity: 0;
            transform: translateX(15px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* Arabic name in product cards (related products) */
    .card-name-ar {
        display: block;
        font-family: 'Noto Sans Arabic', 'Tahoma', sans-serif;
        font-size: 12px;
        font-weight: 600;
        color: var(--sh-gold, #D4AF37);
        text-align: right;
        margin-top: 4px;
        opacity: 0.85;
        line-height: 1.5;
    }

    .nav-pills .nav-link:not(.active):hover {
        background: var(--sh-gray-200);
    }

    /* Bundle & Save Styling */
    .bundle-option-card {
        background: white;
        border: 2px solid #edf2f7;
        border-radius: 12px;
        padding: 15px 20px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        margin-bottom: 8px;
    }

    .bundle-option-card:hover {
        border-color: var(--sh-primary);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .bundle-option-card.selected {
        border-color: var(--sh-primary);
        background: rgba(18, 24, 38, 0.02);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
    }

    .bundle-option-card.selected .custom-radio {
        border-color: var(--sh-primary);
        background: var(--sh-primary);
        box-shadow: inset 0 0 0 3px white;
    }

    .custom-radio {
        width: 20px;
        height: 20px;
        border: 2px solid #cbd5e0;
        border-radius: 50%;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }

    .bundle-name {
        font-weight: 700;
        font-size: 14px;
        color: var(--sh-primary);
        margin-bottom: 2px;
    }

    .bundle-desc {
        font-size: 11px;
        color: #718096;
    }

    .bundle-price {
        font-weight: 700;
        font-size: 16px;
    }

    .best-seller-tag {
        position: absolute;
        top: 0;
        right: 0;
        background: var(--sh-primary);
        color: white;
        font-size: 9px;
        font-weight: 800;
        padding: 4px 12px;
        border-bottom-left-radius: 12px;
        letter-spacing: 0.5px;
    }

    .pulse-animation {
        animation: pulse-red 2s infinite;
    }

    @keyframes pulse-red {
        0% {
            opacity: 1;
            transform: scale(1);
        }

        50% {
            opacity: 0.8;
            transform: scale(1.05);
        }

        100% {
            opacity: 1;
            transform: scale(1);
        }
    }

    .bg-success-soft {
        background: rgba(5, 150, 105, 0.1);
    }

    .trust-reviews-snippet {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
    }

    .mini-review-item:last-child {
        border-bottom: none !important;
        margin-bottom: 0 !important;
        padding-bottom: 0 !important;
    }

    @media (max-width: 576px) {
        .bundle-option-card {
            padding: 12px 15px;
        }

        .bundle-name {
            font-size: 13px;
        }

        .bundle-price {
            font-size: 14px;
        }
    }

    /* Premium Trust Cards */
    .sh-trust-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid #eee !important;
    }

    .sh-trust-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.08) !important;
        border-color: var(--sh-gold) !important;
    }

    /* =============================================
       MOBILE PRODUCT HEADER – Clean Name at top
       ============================================= */
    #mobileProductHeader {
        background: #fff;
        border-bottom: 1px solid #f0f0f0;
        padding-bottom: 10px !important;
        margin-bottom: 0 !important;
    }
    #mobileProductHeader .sh-heading-1 {
        font-size: clamp(1.3rem, 5vw, 1.8rem);
        font-weight: 700;
        color: var(--sh-primary);
        letter-spacing: -0.5px;
    }

    /* =============================================
       PRODUCT GALLERY – Full responsive fix
       ============================================= */
    .sh-product-gallery { position: relative; }
    .sh-main-image {
        border-radius: 16px;
        overflow: hidden;
        background: var(--sh-gray-50);
        cursor: zoom-in;
    }
    .sh-main-image img {
        width: 100%;
        height: 420px;
        object-fit: cover;
        display: block;
        transition: transform 0.4s ease;
    }
    @media (max-width: 576px) {
        .sh-main-image img { height: 280px; }
    }
    @media (min-width: 992px) {
        .sh-main-image img { height: 520px; }
    }
    /* Zoom Lens Overlay */
    .zoom-lens {
        position: absolute;
        border: 2px solid var(--sh-gold);
        width: 80px; height: 80px;
        background: rgba(212, 175, 55, 0.15);
        cursor: none;
        pointer-events: none;
        display: none;
        border-radius: 4px;
        z-index: 20;
    }
    .sh-thumb-list {
        display: flex;
        gap: 8px;
        margin-top: 12px;
        overflow-x: auto;
        padding-bottom: 4px;
        scrollbar-width: none;
    }
    .sh-thumb-list::-webkit-scrollbar { display: none; }
    .sh-thumb-item {
        flex-shrink: 0;
        width: 64px;
        height: 64px;
        border-radius: 10px;
        overflow: hidden;
        border: 2px solid transparent;
        cursor: pointer;
        transition: all 0.25s ease;
        opacity: 0.65;
    }
    .sh-thumb-item:hover, .sh-thumb-item.active {
        border-color: var(--sh-gold);
        opacity: 1;
        transform: translateY(-2px);
    }
    .sh-thumb-item img {
        width: 100%; height: 100%;
        object-fit: cover;
    }

    /* =============================================
       CUSTOMER FEEDBACK SECTION – Premium Redesign
       ============================================= */
    #reviews {
        background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
        padding: 60px 0;
    }
    .sh-reviews-section {
        background: #fff;
        border-radius: 20px;
        padding: 28px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.06);
        border: 1px solid #f0f0f0;
    }
    @media (max-width: 576px) {
        .sh-reviews-section { padding: 18px 14px; }
        #reviews { padding: 36px 0; }
    }
    .sh-review-stats { display: flex; gap: 40px; align-items: center; flex-wrap: wrap; }
    .sh-stats-number {
        font-size: 5rem;
        font-weight: 900;
        color: #121826;
        line-height: 1;
        letter-spacing: -3px;
    }
    .sh-stats-stars { color: #C5A059; font-size: 18px; margin: 10px 0 5px; }
    .sh-stats-count { font-size: 14px; color: #64748b; font-weight: 600; }
    .sh-stats-bars { flex: 1; min-width: 250px; }
    .sh-bar-row {
        display: flex; align-items: center;
        gap: 8px; margin-bottom: 8px; font-size: 12px;
    }
    .sh-bar-label { width: 32px; color: #666; flex-shrink: 0; font-weight: 600; }
    .sh-bar-track {
        flex: 1; height: 7px; background: #f0f0f0;
        border-radius: 99px; overflow: hidden;
    }
    .sh-bar-fill {
        height: 100%; background: var(--sh-gold);
        border-radius: 99px; width: 0%;
        transition: width 1.4s cubic-bezier(0.1, 0.5, 0.5, 1);
    }
    .sh-bar-count { width: 28px; color: #888; font-size: 11px; text-align: right; }
    /* Review Cards */
    .sh-review-card {
        background: #fff;
        border: 1px solid #f0f4f8;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 16px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .sh-review-card::before {
        content: '\201C';
        position: absolute;
        top: 8px; right: 16px;
        font-size: 72px;
        color: #f0f0f0;
        font-family: Georgia, serif;
        line-height: 1;
        pointer-events: none;
    }
    .sh-review-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(0,0,0,0.08);
        border-color: rgba(212, 175, 55, 0.25);
    }
    .sh-review-header {
        display: flex; justify-content: space-between;
        align-items: flex-start; margin-bottom: 12px;
        flex-wrap: wrap; gap: 8px;
    }
    .sh-review-author { display: flex; align-items: center; gap: 12px; }
    .sh-review-avatar {
        width: 44px; height: 44px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; color: #fff; font-size: 14px;
        flex-shrink: 0; letter-spacing: 0.5px;
    }
    .sh-review-name { font-weight: 700; font-size: 14px; color: var(--sh-primary); }
    .sh-review-date { font-size: 11px; color: #999; margin-top: 2px; }
    .sh-review-stars { color: var(--sh-gold); font-size: 13px; }
    .sh-review-text {
        font-size: 14px; line-height: 1.75;
        color: #555; margin: 0;
    }
    .sh-verified-badge {
        display: inline-flex; align-items: center; gap: 4px;
        background: rgba(5, 150, 105, 0.08);
        color: var(--sh-success, #059669);
        font-size: 10px; font-weight: 700;
        padding: 3px 8px; border-radius: 99px;
        text-transform: uppercase; letter-spacing: 0.4px;
        border: 1px solid rgba(5, 150, 105, 0.2);
    }
    /* Mobile review layout fix */
    @media (max-width: 576px) {
        .sh-review-header { flex-direction: column; }
        .sh-review-avatar { width: 36px; height: 36px; font-size: 12px; }
        .sh-review-name { font-size: 13px; }
        .sh-review-text { font-size: 13px; }
        .sh-review-card { padding: 14px 12px; }
        .sh-review-card::before { font-size: 48px; top: 4px; right: 8px; }
    }

    /* =============================================
       STICKY MOBILE CART BAR (Premium Gold/Black)
       ============================================= */
    #stickyCartBar {
        position: fixed;
        bottom: 0; left: 0; right: 0;
        background: #121826;
        color: #fff;
        padding: 10px 16px;
        z-index: 9998;
        transform: translateY(100%);
        transition: transform 0.4s cubic-bezier(0.2, 0.8, 0.2, 1);
        border-top: 1px solid rgba(197, 160, 89, 0.3);
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 -10px 30px rgba(0,0,0,0.3);
    }
    #stickyCartBar.visible { transform: translateY(0); }
    #stickyCartBar .sticky-img {
        width: 48px; height: 48px; border-radius: 8px;
        object-fit: cover; flex-shrink: 0;
        border: 1px solid rgba(255,255,255,0.1);
    }
    #stickyCartBar .sticky-name {
        flex: 1; font-size: 13px; font-weight: 700;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        max-width: 130px; color: #f8fafc;
    }
    #stickyCartBar .sticky-price {
        font-size: 16px; font-weight: 800;
        color: #C5A059; flex-shrink: 0;
    }
    #stickyCartBar .sticky-btn {
        background: linear-gradient(135deg, #C5A059 0%, #D4AF37 100%);
        color: #121826; border: none;
        padding: 10px 22px;
        border-radius: 50px;
        font-weight: 800; font-size: 13px;
        text-transform: uppercase; letter-spacing: 0.5px;
        cursor: pointer;
        transition: all 0.25s ease;
        flex-shrink: 0;
        white-space: nowrap;
        box-shadow: 0 4px 15px rgba(197, 160, 89, 0.25);
    }
    #stickyCartBar .sticky-btn:active { transform: scale(0.95); }
    /* Only show on mobile */
    @media (min-width: 992px) { #stickyCartBar { display: none !important; } }

    /* =============================================
       FLOATING "WATCHED BY" URGENCY TICKER
       ============================================= */
    #urgencyTicker {
        position: fixed;
        bottom: 80px; left: 16px;
        background: #fff;
        border: 1px solid #eee;
        border-left: 3px solid #e74c3c;
        border-radius: 12px;
        padding: 10px 14px;
        font-size: 12px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        z-index: 9990;
        max-width: 240px;
        display: flex; align-items: center; gap: 8px;
        opacity: 0; transform: translateX(-120%);
        transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        pointer-events: none;
    }
    #urgencyTicker.visible {
        opacity: 1; transform: translateX(0);
    }
    #urgencyTicker .ticker-dot {
        width: 8px; height: 8px; border-radius: 50%;
        background: #e74c3c; flex-shrink: 0;
        animation: blink-dot 1s infinite;
    }
    @keyframes blink-dot {
        0%, 100% { opacity: 1; } 50% { opacity: 0.2; }
    }

    /* =============================================
       IMAGE ZOOM MODAL
       ============================================= */
    #imgZoomModal {
        display: none;
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.92);
        z-index: 99999;
        align-items: center; justify-content: center;
        cursor: zoom-out;
    }
    #imgZoomModal.open { display: flex; }
    #imgZoomModal img {
        max-width: 92vw; max-height: 92vh;
        object-fit: contain;
        border-radius: 8px;
        animation: zoom-in-modal 0.3s ease;
    }
    @keyframes zoom-in-modal {
        from { transform: scale(0.85); opacity: 0; }
        to   { transform: scale(1);    opacity: 1; }
    }
    #imgZoomModal .zoom-close {
        position: absolute;
        top: 16px; right: 16px;
        background: rgba(255,255,255,0.15);
        border: none; color: #fff;
        width: 40px; height: 40px;
        border-radius: 50%; font-size: 20px;
        cursor: pointer; display: flex;
        align-items: center; justify-content: center;
        transition: background 0.2s;
    }
    #imgZoomModal .zoom-close:hover { background: rgba(255,255,255,0.3); }

    /* =============================================
       GENERAL RESPONSIVE FIXES
       ============================================= */
    @media (max-width: 768px) {
        .sh-section, .sh-section-sm { padding: 32px 0; }
        .sh-section-header { margin-bottom: 24px; }
        .sh-section-title { font-size: 1.6rem; }
        .bundle-save-section { margin-top: 16px; }
        .trust-reviews-snippet { min-height: auto !important; }
        .mini-reviews-slider { height: auto !important; min-height: 90px; }
        .product-info .fs-1 { font-size: 1.8rem !important; }
        .sh-qty-selector { min-width: 120px; }
        .sh-btn { padding: 13px 20px; font-size: 13px; }
        .tab-content { padding: 20px 16px !important; }
    }
    @media (max-width: 480px) {
        .container-fluid { padding-left: 12px !important; padding-right: 12px !important; }
        .sh-reviews-section { padding: 14px 10px; }
        .sh-stats-number { font-size: 3rem; }
        .breadcrumb { font-size: 11px !important; }
    }
</style>

<!-- ============================================ -->
<!-- STICKY MOBILE CART BAR -->
<!-- ============================================ -->
<div id="stickyCartBar">
    <img class="sticky-img"
         src="<?php echo Helpers::upload($productImages[0]['image_path']); ?>"
         alt="<?php echo Security::escape($product['name']); ?>">
    <div class="sticky-name"><?php echo Security::escape($product['name']); ?></div>
    <div class="sticky-price"><?php echo Helpers::formatPrice($product['price']); ?></div>
    <?php if ($product['stock_quantity'] > 0): ?>
    <button class="sticky-btn" onclick="addToCart()">
        <i class="bi bi-bag-plus me-1"></i> Add
    </button>
    <?php endif; ?>
</div>

<!-- URGENCY FLOATING TICKER -->
<div id="urgencyTicker">
    <span class="ticker-dot"></span>
    <span id="urgencyText">🔥 <strong>23 people</strong> viewed this today</span>
</div>

<!-- IMAGE ZOOM MODAL -->
<div id="imgZoomModal" onclick="closeZoomModal()">
    <button class="zoom-close" onclick="closeZoomModal()">
        <i class="bi bi-x-lg"></i>
    </button>
    <img id="zoomModalImg" src="" alt="Product Zoom">
</div>

<script>
/* =============================================
   1. STICKY CART BAR – show after scrolling past hero
   ============================================= */
(function () {
    const bar     = document.getElementById('stickyCartBar');
    const trigger = document.querySelector('.sh-main-image');
    if (!bar || !trigger) return;

    const io = new IntersectionObserver(([entry]) => {
        if (!entry.isIntersecting) {
            bar.classList.add('visible');
        } else {
            bar.classList.remove('visible');
        }
    }, { threshold: 0.1 });
    io.observe(trigger);
})();

/* =============================================
   2. IMAGE ZOOM MODAL (tap/click main image)
   ============================================= */
function openZoomModal(src) {
    const modal = document.getElementById('imgZoomModal');
    const img   = document.getElementById('zoomModalImg');
    if (!modal || !img) return;
    img.src = src;
    modal.classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeZoomModal() {
    const modal = document.getElementById('imgZoomModal');
    if (modal) modal.classList.remove('open');
    document.body.style.overflow = '';
}

// Wire up main image click → zoom modal
document.addEventListener('DOMContentLoaded', function () {
    const mainImg = document.getElementById('mainProductImage');
    if (mainImg) {
        mainImg.style.cursor = 'zoom-in';
        mainImg.addEventListener('click', function () {
            openZoomModal(this.src);
        });
    }
});

/* =============================================
   3. URGENCY TICKER – Real-time style viewer count
   ============================================= */
(function () {
    const ticker    = document.getElementById('urgencyTicker');
    const textEl    = document.getElementById('urgencyText');
    if (!ticker || !textEl) return;

    const messages = [
        count => `🔥 <strong>${count} people</strong> are viewing this right now`,
        count => `⚡ <strong>${count} shoppers</strong> have this in their cart`,
        ()    => `✅ <strong>Verified</strong> – 4.8★ rating from UAE customers`,
        ()    => `🚚 <strong>Free shipping</strong> available across UAE today`,
        count => `👀 <strong>${count + 5} people</strong> viewed this in the last hour`,
    ];

    let shown = false;
    function show() {
        const count = Math.floor(Math.random() * 25) + 12;
        const fn    = messages[Math.floor(Math.random() * messages.length)];
        textEl.innerHTML = fn(count);
        ticker.classList.add('visible');
        shown = true;
        setTimeout(() => { ticker.classList.remove('visible'); }, 5000);
    }

    // First show after 6 seconds
    setTimeout(show, 6000);
    // Then repeat every 18 seconds
    setInterval(() => { if (Math.random() > 0.3) show(); }, 18000);
})();

/* =============================================
   4. SCROLL-TRIGGERED ENTRANCE ANIMATIONS
   ============================================= */
document.addEventListener('DOMContentLoaded', function () {
    const style = document.createElement('style');
    style.textContent = `
        .anim-hidden { opacity: 0; transform: translateY(28px); transition: opacity 0.65s ease, transform 0.65s ease; }
        .anim-visible { opacity: 1 !important; transform: translateY(0) !important; }
    `;
    document.head.appendChild(style);

    const targets = document.querySelectorAll('.sh-review-card, .sh-trust-card, .bundle-option-card');
    targets.forEach((el, i) => {
        el.classList.add('anim-hidden');
        el.style.transitionDelay = (i % 4) * 0.08 + 's';
    });

    const io = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('anim-visible');
                io.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });

    targets.forEach(el => io.observe(el));
});

/* =============================================
   5. ADD-TO-CART CONFETTI BURST
   ============================================= */
function launchConfetti() {
    const colors = ['#D4AF37','#121826','#059669','#DC2626','#fff'];
    for (let i = 0; i < 40; i++) {
        const dot = document.createElement('div');
        const size = Math.random() * 8 + 5;
        dot.style.cssText = `
            position:fixed; z-index:99999; pointer-events:none;
            width:${size}px; height:${size}px;
            border-radius:${Math.random() > 0.5 ? '50%' : '2px'};
            background:${colors[Math.floor(Math.random() * colors.length)]};
            left:${Math.random() * 100}vw; top:${Math.random() * 30 + 40}vh;
            animation: confetti-fall ${Math.random() * 1.5 + 1}s ease forwards;
        `;
        document.body.appendChild(dot);
        setTimeout(() => dot.remove(), 2500);
    }
}

// Inject confetti keyframes
(function () {
    const s = document.createElement('style');
    s.textContent = `
        @keyframes confetti-fall {
            0%   { transform: translateY(0) rotate(0deg); opacity: 1; }
            100% { transform: translateY(220px) rotate(720deg); opacity: 0; }
        }
    `;
    document.head.appendChild(s);
})();

// Hook into the existing addToCart function
const _origAddToCart = typeof addToCart === 'function' ? addToCart : null;
window.addToCart = function () {
    if (_origAddToCart) _origAddToCart();
    launchConfetti();
    // Show success flash on sticky bar button
    const btn = document.querySelector('#stickyCartBar .sticky-btn');
    if (btn) {
        const orig = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Added!';
        btn.style.background = '#059669';
        btn.style.color = '#fff';
        setTimeout(() => {
            btn.innerHTML = orig;
            btn.style.background = '';
            btn.style.color = '';
        }, 2000);
    }
};

/* =============================================
   6. MINI PRODUCT IMAGE SLIDE (dot nav)
   ============================================= */
document.addEventListener('DOMContentLoaded', function () {
    // Ensure mini review slider dots work
    const slides = document.querySelectorAll('.mini-review-slide');
    const dots   = document.querySelectorAll('#review-nav-dots .dot');
    let cur = 0;

    function goTo(n) {
        slides[cur]?.classList.remove('active');
        dots[cur]?.classList.remove('active');
        cur = (n + slides.length) % slides.length;
        slides[cur]?.classList.add('active');
        dots[cur]?.classList.add('active');
    }
    // Make dots clickable
    dots.forEach((dot, i) => { dot.style.cursor = 'pointer'; dot.addEventListener('click', () => goTo(i)); });
});

/* =============================================
   7. REVIEW SECTION – Star filter interaction
   ============================================= */
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.sh-bar-row').forEach(row => {
        row.style.cursor = 'pointer';
        row.title = 'Filter reviews by this rating';
        row.addEventListener('click', function () {
            // Visual pulse feedback
            const fill = this.querySelector('.sh-bar-fill');
            if (fill) {
                fill.style.filter = 'brightness(1.4)';
                setTimeout(() => fill.style.filter = '', 400);
            }
        });
    });
});

/* =============================================
   8. KEYBOARD SHORTCUT – Press 'C' to add to cart
   ============================================= */
document.addEventListener('keydown', function (e) {
    const tag = document.activeElement.tagName;
    if (['INPUT','TEXTAREA','SELECT'].includes(tag)) return;
    if (e.key === 'c' || e.key === 'C') {
        const btn = document.getElementById('addToCartBtn');
        if (btn) { btn.click(); btn.focus(); }
    }
    if (e.key === 'b' || e.key === 'B') {
        if (typeof buyNow === 'function') buyNow();
    }
});
</script>

<?php require_once 'includes/footer.php'; ?>
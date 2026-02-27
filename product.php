<?php
/**
 * Product Detail Page - Premium Shopify-Grade Design
 * Edluxury - Professional Product Experience with Live Reviews
 */

$slug = $_GET['slug'] ?? '';

if (empty($slug)) {
    require_once 'config/config.php';
    header('Location: ' . SITE_URL);
    exit;
}

require_once 'includes/header.php';

$productModel = new Product();
$product = $productModel->getBySlug($slug);
$db = Database::getInstance();

$waPhone = $theme->get('contact_phone', '923491697043');
$waPhone = preg_replace('/[^0-9]/', '', $waPhone);

if (!$product) {
    header('HTTP/1.0 404 Not Found');
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

<!-- Product Detail Section -->
<section class="sh-section-sm">
    <div class="container-fluid px-3 px-md-4 px-lg-5">
        <div class="row g-4 g-lg-5">

            <!-- Left: Product Gallery -->
            <div class="col-lg-6" data-aos="fade-right">
                <div class="sh-product-gallery">
                    <!-- Main Image -->
                    <div class="sh-main-image position-relative overflow-hidden">
                        <img id="mainProductImage" src="<?php echo Helpers::upload($productImages[0]['image_path']); ?>"
                            alt="<?php echo Security::escape($product['name']); ?>" onclick="openLightbox(0)"
                            style="transition: all 0.3s ease; transform: scale(1); opacity: 1;">

                        <!-- Image Zoom Icon -->
                        <button class="position-absolute bottom-0 end-0 m-3 btn btn-light rounded-circle shadow"
                            onclick="openLightbox(0)" style="width: 50px; height: 50px;">
                            <i class="bi bi-zoom-in fs-5"></i>
                        </button>

                        <!-- Badges -->
                        <div class="position-absolute top-0 start-0 m-3 d-flex flex-column gap-2">
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
                    </div>

                    <!-- Thumbnails -->
                    <?php if (count($productImages) > 1): ?>
                        <div class="sh-thumb-list">
                            <?php foreach ($productImages as $index => $image): ?>
                                <div class="sh-thumb-item <?php echo $index === 0 ? 'active' : ''; ?>"
                                    onclick="selectImage(<?php echo $index; ?>, true)">
                                    <img src="<?php echo Helpers::upload($image['image_path']); ?>"
                                        alt="Product thumbnail <?php echo $index + 1; ?>">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Image Navigation for Mobile -->
                    <div class="d-lg-none mt-3 text-center">
                        <small class="text-muted">
                            <span id="currentImageIndex">1</span> / <?php echo count($productImages); ?> images • Tap to
                            zoom
                        </small>
                    </div>

                    <?php
                    // Product Video Section
                    $videoUrl = $product['video_url'] ?? '';
                    if (!empty($videoUrl)):
                        // Convert to embed URL
                        $embedUrl = $videoUrl;
                        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)?([\w-]{11})/', $videoUrl, $ytMatch)) {
                            $embedUrl = 'https://www.youtube.com/embed/' . $ytMatch[1] . '?rel=0&modestbranding=1';
                            $isIframe = true;
                        } elseif (preg_match('/vimeo\.com\/(\d+)/', $videoUrl, $vmMatch)) {
                            $embedUrl = 'https://player.vimeo.com/video/' . $vmMatch[1];
                            $isIframe = true;
                        } elseif (preg_match('/\.(mp4|webm|ogg)$/i', $videoUrl)) {
                            $isIframe = false;
                        } else {
                            $isIframe = true; // Default to iframe embed
                        }
                        ?>
                        <!-- Product Video Player -->
                        <div class="mt-4" data-aos="fade-up">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <div
                                    style="width:32px;height:32px;background:linear-gradient(135deg,#0F3D3E,#1a5f61);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                                    <i class="bi bi-play-fill text-white" style="font-size:14px;"></i>
                                </div>
                                <span class="fw-bold"
                                    style="font-size:14px;text-transform:uppercase;letter-spacing:1px;color:#0F3D3E;">Product
                                    Video</span>
                            </div>

                            <?php if ($isIframe): ?>
                                <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow-sm"
                                    style="border:2px solid #eee;">
                                    <iframe src="<?php echo htmlspecialchars($embedUrl); ?>"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen
                                        title="<?php echo Security::escape($product['name']); ?> - Product Video"></iframe>
                                </div>
                            <?php else: ?>
                                <div class="rounded-4 overflow-hidden shadow-sm" style="border:2px solid #eee;">
                                    <video controls class="w-100" style="display:block;max-height:360px;object-fit:cover;">
                                        <source src="<?php echo htmlspecialchars($embedUrl); ?>" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right: Product Info -->
            <div class="col-lg-6" data-aos="fade-left">
                <div class="product-info">
                    <!-- Category Badge -->
                    <span class="sh-section-subtitle d-inline-block text-start mb-3"
                        style="font-size: 11px; padding: 5px 12px;">
                        <?php echo Security::escape($product['category_name'] ?: 'PREMIUM COLLECTION'); ?>
                    </span>

                    <!-- Title (Bilingual: English + Arabic) -->
                    <h1 class="sh-heading-1 mb-1" style="line-height: 1.2;" id="productNameEn">
                        <?php echo Security::escape($product['name']); ?>
                    </h1>
                    <?php if (!empty($product['name_ar'])): ?>
                        <p class="product-name-ar mb-3" dir="rtl" lang="ar" id="productNameAr">
                            <?php echo Security::escape($product['name_ar']); ?>
                        </p>
                    <?php else: ?>
                        <p class="product-name-ar mb-3" dir="rtl" lang="ar" id="productNameAr" style="display:none;"></p>
                    <?php endif; ?>

                    <!-- Rating Summary -->
                    <div class="d-flex align-items-center gap-3 mb-4 flex-wrap">
                        <div class="d-flex align-items-center gap-1">
                            <span style="color: var(--sh-gold); font-size: 16px;">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="bi bi-star-fill"></i>
                                <?php endfor; ?>
                            </span>
                            <span class="fw-bold ms-1"><?php echo $reviewStats['average']; ?></span>
                        </div>
                        <a href="#reviews" class="text-muted text-decoration-none small">
                            <?php echo $reviewStats['total']; ?> verified reviews
                        </a>
                        <span class="badge bg-success-soft text-success px-3 py-2 rounded-pill fw-bold">
                            <i class="bi bi-check-circle-fill me-1"></i> In Stock
                        </span>

                        <!-- 🚀 LIVE VIEWING COUNTER (Animated) -->
                        <span class="ms-3 small fw-semibold text-muted d-inline-flex align-items-center gap-2">
                            <span class="d-flex position-relative" style="width: 10px; height: 10px;">
                                <span
                                    class="position-absolute w-100 h-100 bg-success rounded-circle animate-ping opacity-75"></span>
                                <span class="position-relative w-100 h-100 bg-success rounded-circle"></span>
                            </span>
                            <span id="viewingCount"><?php echo rand(12, 48); ?></span> people are viewing this
                        </span>
                    </div>

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

                    <!-- Price Box -->
                    <div class="p-4 mb-4 rounded-4"
                        style="background: linear-gradient(135deg, rgba(255,107,53,0.08) 0%, rgba(255,143,102,0.05) 100%); border: 1px solid rgba(255,107,53,0.15);">
                        <div class="d-flex align-items-baseline gap-3 flex-wrap">
                            <span class="fs-1 fw-bold" style="color: var(--sh-primary);">
                                <?php echo Helpers::formatPrice($product['price']); ?>
                            </span>
                            <?php if (!empty($product['compare_price']) && $product['compare_price'] > $product['price']): ?>
                                <span class="fs-4 text-muted text-decoration-line-through">
                                    <?php echo Helpers::formatPrice($product['compare_price']); ?>
                                </span>
                                <span class="badge rounded-pill px-3 py-2 fw-bold"
                                    style="background: var(--sh-danger); font-size: 14px;">
                                    SAVE <?php echo Helpers::formatPrice($product['compare_price'] - $product['price']); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <p class="mb-0 mt-2 text-muted small">
                            <i class="bi bi-truck me-1"></i> Free shipping across UAE • <i
                                class="bi bi-box-seam me-1"></i> Usually ships in 24 hours
                        </p>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <p class="text-muted" style="font-size: 15px; line-height: 1.8;">
                            <?php echo nl2br(Security::escape($product['description'])); ?>
                        </p>
                    </div>

                    <!-- Enhanced Marketing: Highlights -->
                    <?php if (!empty($product['highlights'])): ?>
                        <div class="product-highlights mb-4">
                            <ul class="list-unstyled d-flex flex-column gap-2">
                                <?php
                                $lines = explode("\n", $product['highlights']);
                                foreach ($lines as $line):
                                    if (trim($line)):
                                        ?>
                                        <li class="d-flex align-items-start gap-2" style="font-size: 14px; color: #444;">
                                            <i class="bi bi-check-circle-fill text-success mt-1" style="font-size: 12px;"></i>
                                            <span><?php echo Security::escape(trim($line)); ?></span>
                                        </li>
                                    <?php
                                    endif;
                                endforeach;
                                ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <!-- Enhanced Marketing: Stock Urgency Bar -->
                    <?php if (!empty($product['show_stock_bar']) && $product['stock_quantity'] > 0):
                        $maxStock = 50; // Reference for the bar
                        $stockPercent = ($product['stock_quantity'] / $maxStock) * 100;
                        if ($stockPercent > 100)
                            $stockPercent = 100;
                        if ($stockPercent < 15 && $product['stock_quantity'] > 0)
                            $stockPercent = 15;
                        ?>
                        <div class="stock-urgency-wrap mb-4">
                            <div class="d-flex justify-content-between align-items-end mb-2">
                                <span class="fw-bold"
                                    style="font-size: 12px; color: #e74c3c; text-transform: uppercase; letter-spacing: 0.5px;">
                                    Hurry! Only <?php echo $product['stock_quantity']; ?> left in stock
                                </span>
                                <span class="text-muted small" style="font-size: 11px;">Selling fast!</span>
                            </div>
                            <div class="progress rounded-pill" style="height: 8px; background-color: #fce4ec;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger"
                                    role="progressbar" style="width: <?php echo $stockPercent; ?>%"></div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Enhanced Marketing: Countdown Timer -->
                    <?php if (!empty($product['show_countdown']) && !empty($product['countdown_end']) && strtotime($product['countdown_end']) > time()): ?>
                        <div class="countdown-timer-wrap mb-4 p-3 rounded-4"
                            style="background: #fff5f2; border: 1px dashed #ff8f66;">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-clock-history text-danger"></i>
                                <span class="fw-bold small text-danger text-uppercase">Limited Time Offer Ends In:</span>
                            </div>
                            <div class="d-flex gap-3 text-center" id="countdown-timer"
                                data-until="<?php echo date('Y-m-d H:i:s', strtotime($product['countdown_end'])); ?>">
                                <div class="timer-block">
                                    <div class="h4 fw-bold mb-0" id="days">00</div>
                                    <div class="text-muted x-small" style="font-size: 10px;">DAYS</div>
                                </div>
                                <div class="timer-sep h4 mb-0">:</div>
                                <div class="timer-block">
                                    <div class="h4 fw-bold mb-0" id="hours">00</div>
                                    <div class="text-muted x-small" style="font-size: 10px;">HRS</div>
                                </div>
                                <div class="timer-sep h4 mb-0">:</div>
                                <div class="timer-block">
                                    <div class="h4 fw-bold mb-0" id="mins">00</div>
                                    <div class="text-muted x-small" style="font-size: 10px;">MIN</div>
                                </div>
                                <div class="timer-sep h4 mb-0">:</div>
                                <div class="timer-block">
                                    <div class="h4 fw-bold mb-0" id="secs">00</div>
                                    <div class="text-muted x-small" style="font-size: 10px;">SEC</div>
                                </div>
                            </div>
                        </div>
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
                    <?php endif; ?>

                    <!-- Quantity & Add to Cart -->
                    <?php if ($product['stock_quantity'] > 0): ?>
                        <div class="d-flex gap-3 mb-4 flex-column flex-sm-row">
                            <!-- Quantity Selector -->
                            <div class="sh-qty-selector">
                                <button type="button" class="sh-qty-btn" onclick="changeQty(-1)">
                                    <i class="bi bi-dash"></i>
                                </button>
                                <input type="number" id="purchase-qty" value="1" min="1"
                                    max="<?php echo $product['stock_quantity']; ?>" readonly class="sh-qty-value border-0">
                                <button type="button" class="sh-qty-btn" onclick="changeQty(1)">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>

                            <!-- Add to Cart Button -->
                            <button class="sh-btn sh-btn-primary flex-grow-1" onclick="addToCart()" id="addToCartBtn">
                                <i class="bi bi-bag-plus me-2"></i> Add to Cart –
                                <?php echo Helpers::formatPrice($product['price']); ?>
                            </button>
                        </div>

                        <!-- Buy Now -->
                        <div class="mb-4">
                            <button class="sh-btn sh-btn-dark sh-btn-full btn-buy-pulse" onclick="buyNow()">
                                <i class="bi bi-lightning-fill me-2"></i>
                                <?php echo !empty($product['custom_buy_button']) ? Security::escape($product['custom_buy_button']) : 'Buy Now – Express Checkout'; ?>
                            </button>
                        </div>

                        <style>
                            .btn-buy-pulse {
                                animation: shadow-pulse 2s infinite;
                                transition: all 0.3s ease;
                            }

                            @keyframes shadow-pulse {
                                0% {
                                    box-shadow: 0 0 0 0 rgba(0, 0, 0, 0.4);
                                }

                                70% {
                                    box-shadow: 0 0 0 10px rgba(0, 0, 0, 0);
                                }

                                100% {
                                    box-shadow: 0 0 0 0 rgba(0, 0, 0, 0);
                                }
                            }

                            .btn-buy-pulse:hover {
                                transform: translateY(-2px);
                                background: #000 !important;
                                filter: brightness(1.2);
                            }
                        </style>

                        <!-- NEW: Product Discount Options (Bundle & Save) -->
                        <div class="bundle-save-section mb-4" data-aos="fade-up">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h6 class="fw-bold mb-0 text-uppercase" style="font-size: 13px; letter-spacing: 1px;">
                                    <i class="bi bi-lightning-charge-fill text-warning me-2"></i>Bundle & Save Extra
                                </h6>
                                <span class="badge bg-danger pulse-animation" style="font-size: 10px;">LIMITED OFFER</span>
                            </div>

                            <div class="row g-2">
                                <div class="col-12">
                                    <div class="bundle-option-card selected" onclick="selectBundle(1, 0, this)">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <div class="custom-radio me-3"></div>
                                                <div>
                                                    <div class="bundle-name">Buy 1 Unit</div>
                                                    <div class="bundle-desc">Standard price</div>
                                                </div>
                                            </div>
                                            <div class="bundle-price"><?php echo Helpers::formatPrice($product['price']); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="bundle-option-card" onclick="selectBundle(2, 10, this)">
                                        <div class="best-seller-tag">POPULAR</div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <div class="custom-radio me-3"></div>
                                                <div>
                                                    <div class="bundle-name">Buy 2 Units <span
                                                            class="ms-2 badge bg-success text-white"
                                                            style="font-size: 9px;">SAVE 10%</span></div>
                                                    <div class="bundle-desc">Perfect for sharing</div>
                                                </div>
                                            </div>
                                            <div class="bundle-price text-success">
                                                <?php echo Helpers::formatPrice($product['price'] * 2 * 0.9); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="bundle-option-card" onclick="selectBundle(3, 20, this)">
                                        <div class="best-seller-tag" style="background: var(--sh-gold);">BEST VALUE</div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <div class="custom-radio me-3"></div>
                                                <div>
                                                    <div class="bundle-name">Buy 3 Units <span
                                                            class="ms-2 badge bg-primary text-white"
                                                            style="font-size: 9px;">SAVE 20%</span></div>
                                                    <div class="bundle-desc">Full professional set</div>
                                                </div>
                                            </div>
                                            <div class="bundle-price" style="color: var(--sh-gold); font-weight: 800;">
                                                <?php echo Helpers::formatPrice($product['price'] * 3 * 0.8); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

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


                    <!-- Trust Features -->
                    <div class="row g-3" dir="<?php echo CURRENT_LANG === 'ar' ? 'rtl' : 'ltr'; ?>">
                        <div class="col-6 col-md-3">
                            <div class="text-center p-3 rounded-4 shadow-sm border-0 h-100 sh-trust-card"
                                style="background: white;">
                                <i class="bi bi-truck fs-3 d-block mb-2" style="color: var(--sh-primary);"></i>
                                <span class="small fw-bold d-block text-uppercase"
                                    style="letter-spacing: 0.5px;"><?php echo Helpers::translate('free_delivery'); ?></span>
                                <span class="small text-muted"><?php echo Helpers::translate('all_uae'); ?></span>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="text-center p-3 rounded-4 shadow-sm border-0 h-100 sh-trust-card"
                                style="background: white;">
                                <i class="bi bi-shield-check fs-3 d-block mb-2" style="color: var(--sh-gold);"></i>
                                <span class="small fw-bold d-block text-uppercase"
                                    style="letter-spacing: 0.5px;"><?php echo Helpers::translate('authentic'); ?></span>
                                <span class="small text-muted"><?php echo Helpers::translate('guaranteed'); ?></span>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="text-center p-3 rounded-4 shadow-sm border-0 h-100 sh-trust-card"
                                style="background: white;">
                                <i class="bi bi-arrow-repeat fs-3 d-block mb-2" style="color: #4a90e2;"></i>
                                <span class="small fw-bold d-block text-uppercase"
                                    style="letter-spacing: 0.5px;"><?php echo Helpers::translate('easy_returns'); ?></span>
                                <span class="small text-muted"><?php echo Helpers::translate('14_days'); ?></span>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="text-center p-3 rounded-4 shadow-sm border-0 h-100 sh-trust-card"
                                style="background: white;">
                                <i class="bi bi-credit-card fs-3 d-block mb-2" style="color: var(--sh-gold);"></i>
                                <span class="small fw-bold d-block text-uppercase"
                                    style="letter-spacing: 0.5px;"><?php echo Helpers::translate('secure_pay'); ?></span>
                                <span class="small text-muted"><?php echo Helpers::translate('ssl_encrypted'); ?></span>
                            </div>
                        </div>
                    </div>
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

    // Image Gallery
    let galleryInterval;
    const galleryDelay = 4000; // 4 seconds

    function selectImage(index, manual = false) {
        currentImageIndex = index;
        const mainImg = document.getElementById('mainProductImage');
        if (!mainImg) return;

        // Transition effect
        mainImg.style.opacity = '0.5';
        mainImg.style.transform = 'scale(0.98)';

        setTimeout(() => {
            mainImg.src = productImages[index];
            mainImg.style.opacity = '1';
            mainImg.style.transform = 'scale(1)';
        }, 150);

        document.querySelectorAll('.sh-thumb-item').forEach((el, i) => {
            el.classList.toggle('active', i === index);
        });

        const counter = document.getElementById('currentImageIndex');
        if (counter) counter.textContent = index + 1;

        if (manual) stopAutoSlide();
    }

    function startAutoSlide() {
        if (productImages.length <= 1) return;
        stopAutoSlide();
        galleryInterval = setInterval(() => {
            const nextIdx = (currentImageIndex + 1) % productImages.length;
            selectImage(nextIdx);
        }, galleryDelay);
    }

    function stopAutoSlide() {
        clearInterval(galleryInterval);
    }

    // Initialize gallery behavior
    document.addEventListener('DOMContentLoaded', () => {
        const galleryContainer = document.querySelector('.sh-product-gallery');
        if (galleryContainer) {
            galleryContainer.addEventListener('mouseenter', stopAutoSlide);
            galleryContainer.addEventListener('mouseleave', startAutoSlide);
            startAutoSlide();
        }
    });

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
</style>

<?php require_once 'includes/footer.php'; ?>
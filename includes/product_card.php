<?php
/**
 * Reusable Product Card Component - Shopify Edition
 * Clean, flat, high-contrast, minimalist
 */
$slug = $product['slug'] ?? '';
$name = $product['name'] ?? 'Product';
$image = $product['primary_image'] ?? '';
$badge = $product['badge'] ?? '';
$price = $product['price'] ?? 0;
$oldPrice = $product['old_price'] ?? null;
?>
<div class="sh-product-card">
    <div class="sh-card-media">
        <a href="<?php echo Helpers::url('product.php?slug=' . $slug); ?>">
            <?php if ($image): ?>
                <img src="<?php echo Helpers::upload($image); ?>" alt="<?php echo Security::escape($name); ?>"
                    class="sh-card-img">
            <?php else: ?>
                <img src="assets/images/placeholder-product.png" class="sh-card-img">
            <?php endif; ?>
        </a>

        <?php if ($badge): ?>
            <span class="sh-card-badge <?php echo 'sh-badge-' . strtolower($badge); ?>">
                <?php echo strtoupper($badge); ?>
            </span>
        <?php endif; ?>

        <div class="sh-card-actions">
            <button class="btn-sh-card add-to-cart-quick" data-id="<?php echo $product['id'] ?? 0; ?>">
                QUICK ADD
            </button>
        </div>

        <button class="btn-wishlist-toggle" data-id="<?php echo $product['id'] ?? 0; ?>"
            data-name="<?php echo Security::escape($name); ?>" data-price="<?php echo Helpers::formatPrice($price); ?>"
            data-image="<?php echo Helpers::upload($image); ?>" data-slug="<?php echo $slug; ?>"
            title="Add to Wishlist">
            <i class="bi bi-heart"></i>
        </button>
    </div>

    <div class="sh-card-body">
        <h3 class="sh-card-title">
            <a
                href="<?php echo Helpers::url('product.php?slug=' . $slug); ?>"><?php echo Security::escape($name); ?></a>
            <?php if (!empty($product['name_ar'])): ?>
                <span class="card-name-ar" dir="rtl" lang="ar"><?php echo Security::escape($product['name_ar']); ?></span>
            <?php endif; ?>
        </h3>
        <div class="sh-card-price">
            <span class="curr-price"><?php echo Helpers::formatPrice($price); ?></span>
            <?php if ($oldPrice): ?>
                <span class="old-price"><?php echo Helpers::formatPrice($oldPrice); ?></span>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .sh-product-card {
        background: #fff;
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }

    .sh-product-card:hover {
        border-color: #eee;
    }

    .sh-card-media {
        position: relative;
        overflow: hidden;
        background: #fdfdfd;
        aspect-ratio: 1 / 1;
    }

    .sh-card-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.8s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .sh-product-card:hover .sh-card-img {
        transform: scale(1.05);
    }

    .sh-card-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        padding: 4px 12px;
        font-size: 10px;
        font-weight: 700;
        color: #fff;
        border-radius: 50px;
        z-index: 2;
    }

    .sh-badge-sale {
        background: #e74c3c;
    }

    .sh-badge-new {
        background: #000;
    }

    .sh-badge-hot {
        background: #f39c12;
    }

    .sh-card-actions {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        padding: 10px;
        opacity: 0;
        transform: translateY(100%);
        transition: all 0.3s ease;
        z-index: 3;
    }

    .sh-product-card:hover .sh-card-actions {
        opacity: 1;
        transform: translateY(0);
    }

    .btn-sh-card {
        width: 100%;
        background: #fff;
        border: 1px solid #000;
        color: #000;
        padding: 8px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        transition: all 0.2s ease;
    }

    .btn-sh-card:hover {
        background: #000;
        color: #fff;
    }

    .sh-card-body {
        padding: 15px 5px;
    }

    .sh-card-title {
        margin: 0 0 8px;
        font-size: 14px;
        font-weight: 500;
        line-height: 1.4;
    }

    .sh-card-title a {
        color: #000;
        text-decoration: none;
        transition: text-decoration 0.2s;
    }

    .sh-card-title a:hover {
        text-decoration: underline;
    }

    .sh-card-price {
        font-size: 15px;
    }

    .curr-price {
        font-weight: 700;
    }

    .old-price {
        color: #999;
        text-decoration: line-through;
        font-size: 13px;
        margin-left: 5px;
    }

    .card-name-ar {
        display: block;
        font-family: 'Noto Sans Arabic', 'Tahoma', sans-serif;
        font-size: 12px;
        font-weight: 600;
        color: #D4AF37;
        text-align: right;
        margin-top: 4px;
        opacity: 0.85;
        line-height: 1.5;
    }

    .btn-wishlist-toggle {
        position: absolute;
        top: 15px;
        right: 15px;
        background: #fff;
        border: none;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        z-index: 4;
        color: #000;
        transition: all 0.3s ease;
        opacity: 0;
        transform: translateY(-10px);
    }

    .sh-product-card:hover .btn-wishlist-toggle {
        opacity: 1;
        transform: translateY(0);
    }

    .btn-wishlist-toggle:hover {
        background: #000;
        color: #fff;
    }

    .btn-wishlist-toggle.active {
        opacity: 1;
        transform: translateY(0);
        color: #e74c3c;
    }

    .btn-wishlist-toggle.active i::before {
        content: "\f415";
        /* bi-heart-fill */
    }
</style>
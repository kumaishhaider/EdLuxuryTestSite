<?php
/**
 * Wishlist Page - Dynamic JavaScript Implementation
 * Edluxury - Premium UI
 */
require_once __DIR__ . '/includes/header.php';
?>

<div class="wishlist-page py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center" data-aos="fade-up">
                <h1 class="display-4 fw-bold mb-3">Your Wishlist</h1>
                <p class="text-muted lead">Save your favorite items and shop them anytime.</p>
                <div class="sh-divider mx-auto"></div>
            </div>
        </div>

        <div id="wishlist-container" class="row g-4 justify-content-center">
            <!-- Shimmer Loading effect or empty state will be here -->
            <div class="col-12 text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .wishlist-page {
        min-height: 60vh;
        background: #fdfdfd;
    }

    .sh-divider {
        width: 60px;
        height: 3px;
        background: var(--sh-gradient-gold);
        border-radius: 2px;
    }

    .empty-wishlist-state {
        padding: 60px 20px;
    }

    .empty-wishlist-icon {
        font-size: 80px;
        color: #eee;
        margin-bottom: 20px;
        display: block;
    }

    .wishlist-item-card {
        height: 100%;
        display: flex;
        flex-direction: column;
        background: #fff;
        border: 1px solid #eee;
        transition: all 0.3s ease;
        position: relative;
    }

    .wishlist-item-card:hover {
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
        border-color: #ddd;
    }

    .remove-wishlist-item {
        position: absolute;
        top: 10px;
        right: 10px;
        background: #fff;
        border: none;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        z-index: 10;
        color: #999;
        transition: all 0.2s;
    }

    .remove-wishlist-item:hover {
        color: #e74c3c;
        transform: scale(1.1);
    }

    .wishlist-item-media {
        aspect-ratio: 1/1;
        overflow: hidden;
    }

    .wishlist-item-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s;
    }

    .wishlist-item-card:hover .wishlist-item-media img {
        transform: scale(1.05);
    }

    .wishlist-item-content {
        padding: 20px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .wishlist-item-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 10px;
        color: #000;
        text-decoration: none;
    }

    .wishlist-item-price {
        font-weight: 700;
        color: #000;
        margin-bottom: 15px;
    }

    .wishlist-actions {
        margin-top: auto;
        display: flex;
        gap: 10px;
    }

    .btn-wishlist-cart {
        flex-grow: 1;
        background: #000;
        color: #fff;
        border: none;
        padding: 10px;
        font-weight: 700;
        font-size: 12px;
        text-transform: uppercase;
        border-radius: 0;
        transition: all 0.3s;
    }

    .btn-wishlist-cart:hover {
        background: #333;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof Wishlist !== 'undefined') {
            Wishlist.renderPage();
        }
    });
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
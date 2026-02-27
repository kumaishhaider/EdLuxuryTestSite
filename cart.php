<?php
/**
 * Cart Page - Premium Shopify-Grade Design
 * Edluxury - VIBRANT & Responsive
 */

$pageTitle = 'Shopping Cart';
require_once 'includes/header.php';

$cart = Cart::getInstance();
$cartSummary = $cart->getSummary();
$db = Database::getInstance();

// Get suggested products
$suggestedProducts = $db->fetchAll("SELECT p.*, pi.image_path 
    FROM products p 
    LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_primary = 1
    WHERE p.status = 'active' AND p.featured = 1 
    ORDER BY RAND() 
    LIMIT 4");
?>

<!-- Cart Section -->
<section class="sh-section" style="background: var(--sh-gray-50);">
    <div class="container-fluid px-3 px-md-4 px-lg-5">

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-5" data-aos="fade-up">
            <ol class="breadcrumb mb-0" style="font-size: 13px; letter-spacing: 0.5px; text-transform: uppercase;">
                <li class="breadcrumb-item"><a href="<?php echo Helpers::url(); ?>"
                        class="text-muted text-decoration-none">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page" style="color: var(--sh-accent);">Shopping Cart
                </li>
            </ol>
        </nav>

        <!-- Cart Header -->
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="sh-section-subtitle" style="color: var(--sh-accent); letter-spacing: 4px;">YOUR BAG</span>
            <h1 class="sh-heading-1 mt-2" style="font-weight: 800; text-transform: uppercase; letter-spacing: -1px;">
                Shopping Cart</h1>
        </div>

        <?php if (empty($cartSummary['items'])): ?>
            <!-- Empty Cart -->
            <div class="text-center py-5" data-aos="fade-up">
                <div class="mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle"
                        style="width: 120px; height: 120px; background: white; box-shadow: var(--sh-shadow-lg);">
                        <i class="bi bi-bag-x" style="font-size: 3rem; color: var(--sh-gray-300);"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-3">Your bag is empty</h3>
                <p class="text-muted mb-4 mx-auto" style="max-width: 400px;">Looks like you haven't added anything to your
                    cart yet. Discover our latest collection and find something special.</p>
                <a href="<?php echo Helpers::url('products.php'); ?>" class="sh-btn sh-btn-primary sh-btn-lg px-5">
                    <i class="bi bi-shop me-2"></i> Start Shopping
                </a>
            </div>
        <?php else: ?>
            <div class="row g-4 g-lg-5">
                <!-- Cart Items -->
                <div class="col-lg-8" data-aos="fade-right">
                    <div class="bg-white rounded-4 shadow-sm p-4 p-md-5">
                        <!-- Cart Header -->
                        <div class="d-flex justify-content-between align-items-center mb-4 pb-4 border-bottom">
                            <h5 class="fw-bold mb-0" style="font-family: var(--sh-font-display);">
                                <span class="d-inline-block p-2 rounded-3 bg-light me-2">
                                    <i class="bi bi-bag-check" style="color: var(--sh-primary);"></i>
                                </span>
                                <?php echo count($cartSummary['items']); ?> Item(s)
                            </h5>
                            <button class="btn btn-link text-muted p-0 text-decoration-none small hover-text-danger"
                                onclick="clearCart()">
                                <i class="bi bi-trash3 me-1"></i> Clear Cart
                            </button>
                        </div>

                        <!-- Cart Items List -->
                        <div class="cart-items-wrapper">
                            <?php foreach ($cartSummary['items'] as $item): ?>
                                <?php
                                $images = $db->fetchAll("SELECT image_path FROM product_images WHERE product_id = ? ORDER BY is_primary DESC LIMIT 1", [$item['product_id']]);
                                $img = !empty($images[0]) ? Helpers::upload($images[0]['image_path']) : Helpers::asset('images/placeholder-product.jpg');
                                ?>
                                <div class="cart-item py-4 border-bottom last-border-0"
                                    id="cart-item-<?php echo $item['product_id']; ?>">
                                    <div class="row align-items-center g-3">
                                        <!-- Product Image -->
                                        <div class="col-4 col-md-2">
                                            <a href="<?php echo Helpers::url('product.php?slug=' . ($item['slug'] ?? '')); ?>"
                                                class="d-block overflow-hidden rounded-3 shadow-sm hover-lift">
                                                <img src="<?php echo $img; ?>"
                                                    alt="<?php echo Security::escape($item['name']); ?>" class="w-100"
                                                    style="aspect-ratio: 1; object-fit: cover; transition: transform 0.5s ease;">
                                            </a>
                                        </div>

                                        <!-- Product Details -->
                                        <div class="col-8 col-md-4">
                                            <p class="text-uppercase x-small fw-bold mb-1"
                                                style="color: var(--sh-accent); letter-spacing: 1px;">
                                                Premium Collection
                                            </p>
                                            <h6 class="fw-bold mb-1 fs-5">
                                                <a href="<?php echo Helpers::url('product.php?slug=' . ($item['slug'] ?? '')); ?>"
                                                    class="text-dark text-decoration-none hover-text-accent">
                                                    <?php echo Security::escape($item['name']); ?>
                                                </a>
                                            </h6>
                                            <div class="d-flex align-items-center gap-2 mt-2">
                                                <span class="text-muted small">Unit Price:</span>
                                                <span
                                                    class="fw-bold small"><?php echo Helpers::formatPrice($item['price']); ?></span>
                                            </div>
                                            <button
                                                class="btn btn-link text-danger p-0 text-decoration-none small d-md-none mt-2"
                                                onclick="removeFromCart(<?php echo $item['product_id']; ?>)">
                                                <i class="bi bi-trash3 me-1"></i> Remove
                                            </button>
                                        </div>

                                        <!-- Quantity -->
                                        <div class="col-6 col-md-3 mt-3 mt-md-0">
                                            <div class="sh-qty-selector mx-auto mx-md-0 shadow-sm border"
                                                style="background: var(--sh-gray-50); border-radius: 30px; overflow: hidden; max-width: 140px;">
                                                <button type="button" class="sh-qty-btn p-2 border-0 bg-transparent"
                                                    onclick="updateCartItem(<?php echo $item['product_id']; ?>, <?php echo $item['quantity'] - 1; ?>)">
                                                    <i class="bi bi-dash"></i>
                                                </button>
                                                <input type="number" value="<?php echo $item['quantity']; ?>" min="1" readonly
                                                    class="sh-qty-value border-0 bg-transparent text-center fw-bold"
                                                    style="width: 40px; font-size: 14px;">
                                                <button type="button" class="sh-qty-btn p-2 border-0 bg-transparent"
                                                    onclick="updateCartItem(<?php echo $item['product_id']; ?>, <?php echo $item['quantity'] + 1; ?>)">
                                                    <i class="bi bi-plus"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Total & Remove -->
                                        <div class="col-6 col-md-3 text-end mt-3 mt-md-0">
                                            <div class="d-flex flex-column align-items-end">
                                                <p class="fw-bold mb-0" style="color: var(--sh-primary); font-size: 20px;">
                                                    <?php echo Helpers::formatPrice($item['subtotal']); ?>
                                                </p>
                                                <button
                                                    class="btn btn-link text-muted p-0 text-decoration-none x-small d-none d-md-inline-block mt-2 hover-text-danger"
                                                    onclick="removeFromCart(<?php echo $item['product_id']; ?>)">
                                                    <i class="bi bi-x-lg me-1"></i> REMOVE
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Continue Shopping -->
                        <div class="mt-5 text-center text-md-start">
                            <a href="<?php echo Helpers::url('products.php'); ?>"
                                class="text-decoration-none fw-bold small text-uppercase"
                                style="color: var(--sh-primary); letter-spacing: 1px;">
                                <i class="bi bi-arrow-left me-2"></i> Keep Shopping
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="col-lg-4" data-aos="fade-left">
                    <div class="sh-cart-summary sticky-top" style="top: 100px; border-top: 5px solid var(--sh-accent);">
                        <h5 class="fw-bold mb-4 pb-3 border-bottom text-uppercase" style="letter-spacing: 1px;">
                            <i class="bi bi-receipt me-2" style="color: var(--sh-accent);"></i> Order Summary
                        </h5>

                        <div class="sh-summary-row py-2">
                            <span class="text-muted">Subtotal</span>
                            <span class="fw-bold"><?php echo Helpers::formatPrice($cartSummary['subtotal']); ?></span>
                        </div>

                        <div class="sh-summary-row py-2">
                            <span class="text-muted">Shipping</span>
                            <span class="text-success fw-bold"><i class="bi bi-check-circle-fill me-1"></i> FREE</span>
                        </div>

                        <!-- High-Conversion Promo Section -->
                        <div class="sh-promo-container my-4 p-4 rounded-4"
                            style="background: white; border: 1px solid var(--sh-gray-100); box-shadow: 0 5px 15px rgba(0,0,0,0.02);">
                            <p class="x-small fw-bold text-uppercase text-muted mb-3" style="letter-spacing: 2px;">
                                <i class="bi bi-tag me-2" style="color: var(--sh-accent);"></i>Promotional Code
                            </p>
                            <div class="sh-promo-input-wrapper position-relative overflow-hidden"
                                style="border-radius: 50px;">
                                <input type="text" id="promoCodeInput" class="form-control border-0 px-4"
                                    placeholder="Enter code here..."
                                    style="height: 54px; background: var(--sh-gray-50); font-weight: 500; font-size: 15px;">
                                <button class="sh-promo-btn" onclick="applyPromo()">
                                    <span>APPLY</span>
                                    <i class="bi bi-arrow-right-short ms-1"></i>
                                </button>
                                <div class="sh-promo-glow"></div>
                            </div>
                        </div>

                        <!-- Premium Total Visualization -->
                        <div class="sh-total-card p-4 rounded-4 mb-4"
                            style="background: #0a0a0a; position: relative; overflow: hidden; box-shadow: 0 15px 35px rgba(0,0,0,0.3);">
                            <div class="sh-total-pattern"></div>
                            <div class="position-relative z-1 d-flex justify-content-between align-items-end">
                                <div>
                                    <span class="text-uppercase x-small fw-bold"
                                        style="color: rgba(255,255,255,0.5); letter-spacing: 3px;">Estimated Total</span>
                                    <div class="d-flex align-items-baseline gap-2 mt-1">
                                        <h2 class="h1 mb-0 fw-900 text-white" style="letter-spacing: -2px;">
                                            <?php echo Helpers::formatPrice($cartSummary['total']); ?>
                                        </h2>
                                        <span class="text-white-50 small mb-1">VAT Incl.</span>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <span class="badge rounded-pill px-3 py-2"
                                        style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.15); font-size: 10px; letter-spacing: 1px;">
                                        <i class="bi bi-shield-check text-success me-1"></i> SECURE
                                    </span>
                                </div>
                            </div>
                        </div>

                        <style>
                            .sh-promo-input-wrapper:focus-within {
                                box-shadow: 0 0 0 2px var(--sh-gold);
                            }

                            .sh-promo-btn {
                                position: absolute;
                                top: 4px;
                                right: 4px;
                                height: calc(100% - 8px);
                                background: #0a0a0a;
                                color: white;
                                border: none;
                                padding: 0 25px;
                                border-radius: 50px;
                                font-weight: 800;
                                font-size: 12px;
                                transition: all 0.3s ease;
                                display: flex;
                                align-items: center;
                            }

                            .sh-promo-btn:hover {
                                background: var(--sh-gold);
                                transform: scale(1.02);
                            }

                            .sh-total-pattern {
                                position: absolute;
                                top: 0;
                                right: 0;
                                width: 150px;
                                height: 100%;
                                background: linear-gradient(135deg, transparent 50%, rgba(212, 175, 55, 0.1) 100%);
                                pointer-events: none;
                            }

                            .sh-total-card::before {
                                content: '';
                                position: absolute;
                                top: -50%;
                                left: -50%;
                                width: 200%;
                                height: 200%;
                                background: radial-gradient(circle at center, rgba(212, 175, 55, 0.05) 0%, transparent 60%);
                                pointer-events: none;
                            }
                        </style>

                        <script>
                            function applyPromo() {
                                const input = document.getElementById('promoCodeInput');
                                const btn = document.querySelector('.sh-promo-btn');
                                if (!input.value) return;

                                // Animated Feedback
                                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>';
                                setTimeout(() => {
                                    input.style.border = '1px solid #ef4444';
                                    btn.innerHTML = '<span>INVALID</span>';
                                    btn.style.background = '#ef4444';
                                    setTimeout(() => {
                                        input.style.border = 'none';
                                        btn.innerHTML = '<span>APPLY</span>';
                                        btn.style.background = '#0a0a0a';
                                    }, 2000);
                                }, 800);
                            }
                        </script>

                        <!-- Checkout Button -->
                        <a href="<?php echo Helpers::url('checkout.php'); ?>"
                            class="sh-btn sh-btn-primary sh-btn-full sh-btn-lg shadow-lg mb-3">
                            <i class="bi bi-shield-lock me-2"></i> SECURE CHECKOUT
                        </a>


                        <!-- Trust Signals -->
                        <div class="mt-5">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="d-flex align-items-center gap-3 p-3 rounded-3 border bg-light">
                                        <div class="bg-white p-2 rounded-circle shadow-sm">
                                            <i class="bi bi-box-seam fs-4" style="color: var(--sh-accent);"></i>
                                        </div>
                                        <div>
                                            <p class="mb-0 fw-bold small">Fast UAE Delivery</p>
                                            <p class="mb-0 x-small text-muted">Estimated: 1-2 business days</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex align-items-center gap-3 p-3 rounded-3 border bg-light">
                                        <div class="bg-white p-2 rounded-circle shadow-sm">
                                            <i class="bi bi-patch-check fs-4" style="color: var(--sh-primary);"></i>
                                        </div>
                                        <div>
                                            <p class="mb-0 fw-bold small">Authenticity Guaranteed</p>
                                            <p class="mb-0 x-small text-muted">100% genuine premium products</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Icons -->
                        <div class="mt-4 pt-4 border-top text-center">
                            <p class="x-small text-uppercase text-muted mb-3" style="letter-spacing: 2px;">WE ACCEPT</p>
                            <div class="d-flex justify-content-center gap-3 grayscale opacity-75">
                                <img src="https://cdn.shopify.com/s/files/1/0533/2089/files/visa.png?v=1627658035"
                                    alt="Visa" height="24">
                                <img src="https://cdn.shopify.com/s/files/1/0533/2089/files/mastercard.png?v=1627658035"
                                    alt="Mastercard" height="24">
                                <img src="https://cdn.shopify.com/s/files/1/0533/2089/files/amex.png?v=1627658035"
                                    alt="Amex" height="24">
                                <img src="https://cdn.shopify.com/s/files/1/0533/2089/files/apple-pay.png?v=1627658035"
                                    alt="Apple Pay" height="24">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Suggested Products -->
<?php if (!empty($suggestedProducts)): ?>
    <section class="sh-section" style="background: var(--sh-gray-50);">
        <div class="container-fluid px-3 px-md-4 px-lg-5">
            <div class="sh-section-header" data-aos="fade-up">
                <span class="sh-section-subtitle">💡 You Might Also Like</span>
                <h2 class="sh-section-title">Complete Your Order</h2>
            </div>

            <div class="row g-3 g-md-4">
                <?php foreach ($suggestedProducts as $product): ?>
                    <div class="col-6 col-md-3" data-aos="fade-up">
                        <div class="sh-product-card">
                            <div class="sh-product-media">
                                <button class="sh-wishlist-btn"><i class="bi bi-heart"></i></button>
                                <a href="<?php echo Helpers::url('product.php?slug=' . $product['slug']); ?>">
                                    <img src="<?php echo $product['image_path'] ? Helpers::upload($product['image_path']) : Helpers::asset('images/placeholder-product.jpg'); ?>"
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

<script>
    function updateCartItem(productId, qty) {
        if (qty < 1) {
            removeFromCart(productId);
            return;
        }

        if (typeof Cart !== 'undefined') {
            Cart.update(productId, qty).then(() => {
                location.reload();
            });
        } else {
            // Fallback - direct AJAX call
            fetch('<?php echo Helpers::url('api/cart.php'); ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'update', product_id: productId, quantity: qty })
            }).then(() => location.reload());
        }
    }

    function removeFromCart(productId) {
        if (typeof Cart !== 'undefined') {
            Cart.remove(productId).then(() => {
                // Animate removal
                const item = document.getElementById('cart-item-' + productId);
                if (item) {
                    item.style.transition = 'all 0.3s ease';
                    item.style.opacity = '0';
                    item.style.transform = 'translateX(-20px)';
                    setTimeout(() => location.reload(), 300);
                } else {
                    location.reload();
                }
            });
        } else {
            fetch('<?php echo Helpers::url('api/cart.php'); ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'remove', product_id: productId })
            }).then(() => location.reload());
        }
    }

    function clearCart() {
        if (confirm('Are you sure you want to clear your cart?')) {
            if (typeof Cart !== 'undefined') {
                Cart.clear().then(() => location.reload());
            } else {
                fetch('<?php echo Helpers::url('api/cart.php'); ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'clear' })
                }).then(() => location.reload());
            }
        }
    }

    function addToCart(productId) {
        if (typeof Cart !== 'undefined') {
            Cart.add(productId, 1).then(() => location.reload());
        }
    }
</script>

<?php require_once 'includes/footer.php'; ?>
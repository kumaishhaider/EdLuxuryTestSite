<?php
/**
 * Checkout Page - Premium Shopify-Grade Design
 * Edluxury - VIBRANT & Conversion-Optimized
 */

require_once 'config/config.php';

// Get cart
$cart = Cart::getInstance();
$cartSummary = $cart->getSummary();

// Redirect if cart is empty
if (empty($cartSummary['items'])) {
    header('Location: ' . SITE_URL . '/cart.php');
    exit;
}

$orderModel = new Order();
$db = Database::getInstance();
$theme = Theme::getInstance();

// Handle form submission
$error = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    // Validate CSRF
    if (!Security::validateCSRFToken($_POST[CSRF_TOKEN_NAME] ?? '')) {
        $error = 'Invalid security token. Please try again.';
    } else {
        // Sanitize inputs
        $orderData = [
            'customer_name' => Security::sanitize($_POST['customer_name'] ?? ''),
            'customer_email' => Security::sanitize($_POST['customer_email'] ?? ''),
            'customer_phone' => Security::sanitize($_POST['customer_phone'] ?? ''),
            'shipping_address' => json_encode([
                'address_line1' => Security::sanitize($_POST['shipping_address'] ?? ''),
                'city' => Security::sanitize($_POST['shipping_city'] ?? ''),
                'emirate' => Security::sanitize($_POST['shipping_emirate'] ?? ''),
                'country' => 'UAE'
            ]),
            'subtotal' => $cartSummary['subtotal'],
            'shipping_cost' => 0,
            'total' => $cartSummary['total'],
            'payment_method' => Security::sanitize($_POST['payment_method'] ?? 'cod'),
            'payment_status' => 'pending',
            'order_status' => 'pending',
            'notes' => Security::sanitize($_POST['notes'] ?? ''),
            'user_id' => Security::isLoggedIn() ? $_SESSION['user_id'] : null
        ];

        // Validate required fields
        if (
            empty($orderData['customer_name']) || empty($orderData['customer_email']) ||
            empty($orderData['customer_phone']) || empty($_POST['shipping_address'])
        ) {
            $error = 'Please fill in all required fields.';
        } else {
            // Create order
            $result = $orderModel->create($orderData, $cartSummary['items']);

            if ($result['success']) {
                $cart->clear();

                // Always send WhatsApp notification to admin for ALL order types
                $emailInstance = new Email();
                $fullOrder = $orderModel->getById($result['order_id']);
                $waPhone = defined('WHATSAPP_NUMBER') ? WHATSAPP_NUMBER : preg_replace('/[^0-9]/', '', $theme->get('contact_phone', '923491697043'));

                $waMsg = "🛒 *NEW ORDER #" . $result['order_number'] . "*\n\n";
                $waMsg .= "👤 *Name:* " . $orderData['customer_name'] . "\n";
                $waMsg .= "📧 *Email:* " . $orderData['customer_email'] . "\n";
                $waMsg .= "📱 *Phone:* " . $orderData['customer_phone'] . "\n";
                $waMsg .= "💳 *Payment:* " . strtoupper($orderData['payment_method']) . "\n\n";
                $waMsg .= "📦 *Items:*\n";
                foreach ($cartSummary['items'] as $item) {
                    $waMsg .= "• " . $item['name'] . " x" . $item['quantity'] . " = " . Helpers::formatPrice($item['subtotal']) . "\n";
                }
                $waMsg .= "\n💰 *Total: " . Helpers::formatPrice($cartSummary['total']) . "*\n\n";
                $rawAddress = Security::sanitize($_POST['shipping_address'] ?? '') . ", " .
                    Security::sanitize($_POST['shipping_city'] ?? '') . ", " .
                    Security::sanitize($_POST['shipping_emirate'] ?? '') . ", UAE";
                $waMsg .= "📍 *Ship To:* " . $rawAddress;

                $waNotifyUrl = "https://api.whatsapp.com/send/?phone=" . $waPhone . "&text=" . urlencode($waMsg) . "&type=phone_number&app_absent=0";
                // Store in session for potential use
                $_SESSION['wa_admin_notify_url'] = $waNotifyUrl;

                // Redirect to success page
                header('Location: ' . SITE_URL . '/order-confirmation.php?order=' . $result['order_number']);
                exit;
            } else {
                $error = $result['message'] ?? 'Failed to place order. Please try again.';
            }
        }
    }
}

$pageTitle = 'Checkout';
require_once 'includes/header.php';

// Pre-fill user data if logged in
$userData = [];
if (Security::isLoggedIn()) {
    $userModel = new User();
    $userData = $userModel->getById($_SESSION['user_id']);
}
?>

<!-- Checkout Section -->
<section class="sh-section" style="background: linear-gradient(135deg, var(--sh-gray-50) 0%, var(--sh-white) 100%);">
    <div class="container-fluid px-3 px-md-4 px-lg-5">

        <!-- Progress Indicator -->
        <div class="d-flex justify-content-center mb-5" data-aos="fade-up">
            <div class="d-flex align-items-center gap-3 gap-md-4">
                <div class="d-flex align-items-center gap-2 text-muted">
                    <span class="d-flex align-items-center justify-content-center rounded-circle"
                        style="width: 32px; height: 32px; background: var(--sh-gray-300); color: white;">
                        <i class="bi bi-check"></i>
                    </span>
                    <span class="d-none d-sm-inline small">Cart</span>
                </div>
                <div style="width: 40px; height: 2px; background: var(--sh-primary);"></div>
                <div class="d-flex align-items-center gap-2">
                    <span class="d-flex align-items-center justify-content-center rounded-circle fw-bold"
                        style="width: 32px; height: 32px; background: var(--sh-gradient-primary); color: white;">
                        2
                    </span>
                    <span class="d-none d-sm-inline small fw-bold">Checkout</span>
                </div>
                <div style="width: 40px; height: 2px; background: var(--sh-gray-300);"></div>
                <div class="d-flex align-items-center gap-2 text-muted">
                    <span class="d-flex align-items-center justify-content-center rounded-circle"
                        style="width: 32px; height: 32px; background: var(--sh-gray-200); color: var(--sh-gray-500);">
                        3
                    </span>
                    <span class="d-none d-sm-inline small">Confirmation</span>
                </div>
            </div>
        </div>

        <!-- Checkout Header -->
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="sh-section-subtitle">🔒 Secure Checkout</span>
            <h1 class="sh-heading-1 mt-2">Complete Your Order</h1>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger rounded-4 mb-4 border-0 shadow-sm" data-aos="fade-up">
                <i class="bi bi-exclamation-triangle me-2"></i> <?php echo Security::escape($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" id="checkoutForm">
            <input type="hidden" name="<?php echo CSRF_TOKEN_NAME; ?>"
                value="<?php echo Security::generateCSRFToken(); ?>">

            <div class="row g-4 g-lg-5">
                <!-- Left: Form -->
                <div class="col-lg-7" data-aos="fade-right">

                    <!-- Contact Information -->
                    <div class="bg-white rounded-4 shadow-sm p-4 mb-4">
                        <h5 class="fw-bold mb-4">
                            <i class="bi bi-person-circle me-2" style="color: var(--sh-primary);"></i>
                            Contact Information
                        </h5>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="sh-form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="customer_name" class="sh-form-input"
                                    placeholder="Enter your full name"
                                    value="<?php echo Security::escape($userData['name'] ?? ''); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="sh-form-label">Phone Number <span class="text-danger">*</span></label>
                                <input type="tel" name="customer_phone" class="sh-form-input"
                                    placeholder="+971 50 XXX XXXX"
                                    value="<?php echo Security::escape($userData['phone'] ?? ''); ?>" required>
                            </div>
                            <div class="col-12">
                                <label class="sh-form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="customer_email" class="sh-form-input"
                                    placeholder="your@email.com"
                                    value="<?php echo Security::escape($userData['email'] ?? ''); ?>" required>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    <div class="bg-white rounded-4 shadow-sm p-4 mb-4">
                        <h5 class="fw-bold mb-4">
                            <i class="bi bi-truck me-2" style="color: var(--sh-primary);"></i>
                            Shipping Address
                        </h5>

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="sh-form-label">Street Address <span class="text-danger">*</span></label>
                                <input type="text" name="shipping_address" class="sh-form-input"
                                    placeholder="Building name, apartment, street"
                                    value="<?php echo Security::escape($userData['address'] ?? ''); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="sh-form-label">City</label>
                                <input type="text" name="shipping_city" class="sh-form-input" placeholder="City / Area"
                                    value="<?php echo Security::escape($userData['city'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="sh-form-label">Emirate <span class="text-danger">*</span></label>
                                <select name="shipping_emirate" class="sh-form-input" required>
                                    <option value="">Select Emirate</option>
                                    <option value="Dubai">Dubai</option>
                                    <option value="Abu Dhabi">Abu Dhabi</option>
                                    <option value="Sharjah">Sharjah</option>
                                    <option value="Ajman">Ajman</option>
                                    <option value="Ras Al Khaimah">Ras Al Khaimah</option>
                                    <option value="Fujairah">Fujairah</option>
                                    <option value="Umm Al Quwain">Umm Al Quwain</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="sh-form-label">Order Notes (Optional)</label>
                                <textarea name="notes" class="sh-form-input" rows="3"
                                    placeholder="Any special instructions for delivery..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method - Premium Card Design -->
                    <div class="rounded-4 shadow-sm p-4" id="paymentSection"
                        style="background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 100%); border: 1px solid rgba(255,255,255,0.08);">
                        <h5 class="fw-bold mb-1" style="color: #fff;">
                            <i class="bi bi-lightning-charge-fill me-2" style="color: #A69C63;"></i>
                            Select Payment Method
                        </h5>
                        <p class="mb-4" style="color: rgba(255,255,255,0.5); font-size: 13px;">Choose how you'd like to
                            pay</p>

                        <div class="row g-3">
                            <!-- Cash on Delivery -->
                            <div class="col-md-6">
                                <input type="radio" class="btn-check" name="payment_method" value="cod" id="payment_cod" checked>
                                <label class="payment-card-label" for="payment_cod"
                                    style="cursor:pointer; display:block; padding: 20px; border-radius: 16px; border: 2px solid rgba(255,255,255,0.1); background: rgba(255,255,255,0.05); transition: all 0.3s ease; text-align: center;">
                                    <div class="payment-icon-wrap"
                                        style="width:60px;height:60px;background:linear-gradient(135deg,#1a1a2e,#16213e);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;border:2px solid rgba(255,255,255,0.1);">
                                        <i class="bi bi-cash-stack" style="font-size: 24px; color: #A69C63;"></i>
                                    </div>
                                    <div class="fw-bold mb-1" style="color: #fff; font-size: 15px;">Cash on Delivery</div>
                                    <small style="color: rgba(255,255,255,0.5);">Pay when you receive</small>
                                    <div class="mt-2">
                                        <span style="background:rgba(40,167,69,0.2);color:#4dbd74;font-size:10px;padding:3px 10px;border-radius:50px;font-weight:700;border:1px solid rgba(40,167,69,0.3);">✓ TRUSTED &amp; SAFE</span>
                                    </div>
                                </label>
                            </div>

                            <!-- Bank Transfer -->
                            <div class="col-md-6">
                                <input type="radio" class="btn-check" name="payment_method" value="bank_transfer" id="payment_bank">
                                <label class="payment-card-label" for="payment_bank"
                                    style="cursor:pointer; display:block; padding: 20px; border-radius: 16px; border: 2px solid rgba(166,156,99,0.25); background: rgba(166,156,99,0.05); transition: all 0.3s ease; text-align: center;">
                                    <div class="payment-icon-wrap"
                                        style="width:60px;height:60px;background:linear-gradient(135deg,#1a1a2e,#2d2a1e);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;border:2px solid rgba(166,156,99,0.3);">
                                        <i class="bi bi-bank" style="font-size: 24px; color: #A69C63;"></i>
                                    </div>
                                    <div class="fw-bold mb-1" style="color: #fff; font-size: 15px;">Bank Transfer</div>
                                    <small style="color: rgba(255,255,255,0.5);">UAE local bank transfer</small>
                                    <div class="mt-2">
                                        <span style="background:rgba(166,156,99,0.2);color:#A69C63;font-size:10px;padding:3px 10px;border-radius:50px;font-weight:700;border:1px solid rgba(166,156,99,0.3);">🏦 SECURE</span>
                                    </div>
                                </label>
                            </div>
                        </div>


                        <!-- Security Strip -->
                        <div class="d-flex align-items-center gap-3 mt-4 p-3 rounded-3"
                            style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06);">
                            <i class="bi bi-shield-lock-fill fs-4" style="color: #A69C63;"></i>
                            <div>
                                <p class="mb-0 small fw-bold" style="color: #fff;">256-bit SSL Encryption</p>
                                <p class="mb-0" style="font-size: 11px; color: rgba(255,255,255,0.4);">Your data is 100%
                                    safe &amp; secure</p>
                            </div>
                            <div class="ms-auto d-flex gap-2 align-items-center">
                                <i class="bi bi-lock-fill" style="color: rgba(255,255,255,0.3);"></i>
                                <i class="bi bi-patch-check-fill" style="color: #A69C63;"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Order Summary -->
                <div class="col-lg-5" data-aos="fade-left">
                    <div class="sh-cart-summary">
                        <h5 class="fw-bold mb-4 pb-3 border-bottom">
                            <i class="bi bi-bag me-2" style="color: var(--sh-primary);"></i>
                            Order Summary
                        </h5>

                        <!-- Items Preview -->
                        <div class="mb-4 pb-4 border-bottom" style="max-height: 300px; overflow-y: auto;">
                            <?php foreach ($cartSummary['items'] as $item): ?>
                                <?php
                                $images = $db->fetchAll("SELECT image_path FROM product_images WHERE product_id = ? ORDER BY is_primary DESC LIMIT 1", [$item['product_id']]);
                                $img = !empty($images[0]) ? Helpers::upload($images[0]['image_path']) : Helpers::asset('images/placeholder-product.jpg');
                                ?>
                                <div class="d-flex gap-3 mb-3">
                                    <div class="position-relative">
                                        <img src="<?php echo $img; ?>" alt="<?php echo Security::escape($item['name']); ?>"
                                            class="rounded-3" style="width: 60px; height: 60px; object-fit: cover;">
                                        <span class="position-absolute top-0 end-0 translate-middle badge rounded-pill"
                                            style="background: var(--sh-gradient-primary);">
                                            <?php echo $item['quantity']; ?>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="fw-bold mb-0 small"><?php echo Security::escape($item['name']); ?></p>
                                        <p class="text-muted mb-0 small">Qty: <?php echo $item['quantity']; ?></p>
                                    </div>
                                    <div class="text-end">
                                        <p class="fw-bold mb-0" style="color: var(--sh-primary);">
                                            <?php echo Helpers::formatPrice($item['subtotal']); ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Totals -->
                        <div class="sh-summary-row">
                            <span>Subtotal</span>
                            <span class="fw-bold"><?php echo Helpers::formatPrice($cartSummary['subtotal']); ?></span>
                        </div>

                        <div class="sh-summary-row">
                            <span>Shipping</span>
                            <span class="text-success fw-bold"><i class="bi bi-check-circle me-1"></i> FREE</span>
                        </div>

                        <div class="sh-summary-total">
                            <span>Total</span>
                            <span><?php echo Helpers::formatPrice($cartSummary['total']); ?></span>
                        </div>

                        <!-- Place Order Button -->
                        <input type="hidden" name="place_order" value="1">
                        <button type="submit" class="sh-btn sh-btn-primary sh-btn-full mt-4" id="submitBtn">
                            <i class="bi bi-check-circle me-2"></i> Place Order
                        </button>

                        <p class="text-center mt-3 small text-muted">
                            By placing your order, you agree to our
                            <a href="<?php echo Helpers::url('page.php?slug=terms-conditions'); ?>"
                                class="text-decoration-none">Terms & Conditions</a>
                        </p>

                        <!-- Trust Badges -->
                        <div class="mt-4 pt-4 border-top">
                            <div class="row g-2 text-center small text-muted">
                                <div class="col-4">
                                    <i class="bi bi-shield-check d-block mb-1 fs-5"
                                        style="color: var(--sh-accent);"></i>
                                    Secure
                                </div>
                                <div class="col-4">
                                    <i class="bi bi-truck d-block mb-1 fs-5" style="color: var(--sh-primary);"></i>
                                    Free Ship
                                </div>
                                <div class="col-4">
                                    <i class="bi bi-patch-check d-block mb-1 fs-5" style="color: var(--sh-info);"></i>
                                    Authentic
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 p-3 rounded-3 text-center" style="background: var(--sh-gray-100);">
                            <p class="small mb-2">Need help with your order?</p>
                            <p class="small fw-bold mb-0">Email: edluxury32@gmail.com</p>
                            <p class="small text-muted">24/7 Expert Concierge Support</p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<script>
    // Form validation and submission handling
    document.getElementById('checkoutForm').addEventListener('submit', function (e) {
        const btn = document.getElementById('submitBtn');
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
        btn.disabled = true;
    });

    // Phone number formatting
    document.querySelector('input[name="customer_phone"]').addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 0 && !value.startsWith('971')) {
            if (value.startsWith('0')) {
                value = '971' + value.substring(1);
            } else if (!value.startsWith('9')) {
                value = '971' + value;
            }
        }
        e.target.value = '+' + value;
    });

    // Payment card selection visual feedback
    document.querySelectorAll('.payment-card-label').forEach(function (label) {
        label.addEventListener('mouseenter', function () {
            this.style.transform = 'translateY(-3px)';
            this.style.boxShadow = '0 8px 25px rgba(0,0,0,0.3)';
        });
        label.addEventListener('mouseleave', function () {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'none';
        });
    });

    // Payment method radio visual update
    document.querySelectorAll('input[name="payment_method"]').forEach(function (radio) {
        radio.addEventListener('change', function () {
            // Reset all
            document.querySelectorAll('.payment-card-label').forEach(function (lbl) {
                lbl.style.borderColor = 'rgba(255,255,255,0.1)';
                lbl.style.background = 'rgba(255,255,255,0.05)';
            });
            // Highlight selected
            const selectedLabel = document.querySelector('label[for="' + this.id + '"]');
            if (selectedLabel) {
                if (this.value === 'whatsapp') {
                    selectedLabel.style.borderColor = '#25D366';
                    selectedLabel.style.background = 'rgba(37,211,102,0.12)';
                } else {
                    selectedLabel.style.borderColor = '#A69C63';
                    selectedLabel.style.background = 'rgba(166,156,99,0.12)';
                }
            }
            // Update submit button text
            const btn = document.getElementById('submitBtn');
            if (this.value === 'whatsapp') {
                btn.innerHTML = '<i class="bi bi-whatsapp me-2"></i>Order via WhatsApp';
                btn.style.background = 'linear-gradient(135deg, #128C7E, #25D366)';
            } else {
                btn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Place Order';
                btn.style.background = '';
            }
        });
    });

    // Initialise default selection
    document.querySelector('input[name="payment_method"]:checked')?.dispatchEvent(new Event('change'));
</script>

<style>
    /* Premium dark payment section */
    #paymentSection .payment-card-label {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    #payment_cod:checked+.payment-card-label {
        border-color: #A69C63 !important;
        background: rgba(166, 156, 99, 0.12) !important;
        box-shadow: 0 0 0 1px rgba(166, 156, 99, 0.3);
    }

    #payment_whatsapp:checked+.payment-card-label {
        border-color: #25D366 !important;
        background: rgba(37, 211, 102, 0.12) !important;
        box-shadow: 0 0 0 1px rgba(37, 211, 102, 0.3);
    }
</style>

<?php require_once 'includes/footer.php'; ?>
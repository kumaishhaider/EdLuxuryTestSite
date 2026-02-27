<?php
/**
 * Track Order Page - Premium Edluxury Experience
 * Customers can verify their order status via Email & Order ID
 */

$pageTitle = 'Track Your Order';
require_once 'includes/header.php';

$db = Database::getInstance();
$trackingResult = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' || (isset($_GET['order']) && isset($_GET['email']))) {
    $orderNumber = trim($_POST['order_number'] ?? $_GET['order'] ?? '');
    $email = trim($_POST['email'] ?? $_GET['email'] ?? '');

    if (empty($orderNumber) || empty($email)) {
        $error = "Please enter both Order ID and Email address.";
    } else {
        $trackingResult = $db->fetchOne(
            "SELECT * FROM orders WHERE order_number = ? AND customer_email = ?",
            [$orderNumber, $email]
        );

        if (!$trackingResult) {
            $error = "No order found with these details. Please check and try again.";
        }
    }
}
?>

<!-- Premium Tracking Hero -->
<section class="sh-section" style="background: var(--sh-gray-50); min-height: 80vh;">
    <div class="container-fluid px-3 px-md-4 px-lg-5">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8" data-aos="fade-up">

                <!-- Page Header -->
                <div class="text-center mb-5">
                    <span class="sh-section-subtitle" style="color: var(--sh-accent); letter-spacing: 4px;">VERIFY
                        SHIPMENT</span>
                    <h1 class="sh-heading-1 mt-2 fw-900 text-uppercase" style="letter-spacing: -1px;">Track Your Order
                    </h1>
                    <p class="text-muted small">Enter your order details sent to your email to see live status.</p>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger rounded-4 border-0 shadow-sm p-4 mb-4 d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                        <div><?php echo $error; ?></div>
                    </div>
                <?php endif; ?>

                <?php if ($trackingResult): ?>
                    <!-- Tracking Status Card -->
                    <div class="card border-0 rounded-4 shadow-lg overflow-hidden" data-aos="zoom-in">
                        <div class="card-header border-0 p-4 d-flex justify-content-between align-items-center"
                            style="background: #0a0a0a; color: white;">
                            <span>Order #<?php echo Security::escape($trackingResult['order_number']); ?></span>
                            <span
                                class="badge rounded-pill bg-success px-3"><?php echo strtoupper($trackingResult['order_status']); ?></span>
                        </div>
                        <div class="card-body p-4 p-md-5 bg-white">

                            <!-- Tracking Timeline -->
                            <div class="tracking-timeline mb-5">
                                <?php
                                $status = $trackingResult['order_status'];
                                $steps = [
                                    'pending' => ['icon' => 'bi-receipt', 'label' => 'Order Placed'],
                                    'processing' => ['icon' => 'bi-gear-wide-connected', 'label' => 'Processing'],
                                    'shipped' => ['icon' => 'bi-truck', 'label' => 'On the Way'],
                                    'delivered' => ['icon' => 'bi-patch-check-fill', 'label' => 'Delivered']
                                ];

                                $statuses = array_keys($steps);
                                $currentIndex = array_search($status, $statuses);
                                if ($currentIndex === false)
                                    $currentIndex = 0; // Default
                                ?>

                                <div class="d-flex justify-content-between position-relative mb-4">
                                    <div class="progress position-absolute top-50 start-0 w-100"
                                        style="height: 2px; transform: translateY(-50%); z-index: 1;">
                                        <div class="progress-bar bg-success"
                                            style="width: <?php echo ($currentIndex / (count($steps) - 1)) * 100; ?>%">
                                        </div>
                                    </div>
                                    <?php
                                    $i = 0;
                                    foreach ($steps as $key => $step):
                                        $isPast = $i <= $currentIndex;
                                        $isCurrent = $i === $currentIndex;
                                        ?>
                                        <div class="text-center position-relative z-2" style="width: 25%;">
                                            <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-2"
                                                style="width: 40px; height: 40px; background: <?php echo $isPast ? '#198754' : '#f8f9fa'; ?>; color: <?php echo $isPast ? 'white' : '#adb5bd'; ?>; border: 2px solid <?php echo $isPast ? '#198754' : '#dee2e6'; ?>;">
                                                <i class="bi <?php echo $step['icon']; ?>"></i>
                                            </div>
                                            <span
                                                class="x-small fw-bold <?php echo $isPast ? 'text-dark' : 'text-muted'; ?>"><?php echo $step['label']; ?></span>
                                        </div>
                                        <?php $i++; endforeach; ?>
                                </div>
                            </div>

                            <!-- Tracking Number -->
                            <div class="p-4 rounded-4 text-center mb-4"
                                style="background: var(--sh-gray-50); border: 1px dashed var(--sh-gray-200);">
                                <p class="x-small text-muted text-uppercase fw-bold mb-1" style="letter-spacing: 2px;">
                                    Official Tracking ID</p>
                                <h3 class="fw-900 mb-0" style="color: var(--sh-primary);">
                                    <?php echo $trackingResult['tracking_number'] ?: 'TBA (Pending Shipment)'; ?>
                                </h3>
                                <?php if ($trackingResult['tracking_number']): ?>
                                    <p class="small text-muted mt-2 mb-0">Partner Carrier: AJEX Express / Aramex</p>
                                <?php endif; ?>
                            </div>

                            <div class="row g-3">
                                <div class="col-6">
                                    <p class="mb-0 text-muted x-small">Date Placed</p>
                                    <p class="fw-bold">
                                        <?php echo date('M d, Y', strtotime($trackingResult['created_at'])); ?>
                                    </p>
                                </div>
                                <div class="col-6 text-end">
                                    <p class="mb-0 text-muted x-small">Payment Method</p>
                                    <p class="fw-bold text-uppercase">
                                        <?php echo Security::escape($trackingResult['payment_method']); ?>
                                    </p>
                                </div>
                            </div>

                            <hr>
                            <div class="text-center mt-4">
                                <a href="track-order.php" class="btn btn-link text-muted text-decoration-none small">
                                    <i class="bi bi-arrow-left me-2"></i>Track Another Order
                                </a>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Tracking Form -->
                    <div class="card border-0 rounded-4 shadow-lg overflow-hidden">
                        <div class="card-body p-4 p-md-5">
                            <form method="POST">
                                <div class="mb-4">
                                    <label class="form-label fw-bold small text-uppercase">Order ID / Number</label>
                                    <div class="sh-promo-input-wrapper position-relative"
                                        style="border-radius: 12px; overflow: hidden; background: var(--sh-gray-50);">
                                        <input type="text" name="order_number" class="form-control border-0 px-4"
                                            placeholder="e.g. ORD-123456" required style="height: 60px;">
                                    </div>
                                    <div class="form-text x-small">Found in your order confirmation email.</div>
                                </div>

                                <div class="mb-5">
                                    <label class="form-label fw-bold small text-uppercase">Customer Email</label>
                                    <div class="sh-promo-input-wrapper position-relative"
                                        style="border-radius: 12px; overflow: hidden; background: var(--sh-gray-50);">
                                        <input type="email" name="email" class="form-control border-0 px-4"
                                            placeholder="email@example.com" required style="height: 60px;">
                                    </div>
                                    <div class="form-text x-small">Email address used during checkout.</div>
                                </div>

                                <button type="submit"
                                    class="sh-btn sh-btn-primary w-100 sh-btn-lg py-3 rounded-3 shadow-lg">
                                    CHECK STATUS <i class="bi bi-chevron-right ms-2 mt-1"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Help Section -->
                <div class="mt-5 text-center">
                    <p class="text-muted small">Need help? Email us at <a href="https://mail.google.com/mail/u/2/#inbox"
                            class="text-success fw-bold text-decoration-none">[Edluxury]</a></p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
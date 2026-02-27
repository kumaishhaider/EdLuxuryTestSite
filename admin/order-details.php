<?php
/**
 * Admin Order Details
 */

require_once __DIR__ . '/../config/config.php';
Security::requireAdminLogin();

$id = $_GET['id'] ?? null;
$db = Database::getInstance();

$order = $db->fetchOne("SELECT * FROM orders WHERE id = ?", [$id]);

if (!$order) {
    Helpers::setFlash('error', 'Order not found');
    Helpers::redirect(ADMIN_URL . '/orders.php');
}

$items = $db->fetchAll("SELECT * FROM order_items WHERE order_id = ?", [$id]);
$pageTitle = 'Order #' . $order['order_number'];

require_once 'includes/header.php';

// Handle Status Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    if (Security::validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $newStatus = $_POST['order_status'];
        $trackingNumber = $_POST['tracking_number'] ?? '';
        
        $oldStatus = $order['order_status'];
        $oldTracking = $order['tracking_number'] ?? '';

        $db->query("UPDATE orders SET order_status = ?, tracking_number = ? WHERE id = ?", [$newStatus, $trackingNumber, $id]);

        // Trigger Notifications
        if ($newStatus !== $oldStatus || $trackingNumber !== $oldTracking) {
            $orderModel = new Order();
            $updatedOrder = $orderModel->getById($id);
            $email = new Email();

            if ($newStatus === 'processing' && $newStatus !== $oldStatus) {
                $email->sendOrderProcessing($updatedOrder);
            } elseif ($newStatus === 'shipped' && !empty($trackingNumber)) {
                // Notify if newly shipped OR if tracking number changed/added on an already shipped order
                if ($newStatus !== $oldStatus || $trackingNumber !== $oldTracking) {
                    $email->sendOrderShipped($updatedOrder);
                }
            } elseif ($newStatus === 'delivered' && $newStatus !== $oldStatus) {
                $email->sendOrderDelivered($updatedOrder);
            }
        }

        Helpers::setFlash('success', 'Order details updated and customer notified');
        // Refresh local variables
        $order['order_status'] = $newStatus;
        $order['tracking_number'] = $trackingNumber;
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Order #
        <?php echo $order['order_number']; ?>
    </h1>
    <div>
        <a href="<?php echo Helpers::url('order-confirmation.php?order=' . $order['order_number']); ?>" target="_blank"
            class="btn btn-outline-primary me-2">
            <i class="bi bi-eye me-2"></i>View Public Page
        </a>
        <a href="<?php echo Helpers::adminUrl('orders.php'); ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back to Orders
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Items -->
        <div class="card mb-4">
            <div class="card-header">
                Items
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td>
                                    <?php echo Security::escape($item['product_name']); ?>
                                </td>
                                <td>
                                    <?php echo Helpers::formatPrice($item['price']); ?>
                                </td>
                                <td>
                                    <?php echo $item['quantity']; ?>
                                </td>
                                <td class="text-end">
                                    <?php echo Helpers::formatPrice($item['total']); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end border-0">Subtotal:</td>
                            <td class="text-end border-0">
                                <?php echo Helpers::formatPrice($order['subtotal']); ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end border-0">Shipping:</td>
                            <td class="text-end border-0">
                                <?php echo Helpers::formatPrice($order['shipping_cost']); ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end border-0 fw-bold">Total:</td>
                            <td class="text-end border-0 fw-bold">
                                <?php echo Helpers::formatPrice($order['total']); ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Status -->
        <div class="card">
            <div class="card-header">
                Manage Order
            </div>
            <div class="card-body">
                <form method="POST" class="row align-items-end">
                    <?php echo Security::getCSRFInput(); ?>
                    <div class="col-md-4">
                        <label class="form-label">Order Status</label>
                        <select name="order_status" class="form-select">
                            <option value="pending" <?php echo $order['order_status'] === 'pending' ? 'selected' : ''; ?>>
                                Pending</option>
                            <option value="processing" <?php echo $order['order_status'] === 'processing' ? 'selected' : ''; ?>>Processing</option>
                            <option value="shipped" <?php echo $order['order_status'] === 'shipped' ? 'selected' : ''; ?>>
                                Shipped</option>
                            <option value="delivered" <?php echo $order['order_status'] === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                            <option value="cancelled" <?php echo $order['order_status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Tracking Number</label>
                        <input type="text" name="tracking_number" class="form-control" placeholder="e.g. ARMX123456"
                            value="<?php echo Security::escape($order['tracking_number'] ?? ''); ?>">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" name="update_status" class="btn btn-primary w-100">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Customer Info -->
        <div class="card mb-4">
            <div class="card-header">
                Customer Details
            </div>
            <div class="card-body">
                <p class="mb-1"><strong>Name:</strong>
                    <?php echo Security::escape($order['customer_name']); ?>
                </p>
                <p class="mb-1"><strong>Email:</strong>
                    <?php echo Security::escape($order['customer_email']); ?>
                </p>
                <p class="mb-1"><strong>Phone:</strong>
                    <?php echo Security::escape($order['customer_phone']); ?>
                </p>

                <h6 class="mt-3">Shipping Address</h6>
                <?php
                $addr = json_decode($order['shipping_address'], true);
                if (is_array($addr)) {
                    ?>
                    <p class="text-muted mb-0">
                        <?php echo Security::escape($addr['address_line1'] ?? ''); ?><br>
                        <?php echo Security::escape($addr['city'] ?? ''); ?><br>
                        <?php echo Security::escape($addr['country'] ?? ''); ?>
                    </p>
                <?php } else { ?>
                    <p class="text-muted mb-0">
                        <?php echo nl2br(Security::escape($order['shipping_address'])); ?>
                    </p>
                <?php } ?>

                <?php if ($order['notes']): ?>
                    <h6 class="mt-3">Notes</h6>
                    <p class="text-muted fst-italic mb-0">"
                        <?php echo Security::escape($order['notes']); ?>"
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
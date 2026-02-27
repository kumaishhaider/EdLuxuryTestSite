<?php
/**
 * Admin Orders List
 */

require_once __DIR__ . '/../config/config.php';
Security::requireAdminLogin();

$db = Database::getInstance();

// Handle Clear Orders
if (isset($_POST['action']) && $_POST['action'] === 'clear_all_orders') {
    // Ideally use a transaction if order_items exists
    try {
        $db->query("DELETE FROM order_items");
        $db->query("DELETE FROM orders");
        Helpers::setFlash('success', 'All orders have been cleared successfully.');
    } catch (Exception $e) {
        Helpers::setFlash('danger', 'Error clearing orders: ' . $e->getMessage());
    }
    header('Location: ' . Helpers::adminUrl('orders.php'));
    exit;
}

$pageTitle = 'Orders';
require_once 'includes/header.php';

$page = $_GET['page'] ?? 1;

// Pagination
$total = $db->fetchOne("SELECT COUNT(*) as count FROM orders")['count'] ?? 0;
$perPage = 20;
$totalPages = ceil($total / $perPage);
$offset = ($page - 1) * $perPage;

$orders = $db->fetchAll(
    "SELECT * FROM orders ORDER BY created_at DESC LIMIT $perPage OFFSET $offset"
);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Orders</h1>
    <form method="POST"
        onsubmit="return confirm('CRITICAL: This will permanently delete ALL orders and their history. Are you absolutely sure?');">
        <input type="hidden" name="action" value="clear_all_orders">
        <button type="submit" class="btn btn-danger d-flex align-items-center gap-2 shadow-sm px-4">
            <i class="bi bi-trash3"></i> Clear All Orders
        </button>
    </form>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Order #</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($orders)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <p class="text-muted mb-0">No orders found.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-bold">#<?php echo $order['order_number']; ?></span>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                <td>
                                    <div><?php echo Security::escape($order['customer_name']); ?></div>
                                    <small class="text-muted"><?php echo Security::escape($order['customer_phone']); ?></small>
                                </td>
                                <td><?php echo Helpers::formatPrice($order['total']); ?></td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        <?php echo strtoupper($order['payment_method']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                    $statusClass = 'secondary';
                                    if ($order['order_status'] === 'completed')
                                        $statusClass = 'success';
                                    if ($order['order_status'] === 'pending')
                                        $statusClass = 'warning';
                                    if ($order['order_status'] === 'cancelled')
                                        $statusClass = 'danger';
                                    ?>
                                    <span class="badge bg-<?php echo $statusClass; ?>">
                                        <?php echo ucfirst($order['order_status']); ?>
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="<?php echo Helpers::adminUrl('order-details.php?id=' . $order['id']); ?>"
                                        class="btn btn-sm btn-outline-primary">
                                        View
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if ($totalPages > 1): ?>
        <div class="card-footer">
            <?php echo Helpers::paginationLinks($page, $totalPages, Helpers::adminUrl('orders.php')); ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
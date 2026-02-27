<?php
/**
 * Admin Dashboard - Simplified Top-Nav Edition
 */

require_once __DIR__ . '/../config/config.php';
Security::requireAdminLogin();

$pageTitle = 'Overview Dashboard';
require_once 'includes/header.php';

// Get statistics
$orderModel = new Order();
$stats = $orderModel->getStatistics();

$db = Database::getInstance();

// Total products
$totalProducts = $db->count('products');

// Total customers
$totalCustomers = $db->count('users');

// Recent orders
$recentOrders = $db->fetchAll("SELECT * FROM orders ORDER BY created_at DESC LIMIT 6");
?>

<div class="row g-4 mb-4">
    <!-- Big Stats -->
    <div class="col-md-3">
        <div class="admin-card text-center border-top border-primary border-4">
            <div class="stat-label mb-2">Total Gross Revenue</div>
            <div class="stat-val"><?php echo Helpers::formatPrice($stats['total_revenue']); ?></div>
            <div class="text-success small fw-bold mt-2"><i class="bi bi-arrow-up"></i> 8% this month</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card text-center">
            <div class="stat-label mb-2">Orders Received</div>
            <div class="stat-val"><?php echo number_format($stats['total_orders']); ?></div>
            <div class="text-muted small mt-2">Life-time statistics</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card text-center">
            <div class="stat-label mb-2">Total Inventory</div>
            <div class="stat-val"><?php echo number_format($totalProducts); ?></div>
            <div class="text-muted small mt-2">Active products</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card text-center">
            <div class="stat-label mb-2">Registered Buyers</div>
            <div class="stat-val"><?php echo number_format($totalCustomers); ?></div>
            <div class="text-muted small mt-2">Verified emails</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Orders Status Table -->
    <div class="col-lg-8">
        <div class="admin-card p-0 overflow-hidden">
            <div class="p-4 border-bottom bg-light d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">Recent Store Activity</h6>
                <a href="<?php echo Helpers::adminUrl('orders.php'); ?>"
                    class="btn btn-sm btn-outline-primary border-0 fw-bold">View History <i
                        class="bi bi-arrow-right"></i></a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th class="text-end pe-4">Manage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentOrders as $order): ?>
                            <tr>
                                <td class="ps-4 fw-bold">#<?php echo Security::escape($order['order_number']); ?></td>
                                <td><?php echo Security::escape($order['customer_name']); ?></td>
                                <td class="fw-bold"><?php echo Helpers::formatPrice($order['total']); ?></td>
                                <td>
                                    <?php
                                    $badgeClass = 'bg-secondary';
                                    if ($order['order_status'] == 'pending')
                                        $badgeClass = 'bg-warning text-dark';
                                    if ($order['order_status'] == 'processing')
                                        $badgeClass = 'bg-info text-white';
                                    if ($order['order_status'] == 'delivered')
                                        $badgeClass = 'bg-success text-white';
                                    ?>
                                    <span class="badge <?php echo $badgeClass; ?> px-3 py-2 fw-bold text-uppercase"
                                        style="font-size: 0.65rem;">
                                        <?php echo $order['order_status']; ?>
                                    </span>
                                </td>
                                <td class="small text-muted"><?php echo date('M d, Y', strtotime($order['created_at'])); ?>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="<?php echo Helpers::adminUrl('order-details.php?id=' . $order['id']); ?>"
                                        class="btn btn-sm btn-light border px-3">Open</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Quick Breakdown Sidebar -->
    <div class="col-lg-4">
        <div class="admin-card">
            <h6 class="fw-bold mb-4">Operations Summary</h6>
            <div class="d-flex flex-column gap-3">
                <div class="p-3 bg-light rounded-3 d-flex justify-content-between align-items-center">
                    <div class="small fw-bold text-muted text-uppercase">Pending Orders</div>
                    <div class="badge bg-warning text-dark fs-6"><?php echo $stats['by_status']['pending'] ?? 0; ?>
                    </div>
                </div>
                <div class="p-3 bg-light rounded-3 d-flex justify-content-between align-items-center">
                    <div class="small fw-bold text-muted text-uppercase">Processing</div>
                    <div class="badge bg-info text-white fs-6"><?php echo $stats['by_status']['processing'] ?? 0; ?>
                    </div>
                </div>
                <div class="p-3 bg-light rounded-3 d-flex justify-content-between align-items-center">
                    <div class="small fw-bold text-muted text-uppercase">Shipped</div>
                    <div class="badge bg-primary text-white fs-6"><?php echo $stats['by_status']['shipped'] ?? 0; ?>
                    </div>
                </div>
                <div class="p-3 bg-light rounded-3 d-flex justify-content-between align-items-center">
                    <div class="small fw-bold text-muted text-uppercase">Delivered</div>
                    <div class="badge bg-success text-white fs-6"><?php echo $stats['by_status']['delivered'] ?? 0; ?>
                    </div>
                </div>
            </div>
            <div class="mt-5 text-center">
                <a href="<?php echo Helpers::adminUrl('products.php'); ?>"
                    class="btn btn-primary w-100 py-2 fw-bold">Manage Inventory</a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
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

// Analytics Stats
$totalAddCarts = $db->count('analytics_events', "event_type = 'add_to_cart'");
$totalWhatsApp = $db->count('analytics_events', "event_type = 'whatsapp_click'");
$totalWishlist = $db->count('analytics_events', "event_type = 'wishlist_add'");

$topAddedProducts = $db->fetchAll("
    SELECT p.name, COUNT(ae.id) as add_count 
    FROM analytics_events ae 
    JOIN products p ON ae.product_id = p.id 
    WHERE ae.event_type = 'add_to_cart' 
    GROUP BY ae.product_id 
    ORDER BY add_count DESC 
    LIMIT 3
");
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
        <div class="admin-card text-center border-top border-warning border-4">
            <div class="stat-label mb-2">Cart Interactions</div>
            <div class="stat-val"><?php echo number_format($totalAddCarts); ?></div>
            <div class="text-warning small fw-bold mt-2"><i class="bi bi-cart-plus"></i> Consumer Taps</div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="admin-card d-flex align-items-center gap-4 border-start border-success border-4">
            <div class="rounded-circle bg-success-subtle p-3 text-success">
                <i class="bi bi-whatsapp fs-3"></i>
            </div>
            <div>
                <div class="stat-label mb-1">WhatsApp Order Intents</div>
                <div class="stat-val" style="font-size: 1.8rem;"><?php echo number_format($totalWhatsApp); ?></div>
                <div class="text-muted small">Direct consumer messages initiated</div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="admin-card d-flex align-items-center gap-4 border-start border-danger border-4">
            <div class="rounded-circle bg-danger-subtle p-3 text-danger">
                <i class="bi bi-heart-fill fs-3"></i>
            </div>
            <div>
                <div class="stat-label mb-1">Wishlist Saves</div>
                <div class="stat-val" style="font-size: 1.8rem;"><?php echo number_format($totalWishlist); ?></div>
                <div class="text-muted small">Items consumers plan to buy later</div>
            </div>
        </div>
    </div>
</div>

<!-- QUICK ACTIONS & ALERTS -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="admin-card">
            <h6 class="fw-bold mb-4 d-flex align-items-center gap-2">
                <i class="bi bi-lightning-fill text-warning"></i> Quick Management Shortcuts
            </h6>
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <a href="<?php echo Helpers::adminUrl('product-form.php'); ?>" class="d-block text-center text-decoration-none p-3 rounded-3 bg-light border hover-shadow-sm transition-all" style="color: #4f46e5;">
                        <i class="bi bi-plus-circle fs-3 mb-2 d-inline-block"></i>
                        <div class="small fw-bold">Add Product</div>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="<?php echo Helpers::adminUrl('category-form.php'); ?>" class="d-block text-center text-decoration-none p-3 rounded-3 bg-light border hover-shadow-sm transition-all" style="color: #0891b2;">
                        <i class="bi bi-tag fs-3 mb-2 d-inline-block"></i>
                        <div class="small fw-bold">New Category</div>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="<?php echo Helpers::adminUrl('collection-form.php'); ?>" class="d-block text-center text-decoration-none p-3 rounded-3 bg-light border hover-shadow-sm transition-all" style="color: #059669;">
                        <i class="bi bi-collection fs-3 mb-2 d-inline-block"></i>
                        <div class="small fw-bold">New Collection</div>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="<?php echo Helpers::url(); ?>" target="_blank" class="d-block text-center text-decoration-none p-3 rounded-3 bg-light border hover-shadow-sm transition-all" style="color: #64748b;">
                        <i class="bi bi-globe fs-3 mb-2 d-inline-block"></i>
                        <div class="small fw-bold">View Store</div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="admin-card bg-white border-0">
            <h6 class="fw-bold mb-4 d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-triangle-fill text-danger"></i> Inventory Alerts
            </h6>
            <?php
            $lowStockProducts = $db->fetchAll("SELECT name, stock_quantity FROM products WHERE stock_quantity < 5 AND status = 'active' LIMIT 4");
            if (empty($lowStockProducts)):
            ?>
                <div class="text-center py-4 bg-light rounded-3">
                    <i class="bi bi-shield-check text-success fs-1"></i>
                    <p class="small text-muted mb-0 mt-2">All items are sufficiently stocked.</p>
                </div>
            <?php else: ?>
                <div class="d-flex flex-column gap-2">
                    <?php foreach ($lowStockProducts as $lp): ?>
                        <div class="p-2 px-3 rounded-3 border-start border-4 border-danger bg-danger-subtle d-flex justify-content-between align-items-center">
                            <div class="small fw-bold text-dark truncate-text" style="max-width: 150px;"><?php echo Security::escape($lp['name']); ?></div>
                            <span class="badge bg-danger rounded-pill"><?php echo $lp['stock_quantity']; ?> left</span>
                        </div>
                    <?php endforeach; ?>
                    <a href="<?php echo Helpers::adminUrl('products.php'); ?>" class="btn btn-sm btn-link text-decoration-none small fw-bold">Review full inventory &rarr;</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .hover-shadow-sm:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.08); transform: translateY(-3px); }
    .transition-all { transition: all 0.2s ease; }
    .truncate-text { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
</style>

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

            <hr class="my-4">
            
            <h6 class="fw-bold mb-3">Top Selling Products</h6>
            <div class="d-flex flex-column gap-2 mb-4">
                <?php
                $topProducts = $db->fetchAll("SELECT product_name, SUM(quantity) as total_sold FROM order_items GROUP BY product_id ORDER BY total_sold DESC LIMIT 3");
                if (empty($topProducts)):
                ?>
                    <p class="small text-muted italic">No sales data yet.</p>
                <?php else: ?>
                    <?php foreach ($topProducts as $tp): ?>
                        <div class="d-flex justify-content-between align-items-center p-2 rounded-2 hover-bg-light">
                            <div class="small fw-semibold text-truncate" style="max-width: 140px;"><?php echo Security::escape($tp['product_name']); ?></div>
                            <div class="badge bg-light text-primary border"><?php echo $tp['total_sold']; ?> sold</div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <h6 class="fw-bold mb-3">Most Added to Cart</h6>
            <div class="d-flex flex-column gap-2">
                <?php if (empty($topAddedProducts)): ?>
                    <p class="small text-muted italic">No cart data yet.</p>
                <?php else: ?>
                    <?php foreach ($topAddedProducts as $tap): ?>
                        <div class="d-flex justify-content-between align-items-center p-2 rounded-2 hover-bg-light">
                            <div class="small fw-semibold text-truncate" style="max-width: 140px;"><?php echo Security::escape($tap['name']); ?></div>
                            <div class="badge bg-light text-warning border"><?php echo $tap['add_count']; ?> taps</div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="mt-5 text-center">
                <a href="<?php echo Helpers::adminUrl('products.php'); ?>"
                    class="btn btn-primary w-100 py-2 fw-bold shadow-sm">Manage Inventory</a>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-bg-light:hover { background: #f8fafc; }
</style>

<?php require_once 'includes/footer.php'; ?>
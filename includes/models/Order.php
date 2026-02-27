<?php
/**
 * Order Model
 * 
 * Handles all order-related database operations
 */

class Order
{
    private $db;
    private $productModel;
    private $email;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->productModel = new Product();
        $this->email = new Email();
    }

    /**
     * Create new order
     */
    public function create($orderData, $items)
    {
        try {
            $this->db->beginTransaction();

            // Generate order number
            $orderData['order_number'] = Helpers::generateOrderNumber();

            // Insert order
            $orderId = $this->db->insert('orders', $orderData);

            // Insert order items and update stock
            foreach ($items as $item) {
                $this->db->insert('order_items', [
                    'order_id' => $orderId,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['name'],
                    'product_sku' => $item['sku'] ?? null,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['subtotal']
                ]);

                // Decrease stock
                $this->productModel->updateStock($item['product_id'], -$item['quantity']);
            }

            // Create payment record
            $this->db->insert('payments', [
                'order_id' => $orderId,
                'payment_method' => $orderData['payment_method'],
                'amount' => $orderData['total'],
                'status' => $orderData['payment_status']
            ]);

            $this->db->commit();

            // Send confirmation email
            $order = $this->getById($orderId);
            $this->email->sendOrderConfirmation($order);

            return ['success' => true, 'order_id' => $orderId, 'order_number' => $orderData['order_number']];

        } catch (Exception $e) {
            $this->db->rollback();
            $errorMsg = $e->getMessage();
            error_log("Order creation failed: " . $errorMsg);
            return ['success' => false, 'message' => 'Failed to create order: ' . $errorMsg];
        }
    }

    /**
     * Get order by ID
     */
    public function getById($id)
    {
        $order = $this->db->fetchOne("SELECT * FROM orders WHERE id = ?", [$id]);

        if ($order) {
            $order['items'] = $this->getItems($id);
            $order['payment'] = $this->getPayment($id);
        }

        return $order;
    }

    /**
     * Get order by order number
     */
    public function getByOrderNumber($orderNumber)
    {
        $order = $this->db->fetchOne("SELECT * FROM orders WHERE order_number = ?", [$orderNumber]);

        if ($order) {
            $order['items'] = $this->getItems($order['id']);
            $order['payment'] = $this->getPayment($order['id']);
        }

        return $order;
    }

    /**
     * Get order items
     */
    public function getItems($orderId)
    {
        return $this->db->fetchAll(
            "SELECT * FROM order_items WHERE order_id = ?",
            [$orderId]
        );
    }

    /**
     * Get payment info
     */
    public function getPayment($orderId)
    {
        return $this->db->fetchOne(
            "SELECT * FROM payments WHERE order_id = ? ORDER BY created_at DESC LIMIT 1",
            [$orderId]
        );
    }

    /**
     * Get all orders with pagination
     */
    public function getAll($filters = [], $page = 1, $perPage = ORDERS_PER_PAGE)
    {
        $where = ["1=1"];
        $params = [];

        if (!empty($filters['user_id'])) {
            $where[] = "user_id = ?";
            $params[] = $filters['user_id'];
        }

        if (!empty($filters['order_status'])) {
            $where[] = "order_status = ?";
            $params[] = $filters['order_status'];
        }

        if (!empty($filters['payment_status'])) {
            $where[] = "payment_status = ?";
            $params[] = $filters['payment_status'];
        }

        if (!empty($filters['search'])) {
            $where[] = "(order_number LIKE ? OR customer_email LIKE ? OR customer_name LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        $whereClause = implode(' AND ', $where);

        // Get total count
        $total = $this->db->count('orders', $whereClause, $params);

        // Get orders
        $orderBy = $filters['sort'] ?? 'created_at DESC';
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT * FROM orders 
                WHERE {$whereClause}
                ORDER BY {$orderBy}
                LIMIT {$perPage} OFFSET {$offset}";

        $orders = $this->db->fetchAll($sql, $params);

        return [
            'orders' => $orders,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => ceil($total / $perPage)
        ];
    }

    /**
     * Update order status
     */
    public function updateStatus($orderId, $status)
    {
        $this->db->update('orders', ['order_status' => $status], 'id = ?', [$orderId]);

        // Send email notification
        $order = $this->getById($orderId);

        if ($status === 'processing') {
            $this->email->sendOrderProcessing($order);
        } elseif ($status === 'shipped' && !empty($order['tracking_number'])) {
            $this->email->sendOrderShipped($order);
        } elseif ($status === 'delivered') {
            $this->email->sendOrderDelivered($order);
        }

        return ['success' => true, 'message' => 'Order status updated'];
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus($orderId, $status)
    {
        $this->db->update('orders', ['payment_status' => $status], 'id = ?', [$orderId]);
        $this->db->update('payments', ['status' => $status], 'order_id = ?', [$orderId]);

        return ['success' => true, 'message' => 'Payment status updated'];
    }

    /**
     * Add tracking number
     */
    public function addTracking($orderId, $trackingNumber)
    {
        $this->db->update('orders', ['tracking_number' => $trackingNumber], 'id = ?', [$orderId]);

        // If order is already shipped or marked as shipped now, send the email
        $order = $this->getById($orderId);
        if ($order['order_status'] === 'shipped') {
            $this->email->sendOrderShipped($order);
        }

        return ['success' => true, 'message' => 'Tracking number added and customer notified'];
    }

    /**
     * Get customer orders
     */
    public function getCustomerOrders($userId)
    {
        return $this->db->fetchAll(
            "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC",
            [$userId]
        );
    }

    /**
     * Get order statistics
     */
    public function getStatistics()
    {
        $stats = [];

        // Total orders
        $stats['total_orders'] = $this->db->count('orders');

        // Total revenue
        $result = $this->db->fetchOne("SELECT SUM(total) as total_revenue FROM orders WHERE payment_status = 'paid'");
        $stats['total_revenue'] = $result['total_revenue'] ?? 0;

        // Orders by status
        $statusCounts = $this->db->fetchAll("SELECT order_status, COUNT(*) as count FROM orders GROUP BY order_status");
        $stats['by_status'] = [];
        foreach ($statusCounts as $row) {
            $stats['by_status'][$row['order_status']] = $row['count'];
        }

        // Recent orders
        $stats['recent_orders'] = $this->db->fetchAll(
            "SELECT * FROM orders ORDER BY created_at DESC LIMIT 10"
        );

        return $stats;
    }

    /**
     * Cancel order
     */
    public function cancel($orderId)
    {
        try {
            $this->db->beginTransaction();

            $order = $this->getById($orderId);

            if (!$order) {
                throw new Exception('Order not found');
            }

            if ($order['order_status'] === 'delivered' || $order['order_status'] === 'cancelled') {
                throw new Exception('Cannot cancel this order');
            }

            // Restore stock
            foreach ($order['items'] as $item) {
                $this->productModel->updateStock($item['product_id'], $item['quantity']);
            }

            // Update order status
            $this->db->update('orders', ['order_status' => 'cancelled'], 'id = ?', [$orderId]);

            $this->db->commit();

            return ['success' => true, 'message' => 'Order cancelled'];

        } catch (Exception $e) {
            $this->db->rollback();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}

<?php
/**
 * Cart Class
 * 
 * Handles shopping cart operations using sessions
 */

class Cart
{
    private $db;
    private $productModel;
    private static $instance = null;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->productModel = new Product();

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Add item to cart
     */
    public function add($productId, $quantity = 1)
    {
        // Validate product exists and is in stock
        if (!$this->productModel->isInStock($productId, $quantity)) {
            return ['success' => false, 'message' => 'Product is out of stock'];
        }

        $product = $this->productModel->getById($productId);
        if (!$product || $product['status'] !== 'active') {
            return ['success' => false, 'message' => 'Product not found'];
        }

        // Add or update quantity
        if (isset($_SESSION['cart'][$productId])) {
            $newQuantity = $_SESSION['cart'][$productId]['quantity'] + $quantity;

            if (!$this->productModel->isInStock($productId, $newQuantity)) {
                return ['success' => false, 'message' => 'Not enough stock available'];
            }

            $_SESSION['cart'][$productId]['quantity'] = $newQuantity;
        } else {
            $_SESSION['cart'][$productId] = [
                'product_id' => $productId,
                'quantity' => $quantity,
                'added_at' => time()
            ];
        }

        return ['success' => true, 'message' => 'Product added to cart', 'cart_count' => $this->getCount()];
    }

    /**
     * Update cart item quantity
     */
    public function update($productId, $quantity)
    {
        if ($quantity <= 0) {
            return $this->remove($productId);
        }

        if (!$this->productModel->isInStock($productId, $quantity)) {
            return ['success' => false, 'message' => 'Not enough stock available'];
        }

        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] = $quantity;
            return ['success' => true, 'message' => 'Cart updated'];
        }

        return ['success' => false, 'message' => 'Product not in cart'];
    }

    /**
     * Remove item from cart
     */
    public function remove($productId)
    {
        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
            return ['success' => true, 'message' => 'Product removed from cart'];
        }

        return ['success' => false, 'message' => 'Product not in cart'];
    }

    /**
     * Clear entire cart
     */
    public function clear()
    {
        $_SESSION['cart'] = [];
        return ['success' => true, 'message' => 'Cart cleared'];
    }

    /**
     * Get cart items with product details
     */
    public function getItems()
    {
        $items = [];

        foreach ($_SESSION['cart'] as $productId => $cartItem) {
            $product = $this->productModel->getById($productId);

            if ($product && $product['status'] === 'active') {
                $primaryImage = null;
                if (!empty($product['images'])) {
                    foreach ($product['images'] as $img) {
                        if ($img['is_primary']) {
                            $primaryImage = $img['image_path'];
                            break;
                        }
                    }
                    if (!$primaryImage) {
                        $primaryImage = $product['images'][0]['image_path'];
                    }
                }

                $items[] = [
                    'product_id' => $productId,
                    'name' => $product['name'],
                    'slug' => $product['slug'],
                    'price' => $product['price'],
                    'image' => $primaryImage,
                    'quantity' => $cartItem['quantity'],
                    'subtotal' => $product['price'] * $cartItem['quantity'],
                    'in_stock' => $product['stock_quantity'] >= $cartItem['quantity']
                ];
            }
        }

        return $items;
    }

    /**
     * Get cart count (total items)
     */
    public function getCount()
    {
        $count = 0;
        foreach ($_SESSION['cart'] as $item) {
            $count += $item['quantity'];
        }
        return $count;
    }

    /**
     * Get item count (alias for getCount)
     */
    public function getItemCount()
    {
        return $this->getCount();
    }

    /**
     * Get cart summary
     */
    public function getSummary()
    {
        $items = $this->getItems();
        $subtotal = 0;

        foreach ($items as $item) {
            $subtotal += $item['subtotal'];
        }

        $shippingCost = $this->calculateShipping($subtotal);
        $tax = $this->calculateTax($subtotal);
        $total = $subtotal + $shippingCost + $tax;

        return [
            'items' => $items,
            'item_count' => $this->getCount(),
            'subtotal' => $subtotal,
            'shipping' => $shippingCost,
            'tax' => $tax,
            'total' => $total
        ];
    }

    /**
     * Calculate shipping cost
     */
    private function calculateShipping($subtotal)
    {
        if (FREE_SHIPPING_THRESHOLD > 0 && $subtotal >= FREE_SHIPPING_THRESHOLD) {
            return 0;
        }
        return FLAT_SHIPPING_RATE;
    }

    /**
     * Calculate tax
     */
    private function calculateTax($subtotal)
    {
        // UAE typically has 5% VAT, but we'll set it to 0 for now
        // Adjust as needed
        return 0;
    }

    /**
     * Validate cart before checkout
     */
    public function validate()
    {
        $errors = [];
        $items = $this->getItems();

        if (empty($items)) {
            $errors[] = 'Cart is empty';
            return ['valid' => false, 'errors' => $errors];
        }

        foreach ($items as $item) {
            if (!$item['in_stock']) {
                $errors[] = $item['name'] . ' is out of stock';
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
}

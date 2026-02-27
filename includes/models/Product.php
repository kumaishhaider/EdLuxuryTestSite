<?php
/**
 * Product Model
 * 
 * Handles all product-related database operations
 */

class Product
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Get all products with pagination and filters
     */
    public function getAll($filters = [], $page = 1, $perPage = PRODUCTS_PER_PAGE)
    {
        $where = ["p.status = 'active'"];
        $params = [];

        if (!empty($filters['category_id'])) {
            $where[] = "p.category_id = ?";
            $params[] = $filters['category_id'];
        }

        if (!empty($filters['collection_id'])) {
            $where[] = "EXISTS (SELECT 1 FROM product_collections pc WHERE pc.product_id = p.id AND pc.collection_id = ?)";
            $params[] = $filters['collection_id'];
        }

        if (!empty($filters['search'])) {
            $where[] = "(p.name LIKE ? OR p.description LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        if (!empty($filters['min_price'])) {
            $where[] = "p.price >= ?";
            $params[] = $filters['min_price'];
        }

        if (!empty($filters['max_price'])) {
            $where[] = "p.price <= ?";
            $params[] = $filters['max_price'];
        }

        if (!empty($filters['badge'])) {
            $where[] = "p.badge = ?";
            $params[] = $filters['badge'];
        }

        if (isset($filters['featured']) && $filters['featured']) {
            $where[] = "p.featured = 1";
        }

        $whereClause = implode(' AND ', $where);

        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM products p WHERE {$whereClause}";
        $totalResult = $this->db->fetchOne($countSql, $params);
        $total = $totalResult['total'];

        // Get products
        $orderBy = $filters['sort'] ?? 'p.created_at DESC';
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT p.*, c.name as category_name,
                (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as primary_image
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE {$whereClause}
                ORDER BY {$orderBy}
                LIMIT {$perPage} OFFSET {$offset}";

        $products = $this->db->fetchAll($sql, $params);

        return [
            'products' => $products,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => ceil($total / $perPage)
        ];
    }

    /**
     * Get product by ID
     */
    public function getById($id)
    {
        $sql = "SELECT p.*, c.name as category_name
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.id = ?";

        $product = $this->db->fetchOne($sql, [$id]);

        if ($product) {
            $product['images'] = $this->getImages($id);
            $product['collections'] = $this->getCollections($id);
        }

        return $product;
    }

    /**
     * Get product by slug
     */
    public function getBySlug($slug)
    {
        $sql = "SELECT p.*, c.name as category_name
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.slug = ? AND p.status = 'active'";

        $product = $this->db->fetchOne($sql, [$slug]);

        if ($product) {
            $product['images'] = $this->getImages($product['id']);
            $product['collections'] = $this->getCollections($product['id']);
            $product['reviews'] = $this->getReviews($product['id']);
        }

        return $product;
    }

    /**
     * Get product images
     */
    public function getImages($productId)
    {
        return $this->db->fetchAll(
            "SELECT * FROM product_images WHERE product_id = ? ORDER BY is_primary DESC, sort_order ASC",
            [$productId]
        );
    }

    /**
     * Get product collections
     */
    public function getCollections($productId)
    {
        return $this->db->fetchAll(
            "SELECT c.* FROM collections c
             INNER JOIN product_collections pc ON c.id = pc.collection_id
             WHERE pc.product_id = ?",
            [$productId]
        );
    }

    /**
     * Get product reviews
     */
    public function getReviews($productId, $status = 'approved')
    {
        try {
            return $this->db->fetchAll(
                "SELECT * FROM product_reviews 
                 WHERE product_id = ? AND status = ?
                 ORDER BY created_at DESC",
                [$productId, $status]
            );
        } catch (Exception $e) {
            // Table may not exist yet
            return [];
        }
    }

    /**
     * Get average rating
     */
    public function getAverageRating($productId)
    {
        try {
            $result = $this->db->fetchOne(
                "SELECT AVG(rating) as avg_rating, COUNT(*) as review_count
                 FROM product_reviews
                 WHERE product_id = ? AND status = 'approved'",
                [$productId]
            );

            return [
                'average' => round($result['avg_rating'] ?? 0, 1),
                'count' => $result['review_count'] ?? 0
            ];
        } catch (Exception $e) {
            return [
                'average' => 0,
                'count' => 0
            ];
        }
    }

    /**
     * Create product
     */
    public function create($data)
    {
        return $this->db->insert('products', $data);
    }

    /**
     * Update product
     */
    public function update($id, $data)
    {
        return $this->db->update('products', $data, 'id = ?', [$id]);
    }

    /**
     * Delete product
     */
    public function delete($id)
    {
        return $this->db->delete('products', 'id = ?', [$id]);
    }

    /**
     * Add product image
     */
    public function addImage($productId, $imagePath, $altText = '', $isPrimary = false)
    {
        if ($isPrimary) {
            // Remove primary flag from other images
            $this->db->update('product_images', ['is_primary' => 0], 'product_id = ?', [$productId]);
        }

        return $this->db->insert('product_images', [
            'product_id' => $productId,
            'image_path' => $imagePath,
            'alt_text' => $altText,
            'is_primary' => $isPrimary ? 1 : 0
        ]);
    }

    /**
     * Delete product image
     */
    public function deleteImage($imageId)
    {
        return $this->db->delete('product_images', 'id = ?', [$imageId]);
    }

    /**
     * Add product to collection
     */
    public function addToCollection($productId, $collectionId)
    {
        try {
            return $this->db->insert('product_collections', [
                'product_id' => $productId,
                'collection_id' => $collectionId
            ]);
        } catch (Exception $e) {
            // Already exists
            return false;
        }
    }

    /**
     * Remove product from collection
     */
    public function removeFromCollection($productId, $collectionId)
    {
        return $this->db->delete('product_collections', 'product_id = ? AND collection_id = ?', [$productId, $collectionId]);
    }

    /**
     * Update stock
     */
    public function updateStock($productId, $quantity)
    {
        $sql = "UPDATE products SET stock_quantity = stock_quantity + ? WHERE id = ?";
        return $this->db->query($sql, [$quantity, $productId]);
    }

    /**
     * Check if product is in stock
     */
    public function isInStock($productId, $quantity = 1)
    {
        $product = $this->getById($productId);
        return $product && $product['stock_quantity'] >= $quantity;
    }

    /**
     * Get related products
     */
    public function getRelated($productId, $limit = 4)
    {
        $product = $this->getById($productId);
        if (!$product) {
            return [];
        }

        $sql = "SELECT p.*, 
                (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as primary_image
                FROM products p
                WHERE p.category_id = ? AND p.id != ? AND p.status = 'active'
                ORDER BY RAND()
                LIMIT ?";

        return $this->db->fetchAll($sql, [$product['category_id'], $productId, $limit]);
    }

    /**
     * Search products
     */
    public function search($query, $limit = 10)
    {
        $searchTerm = '%' . $query . '%';

        $sql = "SELECT p.id, p.name, p.slug, p.price,
                (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as primary_image
                FROM products p
                WHERE (p.name LIKE ? OR p.description LIKE ? OR p.sku LIKE ?)
                AND p.status = 'active'
                LIMIT ?";

        return $this->db->fetchAll($sql, [$searchTerm, $searchTerm, $searchTerm, $limit]);
    }
}

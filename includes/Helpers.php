<?php
/**
 * Helper Functions
 * 
 * Utility functions used throughout the application
 */

class Helpers
{

    /**
     * Generate SEO-friendly slug
     */
    public static function generateSlug($string)
    {
        $string = strtolower(trim($string));
        $string = preg_replace('/[^a-z0-9-]/', '-', $string);
        $string = preg_replace('/-+/', '-', $string);
        return trim($string, '-');
    }

    /**
     * Format price
     */
    public static function formatPrice($price)
    {
        if (CURRENT_LANG === 'ar') {
            return number_format($price, 2) . ' د.إ';
        }
        return 'AED ' . number_format($price, 2);
    }


    /**
     * Format date
     */
    public static function formatDate($date, $format = 'M d, Y')
    {
        return date($format, strtotime($date));
    }

    /**
     * Format datetime
     */
    public static function formatDateTime($datetime, $format = 'M d, Y h:i A')
    {
        return date($format, strtotime($datetime));
    }

    /**
     * Time ago format
     */
    public static function timeAgo($datetime)
    {
        $timestamp = strtotime($datetime);
        $diff = time() - $timestamp;

        if ($diff < 60) {
            return 'just now';
        } elseif ($diff < 3600) {
            $mins = floor($diff / 60);
            return $mins . ' minute' . ($mins > 1 ? 's' : '') . ' ago';
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
        } elseif ($diff < 604800) {
            $days = floor($diff / 86400);
            return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
        } else {
            return self::formatDate($datetime);
        }
    }

    /**
     * Generate URL
     */
    public static function url($path = '')
    {
        // quick fix for missing .htaccess
        if (preg_match('/^product\/([a-zA-Z0-9-]+)$/', $path, $matches)) {
            return SITE_URL . '/product.php?slug=' . $matches[1];
        }
        if (preg_match('/^collection\/([a-zA-Z0-9-]+)$/', $path, $matches)) {
            return SITE_URL . '/collection.php?slug=' . $matches[1];
        }
        if (preg_match('/^category\/([a-zA-Z0-9-]+)$/', $path, $matches)) {
            return SITE_URL . '/category.php?slug=' . $matches[1];
        }
        if (preg_match('/^page\/([a-zA-Z0-9-]+)$/', $path, $matches)) {
            return SITE_URL . '/page.php?slug=' . $matches[1];
        }

        return SITE_URL . '/' . ltrim($path, '/');
    }

    /**
     * Generate admin URL
     */
    public static function adminUrl($path = '')
    {
        return ADMIN_URL . '/' . ltrim($path, '/');
    }

    /**
     * Generate assets URL
     */
    public static function asset($path)
    {
        return ASSETS_URL . '/' . ltrim($path, '/');
    }

    /**
     * Generate uploads URL
     */
    public static function upload($path)
    {
        if (strpos($path, 'http') === 0) {
            return $path;
        }
        return UPLOADS_URL . '/' . ltrim($path, '/');
    }

    /**
     * Redirect
     */
    public static function redirect($url, $statusCode = 302)
    {
        if (!headers_sent()) {
            header('Location: ' . $url, true, $statusCode);
            exit;
        } else {
            echo '<script type="text/javascript">';
            echo 'window.location.href="' . $url . '";';
            echo '</script>';
            echo '<noscript>';
            echo '<meta http-equiv="refresh" content="0;url=' . $url . '" />';
            echo '</noscript>';
            exit;
        }
    }

    /**
     * Get current URL
     */
    public static function currentUrl()
    {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")
            . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }

    /**
     * Flash message
     */
    public static function setFlash($type, $message)
    {
        $_SESSION['flash_message'] = [
            'type' => $type,
            'message' => $message
        ];
    }

    /**
     * Get and clear flash message
     */
    public static function getFlash()
    {
        if (isset($_SESSION['flash_message'])) {
            $flash = $_SESSION['flash_message'];
            unset($_SESSION['flash_message']);
            return $flash;
        }
        return null;
    }

    /**
     * Display flash message HTML
     */
    public static function displayFlash()
    {
        $flash = self::getFlash();
        if ($flash) {
            $alertClass = $flash['type'] === 'success' ? 'alert-success' :
                ($flash['type'] === 'error' ? 'alert-danger' : 'alert-info');

            return '<div class="alert ' . $alertClass . ' alert-dismissible fade show" role="alert">
                        ' . Security::escape($flash['message']) . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>';
        }
        return '';
    }

    /**
     * Paginate array
     */
    public static function paginate($items, $perPage, $currentPage = 1)
    {
        $total = count($items);
        $totalPages = ceil($total / $perPage);
        $currentPage = max(1, min($currentPage, $totalPages));
        $offset = ($currentPage - 1) * $perPage;

        return [
            'items' => array_slice($items, $offset, $perPage),
            'current_page' => $currentPage,
            'total_pages' => $totalPages,
            'total_items' => $total,
            'per_page' => $perPage
        ];
    }

    /**
     * Generate pagination HTML
     */
    public static function paginationLinks($currentPage, $totalPages, $baseUrl)
    {
        if ($totalPages <= 1) {
            return '';
        }

        $html = '<nav aria-label="Page navigation"><ul class="pagination justify-content-center">';

        // Previous
        if ($currentPage > 1) {
            $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?page=' . ($currentPage - 1) . '">Previous</a></li>';
        }

        // Pages
        for ($i = 1; $i <= $totalPages; $i++) {
            if ($i == $currentPage) {
                $html .= '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
            } else {
                $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?page=' . $i . '">' . $i . '</a></li>';
            }
        }

        // Next
        if ($currentPage < $totalPages) {
            $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?page=' . ($currentPage + 1) . '">Next</a></li>';
        }

        $html .= '</ul></nav>';
        return $html;
    }

    /**
     * Truncate text
     */
    public static function truncate($text, $length = 100, $suffix = '...')
    {
        if (strlen($text) <= $length) {
            return $text;
        }
        return substr($text, 0, $length) . $suffix;
    }

    /**
     * Generate order number
     */
    public static function generateOrderNumber()
    {
        return 'ES' . date('Ymd') . strtoupper(substr(uniqid(), -6));
    }

    /**
     * Calculate discount percentage
     */
    public static function discountPercentage($originalPrice, $salePrice)
    {
        if ($originalPrice <= 0) {
            return 0;
        }
        return round((($originalPrice - $salePrice) / $originalPrice) * 100);
    }

    /**
     * Get client IP address
     */
    public static function getClientIP()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }

    /**
     * JSON response
     */
    public static function jsonResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Upload image with resize
     */
    public static function uploadImage($file, $directory = 'products')
    {
        $validation = Security::validateImage($file);
        if (!$validation['valid']) {
            return ['success' => false, 'error' => $validation['error']];
        }

        $uploadDir = UPLOADS_PATH . '/' . $directory;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '_' . time() . '.' . $extension;
        $filepath = $uploadDir . '/' . $filename;

        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            // Create thumbnail
            self::resizeImage($filepath, $uploadDir . '/thumb_' . $filename, THUMBNAIL_WIDTH, THUMBNAIL_HEIGHT);

            return [
                'success' => true,
                'filename' => $filename,
                'path' => $directory . '/' . $filename,
                'url' => UPLOADS_URL . '/' . $directory . '/' . $filename
            ];
        }

        return ['success' => false, 'error' => 'Failed to upload file'];
    }

    /**
     * Resize image
     */
    public static function resizeImage($source, $destination, $width, $height)
    {
        if (!function_exists('imagecreatetruecolor')) {
            return copy($source, $destination);
        }

        list($origWidth, $origHeight, $type) = getimagesize($source);

        $ratio = min($width / $origWidth, $height / $origHeight);
        $newWidth = $origWidth * $ratio;
        $newHeight = $origHeight * $ratio;

        $image = imagecreatetruecolor($newWidth, $newHeight);

        switch ($type) {
            case IMAGETYPE_JPEG:
                $sourceImage = imagecreatefromjpeg($source);
                break;
            case IMAGETYPE_PNG:
                $sourceImage = imagecreatefrompng($source);
                imagealphablending($image, false);
                imagesavealpha($image, true);
                break;
            case IMAGETYPE_GIF:
                $sourceImage = imagecreatefromgif($source);
                break;
            default:
                return false;
        }

        imagecopyresampled($image, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

        switch ($type) {
            case IMAGETYPE_JPEG:
                imagejpeg($image, $destination, 90);
                break;
            case IMAGETYPE_PNG:
                imagepng($image, $destination, 9);
                break;
            case IMAGETYPE_GIF:
                imagegif($image, $destination);
                break;
        }

        imagedestroy($image);
        imagedestroy($sourceImage);

        return true;
    }
    /**
     * Translation Helper
     */
    public static function translate($key)
    {
        $translations = [
            'ar' => [
                'home' => 'الرئيسية',
                'description' => 'وصف المنتج',
                'price' => 'السعر',
                'shop' => 'المتجر',
                'categories' => 'التصنيفات',
                'about' => 'من نحن',
                'contact' => 'اتصل بنا',
                'search' => 'بحث...',
                'my_cart' => 'سلة المشتريات',
                'login' => 'تسجيل الدخول',
                'register' => 'إنشاء حساب',
                'dashboard' => 'لوحة التحكم',
                'logout' => 'تسجيل الخروج',
                'view_cart' => 'عرض السلة',
                'checkout' => 'إتمام الشراء',
                'add_to_cart' => 'أضف إلى السلة',
                'welcome' => 'مرحباً',
                'explore' => 'استكشف متجرنا',
                'find_store' => 'فروعنا',
                'customer_service' => 'خدمة العملاء',
                'track_order' => 'تتبع طلبك',
                'newsletter' => 'النشرة البريدية',
                'follow_us' => 'تابعنا',
                'copyright' => 'جميع الحقوق محفوظة',
                'items' => 'منتجات',
                'free_shipping' => 'شحن مجاني',
                'secure_payment' => 'دفع آمن',
                'quality_guarantee' => 'ضمان الجودة',
                'support' => 'دعم 24/7',
                'order_now' => 'اطلب الآن',
                'read_more' => 'اقرأ المزيد',
                'trending' => 'الأكثر رواجاً',
                'new_arrivals' => 'وصل حديثاً',
                'featured_products' => 'منتجات مختارة',
                'hot_selling' => 'الأكثر مبيعاً',
                'brand_story' => 'قصة علامتنا التجارية',
                'explore_store' => 'استكشف متجرنا',
                'arrivals' => 'وصل حديثاً',
                'shop_category' => 'تسوق حسب الفئة',
                'create_account' => 'إنشاء حساب جديد',
                'first_name' => 'الاسم الأول',
                'last_name' => 'اسم العائلة',
                'email' => 'البريد الإلكتروني',
                'phone' => 'رقم الهاتف',
                'password' => 'كلمة المرور',
                'confirm_password' => 'تأكيد كلمة المرور',
                'remember_me' => 'تذكرني',
                'forgot_password' => 'نسيت كلمة المرور؟',
                'agree_terms' => 'أوافق على الشروط والأحكام',
                'already_have_account' => 'لديك حساب بالفعل؟',
                'no_account' => 'ليس لديك حساب؟',
                'back_to_home' => 'العودة للرئيسية',
                'verified_reviews' => 'تقييمات موثقة',
                'in_stock' => 'متوفر في المخزن',
                'save' => 'توفير',
                'free_shipping_uae' => 'شحن مجاني في جميع أنحاء الإمارات',
                'ships_in_24h' => 'يتم الشحن عادة خلال 24 ساعة',
                'buy_now' => 'اشترِ الآن - دفع سريع',
                'bundle_save' => 'الباقة والتوفير الإضافي',
                'limited_offer' => 'عرض محدود',
                'buy_1_unit' => 'شراء وحدة واحدة',
                'buy_2_units' => 'شراء وحدتين',
                'buy_3_units' => 'شراء 3 وحدات',
                'recent_feedback' => 'آراء العملاء الأخيرة',
                'view_all_reviews' => 'عرض جميع التقييمات',
                'shipping_returns' => 'الشحن والإرجاع',
                'warranty' => 'الضمان',
                'verified_buyer' => 'مشتري موثق',
                'product_video' => 'فيديو المنتج',
                'people_viewing' => 'أشخاص يشاهدون هذا الآن',
                'days_ago' => 'أيام مضت',
                'weeks_ago' => 'أسابيع مضت',
                'month_ago' => 'شهر مضى',
                'standard_price' => 'السعر القياسي',
                'perfect_sharing' => 'مثالي للمشاركة',
                'full_set' => 'مجموعة احترافية كاملة',
                'popular' => 'الأكثر شعبية',
                'best_value' => 'أفضل قيمة',
                'product_not_found' => 'المنتج غير موجود',
                'browse_products' => 'تصفح المنتجات',
                'product_details' => 'تفاصيل المنتج',
                'key_features' => 'الميزات الرئيسية',
                'shipping_info' => 'معلومات الشحن',
                'return_policy' => 'سياسة الإرجاع',
                'warranty_coverage' => 'تغطية الضمان',
                'write_review' => 'اكتب تقييماً',
                'based_on' => 'بناءً على',
                'customer_feedback' => 'آراء العملاء',
                'authentic' => 'أصلي 100%',
                'guaranteed' => 'مضمون',
                'easy_returns' => 'إرجاع سهل',
                '14_days' => '14 يوماً',
                'secure_pay' => 'دفع آمن',
                'ssl_encrypted' => 'مشفر SSL',
                'all_uae' => 'كل الإمارات',
                'free_delivery' => 'توصيل مجاني',
                'about_us' => 'من نحن',
                'tracking_info' => 'معلومات التتبع',
                'shipping_returns_bilingual' => 'Shipping & Returns | الشحن والإرجاع',
                'description_bilingual' => 'Product Description | وصف المنتج',
                'about_us_bilingual' => 'من نحن | About Us'
            ],

            'en' => [
                'home' => 'Home',
                'description' => 'Product Description',
                'price' => 'Price',
                'shop' => 'Shop',
                'categories' => 'Categories',
                'about' => 'About',
                'contact' => 'Contact',
                'search' => 'Search...',
                'my_cart' => 'My Cart',
                'login' => 'Login',
                'register' => 'Register',
                'dashboard' => 'Dashboard',
                'logout' => 'Logout',
                'view_cart' => 'View Cart',
                'checkout' => 'Checkout',
                'add_to_cart' => 'Add to Cart',
                'welcome' => 'Welcome',
                'explore' => 'Explore Store',
                'find_store' => 'Find a Store',
                'customer_service' => 'Customer Service',
                'track_order' => 'Track Order',
                'newsletter' => 'Newsletter',
                'follow_us' => 'Follow Us',
                'copyright' => 'All rights reserved',
                'items' => 'Items',
                'free_shipping' => 'Free Shipping',
                'secure_payment' => 'Secure Payment',
                'quality_guarantee' => 'Quality Guarantee',
                'support' => '24/7 Support',
                'order_now' => 'Order Now',
                'read_more' => 'Read More',
                'trending' => 'Trending',
                'new_arrivals' => 'New Arrivals',
                'featured_products' => 'Featured Products',
                'hot_selling' => 'Hot Selling',
                'brand_story' => 'The Brand Story',
                'explore_store' => 'Explore Our Store',
                'arrivals' => 'Arrivals',
                'shop_category' => 'Shop By Category',
                'create_account' => 'Create Account',
                'first_name' => 'First Name',
                'last_name' => 'Last Name',
                'email' => 'Email',
                'phone' => 'Phone',
                'password' => 'Password',
                'confirm_password' => 'Confirm Password',
                'remember_me' => 'Remember Me',
                'forgot_password' => 'Forgot Password?',
                'agree_terms' => 'I agree to terms',
                'already_have_account' => 'Already have an account?',
                'no_account' => "Don't have an account?",
                'back_to_home' => 'Back to Home',
                'verified_reviews' => 'Verified Reviews',
                'in_stock' => 'In Stock',
                'save' => 'SAVE',
                'free_shipping_uae' => 'Free shipping across UAE',
                'ships_in_24h' => 'Usually ships in 24 hours',
                'buy_now' => 'Buy Now – Express Checkout',
                'bundle_save' => 'Bundle & Save Extra',
                'limited_offer' => 'LIMITED OFFER',
                'buy_1_unit' => 'Buy 1 Unit',
                'buy_2_units' => 'Buy 2 Units',
                'buy_3_units' => 'Buy 3 Units',
                'recent_feedback' => 'Recent Customer Feedback',
                'view_all_reviews' => 'View All Reviews',
                'shipping_returns' => 'Shipping & Returns',
                'warranty' => 'Warranty',
                'verified_buyer' => 'Verified Buyer',
                'product_video' => 'Product Video',
                'people_viewing' => 'people are viewing this',
                'days_ago' => 'days ago',
                'weeks_ago' => 'weeks ago',
                'month_ago' => 'month ago',
                'standard_price' => 'Standard price',
                'perfect_sharing' => 'Perfect for sharing',
                'full_set' => 'Full professional set',
                'popular' => 'POPULAR',
                'best_value' => 'BEST VALUE',
                'product_not_found' => 'Product Not Found',
                'browse_products' => 'Browse Products',
                'product_details' => 'Product Details',
                'key_features' => 'Key Features',
                'shipping_info' => 'Shipping Information',
                'return_policy' => 'Return Policy',
                'warranty_coverage' => 'Warranty Coverage',
                'write_review' => 'Write a Review',
                'based_on' => 'based on',
                'customer_feedback' => 'Customer Feedback',
                'authentic' => '100% Authentic',
                'guaranteed' => 'Guaranteed',
                'easy_returns' => 'Easy Returns',
                '14_days' => '14 Days',
                'secure_pay' => 'Secure Pay',
                'ssl_encrypted' => 'SSL Encrypted',
                'all_uae' => 'All UAE',
                'free_delivery' => 'Free Delivery',
                'about_us' => 'About Us',
                'tracking_info' => 'Tracking Information',
                'shipping_returns_bilingual' => 'Shipping & Returns | الشحن والإرجاع',
                'description_bilingual' => 'Product Description | وصف المنتج',
                'about_us_bilingual' => 'من نحن | About Us'
            ]

        ];


        return $translations[CURRENT_LANG][$key] ?? $key;

    }
}


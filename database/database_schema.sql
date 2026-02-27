-- Edluxury Database Schema
-- Production-ready eCommerce database structure

-- Drop existing tables if they exist (for clean installation)
-- Drop in reverse order of dependencies to avoid foreign key constraint errors
DROP TABLE IF EXISTS `payments`;
DROP TABLE IF EXISTS `order_items`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `product_reviews`;
DROP TABLE IF EXISTS `product_collections`;
DROP TABLE IF EXISTS `product_images`;
DROP TABLE IF EXISTS `shipping_addresses`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `collections`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `admins`;
DROP TABLE IF EXISTS `testimonials`;
DROP TABLE IF EXISTS `faqs`;
DROP TABLE IF EXISTS `pages`;
DROP TABLE IF EXISTS `banners`;
DROP TABLE IF EXISTS `theme_settings`;
DROP TABLE IF EXISTS `email_templates`;

-- Admins table
CREATE TABLE `admins` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `full_name` VARCHAR(100) NOT NULL,
  `role` ENUM('super_admin', 'admin', 'manager') DEFAULT 'admin',
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Users (Customers) table
CREATE TABLE `users` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `first_name` VARCHAR(50) NOT NULL,
  `last_name` VARCHAR(50) NOT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `email_verified` TINYINT(1) DEFAULT 0,
  `reset_token` VARCHAR(100) DEFAULT NULL,
  `reset_token_expires` DATETIME DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Categories table
CREATE TABLE `categories` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `parent_id` INT(11) UNSIGNED DEFAULT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `meta_title` VARCHAR(200) DEFAULT NULL,
  `meta_description` TEXT DEFAULT NULL,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `sort_order` INT(11) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `parent_id` (`parent_id`),
  FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Collections table
CREATE TABLE `collections` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `meta_title` VARCHAR(200) DEFAULT NULL,
  `meta_description` TEXT DEFAULT NULL,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `sort_order` INT(11) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Products table
CREATE TABLE `products` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` INT(11) UNSIGNED DEFAULT NULL,
  `name` VARCHAR(200) NOT NULL,
  `slug` VARCHAR(200) NOT NULL,
  `sku` VARCHAR(50) DEFAULT NULL,
  `barcode` VARCHAR(50) DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `short_description` VARCHAR(500) DEFAULT NULL,
  `price` DECIMAL(10, 2) NOT NULL,
  `compare_price` DECIMAL(10, 2) DEFAULT NULL,
  `cost_price` DECIMAL(10, 2) DEFAULT NULL,
  `stock_quantity` INT(11) DEFAULT 0,
  `low_stock_threshold` INT(11) DEFAULT 5,
  `weight` DECIMAL(8, 2) DEFAULT NULL,
  `dimensions` VARCHAR(100) DEFAULT NULL,
  `badge` ENUM('none', 'new', 'hot', 'sale', 'limited') DEFAULT 'none',
  `featured` TINYINT(1) DEFAULT 0,
  `meta_title` VARCHAR(200) DEFAULT NULL,
  `meta_description` TEXT DEFAULT NULL,
  `status` ENUM('active', 'inactive', 'draft') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `sku` (`sku`),
  KEY `category_id` (`category_id`),
  KEY `featured` (`featured`),
  KEY `status` (`status`),
  FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Product Images table
CREATE TABLE `product_images` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` INT(11) UNSIGNED NOT NULL,
  `image_path` VARCHAR(255) NOT NULL,
  `alt_text` VARCHAR(200) DEFAULT NULL,
  `sort_order` INT(11) DEFAULT 0,
  `is_primary` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Product Collections (Many-to-Many)
CREATE TABLE `product_collections` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` INT(11) UNSIGNED NOT NULL,
  `collection_id` INT(11) UNSIGNED NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_collection` (`product_id`, `collection_id`),
  KEY `product_id` (`product_id`),
  KEY `collection_id` (`collection_id`),
  FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`collection_id`) REFERENCES `collections` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Product Reviews table
CREATE TABLE `product_reviews` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` INT(11) UNSIGNED NOT NULL,
  `user_id` INT(11) UNSIGNED DEFAULT NULL,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `rating` TINYINT(1) NOT NULL,
  `title` VARCHAR(200) DEFAULT NULL,
  `review` TEXT NOT NULL,
  `status` ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `user_id` (`user_id`),
  KEY `status` (`status`),
  FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Shipping Addresses table
CREATE TABLE `shipping_addresses` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) UNSIGNED NOT NULL,
  `full_name` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `address_line1` VARCHAR(200) NOT NULL,
  `address_line2` VARCHAR(200) DEFAULT NULL,
  `city` VARCHAR(100) NOT NULL,
  `state` VARCHAR(100) DEFAULT NULL,
  `postal_code` VARCHAR(20) DEFAULT NULL,
  `country` VARCHAR(100) NOT NULL DEFAULT 'UAE',
  `is_default` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Orders table
CREATE TABLE `orders` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_number` VARCHAR(50) NOT NULL,
  `user_id` INT(11) UNSIGNED DEFAULT NULL,
  `customer_email` VARCHAR(100) NOT NULL,
  `customer_name` VARCHAR(100) NOT NULL,
  `customer_phone` VARCHAR(20) NOT NULL,
  `shipping_address` TEXT NOT NULL,
  `billing_address` TEXT DEFAULT NULL,
  `subtotal` DECIMAL(10, 2) NOT NULL,
  `shipping_cost` DECIMAL(10, 2) DEFAULT 0.00,
  `tax` DECIMAL(10, 2) DEFAULT 0.00,
  `discount` DECIMAL(10, 2) DEFAULT 0.00,
  `total` DECIMAL(10, 2) NOT NULL,
  `payment_method` VARCHAR(50) NOT NULL,
  `payment_status` ENUM('pending', 'paid', 'failed', 'refunded') DEFAULT 'pending',
  `order_status` ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
  `notes` TEXT DEFAULT NULL,
  `tracking_number` VARCHAR(100) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_number` (`order_number`),
  KEY `user_id` (`user_id`),
  KEY `order_status` (`order_status`),
  KEY `payment_status` (`payment_status`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Order Items table
CREATE TABLE `order_items` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` INT(11) UNSIGNED NOT NULL,
  `product_id` INT(11) UNSIGNED DEFAULT NULL,
  `product_name` VARCHAR(200) NOT NULL,
  `product_sku` VARCHAR(50) DEFAULT NULL,
  `quantity` INT(11) NOT NULL,
  `price` DECIMAL(10, 2) NOT NULL,
  `total` DECIMAL(10, 2) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Payments table
CREATE TABLE `payments` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` INT(11) UNSIGNED NOT NULL,
  `transaction_id` VARCHAR(100) DEFAULT NULL,
  `payment_method` VARCHAR(50) NOT NULL,
  `amount` DECIMAL(10, 2) NOT NULL,
  `status` ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
  `payment_details` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Theme Settings table
CREATE TABLE `theme_settings` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `setting_key` VARCHAR(100) NOT NULL,
  `setting_value` TEXT DEFAULT NULL,
  `setting_type` ENUM('color', 'font', 'text', 'boolean', 'json') DEFAULT 'text',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Banners table
CREATE TABLE `banners` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(200) NOT NULL,
  `subtitle` VARCHAR(200) DEFAULT NULL,
  `image` VARCHAR(255) NOT NULL,
  `link_url` VARCHAR(255) DEFAULT NULL,
  `button_text` VARCHAR(50) DEFAULT NULL,
  `position` ENUM('hero', 'secondary', 'sidebar') DEFAULT 'hero',
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `sort_order` INT(11) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `position` (`position`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Pages table (CMS)
CREATE TABLE `pages` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(200) NOT NULL,
  `slug` VARCHAR(200) NOT NULL,
  `content` LONGTEXT DEFAULT NULL,
  `meta_title` VARCHAR(200) DEFAULT NULL,
  `meta_description` TEXT DEFAULT NULL,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- FAQs table
CREATE TABLE `faqs` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `question` VARCHAR(500) NOT NULL,
  `answer` TEXT NOT NULL,
  `sort_order` INT(11) DEFAULT 0,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `sort_order` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Testimonials table
CREATE TABLE `testimonials` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_name` VARCHAR(100) NOT NULL,
  `customer_title` VARCHAR(100) DEFAULT NULL,
  `testimonial` TEXT NOT NULL,
  `rating` TINYINT(1) DEFAULT 5,
  `image` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `sort_order` INT(11) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `rating` (`rating`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Email Templates table
CREATE TABLE `email_templates` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `template_key` VARCHAR(100) NOT NULL,
  `subject` VARCHAR(200) NOT NULL,
  `body` LONGTEXT NOT NULL,
  `variables` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `template_key` (`template_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin account (password: admin123)
INSERT INTO `admins` (`username`, `email`, `password`, `full_name`, `role`, `status`) VALUES
('admin', 'admin@edluxury.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin User', 'super_admin', 'active');

-- Insert default collections
INSERT INTO `collections` (`name`, `slug`, `description`, `status`, `sort_order`) VALUES
('Tech & Gadgets', 'tech-gadgets', 'Upgrade Your World with the Latest Gadgets', 'active', 1),
('Health & Beauty', 'health-beauty', 'Discover skincare and wellness products that make you feel amazing', 'active', 2),
('Home Décor', 'home-decor', 'Handpicked décor pieces designed to inspire modern living', 'active', 3),
('Home & Kitchen', 'home-kitchen', 'Discover kitchen essentials and homeware designed for modern living', 'active', 4);

-- Insert default theme settings
INSERT INTO `theme_settings` (`setting_key`, `setting_value`, `setting_type`) VALUES
('primary_color', '#2563eb', 'color'),
('secondary_color', '#7c3aed', 'color'),
('font_family', 'Inter', 'font'),
('button_style', 'rounded', 'text'),
('show_featured_products', '1', 'boolean'),
('show_testimonials', '1', 'boolean'),
('show_newsletter', '1', 'boolean'),
('site_name', 'Edluxury', 'text'),
('site_tagline', 'Your one-stop shop for effortless and enjoyable e-commerce experiences', 'text'),
('contact_email', 'info@edluxury.com', 'text'),
('contact_phone', '+971 XX XXX XXXX', 'text'),
('free_shipping_threshold', '0', 'text'),
('currency_symbol', 'AED', 'text');

-- Insert default FAQs
INSERT INTO `faqs` (`question`, `answer`, `sort_order`, `status`) VALUES
('What is Edluxury?', 'Edluxury is a UAE-based online store offering premium products across Tech & Gadgets, Health & Beauty, Home Décor, and Home & Kitchen categories.', 1, 'active'),
('Do you offer free delivery?', 'Yes, we offer Free Delivery on all orders across the UAE — no hidden fees.', 2, 'active'),
('How long does delivery take?', 'Delivery usually takes 3–7 business days depending on your location and product availability.', 3, 'active'),
('Can I track my order?', 'Once your order is shipped, you\'ll receive an email or WhatsApp update with a tracking number and delivery details.', 4, 'active'),
('What is your return policy?', 'If your item arrives damaged or defective, contact our support team within 7 days, and we\'ll arrange a free replacement or refund.', 5, 'active'),
('How can I contact customer support?', 'You can reach us anytime via email or WhatsApp. Check our Contact Us page for details.', 6, 'active');

-- Insert default testimonials
INSERT INTO `testimonials` (`customer_name`, `customer_title`, `testimonial`, `rating`, `status`, `sort_order`) VALUES
('Fatima A.', NULL, 'Excellent products and fast delivery! Love the quality.', 5, 'active', 1),
('Aisha R.', NULL, 'The service was amazing, definitely shopping again!', 5, 'active', 2),
('Omar K.', NULL, 'I got my order on time and it\'s exactly as shown!', 5, 'active', 3),
('Sara H.', NULL, 'Absolutely loved the products, thank you Edluxury!', 5, 'active', 4),
('Mohammed B.', NULL, 'Beautiful design and great customer support!', 5, 'active', 5),
('Ahmed S.', NULL, 'Edluxury always delivers on time, highly recommended.', 5, 'active', 6);

-- Insert default pages
INSERT INTO `pages` (`title`, `slug`, `content`, `status`) VALUES
('About Us', 'about-us', '<h1>About Edluxury</h1><p>Welcome to Edluxury, your trusted destination for premium products in the UAE.</p>', 'active'),
('Contact Us', 'contact-us', '<h1>Contact Us</h1><p>Get in touch with our customer support team.</p>', 'active'),
('Privacy Policy', 'privacy-policy', '<h1>Privacy Policy</h1><p>Your privacy is important to us.</p>', 'active'),
('Refund Policy', 'refund-policy', '<h1>Refund Policy</h1><p>7 Days Return & Replacement Policy</p>', 'active'),
('Shipping Policy', 'shipping-policy', '<h1>Shipping Policy</h1><p>Free delivery all over UAE</p>', 'active'),
('Terms of Service', 'terms-of-service', '<h1>Terms of Service</h1><p>Please read these terms carefully.</p>', 'active');

-- Insert email templates
INSERT INTO `email_templates` (`template_key`, `subject`, `body`, `variables`) VALUES
('order_confirmation', 'Order Confirmation - {{order_number}}', '<h1>Thank you for your order!</h1><p>Your order {{order_number}} has been received and is being processed.</p><p>Order Total: {{total}}</p>', 'order_number,total,customer_name'),
('order_shipped', 'Your Order Has Been Shipped - {{order_number}}', '<h1>Your order is on the way!</h1><p>Your order {{order_number}} has been shipped.</p><p>Tracking Number: {{tracking_number}}</p>', 'order_number,tracking_number,customer_name'),
('order_delivered', 'Your Order Has Been Delivered - {{order_number}}', '<h1>Your order has been delivered!</h1><p>We hope you enjoy your purchase from Edluxury.</p>', 'order_number,customer_name'),
('password_reset', 'Password Reset Request', '<h1>Reset Your Password</h1><p>Click the link below to reset your password:</p><p><a href="{{reset_link}}">Reset Password</a></p>', 'reset_link,customer_name');

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 27, 2026 at 06:56 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `edluxury_db`
--
CREATE DATABASE IF NOT EXISTS `edluxury_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `edluxury_db`;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `role` enum('super_admin','admin','manager') DEFAULT 'admin',
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `email`, `password`, `full_name`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@edluxury.com', '$2y$10$WIkXgMOefsnIbGqkCNbrH.OmTOL9OH2AMAqOtkKCkmrolOC8KCiZK', 'Ali Raza', 'super_admin', 'active', '2026-02-11 10:20:47', '2026-02-11 10:43:45');

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

DROP TABLE IF EXISTS `banners`;
CREATE TABLE `banners` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(200) NOT NULL,
  `subtitle` varchar(200) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `link_url` varchar(255) DEFAULT NULL,
  `button_text` varchar(50) DEFAULT NULL,
  `position` enum('hero','secondary','sidebar') DEFAULT 'hero',
  `status` enum('active','inactive') DEFAULT 'active',
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `parent_id` int(11) UNSIGNED DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `meta_title` varchar(200) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `parent_id`, `image`, `meta_title`, `meta_description`, `status`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Health & Beauty', 'health-beauty', 'Welcome to a new standard of Health & Beauty in the Edluxury.\r\n\r\nOur platform is designed to bring premium skincare, wellness, and beauty essentials directly to customers across the Emirates. We carefully curate high-quality products that meet international standards while respecting the lifestyle, climate, and preferences of the UAE market.\r\nFrom advanced skincare solutions formulated for hot and humid weather conditions to luxury beauty products inspired by both global and Middle Eastern trends, our store offers a complete personal care experience.', NULL, 'categories/698c5f99cee1d_1770807193.jpg', NULL, NULL, 'active', 2, '2026-02-11 10:53:13', '2026-02-25 08:25:36'),
(2, 'Car Accessories', 'car-accessories', 'Our platform is built to serve car enthusiasts, daily drivers, and luxury vehicle owners by offering high-quality automotive accessories designed specifically for UAE road and climate conditions.', NULL, 'categories/698c61023801d_1770807554.png', NULL, NULL, 'active', 8, '2026-02-11 10:59:14', '2026-02-11 10:59:14'),
(3, 'Best Sellers', 'best-sellers', 'These top-rated products are popular for a reason — they deliver proven performance, long-lasting durability, and modern design suited for UAE road and weather conditions.', NULL, 'categories/698c61624ecc5_1770807650.jpg', NULL, NULL, 'active', 8, '2026-02-11 11:00:50', '2026-02-11 11:00:50'),
(4, 'Tools & Gadgets', 'tools-gadgets', 'his collection features practical automotive tools and innovative gadgets that help with daily driving, maintenance, emergencies, and long-distance travel.', NULL, 'categories/698c622468850_1770807844.jpg', NULL, NULL, 'active', 3, '2026-02-11 11:04:04', '2026-02-11 11:04:04');

-- --------------------------------------------------------

--
-- Table structure for table `collections`
--

DROP TABLE IF EXISTS `collections`;
CREATE TABLE `collections` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `name_ar` varchar(255) DEFAULT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `meta_title` varchar(200) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `collections`
--

INSERT INTO `collections` (`id`, `name`, `name_ar`, `slug`, `description`, `image`, `meta_title`, `meta_description`, `status`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Tech & Gadgets', 'الأجهزة والتقنيات', 'tech-gadgets', 'Upgrade Your World with the Latest Gadgets', 'https://images.unsplash.com/photo-1550009158-9ebf69173e03?auto=format&fit=crop&q=80&w=800', NULL, NULL, 'active', 1, '2026-02-11 10:20:47', '2026-02-14 07:28:27'),
(2, 'Health & Beauty', 'الصحة والجمال', 'health-beauty', 'Discover skincare and wellness products that make you feel amazing', 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&q=80&w=800', NULL, NULL, 'active', 2, '2026-02-11 10:20:47', '2026-02-14 07:28:27'),
(3, 'Home D├®cor', 'ديكور المنزل', 'home-decor', 'Handpicked d├®cor pieces designed to inspire modern living', 'https://images.unsplash.com/photo-1513519245088-0e12902e5a38?auto=format&fit=crop&q=80&w=800', NULL, NULL, 'active', 3, '2026-02-11 10:20:47', '2026-02-14 07:28:27'),
(4, 'Home & Kitchen', 'المنزل والمطبخ', 'home-kitchen', 'Discover kitchen essentials and homeware designed for modern living', 'https://images.unsplash.com/photo-1556910103-1c02745aae4d?auto=format&fit=crop&q=80&w=800', NULL, NULL, 'active', 4, '2026-02-11 10:20:47', '2026-02-14 07:28:27');

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

DROP TABLE IF EXISTS `email_templates`;
CREATE TABLE `email_templates` (
  `id` int(11) UNSIGNED NOT NULL,
  `template_key` varchar(100) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `body` longtext NOT NULL,
  `variables` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `email_templates`
--

INSERT INTO `email_templates` (`id`, `template_key`, `subject`, `body`, `variables`, `created_at`, `updated_at`) VALUES
(1, 'order_confirmation', 'Order Confirmation - {{order_number}}', '<h1>Thank you for your order!</h1><p>Your order {{order_number}} has been received and is being processed.</p><p>Order Total: {{total}}</p>', 'order_number,total,customer_name', '2026-02-11 10:20:47', '2026-02-11 10:20:47'),
(2, 'order_shipped', 'Your Order Has Been Shipped - {{order_number}}', '<h1>Your order is on the way!</h1><p>Your order {{order_number}} has been shipped.</p><p>Tracking Number: {{tracking_number}}</p>', 'order_number,tracking_number,customer_name', '2026-02-11 10:20:47', '2026-02-11 10:20:47'),
(3, 'order_delivered', 'Your Order Has Been Delivered - {{order_number}}', '<h1>Your order has been delivered!</h1><p>We hope you enjoy your purchase from Edluxury.</p>', 'order_number,customer_name', '2026-02-11 10:20:47', '2026-02-11 10:20:47'),
(4, 'password_reset', 'Password Reset Request', '<h1>Reset Your Password</h1><p>Click the link below to reset your password:</p><p><a href=\"{{reset_link}}\">Reset Password</a></p>', 'reset_link,customer_name', '2026-02-11 10:20:47', '2026-02-11 10:20:47');

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

DROP TABLE IF EXISTS `faqs`;
CREATE TABLE `faqs` (
  `id` int(11) UNSIGNED NOT NULL,
  `question` varchar(500) NOT NULL,
  `answer` text NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `question`, `answer`, `sort_order`, `status`, `created_at`, `updated_at`) VALUES
(1, 'What is Edluxury?', 'Edluxury is a UAE-based online store offering premium products across Tech & Gadgets, Health & Beauty, Home D├®cor, and Home & Kitchen categories.', 1, 'active', '2026-02-11 10:20:47', '2026-02-11 10:20:47'),
(2, 'Do you offer free delivery?', 'Yes, we offer Free Delivery on all orders across the UAE ÔÇö no hidden fees.', 2, 'active', '2026-02-11 10:20:47', '2026-02-11 10:20:47'),
(3, 'How long does delivery take?', 'Delivery usually takes 3ÔÇô7 business days depending on your location and product availability.', 3, 'active', '2026-02-11 10:20:47', '2026-02-11 10:20:47'),
(4, 'Can I track my order?', 'Once your order is shipped, you\'ll receive an email or WhatsApp update with a tracking number and delivery details.', 4, 'active', '2026-02-11 10:20:47', '2026-02-11 10:20:47'),
(5, 'What is your return policy?', 'If your item arrives damaged or defective, contact our support team within 7 days, and we\'ll arrange a free replacement or refund.', 5, 'active', '2026-02-11 10:20:47', '2026-02-11 10:20:47'),
(6, 'How can I contact customer support?', 'You can reach us anytime via email or WhatsApp. Check our Contact Us page for details.', 6, 'active', '2026-02-11 10:20:47', '2026-02-11 10:20:47');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` int(11) UNSIGNED NOT NULL,
  `order_number` varchar(50) NOT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `customer_email` varchar(100) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_phone` varchar(20) NOT NULL,
  `shipping_address` text NOT NULL,
  `billing_address` text DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `shipping_cost` decimal(10,2) DEFAULT 0.00,
  `tax` decimal(10,2) DEFAULT 0.00,
  `discount` decimal(10,2) DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `payment_status` enum('pending','paid','failed','refunded') DEFAULT 'pending',
  `order_status` enum('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `tracking_number` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `user_id`, `customer_email`, `customer_name`, `customer_phone`, `shipping_address`, `billing_address`, `subtotal`, `shipping_cost`, `tax`, `discount`, `total`, `payment_method`, `payment_status`, `order_status`, `notes`, `tracking_number`, `created_at`, `updated_at`) VALUES
(18, 'ES20260218C15273', NULL, 'm.ali436844@gmail.com', 'Ali Raza', '+9711344998809', '{\"address_line1\":\"Dubai\",\"city\":\"Sharjah\",\"emirate\":\"Umm Al Quwain\",\"country\":\"UAE\"}', NULL, 476.97, 0.00, 0.00, 0.00, 476.97, 'cod', 'pending', 'shipped', 'kookokkokoko', 'ES20260218C15273', '2026-02-18 11:59:40', '2026-02-18 12:06:26'),
(19, 'ES2026021934B62D', NULL, 'm.ali436844@gmail.com', 'Ali Raza', '+9711344998809', '{\"address_line1\":\"Dubai\",\"city\":\"Sharjah\",\"emirate\":\"Abu Dhabi\",\"country\":\"UAE\"}', NULL, 159.99, 0.00, 0.00, 0.00, 159.99, 'cod', 'pending', 'pending', 'kokokoko', NULL, '2026-02-19 06:54:43', '2026-02-19 06:54:43'),
(20, 'ES202602196F3BEC', NULL, 'm.ali436844@gmail.com', 'Ali Raza', '+9711344998809', '{\"address_line1\":\"Dubai\",\"city\":\"Sharjah\",\"emirate\":\"Dubai\",\"country\":\"UAE\"}', NULL, 219.96, 0.00, 0.00, 0.00, 219.96, 'cod', 'pending', 'shipped', '', 'ES202602196F3BEC', '2026-02-19 07:58:46', '2026-02-19 07:59:37'),
(21, 'ES2026021917C7D7', NULL, 'hamadknetly19@gmail.com', 'Hamad Ashfaq', '+9713001066116', '{\"address_line1\":\"Dubai\",\"city\":\"Dubai\",\"emirate\":\"Dubai\",\"country\":\"UAE\"}', NULL, 50.99, 0.00, 0.00, 0.00, 50.99, 'cod', 'pending', 'delivered', '', 'ES2026021917C7D7', '2026-02-19 08:08:17', '2026-02-19 08:09:50'),
(22, 'ES20260221ECDC8F', NULL, 'm.ali436844@gmail.com', 'Ali Raza', '+9711344998809', '{\"address_line1\":\"Dubai\",\"city\":\"Sharjah\",\"emirate\":\"Sharjah\",\"country\":\"UAE\"}', NULL, 636.96, 0.00, 0.00, 0.00, 636.96, 'cod', 'pending', 'pending', '', NULL, '2026-02-21 06:06:22', '2026-02-21 06:06:22'),
(23, 'ES20260221565902', NULL, 'm.ali436844@gmail.com', 'xc vbnm,', '+9711344998809', '{\"address_line1\":\"Dubai\",\"city\":\"Sharjah\",\"emirate\":\"Sharjah\",\"country\":\"UAE\"}', NULL, 75.00, 0.00, 0.00, 0.00, 75.00, 'whatsapp', 'pending', 'pending', 'vbnm,', NULL, '2026-02-21 06:20:37', '2026-02-21 06:20:37'),
(24, 'ES2026022110195F', NULL, 'm.ali436844@gmail.com', 'Ali Raza', '+9711344998809', '{\"address_line1\":\"Dubai\",\"city\":\"Sharjah\",\"emirate\":\"Ras Al Khaimah\",\"country\":\"UAE\"}', NULL, 158.99, 0.00, 0.00, 0.00, 158.99, 'cod', 'pending', 'processing', '', ' ES2026022110195F', '2026-02-21 06:23:13', '2026-02-21 06:24:20'),
(25, 'ES202602217DC587', NULL, 'm.ali436844@gmail.com', 'Ali Raza', '+9711344998809', '{\"address_line1\":\"Dubai\",\"city\":\"Sharjah\",\"emirate\":\"Ajman\",\"country\":\"UAE\"}', NULL, 159.99, 0.00, 0.00, 0.00, 159.99, 'cod', 'pending', 'shipped', '', 'ES202602217DC587', '2026-02-21 06:45:59', '2026-02-21 06:47:03'),
(26, 'ES20260221D8D639', NULL, 'm.ali436844@gmail.com', 'Ali Raza', '+9711344998809', '{\"address_line1\":\"Dubai\",\"city\":\"Sharjah\",\"emirate\":\"Dubai\",\"country\":\"UAE\"}', NULL, 68.00, 0.00, 0.00, 0.00, 68.00, 'cod', 'pending', 'delivered', 'kook', 'ES20260221D8D639', '2026-02-21 06:52:45', '2026-02-21 06:55:56'),
(27, 'ES20260221D2F0C8', NULL, 'm.ali436844@gmail.com', 'Ali Raza', '+9711344998809', '{\"address_line1\":\"Dubai\",\"city\":\"Sharjah\",\"emirate\":\"Umm Al Quwain\",\"country\":\"UAE\"}', NULL, 319.98, 0.00, 0.00, 0.00, 319.98, 'bank_transfer', 'pending', 'pending', '', NULL, '2026-02-21 07:22:37', '2026-02-21 07:22:37'),
(28, 'ES2026022138093D', NULL, 'm.ali436844@gmail.com', 'Ali Raza', '+9711344998809', '{\"address_line1\":\"Dubai\",\"city\":\"Sharjah\",\"emirate\":\"Abu Dhabi\",\"country\":\"UAE\"}', NULL, 159.99, 0.00, 0.00, 0.00, 159.99, 'cod', 'pending', 'pending', '', NULL, '2026-02-21 07:24:03', '2026-02-21 07:24:03'),
(29, 'ES202602213E0BCC', NULL, 'm.ali436844@gmail.com', 'Ali Raza', '+9711344998809', '{\"address_line1\":\"Dubai\",\"city\":\"Sharjah\",\"emirate\":\"Ajman\",\"country\":\"UAE\"}', NULL, 159.99, 0.00, 0.00, 0.00, 159.99, 'bank_transfer', 'pending', 'pending', '', NULL, '2026-02-21 07:24:35', '2026-02-21 07:24:35'),
(30, 'ES20260221454372', NULL, 'gillayaanarshad@gmail.com', 'Ayaan Arshad Gill', '+923297265790', '{\"address_line1\":\"pakistan\",\"city\":\"lahore\",\"emirate\":\"Dubai\",\"country\":\"UAE\"}', NULL, 199.00, 0.00, 0.00, 0.00, 199.00, 'cod', 'pending', 'pending', '', NULL, '2026-02-21 09:09:56', '2026-02-21 09:09:56'),
(31, 'ES20260221ECE289', NULL, 'gmsj123@gmail.com', 'ali hayder', '+923004779460', '{\"address_line1\":\"xyz\",\"city\":\"abc\",\"emirate\":\"Dubai\",\"country\":\"UAE\"}', NULL, 151.99, 0.00, 0.00, 0.00, 151.99, 'cod', 'pending', 'pending', '', NULL, '2026-02-21 09:24:30', '2026-02-21 09:24:30'),
(32, 'ES202602224197C3', NULL, 'm.ali436844@gmail.com', 'Ali', '+9236958754', '{\"address_line1\":\"Lahore\",\"city\":\"Lahore\",\"emirate\":\"Fujairah\",\"country\":\"UAE\"}', NULL, 158.99, 0.00, 0.00, 0.00, 158.99, 'cod', 'pending', 'pending', 'Ok done', NULL, '2026-02-22 07:17:40', '2026-02-22 07:17:40'),
(33, 'ES202602247CDA59', NULL, 'm.ali436844@gmail.com', 'Ali Raza', '+9711344998809', '{\"address_line1\":\"Dubai\",\"city\":\"Sharjah\",\"emirate\":\"Fujairah\",\"country\":\"UAE\"}', NULL, 303.98, 0.00, 0.00, 0.00, 303.98, 'cod', 'pending', 'shipped', '', 'ES202602247CDA59', '2026-02-24 16:56:55', '2026-02-24 16:57:59'),
(34, 'ES20260225D22132', NULL, 'm.ali436844@gmail.com', 'Ali Raza', '+971388804425', '{\"address_line1\":\"Lhr\",\"city\":\"Lhr\",\"emirate\":\"Ajman\",\"country\":\"UAE\"}', NULL, 177.98, 0.00, 0.00, 0.00, 177.98, 'cod', 'pending', 'pending', 'Ok', NULL, '2026-02-25 08:33:17', '2026-02-25 08:33:17'),
(35, 'ES20260225F66D8E', NULL, 'm.ali436844@gmail.com', 'Ali', '+971436454848', '{\"address_line1\":\"Shhs\",\"city\":\"Shshdh\",\"emirate\":\"Ras Al Khaimah\",\"country\":\"UAE\"}', NULL, 177.98, 0.00, 0.00, 0.00, 177.98, 'cod', 'pending', 'pending', 'Agah', NULL, '2026-02-25 08:33:19', '2026-02-25 08:33:19'),
(36, 'ES20260226F99C5F', NULL, 'mt9038666@gmail.com', 'malik talha', '+923300324167', '{\"address_line1\":\"Dubai\",\"city\":\"Sharjah\",\"emirate\":\"Dubai\",\"country\":\"UAE\"}', NULL, 178.00, 0.00, 0.00, 0.00, 178.00, 'cod', 'pending', 'shipped', '', ' ES20260226F99C5F', '2026-02-26 07:43:59', '2026-02-26 07:44:53'),
(37, 'ES202602273D9AED', NULL, 'm.ali436844@gmail.com', 'ghgghghg', '+9711344998809', '{\"address_line1\":\"Dubai\",\"city\":\"Sharjah\",\"emirate\":\"Abu Dhabi\",\"country\":\"UAE\"}', NULL, 104.00, 0.00, 0.00, 0.00, 104.00, 'cod', 'pending', 'delivered', '', 'ES202602273D9AED', '2026-02-27 08:48:35', '2026-02-27 08:49:50'),
(38, 'ES202602271A4194', NULL, 'andulrehman305@gmail.com', 'Ali Raza', '+9711344998809', '{\"address_line1\":\"Dubai\",\"city\":\"Sharjah\",\"emirate\":\"Abu Dhabi\",\"country\":\"UAE\"}', NULL, 155.00, 0.00, 0.00, 0.00, 155.00, 'cod', 'pending', 'pending', '', NULL, '2026-02-27 09:56:17', '2026-02-27 09:56:17'),
(39, 'ES202602275A5964', NULL, 'andulrehman305@gmail.com', 'Abdul', '+9711344998809', '{\"address_line1\":\"Dubai\",\"city\":\"Sharjah\",\"emirate\":\"Sharjah\",\"country\":\"UAE\"}', NULL, 155.00, 0.00, 0.00, 0.00, 155.00, 'cod', 'pending', 'pending', 'oookko', NULL, '2026-02-27 09:58:45', '2026-02-27 09:58:45'),
(40, 'ES20260227B72343', NULL, 'andulrehman305@gmail.com', 'MOTAA', '+97113467906', '{\"address_line1\":\"Dubai\",\"city\":\"Sharjah\",\"emirate\":\"Sharjah\",\"country\":\"UAE\"}', NULL, 88.99, 0.00, 0.00, 0.00, 88.99, 'cod', 'pending', 'delivered', 'OK', 'ES20260227B72343', '2026-02-27 10:02:19', '2026-02-27 10:03:27');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
  `id` int(11) UNSIGNED NOT NULL,
  `order_id` int(11) UNSIGNED NOT NULL,
  `product_id` int(11) UNSIGNED DEFAULT NULL,
  `product_name` varchar(200) NOT NULL,
  `product_sku` varchar(50) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `product_sku`, `quantity`, `price`, `total`, `created_at`) VALUES
(24, 18, 11, 'Eye Massager', NULL, 3, 158.99, 476.97, '2026-02-18 11:59:40'),
(25, 19, 9, 'Neck Massager & Shoulder ', NULL, 1, 159.99, 159.99, '2026-02-19 06:54:43'),
(26, 20, 7, 'Multifunctional Peeling Knife', NULL, 4, 54.99, 219.96, '2026-02-19 07:58:47'),
(27, 21, 2, 'Vegetable Slicer', NULL, 1, 50.99, 50.99, '2026-02-19 08:08:17'),
(28, 22, 9, 'Neck Massager & Shoulder ', NULL, 1, 159.99, 159.99, '2026-02-21 06:06:22'),
(29, 22, 11, 'Eye Massager', NULL, 3, 158.99, 476.97, '2026-02-21 06:06:22'),
(30, 23, 4, 'Electric Scrubber', NULL, 1, 75.00, 75.00, '2026-02-21 06:20:37'),
(31, 24, 11, 'Eye Massager', NULL, 1, 158.99, 158.99, '2026-02-21 06:23:13'),
(32, 25, 9, 'Neck Massager & Shoulder ', NULL, 1, 159.99, 159.99, '2026-02-21 06:45:59'),
(33, 26, NULL, 'Yellow gold', NULL, 2, 34.00, 68.00, '2026-02-21 06:52:45'),
(34, 27, 9, 'Neck Massager & Shoulder ', NULL, 2, 159.99, 319.98, '2026-02-21 07:22:37'),
(35, 28, 9, 'Neck Massager & Shoulder ', NULL, 1, 159.99, 159.99, '2026-02-21 07:24:03'),
(36, 29, 9, 'Neck Massager & Shoulder ', NULL, 1, 159.99, 159.99, '2026-02-21 07:24:35'),
(37, 30, 5, 'Espresso Coffee Maker (Portable)', NULL, 1, 199.00, 199.00, '2026-02-21 09:09:56'),
(38, 31, 16, 'Slimming Body Shaper', NULL, 1, 151.99, 151.99, '2026-02-21 09:24:30'),
(39, 32, 11, 'Eye Massager', NULL, 1, 158.99, 158.99, '2026-02-22 07:17:40'),
(40, 33, 16, 'Slimming Body Shaper', NULL, 2, 151.99, 303.98, '2026-02-24 16:56:55'),
(41, 34, 20, '4 Heads Rechargeable Massage Gun', NULL, 2, 88.99, 177.98, '2026-02-25 08:33:17'),
(42, 35, 20, '4 Heads Rechargeable Massage Gun', NULL, 2, 88.99, 177.98, '2026-02-25 08:33:19'),
(43, 36, 24, '7 in 1 Facial Massager Skin Care Tools ', NULL, 1, 178.00, 178.00, '2026-02-26 07:43:59'),
(44, 37, 21, 'Animal Hair Remover Brush ', NULL, 1, 104.00, 104.00, '2026-02-27 08:48:35'),
(45, 38, 23, 'Smart Dog Multifunctional Toy ', NULL, 1, 155.00, 155.00, '2026-02-27 09:56:17'),
(46, 39, 23, 'Smart Dog Multifunctional Toy ', NULL, 1, 155.00, 155.00, '2026-02-27 09:58:45'),
(47, 40, 20, '4 Heads Rechargeable Massage Gun', NULL, 1, 88.99, 88.99, '2026-02-27 10:02:19');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `content` longtext DEFAULT NULL,
  `meta_title` varchar(200) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `title`, `slug`, `content`, `meta_title`, `meta_description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'About Us', 'about-us', '<h1>About Edluxury</h1><p>Welcome to Edluxury, your trusted destination for premium products in the UAE.</p>', NULL, NULL, 'active', '2026-02-11 10:20:47', '2026-02-11 10:20:47'),
(2, 'Contact Us', 'contact-us', '<h1>Contact Us</h1><p>Get in touch with our customer support team.</p>', NULL, NULL, 'active', '2026-02-11 10:20:47', '2026-02-11 10:20:47'),
(3, 'Privacy Policy', 'privacy-policy', '<h1>Privacy Policy</h1><p>Your privacy is important to us.</p>', NULL, NULL, 'active', '2026-02-11 10:20:47', '2026-02-11 10:20:47'),
(4, 'Refund Policy', 'refund-policy', '<h1>Refund Policy</h1><p>7 Days Return & Replacement Policy</p>', NULL, NULL, 'active', '2026-02-11 10:20:47', '2026-02-11 10:20:47'),
(5, 'Shipping Policy', 'shipping-policy', '<h1>Shipping Policy</h1><p>Free delivery all over UAE</p>', NULL, NULL, 'active', '2026-02-11 10:20:47', '2026-02-11 10:20:47'),
(6, 'Terms of Service', 'terms-of-service', '<h1>Terms of Service</h1><p>Please read these terms carefully.</p>', NULL, NULL, 'active', '2026-02-11 10:20:47', '2026-02-11 10:20:47'),
(7, 'Shipping & Delivery', 'shipping-delivery', '\r\n<div class=\"shipping-delivery\">\r\n    <div class=\"text-center mb-5\">\r\n        <div class=\"mb-3\" style=\"font-size: 3rem; color: #A69C63;\"><i class=\"bi bi-truck\"></i></div>\r\n        <h2 class=\"fw-bold\" style=\"color: #0F3D3E;\">Premium UAE Delivery</h2>\r\n        <p class=\"text-muted\">Swift, Secure, and Sophisticated. We deliver luxury to your doorstep across all 7 Emirates.</p>\r\n    </div>\r\n\r\n    <div class=\"row g-4 mb-5\">\r\n        <div class=\"col-md-6\">\r\n            <div class=\"p-4 rounded-4 h-100\" style=\"background: #f8f9fa; border-left: 5px solid #A69C63;\">\r\n                <h5 class=\"fw-bold mb-3\"><i class=\"bi bi-clock-history me-2\"></i> Delivery Timelines</h5>\r\n                <ul class=\"list-unstyled\">\r\n                    <li class=\"mb-2\"><strong>Dubai & Abu Dhabi:</strong> Within 24 Hours</li>\r\n                    <li class=\"mb-2\"><strong>Sharjah, Ajman, UAQ:</strong> Within 24 - 48 Hours</li>\r\n                    <li class=\"mb-2\"><strong>Ras Al Khaimah & Fujairah:</strong> Within 48 Hours</li>\r\n                </ul>\r\n                <p class=\"small text-muted mb-0\">*Orders placed before 2:00 PM GST are processed same-day.</p>\r\n            </div>\r\n        </div>\r\n        <div class=\"col-md-6\">\r\n            <div class=\"p-4 rounded-4 h-100\" style=\"background: #0F3D3E; color: white;\">\r\n                <h5 class=\"fw-bold mb-3 text-warning\"><i class=\"bi bi-gift me-2\"></i> Shipping Rates</h5>\r\n                <p class=\"mb-2\">At Edluxury, we believe premium service should be standard.</p>\r\n                <div class=\"d-flex justify-content-between align-items-center p-3 rounded-3\" style=\"background: rgba(255,255,255,0.1);\">\r\n                    <span class=\"fw-bold\">All Orders Across UAE</span>\r\n                    <span class=\"badge bg-warning text-dark px-3\">FREE SHIPPING</span>\r\n                </div>\r\n                <p class=\"small mt-3 mb-0 opacity-75\">No minimum spend required. No hidden fees.</p>\r\n            </div>\r\n        </div>\r\n    </div>\r\n\r\n    <div class=\"mb-5\">\r\n        <h4 class=\"fw-bold mb-4\" style=\"color: #0F3D3E;\">The Edluxury Delivery Experience</h4>\r\n        <div class=\"row g-4\">\r\n            <div class=\"col-md-4\">\r\n                <div class=\"text-center p-3\">\r\n                    <div class=\"mb-3 fs-2 text-primary\"><i class=\"bi bi-shield-check\"></i></div>\r\n                    <h6 class=\"fw-bold\">Contactless Delivery</h6>\r\n                    <p class=\"small text-muted\">Safe, professional, and discreet delivery protocols for your peace of mind.</p>\r\n                </div>\r\n            </div>\r\n            <div class=\"col-md-4\">\r\n                <div class=\"text-center p-3\">\r\n                    <div class=\"mb-3 fs-2 text-success\"><i class=\"bi bi-geo-alt\"></i></div>\r\n                    <h6 class=\"fw-bold\">Real-Time Tracking</h6>\r\n                    <p class=\"small text-muted\">Receive SMS and WhatsApp updates from the moment your order leaves our Dubai warehouse.</p>\r\n                </div>\r\n            </div>\r\n            <div class=\"col-md-4\">\r\n                <div class=\"text-center p-3\">\r\n                    <div class=\"mb-3 fs-2 text-info\"><i class=\"bi bi-box-seam\"></i></div>\r\n                    <h6 class=\"fw-bold\">Premium Packaging</h6>\r\n                    <p class=\"small text-muted\">Every item is double-boxed and padded to ensure it arrives in pristine condition.</p>\r\n                </div>\r\n            </div>\r\n        </div>\r\n    </div>\r\n\r\n    <div class=\"p-4 rounded-4 bg-light border\">\r\n        <h5 class=\"fw-bold mb-3\">Order Status & Tracking</h5>\r\n        <p>Once your order is dispatched, you will receive a tracking link via email and WhatsApp. You can also track your order directly on our website using your Order ID.</p>\r\n        <a href=\"track-order.php\" class=\"btn btn-dark rounded-pill px-4\">Track Your Order Now</a>\r\n    </div>\r\n</div>\r\n', 'Shipping & Delivery | Edluxury UAE', 'Learn about Edluxury free shipping, delivery timelines, and premium packaging across all 7 Emirates in the UAE.', 'active', '2026-02-27 08:02:47', '2026-02-27 08:02:47'),
(8, 'Returns & Refunds', 'returns-refunds', '\r\n<div class=\"returns-refunds\">\r\n    <div class=\"text-center mb-5\">\r\n        <div class=\"mb-3\" style=\"font-size: 3rem; color: #A69C63;\"><i class=\"bi bi-arrow-counterclockwise\"></i></div>\r\n        <h2 class=\"fw-bold\" style=\"color: #0F3D3E;\">Returns & Refunds Policy</h2>\r\n        <p class=\"text-muted\">Your satisfaction is our priority. If you don\'t love it, we\'ll make it right.</p>\r\n    </div>\r\n\r\n    <div class=\"card border-0 shadow-sm rounded-4 mb-5 overflow-hidden\">\r\n        <div class=\"bg-dark text-white p-4 text-center\">\r\n            <h4 class=\"fw-bold mb-0\">7-Day Hassle-Free Returns</h4>\r\n        </div>\r\n        <div class=\"card-body p-4 p-md-5\">\r\n            <p>We want you to be completely satisfied with your purchase from Edluxury. If for any reason you are not happy with your item, you can return it within <strong>7 days</strong> of delivery for a full refund or exchange.</p>\r\n            \r\n            <h5 class=\"fw-bold mt-4 mb-3\" style=\"color: #0F3D3E;\">Conditions for Returns</h5>\r\n            <div class=\"row g-3\">\r\n                <div class=\"col-md-6\">\r\n                    <div class=\"d-flex align-items-center p-3 bg-light rounded-3 mb-2\">\r\n                        <i class=\"bi bi-check-circle-fill text-success me-3\"></i>\r\n                        <span>Item must be in original condition</span>\r\n                    </div>\r\n                </div>\r\n                <div class=\"col-md-6\">\r\n                    <div class=\"d-flex align-items-center p-3 bg-light rounded-3 mb-2\">\r\n                        <i class=\"bi bi-check-circle-fill text-success me-3\"></i>\r\n                        <span>All tags and labels must be intact</span>\r\n                    </div>\r\n                </div>\r\n                <div class=\"col-md-6\">\r\n                    <div class=\"d-flex align-items-center p-3 bg-light rounded-3 mb-2\">\r\n                        <i class=\"bi bi-check-circle-fill text-success me-3\"></i>\r\n                        <span>Original packaging must be included</span>\r\n                    </div>\r\n                </div>\r\n                <div class=\"col-md-6\">\r\n                    <div class=\"d-flex align-items-center p-3 bg-light rounded-3 mb-2\">\r\n                        <i class=\"bi bi-check-circle-fill text-success me-3\"></i>\r\n                        <span>No signs of wear, usage, or damage</span>\r\n                    </div>\r\n                </div>\r\n            </div>\r\n\r\n            <h5 class=\"fw-bold mt-5 mb-3\" style=\"color: #0F3D3E;\">Non-Returnable Items</h5>\r\n            <p class=\"text-muted\">For hygiene and safety reasons, the following items cannot be returned:</p>\r\n            <ul class=\"text-muted\">\r\n                <li>Personal care items (opened)</li>\r\n                <li>Intimate apparel</li>\r\n                <li>Customized or personalized products</li>\r\n                <li>Items marked as \"Final Sale\"</li>\r\n            </ul>\r\n        </div>\r\n    </div>\r\n\r\n    <div class=\"row g-4 mb-5\">\r\n        <div class=\"col-md-6\">\r\n            <div class=\"p-4 rounded-4 h-100\" style=\"background: #f8f9fa; border-top: 5px solid #0F3D3E;\">\r\n                <h5 class=\"fw-bold mb-3\">How to Start a Return</h5>\r\n                <ol class=\"ps-3\">\r\n                    <li class=\"mb-3\">WhatsApp our concierge team at <strong>+92 349 1697043</strong> or email <strong>edluxury32@gmail.com</strong>.</li>\r\n                    <li class=\"mb-3\">Provide your Order ID and the reason for the return.</li>\r\n                    <li class=\"mb-3\">Our team will schedule a pickup within 24-48 hours.</li>\r\n                    <li class=\"mb-3\">Our courier will collect the item from your location.</li>\r\n                </ol>\r\n            </div>\r\n        </div>\r\n        <div class=\"col-md-6\">\r\n            <div class=\"p-4 rounded-4 h-100\" style=\"background: #f8f9fa; border-top: 5px solid #A69C63;\">\r\n                <h5 class=\"fw-bold mb-3\">Refund Process</h5>\r\n                <p>Once we receive and inspect your returned item, your refund will be processed via your original payment method:</p>\r\n                <ul class=\"list-unstyled\">\r\n                    <li class=\"mb-3\"><strong><i class=\"bi bi-credit-card me-2\"></i> Online Payments:</strong> Refunded to your card within 5-7 business days.</li>\r\n                    <li class=\"mb-3\"><strong><i class=\"bi bi-cash me-2\"></i> Cash on Delivery:</strong> Refunded via bank transfer or store credit (your choice) within 3-5 business days.</li>\r\n                </ul>\r\n            </div>\r\n        </div>\r\n    </div>\r\n\r\n    <div class=\"text-center p-5 rounded-4 bg-dark text-white\">\r\n        <h4 class=\"fw-bold mb-3\">Have a Question?</h4>\r\n        <p class=\"opacity-75 mb-4\">Our support team is available 24/7 to assist you with any questions regarding your return or refund.</p>\r\n        <a href=\"page.php?slug=contact-us\" class=\"btn btn-warning fw-bold rounded-pill px-5\">Contact Support</a>\r\n    </div>\r\n</div>\r\n', 'Returns & Refunds Policy | Edluxury UAE', 'Read our 7-day hassle-free return policy. We offer easy pickups and fast refunds across the UAE.', 'active', '2026-02-27 08:02:47', '2026-02-27 08:02:47');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments` (
  `id` int(11) UNSIGNED NOT NULL,
  `order_id` int(11) UNSIGNED NOT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `payment_method` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','completed','failed','refunded') DEFAULT 'pending',
  `payment_details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `transaction_id`, `payment_method`, `amount`, `status`, `payment_details`, `created_at`, `updated_at`) VALUES
(18, 18, NULL, 'cod', 476.97, 'pending', NULL, '2026-02-18 11:59:40', '2026-02-18 11:59:40'),
(19, 19, NULL, 'cod', 159.99, 'pending', NULL, '2026-02-19 06:54:43', '2026-02-19 06:54:43'),
(20, 20, NULL, 'cod', 219.96, 'pending', NULL, '2026-02-19 07:58:47', '2026-02-19 07:58:47'),
(21, 21, NULL, 'cod', 50.99, 'pending', NULL, '2026-02-19 08:08:17', '2026-02-19 08:08:17'),
(22, 22, NULL, 'cod', 636.96, 'pending', NULL, '2026-02-21 06:06:22', '2026-02-21 06:06:22'),
(23, 23, NULL, 'whatsapp', 75.00, 'pending', NULL, '2026-02-21 06:20:37', '2026-02-21 06:20:37'),
(24, 24, NULL, 'cod', 158.99, 'pending', NULL, '2026-02-21 06:23:13', '2026-02-21 06:23:13'),
(25, 25, NULL, 'cod', 159.99, 'pending', NULL, '2026-02-21 06:45:59', '2026-02-21 06:45:59'),
(26, 26, NULL, 'cod', 68.00, 'pending', NULL, '2026-02-21 06:52:45', '2026-02-21 06:52:45'),
(27, 27, NULL, 'bank_transfer', 319.98, 'pending', NULL, '2026-02-21 07:22:37', '2026-02-21 07:22:37'),
(28, 28, NULL, 'cod', 159.99, 'pending', NULL, '2026-02-21 07:24:03', '2026-02-21 07:24:03'),
(29, 29, NULL, 'bank_transfer', 159.99, 'pending', NULL, '2026-02-21 07:24:35', '2026-02-21 07:24:35'),
(30, 30, NULL, 'cod', 199.00, 'pending', NULL, '2026-02-21 09:09:56', '2026-02-21 09:09:56'),
(31, 31, NULL, 'cod', 151.99, 'pending', NULL, '2026-02-21 09:24:30', '2026-02-21 09:24:30'),
(32, 32, NULL, 'cod', 158.99, 'pending', NULL, '2026-02-22 07:17:40', '2026-02-22 07:17:40'),
(33, 33, NULL, 'cod', 303.98, 'pending', NULL, '2026-02-24 16:56:55', '2026-02-24 16:56:55'),
(34, 34, NULL, 'cod', 177.98, 'pending', NULL, '2026-02-25 08:33:17', '2026-02-25 08:33:17'),
(35, 35, NULL, 'cod', 177.98, 'pending', NULL, '2026-02-25 08:33:19', '2026-02-25 08:33:19'),
(36, 36, NULL, 'cod', 178.00, 'pending', NULL, '2026-02-26 07:43:59', '2026-02-26 07:43:59'),
(37, 37, NULL, 'cod', 104.00, 'pending', NULL, '2026-02-27 08:48:35', '2026-02-27 08:48:35'),
(38, 38, NULL, 'cod', 155.00, 'pending', NULL, '2026-02-27 09:56:17', '2026-02-27 09:56:17'),
(39, 39, NULL, 'cod', 155.00, 'pending', NULL, '2026-02-27 09:58:45', '2026-02-27 09:58:45'),
(40, 40, NULL, 'cod', 88.99, 'pending', NULL, '2026-02-27 10:02:19', '2026-02-27 10:02:19');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int(11) UNSIGNED NOT NULL,
  `category_id` int(11) UNSIGNED DEFAULT NULL,
  `name` varchar(200) NOT NULL,
  `name_ar` varchar(300) DEFAULT NULL,
  `slug` varchar(200) NOT NULL,
  `sku` varchar(50) DEFAULT NULL,
  `barcode` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `short_description` varchar(500) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `compare_price` decimal(10,2) DEFAULT NULL,
  `cost_price` decimal(10,2) DEFAULT NULL,
  `stock_quantity` int(11) DEFAULT 0,
  `low_stock_threshold` int(11) DEFAULT 5,
  `weight` decimal(8,2) DEFAULT NULL,
  `dimensions` varchar(100) DEFAULT NULL,
  `badge` enum('none','new','hot','sale','limited') DEFAULT 'none',
  `featured` tinyint(1) DEFAULT 0,
  `is_winning` tinyint(1) DEFAULT 0,
  `meta_title` varchar(200) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `status` enum('active','inactive','draft') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `video_url` varchar(500) DEFAULT NULL,
  `countdown_end` datetime DEFAULT NULL,
  `show_countdown` tinyint(1) DEFAULT 0,
  `highlights` text DEFAULT NULL,
  `show_stock_bar` tinyint(1) DEFAULT 0,
  `custom_buy_button` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `name_ar`, `slug`, `sku`, `barcode`, `description`, `short_description`, `price`, `compare_price`, `cost_price`, `stock_quantity`, `low_stock_threshold`, `weight`, `dimensions`, `badge`, `featured`, `is_winning`, `meta_title`, `meta_description`, `status`, `created_at`, `updated_at`, `video_url`, `countdown_end`, `show_countdown`, `highlights`, `show_stock_bar`, `custom_buy_button`) VALUES
(2, 4, 'Vegetable Slicer', 'آلة تقطيع الخضروات', 'http://localhost/Edluxury/products.php?category=tools-gadgets', 'AVMC-N-HAE-ZAM', NULL, 'Pull out the stand legs until they snap into place,Rotate the dial to select desired slice thickness,\r\nPlace ingredients into the chute\r\nUse the pusher to press and slice safely\r\n\r\n', 'Place ingredients into the chute\r\n\r\nUse the pusher to press and slice safely\r\n', 50.99, 48.99, NULL, 442, 5, NULL, NULL, 'new', 1, 0, NULL, NULL, 'active', '2026-02-11 12:18:46', '2026-02-19 08:08:17', NULL, NULL, 0, NULL, 0, NULL),
(4, 4, 'Electric Scrubber', 'فرشاة تنظيف كهربائية', 'electric-scrubber', 'PSHS-N-HAE-ZAM', NULL, 'PureSpin Electric Handheld Scrubber is a rechargeable electric cleaning brush designed for effortless scrubbing of bathrooms, kitchens, and other household surfaces .\r\n\r\nOverview:\r\nElectric Hand-Held Scrubber — Clearly stated on box as “ELECTRIC Hand-Held Scrubber” \r\n\r\nMultiple Brush Heads Included — Flat sponge pads and bristle brushes visible \r\n\r\nDigital Display Indicator — LED display shown on device head Rechargeable Design — USB charging cable included ', 'Product Name: PureSpin Electric Handheld Scrubber\r\nCategory: Cleaning Scrubber', 75.00, 73.00, NULL, 484, 5, NULL, NULL, 'sale', 1, 0, NULL, NULL, 'active', '2026-02-11 12:26:08', '2026-02-21 06:20:37', NULL, NULL, 0, NULL, 0, NULL),
(5, 4, 'Espresso Coffee Maker (Portable)', 'ماكينة قهوة إسبريسو (محمولة)', 'tech-gadgets', 'CECM-N-HAE-ZAM', NULL, 'This portable espresso coffee maker is a compact and rechargeable device designed for making fresh coffee anywhere. It supports coffee capsules and ground coffee, making it perfect for travel, office, camping, and daily use. With one-button operation, you can enjoy rich espresso easily on the go.', 'Rechargeable battery for wireless use\r\nCompatible with coffee capsules and ground coffee\r\nOne-button operation for easy brewing\r\nMakes rich espresso with strong pressure extraction\r\nLightweight design with premium build', 199.00, 202.99, NULL, 545, 5, NULL, NULL, 'hot', 1, 0, NULL, NULL, 'active', '2026-02-11 12:35:36', '2026-02-21 09:09:56', NULL, NULL, 0, NULL, 0, NULL),
(7, NULL, 'Multifunctional Peeling Knife', 'سكين تقشير متعدد الوظائف', 'multifunctional-peeling-knife', 'ZMJT156613602BY', NULL, 'Product information:\r\nColor classification: red green black\r\nSize: 22*6cm\r\nMaterial: PP+PS+Austenitic stainless steel\r\n', 'Peeler*1, 10.5 cm storage ', 54.99, 52.00, NULL, 194, 5, NULL, NULL, 'new', 1, 0, NULL, NULL, 'active', '2026-02-11 12:45:05', '2026-02-19 07:58:47', NULL, NULL, 0, NULL, 0, NULL),
(8, 3, 'Multifuctional steam iron', 'مكواة بخارية متعددة الوظائف', 'multifuctional-steam-iron', ' SKU: WB-UA1812', NULL, 'Wide application: This hand ironing machine can iron your clothes, flat iron your hair, or slant perm your curls. It comes with a desktop design, a fine mist function, and simple controls.\r\nStore it easily and safely: This hand ironing machine has a foldable body and an anti-scald base, which makes it easy to store in any space. The insulated bracket design also prevents your clothes and furniture from heat damage.\r\nEasy shaping and smoothing: The ironing board can reach 180 degree high temperature and the panel is crafted from thick porcelain gold, offering a secure and efficient ironing performance that saves you time and energy.\r\nEffective removal of foreign matter: This hand ironing machine cleans your clothes and hair with ease and efficiency. It also eliminates odor and ensures health and safety with its intelligent constant temperature. This reliable partner cares for your delicate life.', 'This hand ironing machine cleans your clothes and hair with ease and efficiency. It also eliminates odor and ensures health and safety with its intelligent constant temperature. This reliable partner cares for your delicate life.', 24.99, 25.99, NULL, 499, 5, NULL, NULL, 'new', 1, 0, NULL, NULL, 'active', '2026-02-12 12:37:39', '2026-02-17 12:03:34', NULL, NULL, 0, NULL, 0, NULL),
(9, 3, 'Neck Massager & Shoulder ', 'جهاز تدليك الرقبة والكتف', 'neck-massager-shoulder', 'SUNM-N-GF-ZAM', NULL, 'A lightweight and portable shoulder and neck massager designed to knead and relax muscles using dual-palm style pressure. Made with soft, skin-friendly material for a comfortable massage experience anytime, anywhere.', 'FEATURES\r\n\r\nKneading massage with dual-palm mechanism\r\n\r\nSkin-friendly, soft-touch material\r\n\r\nLightweight and easy to carry\r\n\r\nEffective for shoulder, neck, and upper-back relief\r\n\r\nSimple 5V power input for convenient use\r\n\r\nHelps reduce muscle stiffness and tension\r\n\r\nPRODUCT SPECIFICATION\r\n\r\nRated Input: 5V\r\n\r\nRated Power: 10W', 159.99, 160.00, NULL, 387, 5, NULL, NULL, 'hot', 1, 0, NULL, NULL, 'active', '2026-02-12 20:17:23', '2026-02-21 07:24:35', 'https://www.youtube.com/shorts/pmTF6gtKcn4?feature=share', NULL, 0, NULL, 0, NULL),
(11, 3, 'Eye Massager', 'جهاز مساج العين ', 'eye-massager', 'EMAT-N-GF-ZAM', NULL, 'Soothe your eyes after a long day with the Eye Massaging Tool, designed to relieve eye strain, improve relaxation, and promote better sleep. With intelligent technology and a comfortable design, it’s your perfect wellness companion for daily use.', 'Product Information\r\n\r\nCategory: Eye Care / Massage Device\r\n\r\nPower: USB Rechargeable\r\n\r\nDesign: Lightweight & Portable\r\n\r\nSuitable For: Students, office workers, travelers, and anyone experiencing eye fatigue.', 158.99, 169.99, NULL, 142, 5, NULL, NULL, 'hot', 1, 0, NULL, NULL, 'active', '2026-02-18 09:23:42', '2026-02-22 07:17:40', NULL, NULL, 0, NULL, 0, NULL),
(15, 4, 'Electric Foam Roller', 'رولة رغوة كهربائية', 'electric-foam-roller', 'ZMJM100815301AZ', NULL, 'Portable\r\nIt weighs not much and can be retracted after use, it is very easy to store and will not take up much space, also, it is convenient for you to carry with you to wherever you like for your use.\r\n\r\nGreat massager\r\nIt will give you micro-projectile touches of suitable pain, making you feel comfortable and driving away your tiredness.\r\n\r\nDurable\r\nThis product has strong durability and strength with high withstanding force and will last for a long period for your usage.', 'Material: EVA + ABS + Electronic component\r\nSize: length 30cm, diameter 8cm\r\nWeight: 0.9kg\r\n1 x Yoga column\r\n1 x USB cable\r\n1 x Manual', 349.99, 350.00, NULL, 400, 5, NULL, NULL, 'new', 0, 0, NULL, NULL, 'active', '2026-02-21 08:04:11', '2026-02-21 08:05:41', '', NULL, 0, NULL, 0, NULL),
(16, 3, 'Slimming Body Shaper', 'مشدّ الجسم للتنحيف', 'slimming-body-shaper', 'WTSS-N-FE-ZAM AED', NULL, 'This waist trimmer slimming body shaper is designed to shape the waist and tummy area while providing a sauna effect to increase sweat during workouts or daily wear. It helps flatten the abdomen, support posture, and improve body shaping for a smoother and more confident look.Waist trimmer body shaper with sauna effect\r\n\r\nHelps flatten the abdomen and shape the waistline\r\n\r\nSupports posture and provides tummy control\r\n\r\nHelps tighten skin and supports postpartum recovery\r\n\r\nComfortable, breathable, and flexible material\r\n\r\nDesigned for daily wear and workout use', 'Effect: Sauna sweat support + body shaping\r\n\r\nMaterial: Stretchable and breathable fabric\r\n\r\nFit Type: Body-hugging compression\r\n\r\nUse Area: Waist, tummy, back support\r\n\r\nSuitable For: Women', 151.99, 155.99, NULL, 347, 5, NULL, NULL, 'hot', 1, 1, NULL, NULL, 'active', '2026-02-21 08:11:50', '2026-02-26 08:07:18', 'https://youtu.be/HOikE2zumVg', NULL, 0, '', 0, ''),
(17, 2, 'Electronic Incense Burner', 'مبخرة إلكترونية', 'electronic-incense-burner', 'EIB-N-HAE-ZAM', NULL, 'Bigger Heating Space:\r\n\r\nThe upgraded model includes a larger heating area, allowing users to place more incense. This feature ensures prolonged fragrance release and a more consistent aromatic experience in your car.\r\nLock Feature for Safer Use:\r\n\r\nA new lock mechanism enhances safety by preventing accidental activation or spillage of hot incense. This feature is particularly useful in a moving vehicle, ensuring the incense burner operates only when intended.', 'Benefits:\r\nEnhanced Aromatic Experience: The larger heating space and built-in storage allow for a more prolonged and customizable incense experience.\r\nConvenience: With USB compatibility, the incense burner is easy to power and use in any vehicle equipped with a USB port.\r\nSafety: The lock feature adds an essential layer of safety, making it suitable for use while driving.\r\nDurability: Constructed with high-quality materials, the burner is built to withstand regular use and provide long-lasting', 159.99, 162.00, NULL, 250, 5, NULL, NULL, 'hot', 1, 0, NULL, NULL, 'active', '2026-02-22 16:38:08', '2026-02-22 16:38:08', 'https://www.youtube.com/shorts/eOx2hVhjHEw?feature=share', NULL, 0, NULL, 0, NULL),
(18, 2, 'Portable Mini Air Purifier', 'جهاز تنقية هواء صغير محمول', 'portable-mini-air-purifier', 'ZMQCQCQC01986-Black', NULL, '1. Usage: Powered by USB cable, connected with the charger or computer. Press the touch switch to change the fan speed and power ON/OFF.\r\n2. Automotive Clean: Start the car and the air purifier will operate automatically. There are two modes for your selection, high speed and low speed.\r\n3. Portable Design: Air purifier Size: Dia 6 * H 1.5 inch, Mini and space-saving, compact and portable for your convenience.\r\n4. The car air purifier with True HEPA(High-Efficiency Particulate Air Filter), a three-stage filtering process that eliminates up to 99% of harmful gases, smoke, bacteria, odors, dust, pollen, pet hair, and other particles from the vehicle.', 'Specifications:\r\nRated voltage: DC5V\r\nRated power: 2W\r\nProduct name: Cup-shaped car purifier\r\nFilter size: 59*19.5mm\r\nProduct size: 72*72* 145mm\r\nProduct weight: 0.5kg\r\nApplicable models: GM\r\nNegative ion concentration: 4-8 million\r\nSuggested use area: 8-20 (㎡)\r\nPackage Content:\r\n1 x Smart Car Air Purifier\r\n\r\n', 149.99, 151.99, NULL, 140, 5, NULL, NULL, 'new', 1, 0, NULL, NULL, 'active', '2026-02-22 17:19:33', '2026-02-22 17:21:59', 'https://www.youtube.com/shorts/h82V9hGxU9Y?feature=share', NULL, 0, NULL, 0, NULL),
(19, 4, 'Three-In-One Cleaning Vacuum Cleaner', 'مكنسة كهربائية للتنظيف ثلاثة في واحد', 'three-in-one-cleaning-vacuum-cleaner', 'ZMJT105641901AZ', NULL, 'Product Information:\r\nProduct Name: Smart Vacuum Cleaner\r\nCharging time: 240 to 300 minutes\r\nContinuous work: about 90min\r\nInput voltage: 5V\r\nCharging cable: USB\r\nInput current: 0.5-1A\r\nWalking mode: random mode\r\nRated power: 4W\r\nColor: black, white\r\n\r\nPacking list:\r\nVacuum cleaner *1\r\nUSB charging cable*1\r\nManual *1', 'Input voltage: 5V\r\nCharging cable: USB\r\nInput current: 0.5-1A\r\nWalking mode: random mode\r\nRated power: 4W\r\nColor: black, white\r\n', 149.99, 138.00, NULL, 250, 5, NULL, NULL, 'sale', 1, 0, NULL, NULL, 'active', '2026-02-24 19:17:27', '2026-02-24 19:19:53', 'https://www.youtube.com/shorts/V7yr1XSTsNc?feature=share', NULL, 0, NULL, 0, NULL),
(20, 1, '4 Heads Rechargeable Massage Gun', 'مسدس تدليك قابل لإعادة الشحن بأربعة رؤوس', '4-heads-rechargeable-massage-gun', '4HMG-N-GF-ZAM', NULL, 'This Rechargeable Muscle Massage Gun is designed to provide deep tissue relief and muscle recovery. With four interchangeable massage heads, it targets different muscle groups effectively. Ideal for athletes, gym users, and anyone needing relaxation after long working hours.This Rechargeable Muscle Massage Gun is designed to provide deep tissue relief and muscle recovery. With four interchangeable massage heads, it targets different muscle groups effectively. Ideal for athletes, gym users, and anyone needing relaxation after long working hours.\r\n\r\nProduct Information:\r\nInput Voltage: 5V\r\n\r\nCharging Type: Type-C\r\n\r\nPower Supply: Rechargeable Lithium Battery\r\n\r\nMotor Power: 30W\r\n\r\nMaterial: ABS Plastic\r\n\r\nProduct Weight: Approx. 550g\r\n\r\nProduct Size: 140 × 140 × 40 mm\r\n\r\nWaterproof: Non-waterproof\r\n', 'Features:\r\n4 interchangeable massage heads\r\n\r\nDeep tissue muscle relief\r\n\r\nHigh-impact percussion therapy\r\n\r\nRechargeable lithium battery\r\n\r\nType-C charging interface\r\n\r\nLightweight and ergonomic design\r\n\r\nQuick head replacement system\r\n\r\nSuitable for full body massage\r\n\r\n', 88.99, 92.00, NULL, 205, 5, NULL, NULL, 'hot', 1, 0, NULL, NULL, 'active', '2026-02-24 19:38:02', '2026-02-27 10:02:19', 'https://www.youtube.com/shorts/QmOzZD5BFGo?feature=share', NULL, 0, NULL, 0, NULL),
(21, 4, 'Animal Hair Remover Brush ', 'فرشاة إزالة شعر الحيوانات', 'animal-hair-remover-brush', 'ZMYD207827101AZ', NULL, 'Overview:\r\n\r\nPrevent flying hair do not hurt the skin\r\n\r\nLight mist essential oil evenly absorbs well\r\n\r\nUVC blue light hair combing and sterilization\r\n\r\nFine and soft needle design comfortable without pulling or tugging\r\n\r\n\r\n\r\nProduct information:\r\nMaterial: abs\r\nSpecifications: spray hair comb UV sterilization (Ceramic White), spray hair comb UV sterilization (pink),\r\nProduct Name: pet sterilization spray row hair comb\r\nPower Input: DC 5V/1A\r\nCharging duration: about 150 minutes\r\nStorage tank capacity: about 30ML\r\nApplicable: Pet dog cat Universal\r\nBattery Specification: 3.7V/300mAh', 'Charging duration: about 150 minutes\r\nStorage tank capacity: about 30ML\r\nApplicable: Pet dog cat Universal\r\nBattery Specification: 3.7V/300mAh\r\nPacking list:\r\n1* Pet Spray Massage Comb\r\n', 104.00, 107.99, NULL, 499, 5, NULL, NULL, 'new', 1, 0, NULL, NULL, 'active', '2026-02-25 07:22:37', '2026-02-27 08:48:35', 'https://youtu.be/Sjfl_AMcczA', NULL, 0, NULL, 0, NULL),
(22, 4, 'Three-in-one Portable Multi-functional Cups Pets Supplies', 'أكواب متعددة الوظائف محمولة ثلاثة في واحد - مستلزمات الحيوانات الأليفة', 'three-in-one-portable-multi-functional-cups-pets-supplies', 'ZMMY179795809IR', NULL, 'Overview:\r\n\r\n1. 3-in-1 Design: This pet water bottle features a built-in 600ml water, 100g food container, and waste bag compartment, providing all-in-one convenience for pet owners on the go.\r\n2. Built-in Filtration System: The water dispenser is equipped with a filtration system that helps remove impurities and ensures your pet has access to clean and fresh water wherever you are.\r\n3. Lockable Dispensing Button: The bottle\'s dispensing button is lockable, allowing you to control the flow of water and prevent any spills or leaks, especially during outdoor activities or hikes.\r\n4. Waste Bag Compartment: The bottle has a convenient compartment that stores waste bags, making it easy to clean up after your pet while you\'re on the move and helping you keep the environment clean and hygienic.\r\nProduct information:\r\nColor: 300ml high-temperature resistance (water grain garbage bag)\r\nSpecifications: Indigo, orange pink, turquoise, dark blue, dark green, twilight gray, milky white, Wisteria purple, cherry blossom powder\r\nMaterial: Plastic\r\nCategory: Pet tableware', 'Size information:\r\nPacking size: 8 x8x23.1cm\r\n\r\n\r\n\r\nPacking List:\r\nCup X1\r\n', 106.00, 112.00, NULL, 120, 5, NULL, NULL, 'sale', 0, 0, NULL, NULL, 'active', '2026-02-25 07:40:04', '2026-02-26 08:02:39', 'https://youtu.be/YD44tpy8cCQ', '2026-02-26 17:02:00', 1, '', 0, 'Buy Now'),
(23, 4, 'Smart Dog Multifunctional Toy ', 'لعبة ذكية متعددة الوظائف للكلاب', 'smart-dog-multifunctional-toy', 'RPMT-N-TY-ZAM', NULL, 'Smart Dog Multifunctional Toy ©– AI Voice, Dancing, Yoga, Patrol & Educational Toy for Kids\r\n\r\n✨ Overview\r\nMeet the Smart AI Robot Dog, your intelligent and playful robotic pet that moves, talks, learns, and entertains! This futuristic robotic dog is packed with interactive features, AI voice chat, educational functions, and programmable movements — making it perfect for kids, tech lovers, and anyone who enjoys smart gadgets.\r\n\r\nWith over 20+ built-in actions, this robot dog can dance, sing, do yoga, attack, patrol, and even perform push-ups! Its LED eyes and realistic movements make playtime more exciting and educational at the same time.\r\n\r\n⚡ Features\r\n🤖 AI Voice & Chat Mode – Talk, interact, and learn through smart conversation\r\n\r\n🐶 Multiple Actions – Forward, backward, turn left/right, sit, swim, shake hands, and more\r\n\r\n💃 Fun Performance – Dances to songs, tells stories, and sings music\r\n\r\n🧠 Educational Function – Teaches through interactive storytelling and motion-based learning\r\n\r\n🕹️ Programmable Actions – Create custom routines for the robot to perform automatically\r\n\r\n💡 LED Lights in Head – Animated eyes and motion lighting effects\r\n\r\n🧘 Special Moves – Somersault, yoga, gongfu, patrol, push-up, and coquetry\r\n\r\n🔋 Rechargeable Battery – Long playtime with easy USB charging', 'Specifications\r\nMaterial: High-quality ABS plastic\r\n\r\nPower: Rechargeable (USB)\r\n\r\nFunctions: AI chat, dance, education, stories, attack, yoga, patrol\r\n\r\nAge Recommendation: 3+ years\r\n\r\nColor: White with black & yellow accents\r\n\r\nPackage Includes: Robot Dog, USB Cable, User Manual\r\n\r\n', 155.00, 162.99, NULL, 198, 5, NULL, NULL, 'sale', 0, 0, NULL, NULL, 'active', '2026-02-25 07:56:12', '2026-02-27 09:58:45', 'https://youtu.be/r_cj4KYguYw', NULL, 0, NULL, 0, NULL),
(24, 1, '7 in 1 Facial Massager Skin Care Tools ', 'جهاز تدليك الوجه 7 في 1، أدوات العناية بالبشرة', '7-in-1-facial-massager-skin-care-tools', 'ZMPF1502502-Platinum-English manual', NULL, 'Overviews:\r\n【Face Clean】 When removing makeup or using a facial cleanser, The facial massager uses the principle of ion charge to suck out the oil, dust and cosmetics remaining in the pores to truly clean your face.\r\n【Red and Blue Light】Blue waves improve acne and blackheads. Red light waves can effectively penetrate the skin, activate collagen activity, and improve dark spots.\r\n【EMS Micro Current】Stimulate facial muscles through a weak current, eliminate edema, reduce wrinkles, lift the skin, and make your face firmer and more refined.\r\n【Introduction of nutrition at 45℃】The facial massager can effectively introduce the nutrition in skin care products into the skin at 45℃, which can better promote absorption and not waste your skin care products, and a warm message will bring it to the face comfortable experience.\r\n【Don’t leave the traces of years on your face】\r\nProduct information:\r\nFunction: color light, vibration, heat\r\nWhether it is portable: yes\r\nColor: silver, platinum\r\nSpecifications: English manual, Chinese manual\r\nApplicable scene: home\r\n\r\nCharging voltage: 5V==1A\r\nRated power: 2.5W\r\nBattery Model: 503040\r\nBattery parameters: 3.7V/500mAh\r\nCharging time: hours\r\nProduct size: 45* 55*151MM', 'Packing list:\r\nMain unit*1, charging cable*1, instruction manual*1, cotton pad ring*1', 178.00, 182.99, NULL, 549, 5, NULL, NULL, 'hot', 1, 0, NULL, NULL, 'active', '2026-02-25 08:08:44', '2026-02-26 08:07:04', 'https://www.youtube.com/shorts/cbRjVM_SEeM?feature=share', '2026-02-25 18:53:00', 1, '100 % Original', 0, 'Buy Now');

-- --------------------------------------------------------

--
-- Table structure for table `product_collections`
--

DROP TABLE IF EXISTS `product_collections`;
CREATE TABLE `product_collections` (
  `id` int(11) UNSIGNED NOT NULL,
  `product_id` int(11) UNSIGNED NOT NULL,
  `collection_id` int(11) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

DROP TABLE IF EXISTS `product_images`;
CREATE TABLE `product_images` (
  `id` int(11) UNSIGNED NOT NULL,
  `product_id` int(11) UNSIGNED NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `alt_text` varchar(200) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_primary` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `alt_text`, `sort_order`, `is_primary`, `created_at`) VALUES
(2, 2, 'products/698c73a62776d_1770812326.jpg', NULL, 0, 1, '2026-02-11 12:18:46'),
(3, 4, 'products/698c7560a763d_1770812768.webp', NULL, 0, 1, '2026-02-11 12:26:08'),
(4, 5, 'products/698c77986e372_1770813336.jpg', NULL, 0, 1, '2026-02-11 12:35:36'),
(5, 7, 'products/698c79d10df70_1770813905.jpg', NULL, 0, 1, '2026-02-11 12:45:05'),
(6, 8, 'products/698dc993888a3_1770899859.jpg', NULL, 0, 1, '2026-02-12 12:37:39'),
(7, 9, 'products/698e3553a487d_1770927443.webp', NULL, 0, 1, '2026-02-12 20:17:23'),
(10, 9, 'products/6990380338f81_1771059203.webp', NULL, 0, 0, '2026-02-14 08:53:23'),
(11, 9, 'products/6990386aa85e1_1771059306.jpg', NULL, 0, 0, '2026-02-14 08:55:06'),
(12, 9, 'products/69903878dcd02_1771059320.webp', NULL, 0, 0, '2026-02-14 08:55:20'),
(13, 11, 'products/6995851eb777a_1771406622.webp', NULL, 0, 1, '2026-02-18 09:23:42'),
(14, 11, 'products/6995851eb99e3_1771406622.webp', NULL, 0, 0, '2026-02-18 09:23:42'),
(16, 15, 'products/699966fb9324c_1771661051.jpg', NULL, 0, 1, '2026-02-21 08:04:11'),
(17, 15, 'products/699966fb978ab_1771661051.jpg', NULL, 0, 0, '2026-02-21 08:04:11'),
(18, 15, 'products/69996755982b6_1771661141.jpg', NULL, 0, 0, '2026-02-21 08:05:41'),
(19, 16, 'products/699968c6baea3_1771661510.jpg', NULL, 0, 1, '2026-02-21 08:11:50'),
(20, 16, 'products/699968c6c47d1_1771661510.webp', NULL, 0, 0, '2026-02-21 08:11:50'),
(21, 17, 'products/699b30f0e3e79_1771778288.webp', NULL, 0, 1, '2026-02-22 16:38:08'),
(22, 17, 'products/699b30f0e587b_1771778288.jpg', NULL, 0, 0, '2026-02-22 16:38:08'),
(23, 18, 'products/699b3aa5659ff_1771780773.jpg', NULL, 0, 1, '2026-02-22 17:19:33'),
(24, 18, 'products/699b3aa569d5d_1771780773.jpg', NULL, 0, 0, '2026-02-22 17:19:33'),
(25, 18, 'products/699b3ad8265ef_1771780824.jpg', NULL, 0, 0, '2026-02-22 17:20:24'),
(26, 19, 'products/699df947dc35e_1771960647.jpg', NULL, 0, 1, '2026-02-24 19:17:27'),
(27, 19, 'products/699df947de496_1771960647.jpg', NULL, 0, 0, '2026-02-24 19:17:27'),
(28, 19, 'products/699df9d53a90d_1771960789.jpg', NULL, 0, 0, '2026-02-24 19:19:49'),
(29, 20, 'products/699dfe1a400c4_1771961882.webp', NULL, 0, 1, '2026-02-24 19:38:02'),
(30, 20, 'products/699dfe1a432c6_1771961882.jpg', NULL, 0, 0, '2026-02-24 19:38:02'),
(31, 20, 'products/699dfef9cd0f8_1771962105.jpg', NULL, 0, 0, '2026-02-24 19:41:45'),
(32, 21, 'products/699ea33d23f2b_1772004157.jpg', NULL, 0, 1, '2026-02-25 07:22:37'),
(33, 21, 'products/699ea33d26589_1772004157.jpg', NULL, 0, 0, '2026-02-25 07:22:37'),
(34, 21, 'products/699ea36441ea8_1772004196.jpg', NULL, 0, 0, '2026-02-25 07:23:16'),
(35, 22, 'products/699ea754c2f8c_1772005204.jpg', NULL, 0, 1, '2026-02-25 07:40:04'),
(36, 22, 'products/699ea754c5f45_1772005204.jpg', NULL, 0, 0, '2026-02-25 07:40:04'),
(37, 22, 'products/699ea7903995d_1772005264.jpg', NULL, 0, 0, '2026-02-25 07:41:04'),
(38, 23, 'products/699eab1c88403_1772006172.webp', NULL, 0, 1, '2026-02-25 07:56:12'),
(39, 23, 'products/699eab1c8a0ff_1772006172.webp', NULL, 0, 0, '2026-02-25 07:56:12'),
(40, 23, 'products/699eab6530607_1772006245.webp', NULL, 0, 0, '2026-02-25 07:57:25'),
(41, 24, 'products/699eae0c2541d_1772006924.jpg', NULL, 0, 1, '2026-02-25 08:08:44'),
(42, 24, 'products/699eae0c28394_1772006924.jpg', NULL, 0, 0, '2026-02-25 08:08:44'),
(43, 24, 'products/699eae3acd672_1772006970.jpg', NULL, 0, 0, '2026-02-25 08:09:30'),
(44, 24, 'products/699eaf13588fd_1772007187.jpg', NULL, 0, 0, '2026-02-25 08:13:07');

-- --------------------------------------------------------

--
-- Table structure for table `product_reviews`
--

DROP TABLE IF EXISTS `product_reviews`;
CREATE TABLE `product_reviews` (
  `id` int(11) UNSIGNED NOT NULL,
  `product_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `rating` tinyint(1) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `review` text NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipping_addresses`
--

DROP TABLE IF EXISTS `shipping_addresses`;
CREATE TABLE `shipping_addresses` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address_line1` varchar(200) NOT NULL,
  `address_line2` varchar(200) DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `country` varchar(100) NOT NULL DEFAULT 'UAE',
  `is_default` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

DROP TABLE IF EXISTS `testimonials`;
CREATE TABLE `testimonials` (
  `id` int(11) UNSIGNED NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_title` varchar(100) DEFAULT NULL,
  `testimonial` text NOT NULL,
  `rating` tinyint(1) DEFAULT 5,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `customer_name`, `customer_title`, `testimonial`, `rating`, `image`, `status`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Fatima A.', NULL, 'Excellent products and fast delivery! Love the quality.', 5, NULL, 'active', 1, '2026-02-11 10:20:47', '2026-02-11 10:20:47'),
(2, 'Aisha R.', NULL, 'The service was amazing, definitely shopping again!', 5, NULL, 'active', 2, '2026-02-11 10:20:47', '2026-02-11 10:20:47'),
(3, 'Omar K.', NULL, 'I got my order on time and it\'s exactly as shown!', 5, NULL, 'active', 3, '2026-02-11 10:20:47', '2026-02-11 10:20:47'),
(4, 'Sara H.', NULL, 'Absolutely loved the products, thank you Edluxury!', 5, NULL, 'active', 4, '2026-02-11 10:20:47', '2026-02-11 10:20:47'),
(5, 'Mohammed B.', NULL, 'Beautiful design and great customer support!', 5, NULL, 'active', 5, '2026-02-11 10:20:47', '2026-02-11 10:20:47'),
(6, 'Ahmed S.', NULL, 'Edluxury always delivers on time, highly recommended.', 5, NULL, 'active', 6, '2026-02-11 10:20:47', '2026-02-11 10:20:47');

-- --------------------------------------------------------

--
-- Table structure for table `theme_settings`
--

DROP TABLE IF EXISTS `theme_settings`;
CREATE TABLE `theme_settings` (
  `id` int(11) UNSIGNED NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_type` enum('color','font','text','boolean','json') DEFAULT 'text',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `theme_settings`
--

INSERT INTO `theme_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `created_at`, `updated_at`) VALUES
(1, 'primary_color', '#dd24eb', 'color', '2026-02-11 10:20:47', '2026-02-11 11:04:47'),
(2, 'secondary_color', '#7c3aed', 'color', '2026-02-11 10:20:47', '2026-02-11 10:20:47'),
(3, 'font_family', 'Inter', 'font', '2026-02-11 10:20:47', '2026-02-11 10:20:47'),
(4, 'button_style', 'rounded', 'text', '2026-02-11 10:20:47', '2026-02-11 10:20:47'),
(5, 'show_featured_products', '1', 'boolean', '2026-02-11 10:20:47', '2026-02-11 10:20:47'),
(6, 'show_testimonials', '1', 'boolean', '2026-02-11 10:20:47', '2026-02-11 10:20:47'),
(7, 'show_newsletter', '1', 'boolean', '2026-02-11 10:20:47', '2026-02-11 10:20:47'),
(8, 'site_name', 'Edluxury', 'text', '2026-02-11 10:20:47', '2026-02-11 10:20:47'),
(9, 'site_tagline', 'Your one-stop shop for effortless and enjoyable e-commerce experiences', 'text', '2026-02-11 10:20:47', '2026-02-11 10:20:47'),
(10, 'contact_email', 'info@edluxury.com', 'text', '2026-02-11 10:20:47', '2026-02-11 10:20:47'),
(11, 'contact_phone', '+92 3491697043', 'text', '2026-02-11 10:20:47', '2026-02-11 11:04:47'),
(12, 'free_shipping_threshold', '0', 'text', '2026-02-11 10:20:47', '2026-02-11 10:20:47'),
(13, 'currency_symbol', 'AED', 'text', '2026-02-11 10:20:47', '2026-02-11 10:20:47'),
(24, 'logo_url', 'https://i.postimg.cc/8cXmXP1F/E.png', 'text', '2026-02-26 08:20:58', '2026-02-26 09:23:44'),
(25, 'favicon_url', '', 'text', '2026-02-26 08:20:58', '2026-02-26 08:20:58');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `email_verified` tinyint(1) DEFAULT 0,
  `reset_token` varchar(100) DEFAULT NULL,
  `reset_token_expires` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `first_name`, `last_name`, `phone`, `status`, `email_verified`, `reset_token`, `reset_token_expires`, `created_at`, `updated_at`) VALUES
(1, 'm.ali436844@gmail.com', '$2y$10$IvhBPVvrgIMUQAVTcdWmh.RsSykPmjK6nXNUwFeNO3o74Koj//vLW', 'Ali', 'Raza', '1344998809', 'active', 0, NULL, NULL, '2026-02-11 11:08:36', '2026-02-11 11:08:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status` (`status`),
  ADD KEY `position` (`position`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `collections`
--
ALTER TABLE `collections`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `email_templates`
--
ALTER TABLE `email_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `template_key` (`template_key`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status` (`status`),
  ADD KEY `sort_order` (`sort_order`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `order_status` (`order_status`),
  ADD KEY `payment_status` (`payment_status`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `featured` (`featured`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `product_collections`
--
ALTER TABLE `product_collections`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_collection` (`product_id`,`collection_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `collection_id` (`collection_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `shipping_addresses`
--
ALTER TABLE `shipping_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status` (`status`),
  ADD KEY `rating` (`rating`);

--
-- Indexes for table `theme_settings`
--
ALTER TABLE `theme_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `collections`
--
ALTER TABLE `collections`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `email_templates`
--
ALTER TABLE `email_templates`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `product_collections`
--
ALTER TABLE `product_collections`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipping_addresses`
--
ALTER TABLE `shipping_addresses`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `theme_settings`
--
ALTER TABLE `theme_settings`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `product_collections`
--
ALTER TABLE `product_collections`
  ADD CONSTRAINT `product_collections_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_collections_ibfk_2` FOREIGN KEY (`collection_id`) REFERENCES `collections` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD CONSTRAINT `product_reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `shipping_addresses`
--
ALTER TABLE `shipping_addresses`
  ADD CONSTRAINT `shipping_addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

# Edluxury - Full-Stack eCommerce Platform

A complete, production-ready eCommerce platform built with PHP and MySQL, featuring a modern Shopify-style interface, comprehensive admin panel, and all essential eCommerce functionality.

## 🌟 Features

### Customer-Facing Features
- **Modern Homepage** with hero banner, collections showcase, featured products, testimonials, and FAQs
- **Product Browsing** with collections (Tech & Gadgets, Health & Beauty, Home Décor, Home & Kitchen)
- **Advanced Product Pages** with image galleries, reviews, ratings, and related products
- **Shopping Cart** with AJAX functionality for seamless add-to-cart experience
- **Secure Checkout** with customer information and address collection
- **Order Tracking** and confirmation emails
- **Responsive Design** optimized for mobile, tablet, and desktop
- **Live Search** with instant product suggestions
- **SEO-Friendly URLs** for better search engine visibility

### Admin Panel Features
- **Dashboard** with real-time statistics (orders, revenue, products, customers)
- **Product Management** with multiple images, categories, collections, and inventory tracking
- **Order Management** with status updates and tracking numbers
- **Customer Management** with order history
- **Theme Customization** panel for colors, fonts, and layout settings
- **Content Management** for pages, FAQs, testimonials, and banners
- **Secure Authentication** with role-based access control

### Technical Features
- **MVC Architecture** for clean, maintainable code
- **PDO Database** with prepared statements for security
- **CSRF Protection** on all forms
- **Password Hashing** using bcrypt
- **Input Sanitization** and validation
- **Session Management** for cart and authentication
- **Email Notifications** for order updates
- **Image Upload & Resize** functionality
- **Pagination** for product listings
- **AJAX APIs** for cart and search

## 📋 Requirements

- **PHP** 7.4 or higher
- **MySQL** 5.7 or higher
- **Apache** with mod_rewrite enabled
- **GD Library** for image processing
- **cPanel** or VPS hosting (for deployment)

## 🚀 Installation (Local Development)

### Step 1: Clone or Download

Place the `Edluxury` folder in your web server's document root:
- **XAMPP**: `C:\xampp\htdocs\Edluxury`
- **WAMP**: `C:\wamp\www\Edluxury`
- **MAMP**: `/Applications/MAMP/htdocs/Edluxury`

### Step 2: Create Database

1. Open phpMyAdmin (http://localhost/phpmyadmin)
2. Create a new database named `edluxury_db`
3. Import the database schema:
   - Click on the `edluxury_db` database
   - Go to the "Import" tab
   - Choose file: `database/database_schema.sql`
   - Click "Go" to import

### Step 3: Configure Database Connection

Edit `config/config.php` and update the database credentials:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'edluxury_db');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### Step 4: Update Site URL

In `config/config.php`, update the site URL:

```php
define('SITE_URL', 'http://localhost/Edluxury');
```

### Step 5: Set File Permissions

Ensure the `uploads` directory is writable:
- **Windows**: Right-click → Properties → Security → Edit → Allow "Full Control"
- **Linux/Mac**: `chmod -R 755 uploads`

### Step 6: Access the Site

- **Storefront**: http://localhost/Edluxury
- **Admin Panel**: http://localhost/Edluxury/admin/login.php

**Default Admin Credentials:**
- Username: `admin`
- Password: `admin123`

⚠️ **IMPORTANT**: Change the default admin password immediately after first login!

## 🌐 Deployment to cPanel

### Step 1: Prepare Files

1. Compress the entire `Edluxury` folder into a ZIP file
2. Exclude the `database` folder from the ZIP (you'll import it separately)

### Step 2: Upload to Server

1. Log in to your cPanel
2. Go to **File Manager**
3. Navigate to `public_html` (or your domain's root directory)
4. Click **Upload** and upload the ZIP file
5. Right-click the ZIP file and select **Extract**
6. Move all files from the extracted folder to your desired location

### Step 3: Create MySQL Database

1. In cPanel, go to **MySQL Databases**
2. Create a new database (e.g., `username_edluxury`)
3. Create a new MySQL user with a strong password
4. Add the user to the database with **ALL PRIVILEGES**
5. Note down the database name, username, and password

### Step 4: Import Database

1. In cPanel, go to **phpMyAdmin**
2. Select your newly created database
3. Click the **Import** tab
4. Upload `database/database_schema.sql`
5. Click **Go** to import

### Step 5: Configure Application

1. Edit `config/config.php` via File Manager or FTP
2. Update database credentials:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'username_edluxury');
define('DB_USER', 'username_dbuser');
define('DB_PASS', 'your_strong_password');
```

3. Update site URL:

```php
define('SITE_URL', 'https://yourdomain.com');
```

4. Update email settings for order notifications:

```php
define('FROM_EMAIL', 'noreply@yourdomain.com');
define('ADMIN_EMAIL', 'admin@yourdomain.com');
```

### Step 6: Configure .htaccess

Edit `.htaccess` and update the RewriteBase:

```apache
RewriteBase /
```

If your site is in a subdirectory:

```apache
RewriteBase /subdirectory/
```

### Step 7: Set File Permissions

Using File Manager or FTP:
- Set `uploads` directory to **755**
- Set `config/config.php` to **644**

### Step 8: Enable SSL (HTTPS)

1. In cPanel, go to **SSL/TLS Status**
2. Enable **AutoSSL** for your domain
3. Wait for the certificate to be issued
4. Update `config/config.php` to use HTTPS in SITE_URL

Uncomment these lines in `.htaccess` to force HTTPS:

```apache
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

### Step 9: Test the Site

1. Visit your domain: `https://yourdomain.com`
2. Test product browsing, cart, and checkout
3. Log in to admin panel: `https://yourdomain.com/admin/login.php`
4. Change the default admin password

## 🔐 Security Recommendations

1. **Change Default Credentials**
   - Update admin password immediately
   - Use strong, unique passwords

2. **Secure config.php**
   ```bash
   chmod 644 config/config.php
   ```

3. **Enable HTTPS**
   - Always use SSL certificate
   - Force HTTPS in .htaccess

4. **Regular Backups**
   - Backup database daily
   - Backup files weekly

5. **Update Email Settings**
   - Configure SMTP for reliable email delivery
   - Test order confirmation emails

6. **File Upload Security**
   - The system only allows image uploads
   - File types are validated server-side

## 📧 Email Configuration (Optional)

For production use, configure SMTP in `config/config.php`:

```php
define('SMTP_ENABLED', true);
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'your-email@gmail.com');
define('SMTP_PASSWORD', 'your-app-password');
define('SMTP_ENCRYPTION', 'tls');
```

For Gmail, you'll need to:
1. Enable 2-factor authentication
2. Generate an App Password
3. Use the App Password in SMTP_PASSWORD

## 🎨 Theme Customization

### Via Admin Panel

1. Log in to admin panel
2. Go to **Theme Settings**
3. Customize:
   - Primary and secondary colors
   - Font family
   - Homepage sections (enable/disable)
   - Upload banners and logos

### Via Code

Edit `assets/css/main.css` to customize:
- Colors (CSS variables in `:root`)
- Fonts
- Spacing and layout
- Animations

## 📁 Project Structure

```
Edluxury/
├── admin/                  # Admin panel
│   ├── includes/          # Admin header/footer
│   ├── login.php          # Admin login
│   ├── dashboard.php      # Admin dashboard
│   └── ...                # Other admin pages
├── api/                   # API endpoints
│   ├── cart.php           # Cart API
│   └── search.php         # Search API
├── assets/                # Static assets
│   ├── css/               # Stylesheets
│   ├── js/                # JavaScript files
│   └── images/            # Static images
├── config/                # Configuration
│   └── config.php         # Main config file
├── database/              # Database files
│   └── database_schema.sql # Database structure
├── includes/              # Core includes
│   ├── models/            # Data models
│   ├── Database.php       # Database class
│   ├── Security.php       # Security utilities
│   ├── Helpers.php        # Helper functions
│   ├── Email.php          # Email class
│   ├── header.php         # Site header
│   └── footer.php         # Site footer
├── uploads/               # User uploads
│   └── products/          # Product images
├── index.php              # Homepage
├── product.php            # Product detail
├── cart.php               # Shopping cart
├── checkout.php           # Checkout page
├── collection.php         # Collection page
└── .htaccess              # Apache config
```

## 🛠️ Admin Panel Usage

### Adding Products

1. Go to **Products** → **Add New**
2. Fill in product details:
   - Name, description, price
   - SKU and barcode (optional)
   - Category and collections
   - Stock quantity
   - Product badges (New, Hot, Sale, Limited)
3. Upload product images (first image is primary)
4. Set SEO fields (meta title, description, slug)
5. Click **Save Product**

### Managing Orders

1. Go to **Orders**
2. View all orders with filters
3. Click on an order to view details
4. Update order status:
   - Pending → Processing → Shipped → Delivered
5. Add tracking number for shipped orders
6. Customers receive email notifications automatically

### Theme Customization

1. Go to **Theme Settings**
2. Customize colors using color pickers
3. Select font family from dropdown
4. Toggle homepage sections on/off
5. Upload hero banners
6. Changes apply immediately to storefront

## 🐛 Troubleshooting

### Database Connection Error

- Check database credentials in `config/config.php`
- Ensure MySQL service is running
- Verify database exists and user has permissions

### Images Not Uploading

- Check `uploads` directory permissions (755)
- Verify GD library is installed: `php -m | grep gd`
- Check `upload_max_filesize` in php.ini

### .htaccess Not Working

- Ensure mod_rewrite is enabled
- Check Apache configuration allows .htaccess
- Verify RewriteBase is correct

### Email Not Sending

- Check SMTP configuration in `config/config.php`
- Test with PHP mail() function first
- Check spam folder
- Verify firewall allows SMTP ports

## 📊 Default Data

The database comes pre-populated with:
- **Admin Account**: username `admin`, password `admin123`
- **4 Collections**: Tech & Gadgets, Health & Beauty, Home Décor, Home & Kitchen
- **6 FAQs**: Common customer questions
- **6 Testimonials**: Sample customer reviews
- **6 Static Pages**: About, Contact, Privacy Policy, Refund Policy, Shipping Policy, Terms of Service
- **Theme Settings**: Default colors and configuration

## 🔄 Updates & Maintenance

### Backup Procedure

1. **Database Backup**:
   - cPanel → phpMyAdmin → Export → SQL
   - Or use cPanel Backup Wizard

2. **Files Backup**:
   - Download entire site via FTP
   - Or use cPanel File Manager → Compress → Download

### Adding New Features

The codebase follows MVC pattern:
- **Models**: `includes/models/` - Add new data models here
- **Views**: Root directory - Add new pages here
- **Controllers**: Embedded in pages or `admin/` for admin features

## 📝 License

This is a custom-built eCommerce platform for Edluxury. All rights reserved.

## 🆘 Support

For technical support or questions:
- Check the troubleshooting section above
- Review the code comments for implementation details
- Contact your development team

## 🎉 Getting Started Checklist

- [ ] Import database schema
- [ ] Update config.php with database credentials
- [ ] Update site URL in config.php
- [ ] Set file permissions for uploads directory
- [ ] Access admin panel and change default password
- [ ] Add your first product
- [ ] Customize theme colors and settings
- [ ] Update static pages (About, Contact, etc.)
- [ ] Configure email settings
- [ ] Test checkout process
- [ ] Enable SSL certificate
- [ ] Set up regular backups

---

**Built with ❤️ for Edluxury**

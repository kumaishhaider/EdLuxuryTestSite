<?php
/**
 * Admin Header - New Simplified & Robust Layout
 */

if (!Security::isAdminLoggedIn()) {
    header('Location: ' . ADMIN_URL . '/login.php');
    exit;
}

$adminName = $_SESSION['admin_name'] ?? 'Admin';
$theme = new Theme();
$siteName = $theme->get('site_name', 'Edluxury');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Favicon -->
    <?php
    $faviconUrl = $theme->get('favicon_url');
    if ($faviconUrl) {
        $faviconPath = (strpos($faviconUrl, 'http') === 0) ? $faviconUrl : Helpers::url($faviconUrl);
    } else {
        $faviconPath = Helpers::asset('images/favicon.png');
    }
    ?>
    <link rel="icon" type="image/png" href="<?php echo $faviconPath; ?>">

    <title><?php echo isset($pageTitle) ? Security::escape($pageTitle) . ' | ' : ''; ?>Admin Panel -
        <?php echo $siteName; ?></title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Ultra-Clean CSS -->
    <style>
        :root {
            --admin-primary: #4f46e5;
            --admin-bg: #f3f4f6;
        }

        body {
            background-color: var(--admin-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar-admin {
            background: #1e293b;
            padding: 0.8rem 2rem;
        }

        .nav-link-admin {
            color: #94a3b8 !important;
            font-weight: 500;
            font-size: 0.95rem;
            padding: 0.5rem 1rem !important;
        }

        .nav-link-admin:hover,
        .nav-link-admin.active {
            color: #fff !important;
        }

        .admin-card {
            background: #fff;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            height: 100%;
        }

        .page-header {
            background: #fff;
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 2rem;
        }

        .stat-val {
            font-size: 1.8rem;
            font-weight: 700;
            color: #1e293b;
        }

        .stat-label {
            font-size: 0.85rem;
            color: #64748b;
            text-transform: uppercase;
            font-weight: 600;
        }

        .btn-primary-admin {
            background-color: var(--admin-primary);
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: 6px;
        }

        .live-badge {
            background: #fee2e2;
            color: #ef4444;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.8rem;
            border: 1px solid #fecaca;
        }
    </style>
</head>

<body>
    <!-- Top Modern Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-admin sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2"
                href="<?php echo Helpers::adminUrl('dashboard.php'); ?>">
                <i class="bi bi-cpu-fill text-primary"></i> <span>ADMIN PANEL</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="adminNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item"><a
                            class="nav-link nav-link-admin <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>"
                            href="<?php echo Helpers::adminUrl('dashboard.php'); ?>"><i
                                class="bi bi-speedometer2 me-1"></i> Dashboard</a></li>
                    <li class="nav-item"><a
                            class="nav-link nav-link-admin <?php echo basename($_SERVER['PHP_SELF']) == 'orders.php' ? 'active' : ''; ?>"
                            href="<?php echo Helpers::adminUrl('orders.php'); ?>"><i class="bi bi-cart3 me-1"></i>
                            Orders</a></li>
                    <li class="nav-item"><a
                            class="nav-link nav-link-admin <?php echo basename($_SERVER['PHP_SELF']) == 'products.php' ? 'active' : ''; ?>"
                            href="<?php echo Helpers::adminUrl('products.php'); ?>"><i class="bi bi-box-seam me-1"></i>
                            Products</a></li>
                    <li class="nav-item"><a
                            class="nav-link nav-link-admin <?php echo basename($_SERVER['PHP_SELF']) == 'categories.php' ? 'active' : ''; ?>"
                            href="<?php echo Helpers::adminUrl('categories.php'); ?>"><i class="bi bi-tags me-1"></i>
                            Categories</a></li>
                    <li class="nav-item"><a
                            class="nav-link nav-link-admin <?php echo basename($_SERVER['PHP_SELF']) == 'customers.php' ? 'active' : ''; ?>"
                            href="<?php echo Helpers::adminUrl('customers.php'); ?>"><i class="bi bi-people me-1"></i>
                            customers</a></li>
                    <li class="nav-item"><a
                            class="nav-link nav-link-admin <?php echo basename($_SERVER['PHP_SELF']) == 'theme-settings.php' ? 'active' : ''; ?>"
                            href="<?php echo Helpers::adminUrl('theme-settings.php'); ?>"><i
                                class="bi bi-sliders me-1"></i> Settings</a></li>
                </ul>
                <div class="d-flex align-items-center gap-4">
                    <div class="live-badge d-none d-md-block">
                        <i class="bi bi-circle-fill me-1 small"></i> Live Viewers: <span id="adminLiveViewers">42</span>
                    </div>
                    <div class="dropdown">
                        <a href="#"
                            class="d-flex align-items-center gap-2 text-decoration-none text-light dropdown-toggle"
                            data-bs-toggle="dropdown">
                            <span class="small fw-semibold"><?php echo Security::escape($adminName); ?></span>
                            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($adminName); ?>&background=4f46e5&color=fff"
                                class="rounded-circle" width="32" height="32">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                            <li><a class="dropdown-item py-2" href="<?php echo Helpers::adminUrl('profile.php'); ?>"><i
                                        class="bi bi-person-gear me-2"></i> My Profile</a></li>
                            <li><a class="dropdown-item py-2" href="<?php echo Helpers::url(); ?>" target="_blank"><i
                                        class="bi bi-globe me-2"></i> View Store</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item py-2 text-danger"
                                    href="<?php echo Helpers::adminUrl('logout.php'); ?>"><i
                                        class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Title Header -->
    <div class="page-header">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-bold"><?php echo $pageTitle; ?></h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Admin</a></li>
                    <li class="breadcrumb-item active"><?php echo $pageTitle; ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="container-fluid px-lg-5 pb-5">
        <?php echo Helpers::displayFlash(); ?>
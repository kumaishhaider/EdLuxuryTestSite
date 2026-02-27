<?php
/**
 * Admin Product Form (Add/Edit)
 */

require_once __DIR__ . '/../config/config.php';
Security::requireAdminLogin();

$id = $_GET['id'] ?? null;
$product = null;
$db = Database::getInstance();
$categories = $db->fetchAll("SELECT * FROM categories WHERE status = 'active'");

if ($id) {
    $product = $db->fetchOne("SELECT * FROM products WHERE id = ?", [$id]);
    if (!$product) {
        Helpers::setFlash('error', 'Product not found');
        Helpers::redirect(ADMIN_URL . '/products.php');
    }
    $pageTitle = 'Edit Product';
} else {
    $pageTitle = 'Add New Product';
}

require_once 'includes/header.php';

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Security::validateCSRFToken($_POST['csrf_token'] ?? '')) {
        Helpers::setFlash('error', 'Invalid security token');
    } else {
        // Extract and sanitize data
        $name = $_POST['name'];
        $name_ar = $_POST['name_ar'] ?? null;
        $description = $_POST['description'];
        $short_description = $_POST['short_description'];
        $price = $_POST['price'];
        $compare_price = $_POST['compare_price'] ?: null;
        $sku = $_POST['sku'];
        $stock_quantity = $_POST['stock_quantity'];
        $category_id = $_POST['category_id'] ?: null;
        $status = $_POST['status'];
        $badge = $_POST['badge'] ?? 'none';
        $featured = isset($_POST['is_featured']) ? 1 : 0;
        $is_winning = isset($_POST['is_winning']) ? 1 : 0;
        $video_url = trim($_POST['video_url'] ?? '');
        $slug = !empty($_POST['slug']) ? Helpers::generateSlug($_POST['slug']) : Helpers::generateSlug($name);

        // If this is set as winning, un-win others to maintain single winner
        if ($is_winning) {
            $db->query("UPDATE products SET is_winning = 0");
        }

        try {
            if ($id) {
                // Update query
                $sql = "UPDATE products SET 
                        category_id = ?, name = ?, name_ar = ?, slug = ?, description = ?, 
                        short_description = ?, price = ?, compare_price = ?, sku = ?, 
                        stock_quantity = ?, badge = ?, featured = ?, is_winning = ?, status = ?, 
                        video_url = ?, countdown_end = ?, show_countdown = ?, highlights = ?, 
                        show_stock_bar = ?, custom_buy_button = ?, updated_at = NOW() 
                        WHERE id = ?";
                $db->query($sql, [
                    $category_id,
                    $name,
                    $name_ar,
                    $slug,
                    $description,
                    $short_description,
                    $price,
                    $compare_price,
                    $sku,
                    $stock_quantity,
                    $badge,
                    $featured,
                    $is_winning,
                    $status,
                    $video_url,
                    $_POST['countdown_end'] ?: null,
                    isset($_POST['show_countdown']) ? 1 : 0,
                    $_POST['highlights'] ?? '',
                    isset($_POST['show_stock_bar']) ? 1 : 0,
                    $_POST['custom_buy_button'] ?? '',
                    $id
                ]);

                // Handle Primary Image Upload
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $upload = Helpers::uploadImage($_FILES['image']);
                    if ($upload['success']) {
                        $db->query("DELETE FROM product_images WHERE product_id = ? AND is_primary = 1", [$id]);
                        $db->query("INSERT INTO product_images (product_id, image_path, is_primary) VALUES (?, ?, 1)", [$id, $upload['path']]);
                    }
                }

                // Handle Gallery Images Upload
                if (isset($_FILES['gallery_images']) && !empty($_FILES['gallery_images']['name'][0])) {
                    foreach ($_FILES['gallery_images']['name'] as $key => $name) {
                        if ($_FILES['gallery_images']['error'][$key] === UPLOAD_ERR_OK) {
                            $file = [
                                'name' => $_FILES['gallery_images']['name'][$key],
                                'type' => $_FILES['gallery_images']['type'][$key],
                                'tmp_name' => $_FILES['gallery_images']['tmp_name'][$key],
                                'error' => $_FILES['gallery_images']['error'][$key],
                                'size' => $_FILES['gallery_images']['size'][$key]
                            ];
                            $upload = Helpers::uploadImage($file);
                            if ($upload['success']) {
                                $db->query("INSERT INTO product_images (product_id, image_path, is_primary) VALUES (?, ?, 0)", [$id, $upload['path']]);
                            }
                        }
                    }
                }

                // Handle Gallery Image Deletion
                if (isset($_POST['delete_images']) && is_array($_POST['delete_images'])) {
                    foreach ($_POST['delete_images'] as $imgId) {
                        $db->query("DELETE FROM product_images WHERE id = ? AND product_id = ?", [$imgId, $id]);
                    }
                }

                Helpers::setFlash('success', 'Product updated successfully');
            } else {
                // Insert query
                $sql = "INSERT INTO products (
                        category_id, name, name_ar, slug, description, short_description, 
                        price, compare_price, sku, stock_quantity, badge, featured, is_winning, 
                        status, video_url, countdown_end, show_countdown, highlights, 
                        show_stock_bar, custom_buy_button
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $db->query($sql, [
                    $category_id,
                    $name,
                    $name_ar,
                    $slug,
                    $description,
                    $short_description,
                    $price,
                    $compare_price,
                    $sku,
                    $stock_quantity,
                    $badge,
                    $featured,
                    $is_winning,
                    $status,
                    $video_url,
                    $_POST['countdown_end'] ?: null,
                    isset($_POST['show_countdown']) ? 1 : 0,
                    $_POST['highlights'] ?? '',
                    isset($_POST['show_stock_bar']) ? 1 : 0,
                    $_POST['custom_buy_button'] ?? ''
                ]);
                $newId = $db->lastInsertId();

                // Handle Primary Image Upload
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $upload = Helpers::uploadImage($_FILES['image']);
                    if ($upload['success']) {
                        $db->query("INSERT INTO product_images (product_id, image_path, is_primary) VALUES (?, ?, 1)", [$newId, $upload['path']]);
                    }
                }

                // Handle Gallery Images Upload
                if (isset($_FILES['gallery_images']) && !empty($_FILES['gallery_images']['name'][0])) {
                    foreach ($_FILES['gallery_images']['name'] as $key => $name) {
                        if ($_FILES['gallery_images']['error'][$key] === UPLOAD_ERR_OK) {
                            $file = [
                                'name' => $_FILES['gallery_images']['name'][$key],
                                'type' => $_FILES['gallery_images']['type'][$key],
                                'tmp_name' => $_FILES['gallery_images']['tmp_name'][$key],
                                'error' => $_FILES['gallery_images']['error'][$key],
                                'size' => $_FILES['gallery_images']['size'][$key]
                            ];
                            $upload = Helpers::uploadImage($file);
                            if ($upload['success']) {
                                $db->query("INSERT INTO product_images (product_id, image_path, is_primary) VALUES (?, ?, 0)", [$newId, $upload['path']]);
                            }
                        }
                    }
                }

                Helpers::setFlash('success', 'Product created successfully');
                Helpers::redirect(ADMIN_URL . '/products.php');
            }
        } catch (Exception $e) {
            Helpers::setFlash('error', 'Error saving product: ' . $e->getMessage());
        }
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>
        <?php echo $pageTitle; ?>
    </h1>
    <a href="<?php echo Helpers::adminUrl('products.php'); ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
            <?php echo Security::getCSRFInput(); ?>

            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">Product Name (English) *</label>
                        <input type="text" name="name" class="form-control" required
                            value="<?php echo Security::escape($product['name'] ?? ''); ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">اسم المنتج (Arabic Name)</label>
                        <input type="text" name="name_ar" class="form-control" dir="rtl" lang="ar"
                            placeholder="أدخل اسم المنتج بالعربية"
                            value="<?php echo Security::escape($product['name_ar'] ?? ''); ?>"
                            style="font-family: 'Noto Sans Arabic', 'Tahoma', sans-serif; font-size: 16px;">
                        <div class="form-text">Arabic product name for UAE audience. <span
                                class="text-primary">Recommended for better engagement.</span></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control"
                            rows="6"><?php echo Security::escape($product['description'] ?? ''); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Short Description</label>
                        <textarea name="short_description" class="form-control"
                            rows="2"><?php echo Security::escape($product['short_description'] ?? ''); ?></textarea>
                        <div class="form-text">Shown on simple product cards.</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Price *</label>
                            <input type="number" step="0.01" name="price" class="form-control" required
                                value="<?php echo $product['price'] ?? ''; ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Compare at Price</label>
                            <input type="number" step="0.01" name="compare_price" class="form-control"
                                value="<?php echo $product['compare_price'] ?? ''; ?>">
                            <div class="form-text">Original price before sale (optional).</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">SKU</label>
                            <input type="text" name="sku" class="form-control"
                                value="<?php echo Security::escape($product['sku'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Stock Quantity *</label>
                            <input type="number" name="stock_quantity" class="form-control" required
                                value="<?php echo $product['stock_quantity'] ?? 0; ?>">
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card bg-light mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Organization</h5>

                            <div class="mb-3">
                                <label class="form-label">Category</label>
                                <select name="category_id" class="form-select">
                                    <option value="">-- None --</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo $cat['id']; ?>" <?php echo ($product && $product['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                            <?php echo Security::escape($cat['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="active" <?php echo ($product && $product['status'] === 'active') ? 'selected' : ''; ?>>Active</option>
                                    <option value="inactive" <?php echo ($product && $product['status'] === 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                            </div>

                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured"
                                    value="1" <?php echo ($product && $product['featured']) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="is_featured">Featured Product</label>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="is_winning" id="is_winning"
                                    value="1" <?php echo ($product && (isset($product['is_winning']) && $product['is_winning'])) ? 'checked' : ''; ?>>
                                <label class="form-check-label fw-bold text-primary" for="is_winning">🏆 Winning Product
                                    Spotlight</label>
                                <div class="form-text small">Promotes this product to the hero section on homepage.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Badge</label>
                                <select name="badge" class="form-select">
                                    <option value="none" <?php echo ($product && $product['badge'] === 'none') ? 'selected' : ''; ?>>None</option>
                                    <option value="sale" <?php echo ($product && $product['badge'] === 'sale') ? 'selected' : ''; ?>>Sale</option>
                                    <option value="new" <?php echo ($product && $product['badge'] === 'new') ? 'selected' : ''; ?>>New</option>
                                    <option value="hot" <?php echo ($product && $product['badge'] === 'hot') ? 'selected' : ''; ?>>Hot</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card bg-light shadow-sm mb-3">
                        <div class="card-body">
                            <h5 class="card-title fw-bold"><i class="bi bi-image me-2 text-primary"></i>Primary Image
                            </h5>
                            <div class="mb-3">
                                <input type="file" name="image" class="form-control" accept="image/*">
                            </div>
                            <?php if ($product):
                                $img = $db->fetchOne("SELECT image_path FROM product_images WHERE product_id = ? AND is_primary = 1", [$id]);
                                if ($img): ?>
                                    <div class="mt-2 position-relative">
                                        <img src="<?php echo Helpers::upload($img['image_path']); ?>"
                                            class="img-fluid rounded border shadow-sm"
                                            style="max-height: 150px; width: 100%; object-fit: cover;">
                                    </div>
                                <?php endif; endif; ?>
                        </div>
                    </div>

                    <div class="card bg-light shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title fw-bold"><i class="bi bi-images me-2 text-primary"></i>Image Gallery
                            </h5>
                            <div class="mb-3">
                                <label class="form-label small text-muted">Add more images for this product</label>
                                <input type="file" name="gallery_images[]" class="form-control" accept="image/*"
                                    multiple>
                            </div>

                            <?php if ($product):
                                $gallery = $db->fetchAll("SELECT * FROM product_images WHERE product_id = ? AND is_primary = 0", [$id]);
                                if (!empty($gallery)): ?>
                                    <div class="row g-2 mt-2">
                                        <?php foreach ($gallery as $gImg): ?>
                                            <div class="col-4">
                                                <div class="position-relative border rounded overflow-hidden shadow-sm h-100">
                                                    <img src="<?php echo Helpers::upload($gImg['image_path']); ?>"
                                                        class="img-fluid w-100" style="aspect-ratio: 1; object-fit: cover;">
                                                    <div class="position-absolute top-0 end-0 p-1">
                                                        <div class="form-check">
                                                            <input class="form-check-input bg-danger border-danger" type="checkbox"
                                                                name="delete_images[]" value="<?php echo $gImg['id']; ?>"
                                                                id="del-<?php echo $gImg['id']; ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                        <div class="form-text x-small text-danger mt-2"><i
                                                class="bi bi-info-circle me-1"></i>Check icons and save to delete gallery
                                            images.</div>
                                    </div>
                                <?php endif; endif; ?>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="form-label">URL Slug (Optional)</label>
                        <input type="text" name="slug" class="form-control form-control-sm"
                            value="<?php echo Security::escape($product['slug'] ?? ''); ?>">
                        <div class="form-text">Leave empty to auto-generate.</div>
                    </div>

                    <!-- Marketing & Urgency Features -->
                    <div class="card shadow-sm mt-3" style="border-left: 3px solid #ff6b35;">
                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-3">
                                <i class="bi bi-lightning-charge-fill me-2" style="color:#ff6b35;"></i>Urgency &
                                Marketing
                            </h5>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Custom "Buy Now" Button Text</label>
                                <input type="text" name="custom_buy_button" class="form-control form-control-sm"
                                    placeholder="e.g. Buy Now - Express Checkout"
                                    value="<?php echo Security::escape($product['custom_buy_button'] ?? ''); ?>">
                                <div class="form-text">Personalize the call to action.</div>
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="show_stock_bar"
                                    id="show_stock_bar" value="1" <?php echo ($product && (isset($product['show_stock_bar']) && $product['show_stock_bar'])) ? 'checked' : ''; ?>>
                                <label class="form-check-label fw-bold small" for="show_stock_bar">Show Live Stock
                                    Urgency Bar</label>
                                <div class="form-text">Displays a visual progress bar for remaining stock.</div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check form-switch mb-1">
                                    <input class="form-check-input" type="checkbox" name="show_countdown"
                                        id="show_countdown" value="1" <?php echo ($product && (isset($product['show_countdown']) && $product['show_countdown'])) ? 'checked' : ''; ?>>
                                    <label class="form-check-label fw-bold small" for="show_countdown">Show Limited Time
                                        Offer Timer</label>
                                </div>
                                <label class="form-label small">Offer Ends At:</label>
                                <input type="datetime-local" name="countdown_end" class="form-control form-control-sm"
                                    value="<?php echo ($product && !empty($product['countdown_end'])) ? date('Y-m-d\TH:i', strtotime($product['countdown_end'])) : ''; ?>">
                            </div>

                            <div class="mb-0">
                                <label class="form-label small fw-bold">Product Highlights (One per line)</label>
                                <textarea name="highlights" class="form-control form-control-sm" rows="4"
                                    placeholder="• 100% Original Brand&#10;• 2 Years UAE Warranty&#10;• Fast 24h Shipping"><?php echo Security::escape($product['highlights'] ?? ''); ?></textarea>
                                <div class="form-text">Key selling points shown near the price.</div>
                            </div>
                        </div>
                    </div>

                    <!-- Video URL Card -->
                    <div class="card shadow-sm mt-3" style="border-left: 3px solid #0F3D3E;">
                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-1">
                                <i class="bi bi-play-circle-fill me-2" style="color:#0F3D3E;"></i>Product Video
                            </h5>
                            <p class="text-muted small mb-3">Paste a YouTube, Vimeo, or direct video URL to display on
                                the product page.</p>
                            <div class="mb-3">
                                <label class="form-label small">Video URL</label>
                                <input type="url" name="video_url" id="videoUrlInput"
                                    class="form-control form-control-sm"
                                    placeholder="https://www.youtube.com/watch?v=..."
                                    value="<?php echo Security::escape($product['video_url'] ?? ''); ?>">
                                <div class="form-text">Supports YouTube, Vimeo, and direct .mp4 URLs</div>
                            </div>
                            <!-- Live preview -->
                            <div id="videoPreviewWrap" style="display:none;">
                                <label class="form-label small fw-bold">Preview:</label>
                                <div class="ratio ratio-16x9 rounded overflow-hidden border">
                                    <iframe id="videoPreviewFrame" src="" allow="autoplay; encrypted-media"
                                        allowfullscreen></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        (function () {
                            const inp = document.getElementById('videoUrlInput');
                            const wrap = document.getElementById('videoPreviewWrap');
                            const frame = document.getElementById('videoPreviewFrame');

                            function getEmbedUrl(url) {
                                if (!url) return null;
                                // YouTube
                                let yt = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([\w-]{11})/);
                                if (yt) return 'https://www.youtube.com/embed/' + yt[1];
                                // Vimeo
                                let vm = url.match(/vimeo\.com\/(\d+)/);
                                if (vm) return 'https://player.vimeo.com/video/' + vm[1];
                                // Direct video - can't embed in iframe, skip preview
                                if (url.match(/\.(mp4|webm|ogg)$/i)) return url;
                                return null;
                            }

                            function updatePreview() {
                                const embedUrl = getEmbedUrl(inp.value.trim());
                                if (embedUrl) {
                                    frame.src = embedUrl;
                                    wrap.style.display = 'block';
                                } else {
                                    wrap.style.display = 'none';
                                    frame.src = '';
                                }
                            }

                            inp.addEventListener('input', updatePreview);
                            // Init on load if editing
                            if (inp.value) updatePreview();
                        })();
                    </script>
                </div>
            </div>

            <hr>
            <div class="d-flex justify-content-end gap-2">
                <a href="<?php echo Helpers::adminUrl('products.php'); ?>" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary px-4">Save Product</button>
            </div>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
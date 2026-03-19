<?php
/**
 * Product Magic Sync - Inspired by Shopify Sync
 * Edluxury - Conversion Focused
 */

require_once __DIR__ . '/../config/config.php';
Security::requireAdminLogin();

$pageTitle = 'Product Magic Sync';
require_once 'includes/header.php';

$db = Database::getInstance();
$categories = $db->fetchAll("SELECT * FROM categories WHERE status = 'active'");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold mb-1">
            <i class="bi bi-magic text-primary me-2"></i>Product Magic Sync
        </h1>
        <p class="text-muted small mb-0">Import products from Shopify or other stores with a single click.</p>
    </div>
    <a href="<?php echo Helpers::adminUrl('products.php'); ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back to Inventory
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-primary text-white py-3 px-4">
                <h5 class="mb-0 fw-bold"><i class="bi bi-link-45deg me-2"></i>Paste Product URL</h5>
            </div>
            <div class="card-body p-4">
                <form id="syncForm">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Select Source</label>
                        <div class="d-flex gap-3 mb-3">
                            <div class="flex-fill">
                                <input type="radio" class="btn-check" name="source_type" id="source_shopify" value="shopify" checked>
                                <label class="btn btn-outline-primary w-100 py-3 fw-bold" for="source_shopify">
                                    <i class="bi bi-shop me-2"></i>SHOPIFY
                                </label>
                            </div>
                            <div class="flex-fill">
                                <input type="radio" class="btn-check" name="source_type" id="source_cj" value="cj">
                                <label class="btn btn-outline-primary w-100 py-3 fw-bold" for="source_cj">
                                    <i class="bi bi-box-seam me-2"></i>CJ DROPSHIPPING
                                </label>
                            </div>
                        </div>

                        <div id="urlInputContainer">
                            <label class="form-label fw-bold" id="urlLabel">Product URL</label>
                            <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden">
                                <span class="input-group-text border-0 bg-light"><i class="bi bi-globe"></i></span>
                                <input type="url" id="productUrl" class="form-control border-0 bg-light" 
                                    placeholder="Paste product URL here..." required>
                                <button class="btn btn-primary px-4 fw-bold" type="submit" id="syncBtn">
                                    <i class="bi bi-lightning-fill me-2"></i>START SYNC
                                </button>
                            </div>
                        </div>

                        <!-- CJ Settings (Hidden by default) -->
                        <div id="cjSettings" class="mt-3 p-3 border rounded-3 bg-light" style="display:none;">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label small fw-bold text-primary mb-0">
                                    <i class="bi bi-key-fill me-1"></i> CJ Dropshipping API Key
                                </label>
                                <a href="https://cjdropshipping.com/myCJ.html#/authorization/API" target="_blank" class="small text-decoration-none" style="font-size: 11px;">
                                    <i class="bi bi-question-circle me-1"></i>Where to get?
                                </a>
                            </div>
                            <input type="text" id="cjApiKey" class="form-control form-control-sm border-0 shadow-sm" 
                                placeholder="Enter your CJ API Key from 'My CJ > Authorization > API'" 
                                value="<?php echo $_SESSION['cj_api_key'] ?? ''; ?>">
                            <div class="form-text x-small">Required for syncing from CJ Dropshipping. Code is encrypted and saved for this session.</div>
                        </div>
                    </div>

                    <div class="sync-options bg-light p-3 rounded-3 mb-4">
                        <h6 class="fw-bold mb-3 small text-uppercase text-muted">Import Settings</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Assign to Category</label>
                                <select id="category_id" class="form-select border-0 shadow-sm">
                                    <option value="">-- None --</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo $cat['id']; ?>">
                                            <?php echo Security::escape($cat['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Stock Quantity</label>
                                <input type="number" id="stock_quantity" class="form-control border-0 shadow-sm" value="100">
                            </div>
                        </div>
                    </div>

                    <!-- Progress Indicator (Hidden by default) -->
                    <div id="syncProgress" style="display:none;">
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status"></div>
                            <h5 class="fw-bold" id="statusMsg">Analysing Product URL...</h5>
                            <p class="text-muted small" id="subStatusMsg">This takes about 5-10 seconds depending on image count.</p>
                            
                            <div class="progress mt-4" style="height: 10px; border-radius: 50px;">
                                <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Result Card (Hidden by default) -->
                    <div id="syncResult" style="display:none;" class="mt-4 p-4 rounded-4 border-start border-4 border-success bg-success-subtle">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-success text-white p-2" style="width:40px;height:40px;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-check-lg fs-4"></i>
                            </div>
                            <div>
                                <h5 class="mb-1 fw-bold text-success">Import Successful!</h5>
                                <p class="mb-0 text-dark small" id="resultMsg"></p>
                            </div>
                            <a href="#" id="editBtn" class="btn btn-dark ms-auto fw-bold px-4">EDIT PRODUCT</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-question-circle text-primary me-2"></i>How it works?</h5>
                <ul class="list-unstyled mb-0">
                    <li class="mb-3 d-flex gap-2">
                        <i class="bi bi-1-circle-fill text-primary"></i>
                        <span class="small">Paste any Shopify product URL into the box.</span>
                    </li>
                    <li class="mb-3 d-flex gap-2">
                        <i class="bi bi-2-circle-fill text-primary"></i>
                        <span class="small">Our AI crawler fetches the official JSON metadata.</span>
                    </li>
                    <li class="mb-3 d-flex gap-2">
                        <i class="bi bi-3-circle-fill text-primary"></i>
                        <span class="small">Images are downloaded and optimized for your server.</span>
                    </li>
                    <li class="mb-0 d-flex gap-2">
                        <i class="bi bi-check-circle-fill text-success"></i>
                        <span class="small fw-bold">The product is ready for UAE consumers!</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 bg-dark text-white">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3 opacity-75 text-uppercase small">Pro Tip</h6>
                <p class="small mb-0">Use this to quickly benchmark against competitors in the UAE. After syncing, make sure to add <span class="text-primary fw-bold">Arabic Translations</span> and locally optimized pricing to increase conversions.</p>
            </div>
        </div>
    </div>
</div>

<script>
// Source Toggle Logic
document.querySelectorAll('input[name="source_type"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const cjSettings = document.getElementById('cjSettings');
        const urlLabel = document.getElementById('urlLabel');
        const urlInput = document.getElementById('productUrl');
        
        if (this.value === 'cj') {
            cjSettings.style.display = 'block';
            urlLabel.innerText = 'CJ Product URL or ID';
            urlInput.placeholder = 'e.g. https://cjdropshipping.com/product/... or Product ID';
        } else {
            cjSettings.style.display = 'none';
            urlLabel.innerText = 'Shopify Product URL';
            urlInput.placeholder = 'https://competitor.com/products/luxury-watch';
        }
    });
});

document.getElementById('syncForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const urlInput = document.getElementById('productUrl');
    const syncBtn = document.getElementById('syncBtn');
    const syncProgress = document.getElementById('syncProgress');
    const syncResult = document.getElementById('syncResult');
    const statusMsg = document.getElementById('statusMsg');
    const progressBar = document.getElementById('progressBar');
    const sourceType = document.querySelector('input[name="source_type"]:checked').value;
    
    // UI Feedback
    syncBtn.disabled = true;
    syncProgress.style.display = 'block';
    syncResult.style.display = 'none';
    progressBar.style.width = '10%';
    
    const formData = {
        url: urlInput.value,
        category_id: document.getElementById('category_id').value,
        stock_quantity: document.getElementById('stock_quantity').value,
        api_key: document.getElementById('cjApiKey')?.value || ''
    };

    try {
        statusMsg.innerText = `Connecting to ${sourceType === 'cj' ? 'CJ Dropshipping' : 'Source Store'}...`;
        progressBar.style.width = '30%';

        const endpoint = sourceType === 'cj' ? 'sync-cj-product.php' : 'sync-shopify-product.php';
        const response = await fetch(`${SITE_URL}/api/${endpoint}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(formData)
        });

        const data = await response.json();
        
        if (data.success) {
            progressBar.style.width = '100%';
            statusMsg.innerText = 'Finalising product entries...';
            
            setTimeout(() => {
                syncProgress.style.display = 'none';
                syncResult.style.display = 'block';
                document.getElementById('resultMsg').innerText = `"${data.product_name}" has been added to your inventory.`;
                document.getElementById('editBtn').href = `${ADMIN_URL}/product-form.php?id=${data.product_id}`;
                syncBtn.disabled = false;
            }, 1000);
        } else {
            throw new Error(data.message || 'Sync failed');
        }
    } catch (error) {
        alert('Error: ' + error.message);
        syncProgress.style.display = 'none';
        syncBtn.disabled = false;
    }
});
</script>

<?php require_once 'includes/footer.php'; ?>

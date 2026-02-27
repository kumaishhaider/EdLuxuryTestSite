<?php
require_once 'c:/xampp/htdocs/Edluxury/config/config.php';
$db = Database::getInstance();

$shipping_content = '
<div class="shipping-delivery">
    <div class="text-center mb-5">
        <div class="mb-3" style="font-size: 3rem; color: #A69C63;"><i class="bi bi-truck"></i></div>
        <h2 class="fw-bold" style="color: #0F3D3E;">Premium UAE Delivery</h2>
        <p class="text-muted">Swift, Secure, and Sophisticated. We deliver luxury to your doorstep across all 7 Emirates.</p>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <div class="p-4 rounded-4 h-100" style="background: #f8f9fa; border-left: 5px solid #A69C63;">
                <h5 class="fw-bold mb-3"><i class="bi bi-clock-history me-2"></i> Delivery Timelines</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><strong>Dubai & Abu Dhabi:</strong> Within 24 Hours</li>
                    <li class="mb-2"><strong>Sharjah, Ajman, UAQ:</strong> Within 24 - 48 Hours</li>
                    <li class="mb-2"><strong>Ras Al Khaimah & Fujairah:</strong> Within 48 Hours</li>
                </ul>
                <p class="small text-muted mb-0">*Orders placed before 2:00 PM GST are processed same-day.</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="p-4 rounded-4 h-100" style="background: #0F3D3E; color: white;">
                <h5 class="fw-bold mb-3 text-warning"><i class="bi bi-gift me-2"></i> Shipping Rates</h5>
                <p class="mb-2">At Edluxury, we believe premium service should be standard.</p>
                <div class="d-flex justify-content-between align-items-center p-3 rounded-3" style="background: rgba(255,255,255,0.1);">
                    <span class="fw-bold">All Orders Across UAE</span>
                    <span class="badge bg-warning text-dark px-3">FREE SHIPPING</span>
                </div>
                <p class="small mt-3 mb-0 opacity-75">No minimum spend required. No hidden fees.</p>
            </div>
        </div>
    </div>

    <div class="mb-5">
        <h4 class="fw-bold mb-4" style="color: #0F3D3E;">The Edluxury Delivery Experience</h4>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="text-center p-3">
                    <div class="mb-3 fs-2 text-primary"><i class="bi bi-shield-check"></i></div>
                    <h6 class="fw-bold">Contactless Delivery</h6>
                    <p class="small text-muted">Safe, professional, and discreet delivery protocols for your peace of mind.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-3">
                    <div class="mb-3 fs-2 text-success"><i class="bi bi-geo-alt"></i></div>
                    <h6 class="fw-bold">Real-Time Tracking</h6>
                    <p class="small text-muted">Receive SMS and WhatsApp updates from the moment your order leaves our Dubai warehouse.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-3">
                    <div class="mb-3 fs-2 text-info"><i class="bi bi-box-seam"></i></div>
                    <h6 class="fw-bold">Premium Packaging</h6>
                    <p class="small text-muted">Every item is double-boxed and padded to ensure it arrives in pristine condition.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="p-4 rounded-4 bg-light border">
        <h5 class="fw-bold mb-3">Order Status & Tracking</h5>
        <p>Once your order is dispatched, you will receive a tracking link via email and WhatsApp. You can also track your order directly on our website using your Order ID.</p>
        <a href="track-order.php" class="btn btn-dark rounded-pill px-4">Track Your Order Now</a>
    </div>
</div>
';

$returns_content = '
<div class="returns-refunds">
    <div class="text-center mb-5">
        <div class="mb-3" style="font-size: 3rem; color: #A69C63;"><i class="bi bi-arrow-counterclockwise"></i></div>
        <h2 class="fw-bold" style="color: #0F3D3E;">Returns & Refunds Policy</h2>
        <p class="text-muted">Your satisfaction is our priority. If you don\'t love it, we\'ll make it right.</p>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-5 overflow-hidden">
        <div class="bg-dark text-white p-4 text-center">
            <h4 class="fw-bold mb-0">7-Day Hassle-Free Returns</h4>
        </div>
        <div class="card-body p-4 p-md-5">
            <p>We want you to be completely satisfied with your purchase from Edluxury. If for any reason you are not happy with your item, you can return it within <strong>7 days</strong> of delivery for a full refund or exchange.</p>
            
            <h5 class="fw-bold mt-4 mb-3" style="color: #0F3D3E;">Conditions for Returns</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="d-flex align-items-center p-3 bg-light rounded-3 mb-2">
                        <i class="bi bi-check-circle-fill text-success me-3"></i>
                        <span>Item must be in original condition</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center p-3 bg-light rounded-3 mb-2">
                        <i class="bi bi-check-circle-fill text-success me-3"></i>
                        <span>All tags and labels must be intact</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center p-3 bg-light rounded-3 mb-2">
                        <i class="bi bi-check-circle-fill text-success me-3"></i>
                        <span>Original packaging must be included</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center p-3 bg-light rounded-3 mb-2">
                        <i class="bi bi-check-circle-fill text-success me-3"></i>
                        <span>No signs of wear, usage, or damage</span>
                    </div>
                </div>
            </div>

            <h5 class="fw-bold mt-5 mb-3" style="color: #0F3D3E;">Non-Returnable Items</h5>
            <p class="text-muted">For hygiene and safety reasons, the following items cannot be returned:</p>
            <ul class="text-muted">
                <li>Personal care items (opened)</li>
                <li>Intimate apparel</li>
                <li>Customized or personalized products</li>
                <li>Items marked as "Final Sale"</li>
            </ul>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <div class="p-4 rounded-4 h-100" style="background: #f8f9fa; border-top: 5px solid #0F3D3E;">
                <h5 class="fw-bold mb-3">How to Start a Return</h5>
                <ol class="ps-3">
                    <li class="mb-3">WhatsApp our concierge team at <strong>+92 349 1697043</strong> or email <strong>edluxury32@gmail.com</strong>.</li>
                    <li class="mb-3">Provide your Order ID and the reason for the return.</li>
                    <li class="mb-3">Our team will schedule a pickup within 24-48 hours.</li>
                    <li class="mb-3">Our courier will collect the item from your location.</li>
                </ol>
            </div>
        </div>
        <div class="col-md-6">
            <div class="p-4 rounded-4 h-100" style="background: #f8f9fa; border-top: 5px solid #A69C63;">
                <h5 class="fw-bold mb-3">Refund Process</h5>
                <p>Once we receive and inspect your returned item, your refund will be processed via your original payment method:</p>
                <ul class="list-unstyled">
                    <li class="mb-3"><strong><i class="bi bi-credit-card me-2"></i> Online Payments:</strong> Refunded to your card within 5-7 business days.</li>
                    <li class="mb-3"><strong><i class="bi bi-cash me-2"></i> Cash on Delivery:</strong> Refunded via bank transfer or store credit (your choice) within 3-5 business days.</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="text-center p-5 rounded-4 bg-dark text-white">
        <h4 class="fw-bold mb-3">Have a Question?</h4>
        <p class="opacity-75 mb-4">Our support team is available 24/7 to assist you with any questions regarding your return or refund.</p>
        <a href="page.php?slug=contact-us" class="btn btn-warning fw-bold rounded-pill px-5">Contact Support</a>
    </div>
</div>
';

$pages = [
    [
        'title' => 'Shipping & Delivery',
        'slug' => 'shipping-delivery',
        'content' => $shipping_content,
        'meta_title' => 'Shipping & Delivery | Edluxury UAE',
        'meta_description' => 'Learn about Edluxury free shipping, delivery timelines, and premium packaging across all 7 Emirates in the UAE.',
        'status' => 'active'
    ],
    [
        'title' => 'Returns & Refunds',
        'slug' => 'returns-refunds',
        'content' => $returns_content,
        'meta_title' => 'Returns & Refunds Policy | Edluxury UAE',
        'meta_description' => 'Read our 7-day hassle-free return policy. We offer easy pickups and fast refunds across the UAE.',
        'status' => 'active'
    ]
];

foreach ($pages as $p) {
    // Check if exists
    $exists = $db->fetchOne("SELECT id FROM pages WHERE slug = ?", [$p['slug']]);
    if ($exists) {
        $db->update("pages", [
            'title' => $p['title'],
            'content' => $p['content'],
            'meta_title' => $p['meta_title'],
            'meta_description' => $p['meta_description'],
            'status' => $p['status'],
            'updated_at' => date('Y-m-d H:i:s')
        ], "id = " . $exists['id']);
        echo "Updated slug: " . $p['slug'] . "\n";
    } else {
        $db->insert("pages", $p);
        echo "Inserted slug: " . $p['slug'] . "\n";
    }
}

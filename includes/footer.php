</main>
<!-- End Main Content -->

<!-- =====================================================
     PREMIUM EDLUXURY FOOTER - Fully Responsive
     ===================================================== -->
<style>
    /* ============================================
   FOOTER REDESIGN - Premium Edition
   ============================================ */

    /* Footer wave divider */
    .footer-wave {
        display: block;
        overflow: hidden;
        line-height: 0;
        background: var(--sh-gray-50);
    }

    .footer-wave svg {
        display: block;
        width: 100%;
        height: 60px;
    }

    @media (max-width: 576px) {
        .footer-wave svg {
            height: 35px;
        }
    }

    /* Main footer wrapper */
    .edl-footer {
        background: linear-gradient(160deg, #0A0F1A 0%, #121826 60%, #0d1829 100%);
        color: rgba(255, 255, 255, 0.75);
        font-size: 15px;
        position: relative;
        overflow: hidden;
    }

    /* Decorative background orbs */
    .edl-footer::before {
        content: '';
        position: absolute;
        top: -120px;
        left: -120px;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(212, 175, 55, 0.06) 0%, transparent 70%);
        pointer-events: none;
        z-index: 0;
    }

    .edl-footer::after {
        content: '';
        position: absolute;
        bottom: 80px;
        right: -80px;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(197, 160, 89, 0.05) 0%, transparent 70%);
        pointer-events: none;
        z-index: 0;
    }

    .edl-footer>* {
        position: relative;
        z-index: 1;
    }

    /* ---- Trust Bar ---- */
    .edl-trust-bar {
        border-bottom: 1px solid rgba(255, 255, 255, 0.07);
        padding: 18px 0;
    }

    .edl-trust-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 12px;
        border-radius: 10px;
        transition: background 0.3s ease;
        white-space: nowrap;
    }

    .edl-trust-item:hover {
        background: rgba(255, 255, 255, 0.05);
    }

    .edl-trust-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, rgba(212, 175, 55, 0.2) 0%, rgba(197, 160, 89, 0.1) 100%);
        border: 1px solid rgba(212, 175, 55, 0.25);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: var(--sh-gold);
        flex-shrink: 0;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .edl-trust-item:hover .edl-trust-icon {
        transform: scale(1.1) rotate(-3deg);
        box-shadow: 0 0 15px rgba(212, 175, 55, 0.25);
    }

    .edl-trust-text strong {
        display: block;
        font-size: 13px;
        font-weight: 700;
        color: #fff;
        line-height: 1.3;
    }

    .edl-trust-text span {
        font-size: 11px;
        color: rgba(255, 255, 255, 0.45);
    }

    /* ---- Newsletter ---- */
    .edl-newsletter-wrap {
        background: linear-gradient(135deg, rgba(212, 175, 55, 0.08) 0%, rgba(18, 24, 38, 0) 100%);
        border: 1px solid rgba(212, 175, 55, 0.15);
        border-radius: 20px;
        padding: 36px 40px;
        position: relative;
        overflow: hidden;
    }

    .edl-newsletter-wrap::after {
        content: '\f2a6';
        font-family: "bootstrap-icons";
        position: absolute;
        right: 30px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 100px;
        color: rgba(212, 175, 55, 0.06);
        pointer-events: none;
    }

    @media (max-width: 576px) {
        .edl-newsletter-wrap {
            padding: 24px 20px;
        }

        .edl-newsletter-wrap::after {
            font-size: 60px;
        }
    }

    .edl-newsletter-title {
        font-family: var(--sh-font-display);
        font-size: clamp(1.2rem, 3vw, 1.6rem);
        font-weight: 800;
        color: #fff;
        margin-bottom: 6px;
    }

    .edl-newsletter-sub {
        font-size: 14px;
        color: rgba(255, 255, 255, 0.5);
        margin-bottom: 20px;
    }

    .edl-newsletter-form {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .edl-nl-input {
        flex: 1;
        min-width: 220px;
        padding: 14px 20px;
        background: rgba(255, 255, 255, 0.07);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 50px;
        color: #fff;
        font-size: 14px;
        outline: none;
        transition: all 0.3s ease;
    }

    .edl-nl-input::placeholder {
        color: rgba(255, 255, 255, 0.35);
    }

    .edl-nl-input:focus {
        border-color: var(--sh-gold);
        background: rgba(212, 175, 55, 0.08);
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.12);
    }

    .edl-nl-btn {
        padding: 14px 28px;
        background: linear-gradient(135deg, #C5A059 0%, #D4AF37 100%);
        color: #0A0F1A;
        font-weight: 700;
        font-size: 14px;
        border: none;
        border-radius: 50px;
        cursor: pointer;
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
    }

    .edl-nl-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(212, 175, 55, 0.4);
        background: linear-gradient(135deg, #D4AF37 0%, #C5A059 100%);
    }

    .edl-nl-btn i {
        font-size: 16px;
    }

    small.edl-nl-privacy {
        font-size: 11px;
        color: rgba(255, 255, 255, 0.3);
        margin-top: 10px;
        display: block;
    }

    /* ---- Footer Body Grid ---- */
    .edl-footer-body {
        padding: 56px 0 40px;
    }

    /* Brand Column */
    .edl-brand-logo {
        font-family: var(--sh-font-display);
        font-size: 30px;
        font-weight: 900;
        letter-spacing: -1px;
        background: linear-gradient(135deg, #C5A059 0%, #D4AF37 60%, #E8C96D 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        display: inline-block;
        margin-bottom: 12px;
        text-decoration: none;
    }

    .edl-brand-tagline {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.45);
        line-height: 1.7;
        margin-bottom: 20px;
        max-width: 240px;
    }

    /* Rating Stars in Footer */
    .edl-footer-rating {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
    }

    .edl-footer-rating .stars {
        color: var(--sh-gold);
        font-size: 13px;
    }

    .edl-footer-rating span {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.4);
    }

    /* Social Links */
    .edl-social-row {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .edl-social-btn {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        text-decoration: none;
        color: rgba(255, 255, 255, 0.6);
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        position: relative;
        overflow: hidden;
    }

    .edl-social-btn::before {
        content: '';
        position: absolute;
        inset: 0;
        opacity: 0;
        transition: opacity 0.3s ease;
        border-radius: inherit;
    }

    .edl-social-btn:hover {
        color: #fff;
        transform: translateY(-4px) scale(1.08);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
    }

    .edl-social-btn:hover::before {
        opacity: 1;
    }

    /* Per-network colors */
    .edl-social-btn[data-net="facebook"]:hover {
        background: #1877F2;
        border-color: #1877F2;
    }

    .edl-social-btn[data-net="instagram"]:hover {
        background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888);
        border-color: #dc2743;
    }

    .edl-social-btn[data-net="twitter"]:hover {
        background: #000;
        border-color: #555;
    }

    .edl-social-btn[data-net="tiktok"]:hover {
        background: #010101;
        border-color: #ff0050;
    }

    .edl-social-btn[data-net="youtube"]:hover {
        background: #FF0000;
        border-color: #FF0000;
    }

    .edl-social-btn[data-net="whatsapp"]:hover {
        background: #25D366;
        border-color: #25D366;
    }

    /* ---- Footer Nav Columns ---- */
    .edl-footer-col-title {
        font-family: var(--sh-font-display);
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 2.5px;
        color: var(--sh-gold);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .edl-footer-col-title::after {
        content: '';
        flex: 1;
        height: 1px;
        background: rgba(212, 175, 55, 0.2);
    }

    .edl-footer-nav {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .edl-footer-nav li {
        margin-bottom: 12px;
    }

    .edl-footer-nav a {
        color: rgba(255, 255, 255, 0.55);
        text-decoration: none;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.25s ease;
        padding: 2px 0;
    }

    .edl-footer-nav a i {
        font-size: 13px;
        color: var(--sh-gold);
        opacity: 0.6;
        flex-shrink: 0;
        transition: all 0.25s ease;
    }

    .edl-footer-nav a:hover {
        color: #fff;
        padding-left: 6px;
    }

    .edl-footer-nav a:hover i {
        opacity: 1;
        transform: scale(1.15);
    }

    /* Contact items */
    .edl-contact-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 16px;
        color: rgba(255, 255, 255, 0.55);
        font-size: 14px;
        text-decoration: none;
        transition: color 0.25s ease;
    }

    .edl-contact-item:hover {
        color: #fff;
    }

    .edl-contact-icon {
        width: 36px;
        height: 36px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
        transition: all 0.3s ease;
    }

    .edl-contact-item:hover .edl-contact-icon {
        background: rgba(212, 175, 55, 0.15);
        border-color: rgba(212, 175, 55, 0.3);
        color: var(--sh-gold);
    }

    .edl-contact-label {
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: rgba(255, 255, 255, 0.3);
        line-height: 1;
        margin-bottom: 3px;
    }

    .edl-contact-value {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.65);
        line-height: 1.3;
        word-break: break-all;
    }

    /* ---- Payment & Bottom Bar ---- */
    .edl-divider {
        height: 1px;
        background: linear-gradient(90deg, transparent 0%, rgba(212, 175, 55, 0.2) 30%, rgba(212, 175, 55, 0.2) 70%, transparent 100%);
        margin: 0;
    }

    .edl-payment-section {
        padding: 24px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .edl-payment-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: rgba(255, 255, 255, 0.3);
        margin-bottom: 10px;
        text-align: center;
    }

    .edl-payment-icons {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        gap: 8px;
    }

    .edl-payment-badge {
        display: flex;
        align-items: center;
        gap: 5px;
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        padding: 7px 14px;
        font-size: 13px;
        font-weight: 700;
        color: rgba(255, 255, 255, 0.6);
        transition: all 0.25s ease;
        white-space: nowrap;
    }

    .edl-payment-badge:hover {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
        transform: translateY(-2px);
    }

    .edl-payment-badge i {
        font-size: 18px;
    }

    /* Locale info bar */
    .edl-location-bar {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        color: rgba(255, 255, 255, 0.35);
        padding: 14px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        flex-wrap: wrap;
        justify-content: center;
    }

    .edl-location-flag {
        font-size: 20px;
        line-height: 1;
    }

    .edl-location-bar .separator {
        width: 4px;
        height: 4px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
    }

    /* Bottom copyright */
    .edl-footer-bottom {
        padding: 20px 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
    }

    .edl-copyright {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.3);
    }

    .edl-copyright strong {
        color: rgba(255, 255, 255, 0.55);
    }

    .edl-bottom-links {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }

    .edl-bottom-links a {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.3);
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .edl-bottom-links a:hover {
        color: var(--sh-gold);
    }

    /* ---- Mobile Accordion for footer columns ---- */
    @media (max-width: 767px) {
        .edl-footer-col-title {
            cursor: pointer;
            user-select: none;
            margin-bottom: 0;
            padding-bottom: 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .edl-footer-col-title .acc-icon {
            margin-left: auto;
            transition: transform 0.3s ease;
            font-size: 16px;
            opacity: 0.5;
            color: #fff;
        }

        .edl-footer-col-title.open .acc-icon {
            transform: rotate(180deg);
        }

        .edl-footer-col-title::after {
            display: none;
        }

        .edl-footer-col-body {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1), padding 0.3s ease;
            padding-top: 0;
        }

        .edl-footer-col-body.open {
            max-height: 500px;
            padding-top: 14px;
        }

        /* Brand col always visible */
        .edl-brand-col .edl-footer-col-body {
            max-height: none;
            overflow: visible;
        }

        .edl-footer-bottom {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .edl-newsletter-wrap {
            padding: 24px 16px;
        }

        .edl-footer-body {
            padding: 40px 0 30px;
        }

        .edl-location-bar {
            font-size: 11px;
        }
    }

    /* Scroll to top button */
    #edlBackToTop {
        position: fixed;
        bottom: 110px;
        right: 24px;
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #C5A059 0%, #D4AF37 100%);
        color: #0A0F1A;
        border: none;
        border-radius: 14px;
        font-size: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 998;
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
        box-shadow: 0 4px 20px rgba(212, 175, 55, 0.35);
    }

    #edlBackToTop.visible {
        opacity: 1;
        transform: translateY(0);
    }

    #edlBackToTop:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 28px rgba(212, 175, 55, 0.5);
    }

    @media (max-width: 576px) {
        #edlBackToTop {
            bottom: 95px;
            right: 16px;
            width: 42px;
            height: 42px;
            font-size: 16px;
        }
    }
</style>

<!-- Wave Divider -->
<div class="footer-wave">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 60" preserveAspectRatio="none">
        <path fill="#0A0F1A" fill-opacity="1" d="M0,20 C240,55 480,0 720,30 C960,58 1200,5 1440,25 L1440,60 L0,60 Z" />
    </svg>
</div>

<footer class="edl-footer">
    <div class="container-fluid px-3 px-md-4 px-lg-5">

        <!-- ── Trust Bar ── -->
        <div class="edl-trust-bar">
            <div class="row row-cols-2 row-cols-sm-2 row-cols-md-4 g-2 justify-content-center">
                <div class="col d-flex justify-content-center justify-content-md-start">
                    <div class="edl-trust-item">
                        <div class="edl-trust-icon"><i class="bi bi-truck"></i></div>
                        <div class="edl-trust-text">
                            <strong>Free Delivery</strong>
                            <span>On orders over AED 200</span>
                        </div>
                    </div>
                </div>
                <div class="col d-flex justify-content-center">
                    <div class="edl-trust-item">
                        <div class="edl-trust-icon"><i class="bi bi-shield-check"></i></div>
                        <div class="edl-trust-text">
                            <strong>Secure Payment</strong>
                            <span>100% safe transactions</span>
                        </div>
                    </div>
                </div>
                <div class="col d-flex justify-content-center">
                    <div class="edl-trust-item">
                        <div class="edl-trust-icon"><i class="bi bi-arrow-counterclockwise"></i></div>
                        <div class="edl-trust-text">
                            <strong>Easy Returns</strong>
                            <span>30-day return policy</span>
                        </div>
                    </div>
                </div>
                <div class="col d-flex justify-content-center justify-content-md-end">
                    <div class="edl-trust-item">
                        <div class="edl-trust-icon"><i class="bi bi-headset"></i></div>
                        <div class="edl-trust-text">
                            <strong>24/7 Support</strong>
                            <span>Always here to help</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── Newsletter ── -->
        <div class="py-4 py-md-5">
            <div class="edl-newsletter-wrap">
                <div class="row align-items-center g-3">
                    <div class="col-lg-5">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <i class="bi bi-envelope-heart-fill" style="color:var(--sh-gold);font-size:22px;"></i>
                            <div class="edl-newsletter-title">Get 10% Off Your First Order</div>
                        </div>
                        <p class="edl-newsletter-sub mb-0">Join 15,000+ shoppers and get exclusive deals, new arrivals &
                            styling tips.</p>
                    </div>
                    <div class="col-lg-7">
                        <form class="edl-newsletter-form" id="footerNewsletterForm" novalidate>
                            <input type="email" class="edl-nl-input" id="footerEmailInput"
                                placeholder="Enter your email address..." autocomplete="email" required>
                            <button type="submit" class="edl-nl-btn" id="footerNlBtn">
                                <i class="bi bi-send-fill"></i>
                                <span>Subscribe</span>
                            </button>
                        </form>
                        <small class="edl-nl-privacy">
                            <i class="bi bi-lock-fill me-1"></i>
                            No spam, ever. Unsubscribe at any time. Privacy Policy applies.
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── Footer Main Body ── -->
        <div class="edl-footer-body">
            <div class="row g-4 g-lg-5">

                <!-- Brand Column -->
                <div class="col-12 col-md-6 col-lg-3 edl-brand-col">
                    <a href="<?php echo Helpers::url('index.php'); ?>" class="edl-brand-logo">Edluxuryy</a>
                    <p class="edl-brand-tagline">Your premier destination for authentic, premium products in the UAE.
                        Excellence delivered to your door.</p>

                    <!-- Star Rating Snippet -->
                    <div class="edl-footer-rating">
                        <div class="stars">
                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-half"></i>
                        </div>
                        <span>4.8 / 5 — 2,400+ Reviews</span>
                    </div>

                    <!-- Social Icons -->
                    <div class="edl-social-row">
                        <a href="https://facebook.com" target="_blank" class="edl-social-btn" data-net="facebook"
                            title="Follow us on Facebook" aria-label="Facebook">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="https://instagram.com" target="_blank" class="edl-social-btn" data-net="instagram"
                            title="Follow us on Instagram" aria-label="Instagram">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="https://twitter.com" target="_blank" class="edl-social-btn" data-net="twitter"
                            title="Follow us on X (Twitter)" aria-label="Twitter/X">
                            <i class="bi bi-twitter-x"></i>
                        </a>
                        <a href="https://tiktok.com" target="_blank" class="edl-social-btn" data-net="tiktok"
                            title="Follow us on TikTok" aria-label="TikTok">
                            <i class="bi bi-tiktok"></i>
                        </a>
                        <a href="https://youtube.com" target="_blank" class="edl-social-btn" data-net="youtube"
                            title="Subscribe on YouTube" aria-label="YouTube">
                            <i class="bi bi-youtube"></i>
                        </a>
                        <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $theme->get('contact_phone', '971500000000')); ?>"
                            target="_blank" class="edl-social-btn" data-net="whatsapp" title="Chat on WhatsApp"
                            aria-label="WhatsApp">
                            <i class="bi bi-whatsapp"></i>
                        </a>
                    </div>
                </div>

                <!-- Shop Column -->
                <div class="col-6 col-md-3 col-lg-2">
                    <h6 class="edl-footer-col-title" data-acc="shop">
                        <i class="bi bi-bag-heart"></i> Shop
                        <i class="bi bi-chevron-down acc-icon d-md-none"></i>
                    </h6>
                    <div class="edl-footer-col-body" id="acc-shop">
                        <ul class="edl-footer-nav">
                            <li><a href="<?php echo Helpers::url('products.php'); ?>"><i
                                        class="bi bi-grid-3x3-gap"></i>All Products</a></li>
                            <li><a href="<?php echo Helpers::url('products.php?badge=new'); ?>"><i
                                        class="bi bi-stars"></i>New Arrivals</a></li>
                            <li><a href="<?php echo Helpers::url('products.php?badge=bestseller'); ?>"><i
                                        class="bi bi-fire"></i>Best Sellers</a></li>
                            <li><a href="<?php echo Helpers::url('products.php?badge=sale'); ?>"><i
                                        class="bi bi-tag"></i>Sale Items</a></li>
                            <li><a href="<?php echo Helpers::url('products.php'); ?>"><i class="bi bi-gift"></i>Gift
                                    Cards</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Help Column -->
                <div class="col-6 col-md-3 col-lg-2">
                    <h6 class="edl-footer-col-title" data-acc="help">
                        <i class="bi bi-life-preserver"></i> Help
                        <i class="bi bi-chevron-down acc-icon d-md-none"></i>
                    </h6>
                    <div class="edl-footer-col-body" id="acc-help">
                        <ul class="edl-footer-nav">
                            <li><a href="<?php echo Helpers::url('track-order.php'); ?>"><i
                                        class="bi bi-geo-alt"></i>Track Your Order</a></li>
                            <li><a href="<?php echo Helpers::url('page.php?slug=faq'); ?>"><i
                                        class="bi bi-patch-question"></i>FAQ</a></li>
                            <li><a href="<?php echo Helpers::url('page.php?slug=shipping-delivery'); ?>"><i
                                        class="bi bi-box-seam"></i>Shipping & Delivery</a></li>
                            <li><a href="<?php echo Helpers::url('page.php?slug=returns-refunds'); ?>"><i
                                        class="bi bi-arrow-return-left"></i>Returns & Refunds</a></li>
                            <li><a href="<?php echo Helpers::url('page.php?slug=contact-us'); ?>"><i
                                        class="bi bi-chat-left-text"></i>Contact Us</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Contact Column -->
                <div class="col-12 col-md-6 col-lg-3">
                    <h6 class="edl-footer-col-title" data-acc="contact">
                        <i class="bi bi-telephone-inbound"></i> Contact
                        <i class="bi bi-chevron-down acc-icon d-md-none"></i>
                    </h6>
                    <div class="edl-footer-col-body open" id="acc-contact">
                        <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $theme->get('contact_phone', '971500000000')); ?>"
                            target="_blank" class="edl-contact-item" style="color:rgba(255,255,255,0.55);">
                            <div class="edl-contact-icon" style="color:#25D366;"><i class="bi bi-whatsapp"></i></div>
                            <div>
                                <div class="edl-contact-label">WhatsApp</div>
                                <div class="edl-contact-value">Chat with us</div>
                            </div>
                        </a>
                        <a href="mailto:<?php echo $theme->get('contact_email', 'info@edluxury.ae'); ?>"
                            class="edl-contact-item" style="color:rgba(255,255,255,0.55);">
                            <div class="edl-contact-icon" style="color:var(--sh-gold);"><i
                                    class="bi bi-envelope-fill"></i></div>
                            <div>
                                <div class="edl-contact-label">Email Us</div>
                                <div class="edl-contact-value">
                                    <?php echo $theme->get('contact_email', 'info@edluxury.ae'); ?>
                                </div>
                            </div>
                        </a>
                        <a href="tel:<?php echo $theme->get('contact_phone', '+971500000000'); ?>"
                            class="edl-contact-item" style="color:rgba(255,255,255,0.55);">
                            <div class="edl-contact-icon" style="color:var(--sh-accent);"><i
                                    class="bi bi-telephone-fill"></i></div>
                            <div>
                                <div class="edl-contact-label">Call Us</div>
                                <div class="edl-contact-value">
                                    <?php echo $theme->get('contact_phone', '+971 50 000 0000'); ?>
                                </div>
                            </div>
                        </a>
                        <div class="edl-contact-item" style="pointer-events:none;">
                            <div class="edl-contact-icon" style="color:#94A3B8;"><i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div>
                                <div class="edl-contact-label">Location</div>
                                <div class="edl-contact-value">Dubai, United Arab Emirates</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div><!-- /row -->
        </div><!-- /edl-footer-body -->

        <!-- ── Divider ── -->
        <div class="edl-divider"></div>

        <!-- ── Payment Methods ── -->
        <div class="edl-payment-section">
            <p class="edl-payment-label">Accepted Payment Methods</p>
            <div class="edl-payment-icons">
                <div class="edl-payment-badge">
                    <i class="bi bi-credit-card-2-front-fill" style="color:#1A1F71;"></i> Visa
                </div>
                <div class="edl-payment-badge">
                    <i class="bi bi-credit-card-fill" style="color:#EB001B;"></i> Mastercard
                </div>
                <div class="edl-payment-badge">
                    <i class="bi bi-paypal" style="color:#003087;"></i> PayPal
                </div>
                <div class="edl-payment-badge">
                    <i class="bi bi-cash-coin" style="color:var(--sh-gold);"></i> Cash on Delivery
                </div>
                <div class="edl-payment-badge">
                    <i class="bi bi-phone-fill" style="color:#E71D1D;"></i> Apple Pay
                </div>
                <div class="edl-payment-badge">
                    <i class="bi bi-google" style="color:#4285F4;"></i> Google Pay
                </div>
            </div>
        </div>

        <!-- ── Location / Currency Bar ── -->
        <div class="edl-location-bar">
            <span class="edl-location-flag">🇦🇪</span>
            <span>Serving the UAE</span>
            <span class="separator"></span>
            <span>Currency: AED (د.إ)</span>
            <span class="separator"></span>
            <i class="bi bi-translate me-1"></i>
            <span>English | العربية</span>
            <span class="separator"></span>
            <i class="bi bi-shield-lock-fill me-1" style="color:var(--sh-gold);"></i>
            <span>SSL Secured</span>
        </div>

        <!-- ── Bottom Bar ── -->
        <div class="edl-footer-bottom">
            <p class="edl-copyright mb-0">
                &copy; <?php echo date('Y'); ?> <strong>Edluxury</strong>. All rights reserved.
                Made with <i class="bi bi-heart-fill" style="color:#e74c3c;font-size:11px;"></i> in UAE
            </p>
            <div class="edl-bottom-links">
                <a href="<?php echo Helpers::url('page.php?slug=privacy-policy'); ?>">Privacy Policy</a>
                <a href="<?php echo Helpers::url('page.php?slug=terms-conditions'); ?>">Terms & Conditions</a>
                <a href="<?php echo Helpers::url('page.php?slug=cookie-policy'); ?>">Cookie Policy</a>
            </div>
        </div>

    </div><!-- /container -->
</footer>

<!-- ── Back To Top Button ── -->
<button id="edlBackToTop" aria-label="Back to top" title="Back to top">
    <i class="bi bi-arrow-up-short"></i>
</button>

<!-- ── AI Chatbot Widget ── -->
<div id="chatbot-widget" class="position-fixed" style="bottom: 24px; right: 24px; z-index: 1050;">
    <!-- Launcher Button -->
    <button id="chatbot-launcher" class="btn rounded-circle shadow-lg d-flex align-items-center justify-content-center"
        style="width:60px;height:60px;background:linear-gradient(135deg,#0F3D3E 0%,#1a5f61 100%);border:2px solid rgba(255,255,255,0.15);transition:all 0.4s cubic-bezier(0.175,0.885,0.32,1.275);"
        aria-label="Open chat">
        <i class="bi bi-chat-dots-fill text-white" style="font-size:24px;"></i>
        <span
            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-2 border-white"
            id="bot-notif-dot" style="display:none;padding:6px;"></span>
    </button>

    <!-- Chat Window -->
    <div id="chatbot-window" class="card shadow-lg border-0 rounded-4 overflow-hidden d-none"
        style="width:360px;height:520px;position:absolute;bottom:80px;right:0;background:#fff;transition:all 0.3s ease;">
        <!-- Header -->
        <div class="card-header p-3 border-0 d-flex align-items-center"
            style="background:linear-gradient(135deg,#0F3D3E 0%,#1a5f61 100%);">
            <div class="position-relative me-3">
                <div class="rounded-circle overflow-hidden bg-white d-flex align-items-center justify-content-center"
                    style="width:42px;height:42px;border:2px solid rgba(255,255,255,0.2);">
                    <img src="https://ui-avatars.com/api/?name=Edluxury&background=A69C63&color=fff&bold=true" alt="Bot"
                        class="img-fluid">
                </div>
                <span class="position-absolute bottom-0 end-0 bg-success rounded-circle border border-2 border-white"
                    style="width:11px;height:11px;"></span>
            </div>
            <div class="flex-grow-1">
                <h6 class="text-white mb-0 fw-bold" style="font-size:14px;">Edluxury Concierge</h6>
                <small class="text-white-50" style="font-size:11px;"><i class="bi bi-robot me-1"></i>AI Powered ·
                    24/7</small>
            </div>
            <button type="button" id="chatbot-close" class="btn-close btn-close-white ms-auto shadow-none"
                style="font-size:13px;"></button>
        </div>
        <!-- Messages -->
        <div class="card-body p-0 d-flex flex-column bg-light" style="height:calc(100% - 125px);">
            <div id="chatbot-messages" class="flex-grow-1 p-3 overflow-y-auto" style="scroll-behavior:smooth;"></div>
            <div id="chatbot-quick-replies" class="p-2 border-top bg-white d-flex gap-2 overflow-x-auto"
                style="scrollbar-width:none;"></div>
        </div>
        <!-- Input -->
        <div class="card-footer p-3 bg-white border-0">
            <div class="input-group">
                <input type="text" id="chatbot-input" class="form-control border-0 bg-light rounded-start-3 p-2"
                    placeholder="Type your question..." style="box-shadow:none;">
                <button class="btn rounded-end-3 px-3" id="chatbot-send"
                    style="background:#0F3D3E;border:none;color:#fff;">
                    <i class="bi bi-send-fill"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- AOS Animation Library -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<!-- Main JS -->
<script src="<?php echo Helpers::asset('js/main.js'); ?>"></script>

<script>
    /* ====================================================
       FOOTER JAVASCRIPT — Edluxury Premium
       ==================================================== */

    // ── AOS Init ──
    AOS.init({ duration: 800, once: true, offset: 50, easing: 'ease-out-cubic' });

    // ── Header Scroll Effect ──
    const _hdr = document.getElementById('mainHeader');
    if (_hdr) {
        window.addEventListener('scroll', () => {
            _hdr.classList.toggle('scrolled', window.scrollY > 50);
        }, { passive: true });
    }

    // ── Back To Top (premium gold button) ──
    const edlBtt = document.getElementById('edlBackToTop');
    if (edlBtt) {
        window.addEventListener('scroll', () => {
            edlBtt.classList.toggle('visible', window.scrollY > 350);
        }, { passive: true });
        edlBtt.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // ── Mobile Accordion for footer columns ──
    document.querySelectorAll('.edl-footer-col-title[data-acc]').forEach(title => {
        title.addEventListener('click', () => {
            if (window.innerWidth >= 768) return; // desktop: always open
            const body = document.getElementById('acc-' + title.dataset.acc);
            if (!body) return;
            const isOpen = body.classList.contains('open');
            // Close all
            document.querySelectorAll('.edl-footer-col-body').forEach(b => b.classList.remove('open'));
            document.querySelectorAll('.edl-footer-col-title[data-acc]').forEach(t => t.classList.remove('open'));
            // Toggle
            if (!isOpen) {
                body.classList.add('open');
                title.classList.add('open');
            }
        });
    });

    // On resize, reset accordion state
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 768) {
            document.querySelectorAll('.edl-footer-col-body').forEach(b => b.style.maxHeight = '');
        }
    }, { passive: true });

    // ── Newsletter Form ──
    const nlForm = document.getElementById('footerNewsletterForm');
    const nlInput = document.getElementById('footerEmailInput');
    const nlBtn = document.getElementById('footerNlBtn');

    if (nlForm && nlInput && nlBtn) {
        nlForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const email = nlInput.value.trim();
            const emailRx = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            // Validate
            if (!emailRx.test(email)) {
                nlInput.style.borderColor = '#ef4444';
                nlInput.style.boxShadow = '0 0 0 3px rgba(239,68,68,0.15)';
                nlInput.placeholder = 'Please enter a valid email...';
                nlInput.value = '';
                setTimeout(() => {
                    nlInput.style.borderColor = '';
                    nlInput.style.boxShadow = '';
                    nlInput.placeholder = 'Enter your email address...';
                }, 2500);
                return;
            }

            // Success state
            nlBtn.innerHTML = '<i class="bi bi-check-circle-fill"></i> <span>Subscribed!</span>';
            nlBtn.style.background = 'linear-gradient(135deg,#059669 0%,#10b981 100%)';
            nlBtn.style.color = '#fff';
            nlBtn.disabled = true;
            nlInput.value = '';
            nlInput.placeholder = 'You\'re subscribed! 🎉';
            nlInput.disabled = true;
            nlInput.style.borderColor = 'rgba(16,185,129,0.4)';

            // Fire toast notification
            showToast('🎉 Welcome! You\'ve subscribed for 10% off your first order.', 'success');

            // Reset after delay
            setTimeout(() => {
                nlBtn.innerHTML = '<i class="bi bi-send-fill"></i> <span>Subscribe</span>';
                nlBtn.style.background = '';
                nlBtn.style.color = '';
                nlBtn.disabled = false;
                nlInput.disabled = false;
                nlInput.placeholder = 'Enter your email address...';
                nlInput.style.borderColor = '';
            }, 6000);
        });
    }

    // ── Toast Notification ──
    function showToast(message, type = 'success') {
        let container = document.getElementById('toastContainer');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toastContainer';
            container.className = 'toast-container position-fixed top-0 end-0 p-3';
            container.style.zIndex = '1100';
            document.body.appendChild(container);
        }
        const bgMap = {
            success: 'linear-gradient(135deg,#059669,#10b981)',
            error: 'linear-gradient(135deg,#dc2626,#ef4444)',
            info: 'linear-gradient(135deg,#0284c7,#38bdf8)'
        };
        const iconMap = { success: 'check-circle-fill', error: 'x-circle-fill', info: 'info-circle-fill' };
        const toast = document.createElement('div');
        toast.className = 'toast align-items-center text-white border-0 show rounded-3 mb-2';
        toast.style.cssText = `background:${bgMap[type] || bgMap.info};min-width:280px;box-shadow:0 8px 24px rgba(0,0,0,0.15);`;
        toast.innerHTML = `
        <div class="d-flex align-items-center gap-2 p-3">
            <i class="bi bi-${iconMap[type] || iconMap.info} fs-5 flex-shrink-0"></i>
            <div class="toast-body p-0 flex-grow-1 fw-medium" style="font-size:14px;">${message}</div>
            <button type="button" class="btn-close btn-close-white flex-shrink-0" onclick="this.closest('.toast').remove()"></button>
        </div>`;
        container.appendChild(toast);
        setTimeout(() => toast.remove(), 5000);
    }

    // ── Cart Count Update ──
    function updateCartCount(count) {
        const badge = document.getElementById('cartCount');
        if (badge) {
            badge.textContent = count;
            badge.classList.toggle('d-none', !count);
        }
    }
    document.addEventListener('cartUpdated', (e) => updateCartCount(e.detail.count));

    // ── Social icon tooltip (title attr already set, Bootstrap tooltip) ──
    document.querySelectorAll('.edl-social-btn').forEach(btn => {
        new bootstrap.Tooltip(btn, { placement: 'top', trigger: 'hover' });
    });
</script>

<!-- Edluxury AI Chatbot -->
<script src="<?php echo Helpers::asset('js/chatbot.js'); ?>"></script>
</body>

</html>
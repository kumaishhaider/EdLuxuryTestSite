<?php
/**
 * CMS Page Handler (About Us, Contact Us, etc.)
 */

$slug = $_GET['slug'] ?? '';
if (empty($slug)) {
    header("HTTP/1.0 404 Not Found");
    require_once 'includes/header.php';
    echo '<div class="container my-5 text-center"><h1>Page Not Found</h1></div>';
    require_once 'includes/footer.php';
    exit;
}

require_once 'config/config.php';
$db = Database::getInstance();
$page = $db->fetchOne("SELECT * FROM pages WHERE slug = ? AND status = 'active'", [$slug]);

if (!$page) {
    header("HTTP/1.0 404 Not Found");
    require_once 'includes/header.php';
    echo '<div class="container my-5 text-center"><h1>Page Not Found</h1><p>The page you are looking for does not exist.</p></div>';
    require_once 'includes/footer.php';
    exit;
}

$pageTitle = $page['title'];
require_once 'includes/header.php';
?>

<?php if ($slug === 'about-us'): ?>

    <!-- ═══════════════════════════════════════════════════════
     ABOUT US — PREMIUM EDLUXURY EXPERIENCE
     ═══════════════════════════════════════════════════════ -->

    <style>
        /* ── Hero ── */
        .about-hero {
            min-height: 92vh;
            background: linear-gradient(135deg, #020d0e 0%, #0F3D3E 50%, #1a5f61 100%);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
        }

        .about-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 70% 50%, rgba(166, 156, 99, 0.12) 0%, transparent 60%),
                url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23A69C63' fill-opacity='0.04'%3E%3Cpath d='M50 50c0-5.523 4.477-10 10-10s10 4.477 10 10-4.477 10-10 10c0 5.523-4.477 10-10 10s-10-4.477-10-10 4.477-10 10-10zM10 10c0-5.523 4.477-10 10-10s10 4.477 10 10-4.477 10-10 10c0 5.523-4.477 10-10 10S0 25.523 0 20s4.477-10 10-10z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            pointer-events: none;
        }

        .hero-gold-circle {
            position: absolute;
            border-radius: 50%;
            border: 1px solid rgba(166, 156, 99, 0.2);
            animation: rotateRing 30s linear infinite;
        }

        @keyframes rotateRing {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .hero-floating-badge {
            background: rgba(166, 156, 99, 0.12);
            border: 1px solid rgba(166, 156, 99, 0.3);
            border-radius: 50px;
            padding: 8px 20px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 3px;
            color: #A69C63;
            text-transform: uppercase;
            display: inline-block;
            animation: fadeIn 1s ease;
        }

        .hero-headline {
            font-size: clamp(2.2rem, 8vw, 6.5rem);
            font-weight: 900;
            line-height: 1.0;
            letter-spacing: -3px;
            color: #fff;
            animation: heroSlideUp 0.9s ease;
        }

        .hero-headline span {
            color: #A69C63;
        }

        @keyframes heroSlideUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hero-stat-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 24px;
            text-align: center;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .hero-stat-card:hover {
            background: rgba(166, 156, 99, 0.12);
            border-color: rgba(166, 156, 99, 0.4);
            transform: translateY(-5px);
        }

        .hero-stat-number {
            font-size: 2.5rem;
            font-weight: 900;
            color: #A69C63;
            line-height: 1;
        }

        .hero-stat-label {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.5);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 6px;
        }

        /* ── Story ── */
        .story-section {
            background: #fff;
            padding: 120px 0;
        }

        .story-pill {
            background: linear-gradient(135deg, rgba(15, 61, 62, 0.08), rgba(166, 156, 99, 0.08));
            border: 1px solid rgba(15, 61, 62, 0.12);
            border-radius: 50px;
            padding: 6px 16px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #0F3D3E;
            display: inline-block;
            margin-bottom: 16px;
        }

        .story-visual {
            position: relative;
            border-radius: 28px;
            overflow: hidden;
        }

        .story-visual img {
            width: 100%;
            height: 550px;
            object-fit: cover;
            border-radius: 28px;
        }

        @media (max-width: 768px) {
            .story-visual img {
                height: 350px;
            }
        }

        .story-visual-badge {
            position: absolute;
            bottom: 24px;
            left: 24px;
            background: white;
            border-radius: 16px;
            padding: 16px 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            gap: 12px;
            animation: float 4s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-8px);
            }
        }

        .story-accent-bar {
            position: absolute;
            top: 24px;
            right: -8px;
            width: 5px;
            height: 100px;
            background: linear-gradient(180deg, #A69C63, transparent);
            border-radius: 4px;
        }

        /* ── Values ── */
        .values-section {
            background: linear-gradient(135deg, #020d0e 0%, #0F3D3E 100%);
            padding: 120px 0;
            position: relative;
            overflow: hidden;
        }

        .values-section::before {
            content: '';
            position: absolute;
            top: -200px;
            right: -200px;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(166, 156, 99, 0.08), transparent 70%);
            pointer-events: none;
        }

        .value-card {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            padding: 36px 28px;
            height: 100%;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .value-card::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, transparent, #A69C63, transparent);
            transform: scaleX(0);
            transition: transform 0.4s ease;
        }

        .value-card:hover {
            background: rgba(166, 156, 99, 0.08);
            border-color: rgba(166, 156, 99, 0.25);
            transform: translateY(-8px);
        }

        .value-card:hover::before {
            transform: scaleX(1);
        }

        .value-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, rgba(166, 156, 99, 0.2), rgba(166, 156, 99, 0.05));
            border: 1px solid rgba(166, 156, 99, 0.3);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            color: #A69C63;
            margin-bottom: 20px;
        }

        /* ── Timeline ── */
        .timeline-section {
            background: #f8f8f8;
            padding: 120px 0;
        }

        .timeline-line {
            width: 2px;
            background: linear-gradient(180deg, #A69C63, rgba(166, 156, 99, 0.1));
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            height: 100%;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 60px;
        }

        .timeline-dot {
            width: 18px;
            height: 18px;
            background: #A69C63;
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 0 0 4px rgba(166, 156, 99, 0.2);
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            top: 24px;
            z-index: 2;
        }

        .timeline-card {
            background: white;
            border-radius: 20px;
            padding: 28px 32px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.06);
            border: 1px solid #eee;
            transition: all 0.3s ease;
            max-width: 42%;
        }

        .timeline-card:hover {
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
            transform: translateY(-4px);
        }

        .timeline-year {
            font-size: 13px;
            font-weight: 700;
            color: #A69C63;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        /* ── Team ── */
        .team-section {
            background: #fff;
            padding: 100px 0;
        }

        .team-card {
            border-radius: 24px;
            overflow: hidden;
            background: white;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.07);
            transition: all 0.4s ease;
            border: 1px solid #f0f0f0;
        }

        .team-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.12);
        }

        .team-avatar {
            height: 220px;
            background: linear-gradient(135deg, #0F3D3E, #1a5f61);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 64px;
            font-weight: 900;
            color: #A69C63;
            letter-spacing: -1px;
            position: relative;
            overflow: hidden;
        }

        .team-avatar::after {
            content: '';
            position: absolute;
            bottom: -20px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 40px;
            background: white;
            border-radius: 50%;
        }

        .team-info {
            padding: 24px;
            text-align: center;
        }

        /* ── CTA ── */
        .cta-section {
            background: linear-gradient(135deg, #A69C63 0%, #c9bb80 50%, #A69C63 100%);
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none'%3E%3Cg fill='%23FFFFFF' fill-opacity='0.06'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            pointer-events: none;
        }

        /* ── Counters ── */
        .counter-up {
            display: inline-block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <!-- ▌ Section 1: Hero ───────────────────────────────────── -->
    <section class="about-hero">
        <!-- Decorative Rings -->
        <div class="hero-gold-circle" style="width:600px;height:600px;top:-150px;right:-150px;opacity:0.3;"></div>
        <div class="hero-gold-circle"
            style="width:300px;height:300px;bottom:-80px;left:10%;opacity:0.2;animation-duration:20s;animation-direction:reverse;">
        </div>

        <div class="container-fluid px-3 px-md-4 px-lg-5 position-relative z-1">
            <div class="row align-items-center g-5">
                <div class="col-lg-7" data-aos="fade-right">
                    <span class="hero-floating-badge">🇦🇪 UAE's Premier Luxury Destination</span>
                    <h1 class="hero-headline mt-4 mb-4">
                        Where<br>
                        <span>Luxury</span><br>
                        Meets Trust
                    </h1>
                    <p class="text-white mb-5" style="font-size:18px;opacity:0.65;max-width:480px;line-height:1.8;">
                        We are more than a store — we are a lifestyle curator. Every item is handpicked, quality-certified,
                        and delivered with the care you deserve.
                    </p>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="<?php echo Helpers::url('products.php'); ?>"
                            class="btn btn-warning fw-bold px-5 py-3 rounded-pill"
                            style="background:#A69C63;border:none;font-size:15px;">
                            Explore Collection <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                        <a href="#our-story" class="btn btn-outline-light fw-bold px-5 py-3 rounded-pill"
                            style="font-size:15px;">
                            Our Story <i class="bi bi-play-circle ms-2"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-5" data-aos="fade-left">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="hero-stat-card">
                                <div class="hero-stat-number"><span class="counter-up" data-target="10000">0</span>+</div>
                                <div class="hero-stat-label">Happy Customers</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="hero-stat-card">
                                <div class="hero-stat-number"><span class="counter-up" data-target="500">0</span>+</div>
                                <div class="hero-stat-label">Products Curated</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="hero-stat-card">
                                <div class="hero-stat-number">7</div>
                                <div class="hero-stat-label">Emirates Served</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="hero-stat-card">
                                <div class="hero-stat-number"><span class="counter-up" data-target="4">0</span>.9★</div>
                                <div class="hero-stat-label">Average Rating</div>
                            </div>
                        </div>
                    </div>

                    <!-- Trust Strip -->
                    <div class="mt-4 p-4 rounded-4"
                        style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-shield-fill-check fs-2" style="color:#A69C63;"></i>
                            <div>
                                <p class="text-white fw-bold mb-0" style="font-size:14px;">100% Authenticity Guaranteed</p>
                                <p style="color:rgba(255,255,255,0.5);font-size:12px;margin:0;">Every item inspected &
                                    certified before dispatch</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scroll indicator -->
        <div class="position-absolute bottom-0 start-50 translate-middle-x pb-4 text-center d-none d-md-block"
            style="animation:float 2s ease-in-out infinite;">
            <i class="bi bi-chevron-double-down text-white" style="font-size:20px;opacity:0.4;"></i>
        </div>
    </section>

    <!-- ▌ Section 2: Our Story ─────────────────────────────── -->
    <section class="story-section" id="our-story">
        <div class="container-fluid px-3 px-md-4 px-lg-5">
            <div class="row align-items-center g-5">
                <!-- Visual -->
                <div class="col-lg-5" data-aos="fade-right">
                    <div class="story-visual">
                        <img src="https://images.unsplash.com/photo-1512453979798-5ea266f8880c?q=80&w=800&auto=format&fit=crop"
                            alt="Dubai Luxury Lifestyle"
                            onerror="this.src='https://images.unsplash.com/photo-1633158829585-23ba8f7c8caf?q=80&w=800'">
                        <div class="story-accent-bar"></div>
                        <!-- Floating Badge -->
                        <div class="story-visual-badge">
                            <div
                                style="width:48px;height:48px;background:linear-gradient(135deg,#0F3D3E,#A69C63);border-radius:12px;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-gem text-white fs-5"></i>
                            </div>
                            <div>
                                <p class="fw-bold mb-0" style="font-size:14px;color:#0F3D3E;">Since 2021</p>
                                <p class="mb-0" style="font-size:12px;color:#999;">Dubai, UAE 🇦🇪</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="col-lg-7" data-aos="fade-left">
                    <span class="story-pill">📖 Our Heritage</span>
                    <h2 class="fw-bold mb-4" style="font-size:clamp(2rem,4vw,3.2rem);color:#0F3D3E;line-height:1.2;">
                        Born From the<br>Golden Sands of <span style="color:#A69C63;">Dubai</span>
                    </h2>
                    <p class="text-muted mb-4" style="font-size:17px;line-height:1.9;">
                        Edluxury was founded with a single vision — to make world-class luxury products accessible to
                        everyone across the UAE. We saw a gap between premium quality and affordable price, and we decided
                        to bridge it.
                    </p>
                    <p class="text-muted mb-5" style="font-size:16px;line-height:1.9;">
                        Today, we serve thousands of customers from Dubai to Fujairah, delivering meticulously selected
                        products that blend elegance, performance, and value. Our team personally reviews every item before
                        it earns a place in our collection.
                    </p>

                    <!-- Mini Values -->
                    <div class="row g-3">
                        <?php
                        $miniVals = [
                            ['bi-gem', '#A69C63', 'Curated Quality', 'Only the finest items pass our standards'],
                            ['bi-lightning-charge', '#0F3D3E', 'Fast Delivery', '24-48 hour delivery across all Emirates'],
                            ['bi-arrow-repeat', '#1a5f61', '7-Day Returns', 'Hassle-free, no-questions return policy'],
                            ['bi-telephone-outbound', '#A69C63', '24/7 Support', 'Our concierge team is always here'],
                        ];
                        foreach ($miniVals as $v): ?>
                            <div class="col-6">
                                <div class="d-flex align-items-start gap-3 p-3 rounded-3"
                                    style="background:#f8f8f8;border:1px solid #eee;">
                                    <div
                                        style="width:38px;height:38px;background:<?= $v[1] ?>;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i class="bi <?= $v[0] ?> text-white" style="font-size:16px;"></i>
                                    </div>
                                    <div>
                                        <p class="fw-bold mb-0" style="font-size:13px;color:#111;"><?= $v[2] ?></p>
                                        <p class="mb-0" style="font-size:11px;color:#999;"><?= $v[3] ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ▌ Section 3: Core Values ───────────────────────────── -->
    <section class="values-section">
        <div class="container-fluid px-3 px-md-4 px-lg-5 position-relative z-1">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="hero-floating-badge mb-3 d-inline-block">✨ What We Stand For</span>
                <h2 class="fw-bold text-white mt-3" style="font-size:clamp(2rem,4vw,3rem);">Our Core Values</h2>
                <p style="color:rgba(255,255,255,0.5);max-width:560px;margin:0 auto;font-size:16px;">
                    Everything we do is guided by these foundational pillars that ensure your experience with Edluxury is
                    truly exceptional.
                </p>
            </div>

            <div class="row g-4">
                <?php
                $values = [
                    ['bi-gem', '#A69C63', 'Diamond Quality', 'Every product is hand-selected and quality-inspected. We reject anything that does not meet our premium benchmark.'],
                    ['bi-shield-check', '#4CAF50', 'Authentic Guarantee', '100% genuine products, sourced directly from verified suppliers. You will never receive a counterfeit from us.'],
                    ['bi-heart', '#E91E63', 'Customer-First Always', 'You are the reason we exist. Every policy, process, and experience is designed to delight you completely.'],
                    ['bi-truck', '#2196F3', 'Express UAE Delivery', 'We ship across all 7 Emirates in 24-48 hours using premium couriers. Fast, tracked, and damage-free.'],
                    ['bi-cash-stack', '#FF9800', 'Cash on Delivery', 'Pay only when you hold your product in your hands. No upfront risk — that is our commitment to your peace of mind.'],
                    ['bi-arrow-counterclockwise', '#9C27B0', 'Easy 7-Day Returns', 'Did not love it? No problem. Our hassle-free return process is quick, simple, and handled with zero pressure.'],
                ];
                foreach ($values as $i => $v): ?>
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?= $i * 80 ?>">
                        <div class="value-card">
                            <div class="value-icon"
                                style="color:<?= $v[1] ?>;border-color:<?= $v[1] ?>40;background:<?= $v[1] ?>18;">
                                <i class="bi <?= $v[0] ?>"></i>
                            </div>
                            <h5 class="text-white fw-bold mb-2"><?= $v[2] ?></h5>
                            <p style="color:rgba(255,255,255,0.5);font-size:14px;line-height:1.7;margin:0;"><?= $v[3] ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ▌ Section 4: Journey Timeline ─────────────────────── -->
    <section class="timeline-section">
        <div class="container-fluid px-3 px-md-4 px-lg-5">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="story-pill">🚀 Our Journey</span>
                <h2 class="fw-bold mt-3" style="font-size:clamp(2rem,4vw,3rem);color:#0F3D3E;">How We Grew</h2>
            </div>

            <div class="position-relative" id="timelineWrapper">
                <div class="timeline-line d-none d-md-block"></div>

                <!-- Mobile: just cards stacked -->
                <!-- Desktop: alternating sides -->
                <?php
                $milestones = [
                    ['2021', '🌱 Founded', 'Edluxury was born in Dubai with a dream to make luxury accessible to every UAE resident.'],
                    ['2022', '📦 500 Products', 'We expanded our catalog to 500+ premium products across 20+ categories.'],
                    ['2022', '🚚 Fast Delivery', 'Launched our express 24-hour delivery service across Dubai and Abu Dhabi.'],
                    ['2023', '⭐ 5,000 Customers', 'Crossed the milestone of 5,000 satisfied customers with a 4.9-star average rating.'],
                    ['2024', '🇦🇪 All Emirates', 'Extended service to all 7 Emirates, making us truly UAE-wide.'],
                    ['2025', '💎 Premium Concierge', 'Launched our 24/7 AI Concierge support and personalized shopping service.'],
                ];
                foreach ($milestones as $i => $m):
                    $isLeft = $i % 2 === 0;
                    ?>
                    <div class="timeline-item d-none d-md-flex <?= $isLeft ? 'justify-content-start' : 'justify-content-end' ?>"
                        data-aos="<?= $isLeft ? 'fade-right' : 'fade-left' ?>">
                        <div class="timeline-card <?= $isLeft ? 'me-auto' : 'ms-auto' ?>"
                            style="margin-<?= $isLeft ? 'right' : 'left' ?>: calc(50% + 30px);">
                            <div class="timeline-year"><?= $m[0] ?></div>
                            <h6 class="fw-bold mb-2" style="color:#0F3D3E;font-size:16px;"><?= $m[1] ?></h6>
                            <p class="mb-0 text-muted" style="font-size:13px;line-height:1.6;"><?= $m[2] ?></p>
                        </div>
                        <div class="timeline-dot"></div>
                    </div>
                <?php endforeach; ?>

                <!-- Mobile version -->
                <div class="d-md-none">
                    <?php foreach ($milestones as $m): ?>
                        <div class="card border-0 shadow-sm rounded-4 mb-3 p-3" data-aos="fade-up">
                            <div class="timeline-year"><?= $m[0] ?></div>
                            <h6 class="fw-bold mb-1" style="color:#0F3D3E;"><?= $m[1] ?></h6>
                            <p class="mb-0 text-muted small"><?= $m[2] ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- ▌ Section 5: Team Profiles ─────────────────────────── -->
    <section class="team-section">
        <div class="container-fluid px-3 px-md-4 px-lg-5">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="story-pill">👥 The People Behind Edluxury</span>
                <h2 class="fw-bold mt-3" style="font-size:clamp(2rem,4vw,3rem);color:#0F3D3E;">Meet Our Team</h2>
                <p class="text-muted" style="max-width:520px;margin:12px auto 0;font-size:16px;">
                    A passionate team of luxury experts, curators, and customer advocates based in Dubai.
                </p>
            </div>

            <div class="row g-4 justify-content-center">
                <?php
                $team = [
                    ['AK', 'Ahmed Al Kaabi', 'Founder & CEO', 'Visionary leader with 10+ years in UAE luxury retail.'],
                    ['SM', 'Sara Al Mansouri', 'Head of Curation', 'Travels the world to handpick only the finest products.'],
                    ['RH', 'Rashid Hassan', 'Operations Director', 'Ensures every order is delivered perfectly and on time.'],
                    ["NA", "Noura Al Ameri", "Customer Experience", "Champions every customer's happiness and satisfaction."],
                ];
                foreach ($team as $i => $t): ?>
                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="<?= $i * 100 ?>">
                        <div class="team-card">
                            <div class="team-avatar"><?= $t[0] ?></div>
                            <div class="team-info">
                                <h6 class="fw-bold mb-0" style="color:#0F3D3E;font-size:15px;"><?= $t[1] ?></h6>
                                <p class="text-muted mb-2" style="font-size:12px;letter-spacing:1px;text-transform:uppercase;">
                                    <?= $t[2] ?>
                                </p>
                                <p class="text-muted mb-0" style="font-size:13px;line-height:1.6;"><?= $t[3] ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ▌ Section 6: Testimonials ──────────────────────────── -->
    <section style="background:#f8f8f8;padding:100px 0;">
        <div class="container-fluid px-3 px-md-4 px-lg-5">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="story-pill">💬 What Our Customers Say</span>
                <h2 class="fw-bold mt-3" style="font-size:clamp(2rem,4vw,3rem);color:#0F3D3E;">Real Stories, Real Trust</h2>
            </div>

            <div class="row g-4">
                <?php
                $testimonials = [
                    ['⭐⭐⭐⭐⭐', 'Noura Al Maktoum', 'Dubai, UAE', 'Edluxury changed how I shop. Every single item I have ordered has been exactly as described — premium, authentic, and beautifully packaged.'],
                    ['⭐⭐⭐⭐⭐', 'Sultan Al Qasimi', 'Sharjah, UAE', 'I have ordered from them 5 times and each time feels like a luxury unboxing experience. The delivery speed is unmatched in the UAE.'],
                    ['⭐⭐⭐⭐⭐', 'Amna Al Sayed', 'Abu Dhabi, UAE', 'Best online shopping experience in Abu Dhabi. Their concierge team handled my query in minutes. Absolutely 5-star service!'],
                    ['⭐⭐⭐⭐⭐', 'Rashed Al Blooshi', 'Ajman, UAE', 'I was nervous about COD shopping online but Edluxury made it so easy and trustworthy. Never had a single problem.'],
                ];
                foreach ($testimonials as $i => $t): ?>
                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="<?= $i * 100 ?>">
                        <div class="p-4 rounded-4 h-100"
                            style="background:white;border:1px solid #eee;box-shadow:0 4px 20px rgba(0,0,0,0.04);">
                            <div class="mb-3" style="font-size:16px;letter-spacing:2px;"><?= $t[0] ?></div>
                            <p class="text-muted mb-4" style="font-size:15px;line-height:1.8;font-style:italic;">"<?= $t[3] ?>"
                            </p>
                            <div class="d-flex align-items-center gap-3">
                                <div
                                    style="width:44px;height:44px;background:linear-gradient(135deg,#0F3D3E,#A69C63);border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-weight:900;font-size:16px;">
                                    <?= $t[1][0] ?>
                                </div>
                                <div>
                                    <p class="fw-bold mb-0" style="font-size:14px;color:#0F3D3E;"><?= $t[1] ?></p>
                                    <p class="mb-0" style="font-size:12px;color:#999;"><?= $t[2] ?></p>
                                </div>
                                <div class="ms-auto">
                                    <span
                                        style="background:#A69C6318;color:#A69C63;font-size:10px;font-weight:700;padding:4px 10px;border-radius:50px;border:1px solid #A69C6330;">✓
                                        VERIFIED</span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ▌ Section 7: CTA Banner ────────────────────────────── -->
    <section class="cta-section">
        <div class="container-fluid px-3 px-md-4 px-lg-5 text-center position-relative z-1" data-aos="zoom-in">
            <p class="fw-bold text-dark mb-2" style="font-size:13px;letter-spacing:3px;text-transform:uppercase;">Ready to
                Experience Luxury?</p>
            <h2 class="fw-bold mb-4 text-dark" style="font-size:clamp(2rem,5vw,3.5rem);">Shop the Collection Today</h2>
            <p class="text-dark mb-5" style="font-size:17px;opacity:0.7;max-width:500px;margin:0 auto 32px;">
                Join over 10,000 satisfied customers across the UAE who trust Edluxury for their premium lifestyle needs.
            </p>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="<?php echo Helpers::url('products.php'); ?>" class="btn btn-dark fw-bold px-5 py-3 rounded-pill"
                    style="font-size:15px;">
                    <i class="bi bi-bag me-2"></i> Shop Now
                </a>
                <a href="<?php echo Helpers::url('page.php?slug=contact-us'); ?>"
                    class="btn btn-outline-dark fw-bold px-5 py-3 rounded-pill" style="font-size:15px;">
                    <i class="bi bi-envelope me-2"></i> Contact Us
                </a>
            </div>
        </div>
    </section>

    <!-- Counter Animate Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const counters = document.querySelectorAll('.counter-up');
            const speed = 30;

            const animateCounter = (el) => {
                const target = parseInt(el.getAttribute('data-target'));
                const step = Math.ceil(target / (1200 / speed));
                let current = 0;
                const timer = setInterval(() => {
                    current += step;
                    if (current >= target) { current = target; clearInterval(timer); }
                    el.textContent = current.toLocaleString();
                }, speed);
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(e => {
                    if (e.isIntersecting) {
                        animateCounter(e.target);
                        observer.unobserve(e.target);
                    }
                });
            }, { threshold: 0.5 });

            counters.forEach(c => observer.observe(c));
        });
    </script>

<?php else: ?>

    <!-- ═══════════════════════════════════════════════════════
     GENERIC / OTHER PAGES
     ═══════════════════════════════════════════════════════ -->

    <!-- Page Header -->
    <div class="py-5 bg-dark text-white mb-5 position-relative overflow-hidden">
        <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10"
            style="background-image: url('<?php echo Helpers::asset("images/pattern-bg.png"); ?>'); background-size: cover;">
        </div>
        <div class="container text-center position-relative z-1" data-aos="fade-down">
            <h1 class="display-4 fw-bold mb-2" style="color:#A69C63;">
                <?php echo Security::escape($page['title']); ?>
            </h1>
            <div class="mx-auto mt-3" style="width:60px;height:3px;background:#A69C63;border-radius:2px;"></div>
        </div>
    </div>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="cms-content mb-5 p-4 p-md-5 bg-white rounded-4 shadow-sm" data-aos="fade-up"
                    style="border:1px solid #eee;line-height:1.9;font-size:16px;color:#444;">
                    <?php echo $page['content']; ?>
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>

<!-- CONTACT US SPECIFIC CONTENT -->
<?php if ($slug === 'contact-us'): ?>
    <div class="container my-5">
        <div class="row g-4" data-aos="fade-up">
            <div class="col-md-5">
                <div class="p-4 bg-dark text-white rounded-4 shadow h-100 position-relative overflow-hidden">
                    <div class="position-absolute top-0 end-0 opacity-10">
                        <i class="bi bi-geo-alt-fill" style="font-size: 10rem;"></i>
                    </div>
                    <h3 class="fw-bold mb-4" style="color:#A69C63;">Get in Touch</h3>
                    <p class="mb-4 opacity-75">Have questions about our products or your order? We're here to help!</p>
                    <div class="d-flex align-items-start mb-4">
                        <div class="me-3" style="color:#A69C63;"><i class="bi bi-envelope fs-4"></i></div>
                        <div>
                            <h6 class="mb-1 fw-bold">Email Us</h6>
                            <a href="mailto:edluxury32@gmail.com"
                                class="text-white text-decoration-none opacity-75">edluxury32@gmail.com</a>
                        </div>
                    </div>
                    <div class="d-flex align-items-start mb-4">
                        <div class="me-3" style="color:#A69C63;"><i class="bi bi-geo-alt fs-4"></i></div>
                        <div>
                            <h6 class="mb-1 fw-bold">Location</h6>
                            <span class="opacity-75">Dubai, United Arab Emirates</span>
                        </div>
                    </div>
                    <div class="mt-auto pt-4">
                        <small class="text-muted text-uppercase" style="letter-spacing:2px;">Support Hours</small>
                        <div class="d-flex justify-content-between mt-2 border-bottom border-secondary pb-2">
                            <span>Mon - Fri</span><span>9:00 AM - 9:00 PM GST</span>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <span>Sat - Sun</span><span>10:00 AM - 6:00 PM GST</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <form class="p-5 border rounded-4 shadow-sm h-100 bg-white"
                    onsubmit="event.preventDefault(); alert('Message sent! We will reply within 2 hours.');">
                    <h3 class="fw-bold mb-4" style="color:#0F3D3E;">Send a Message</h3>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small text-uppercase text-muted fw-bold">Name</label>
                            <input type="text" class="form-control bg-light border-0 py-3" required placeholder="Your Name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-uppercase text-muted fw-bold">Email</label>
                            <input type="email" class="form-control bg-light border-0 py-3" required
                                placeholder="your@email.com">
                        </div>
                    </div>
                    <div class="mb-3 mt-3">
                        <label class="form-label small text-uppercase text-muted fw-bold">Subject</label>
                        <input type="text" class="form-control bg-light border-0 py-3" required placeholder="Order Inquiry">
                    </div>
                    <div class="mb-4">
                        <label class="form-label small text-uppercase text-muted fw-bold">Message</label>
                        <textarea class="form-control bg-light border-0 py-3" rows="5" required
                            placeholder="How can we help?"></textarea>
                    </div>
                    <button type="submit" class="btn w-100 py-3 fw-bold text-white rounded-pill"
                        style="background:linear-gradient(135deg,#0F3D3E,#1a5f61);">
                        <i class="bi bi-send me-2"></i> Send Message
                    </button>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<style>
    .cms-content {
        font-family: var(--font-body, sans-serif);
    }

    .cms-content h2,
    .cms-content h3 {
        margin-top: 2rem;
        margin-bottom: 1rem;
    }
</style>

<?php require_once 'includes/footer.php'; ?>
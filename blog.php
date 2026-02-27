<?php
$pageTitle = 'Blog';
require_once 'includes/header.php';
?>

<div class="container py-5 mt-5">
    <div class="text-center mb-5" data-aos="fade-up">
        <h1 class="display-4 fw-bold">Our Blog</h1>
        <p class="text-muted">Stay updated with the latest trends and stories from Edluxury.</p>
    </div>

    <div class="row g-4">
        <!-- Placeholder Blog Posts -->
        <?php for ($i = 1; $i <= 3; $i++): ?>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="<?php echo $i * 100; ?>">
                <div class="card border-0 shadow-sm h-100 overflow-hidden">
                    <div class="ratio ratio-16x9 bg-light">
                        <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&q=80&w=800"
                            alt="Blog post" class="object-fit-cover">
                    </div>
                    <div class="card-body p-4">
                        <div class="text-primary-gold small fw-bold mb-2">TRENDS • JAN 2026</div>
                        <h5 class="card-title fw-bold">Top 10 Luxury Trends to Follow This Season</h5>
                        <p class="card-text text-muted">Discover the most sought-after styles that are defining the luxury
                            landscape in the UAE right now...</p>
                        <a href="#" class="btn btn-outline-dark btn-sm rounded-pill px-4">Read More</a>
                    </div>
                </div>
            </div>
        <?php endfor; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
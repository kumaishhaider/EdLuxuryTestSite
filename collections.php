<?php
/**
 * All Collections Page
 */
$pageTitle = 'Collections';
require_once 'includes/header.php';

$db = Database::getInstance();
$collections = $db->fetchAll("SELECT * FROM collections WHERE status = 'active' ORDER BY sort_order ASC");
?>

<div class="container my-5">
    <h1 class="fw-bold mb-5">All Collections</h1>

    <div class="row g-4">
        <?php foreach ($collections as $collection): ?>
            <div class="col-md-4">
                <a href="<?php echo Helpers::url('collection.php?slug=' . $collection['slug']); ?>"
                    class="text-decoration-none group">
                    <div class="collection-card-wrapper border mb-3 overflow-hidden">
                        <?php
                        $image = $collection['image'] ? Helpers::upload($collection['image']) : Helpers::asset('images/placeholder-collection.jpg');
                        ?>
                        <img src="<?php echo $image; ?>" alt="<?php echo Security::escape($collection['name']); ?>"
                            class="img-fluid w-100 transition-transform duration-500 hover:scale-110"
                            style="aspect-ratio: 16/9; object-fit: cover;">
                    </div>
                    <h3 class="fw-bold text-dark">
                        <?php echo Security::escape($collection['name']); ?>
                    </h3>
                    <p class="text-muted small">
                        <?php echo Security::escape($collection['description']); ?>
                    </p>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
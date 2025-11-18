<?php
require_once 'config.php';
require_once 'database.php';
require_once 'functions.php';

$pageTitle = '404 - Page Not Found';

include 'includes/header.php';
?>

<div class="error-page">
    <div class="container">
        <div class="error-content">
            <h1>404</h1>
            <h2>Page Not Found</h2>
            <p>Sorry, the page you're looking for doesn't exist or has been moved.</p>
            <div class="error-actions">
                <a href="<?php echo BASE_URL; ?>/" class="btn btn-primary">Go Home</a>
                <a href="<?php echo BASE_URL; ?>/products/" class="btn btn-secondary">Browse Products</a>
                <a href="<?php echo BASE_URL; ?>/blog/" class="btn btn-secondary">Read Blog</a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

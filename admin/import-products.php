<?php
require_once '../config.php';
require_once '../database.php';
require_once '../functions.php';

requireLogin();

$pageTitle = 'Import Products';

$result = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Run the import
    ob_start();
    include '../import-products.php';
    $output = ob_get_clean();

    $result = [
        'success' => true,
        'output' => $output
    ];
}

include 'header.php';
?>

<div class="admin-page">
    <div class="page-header">
        <h1>Import Products</h1>
        <a href="products.php" class="btn btn-secondary">← Back to Products</a>
    </div>

    <div class="import-section">
        <div class="info-box">
            <h3>About Product Import</h3>
            <p>This will import all products from <code>products.json</code> file in the root directory.</p>
            <ul>
                <li>Existing products (matching ID) will be updated</li>
                <li>New products will be added</li>
                <li>All product URLs and tags will be refreshed</li>
            </ul>
        </div>

        <?php if ($result): ?>
            <div class="result-box <?php echo $result['success'] ? 'success' : 'error'; ?>">
                <h3>Import Results</h3>
                <pre><?php echo escape($result['output']); ?></pre>
            </div>
        <?php endif; ?>

        <form method="POST" onsubmit="return confirm('This will update all products. Continue?');">
            <button type="submit" class="btn btn-primary btn-lg">
                <?php echo $result ? 'Import Again' : 'Start Import'; ?>
            </button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>

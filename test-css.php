<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>CSS Test</title>
    <style>
        body { font-family: monospace; padding: 20px; }
        .test { background: #f0f0f0; padding: 10px; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>CSS Loading Test</h1>

    <div class="test">
        <h2>Configuration</h2>
        <p><strong>BASE_URL:</strong> <?php echo BASE_URL; ?></p>
    </div>

    <div class="test">
        <h2>Expected CSS URLs</h2>
        <p><strong>Admin CSS:</strong> <?php echo BASE_URL; ?>/assets/css/admin.css</p>
        <p><strong>Frontend CSS:</strong> <?php echo BASE_URL; ?>/assets/css/style.css</p>
    </div>

    <div class="test">
        <h2>Test CSS Files</h2>
        <p><a href="<?php echo BASE_URL; ?>/assets/css/admin.css" target="_blank">Click to test admin.css</a></p>
        <p><a href="<?php echo BASE_URL; ?>/assets/css/style.css" target="_blank">Click to test style.css</a></p>
    </div>

    <div class="test">
        <h2>Admin CSS Load Test</h2>
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/admin.css">
        <div class="admin-nav" style="width: 250px; min-height: 100px; border: 2px solid red;">
            <p>If this has admin styling (dark background), CSS is loading!</p>
            <p>If you see red border and no dark bg, CSS is NOT loading.</p>
        </div>
    </div>

    <div class="test">
        <h2>Browser Console Check</h2>
        <p>Open browser Developer Tools (F12), check Console for any errors loading CSS files.</p>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? escape($pageTitle) . ' - ' : ''; ?>Admin - <?php echo escape(getSetting('site_name')); ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/admin.css">
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
</head>
<body class="admin-body">
    <nav class="admin-nav">
        <div class="nav-header">
            <a href="<?php echo BASE_URL; ?>/admin/" class="nav-logo">
                <?php echo escape(getSetting('site_name')); ?> Admin
            </a>
        </div>
        <ul class="nav-menu">
            <li><a href="<?php echo BASE_URL; ?>/admin/">Dashboard</a></li>
            <li><a href="<?php echo BASE_URL; ?>/admin/posts.php">Blog Posts</a></li>
            <li><a href="<?php echo BASE_URL; ?>/admin/products.php">Products</a></li>
            <li><a href="<?php echo BASE_URL; ?>/admin/pages.php">Pages</a></li>
            <li><a href="<?php echo BASE_URL; ?>/admin/settings.php">Settings</a></li>
        </ul>
        <div class="nav-footer">
            <span>Welcome, <?php echo escape($_SESSION['username']); ?></span>
            <a href="<?php echo BASE_URL; ?>/" target="_blank">View Site</a>
            <a href="<?php echo BASE_URL; ?>/admin/logout.php">Logout</a>
        </div>
    </nav>

    <main class="admin-content">
        <?php if (hasFlash('success')): ?>
            <div class="alert alert-success"><?php echo escape(getFlash('success')); ?></div>
        <?php endif; ?>

        <?php if (hasFlash('error')): ?>
            <div class="alert alert-error"><?php echo escape(getFlash('error')); ?></div>
        <?php endif; ?>

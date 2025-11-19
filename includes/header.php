<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo escape($seoDescription ?? getSetting('site_description')); ?>">
    <title><?php echo escape($seoTitle ?? (($pageTitle ?? '') ? $pageTitle . ' - ' . getSetting('site_name') : getSetting('site_name'))); ?></title>

    <!-- SEO Meta Tags -->
    <meta property="og:title" content="<?php echo escape($seoTitle ?? $pageTitle ?? getSetting('site_name')); ?>">
    <meta property="og:description" content="<?php echo escape($seoDescription ?? getSetting('site_description')); ?>">
    <meta property="og:type" content="website">
    <?php if (isset($ogImage)): ?>
    <meta property="og:image" content="<?php echo escape($ogImage); ?>">
    <?php endif; ?>

    <!-- Favicon -->
    <link rel="icon" href="<?php echo BASE_URL; ?>/assets/favicon.ico" type="image/x-icon">

    <!-- Styles -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="<?php echo BASE_URL; ?>/">
                        <?php if (getSetting('logo_url')): ?>
                            <img src="<?php echo escape(getSetting('logo_url')); ?>" alt="<?php echo escape(getSetting('site_name')); ?>">
                        <?php else: ?>
                            <span class="logo-text"><?php echo escape(getSetting('site_name')); ?></span>
                        <?php endif; ?>
                    </a>
                </div>

                <button class="mobile-menu-toggle" id="mobileMenuToggle">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>

                <nav class="main-nav" id="mainNav">
                    <ul>
                        <li><a href="<?php echo BASE_URL; ?>/">Home</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/products/">Products</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/blog/">Blog</a></li>

                        <?php
                        // Get menu pages
                        $menuPages = db()->fetchAll('SELECT * FROM pages WHERE show_in_menu = 1 ORDER BY menu_order ASC, title ASC');
                        foreach ($menuPages as $menuPage):
                        ?>
                            <li><a href="<?php echo BASE_URL; ?>/<?php echo $menuPage['slug']; ?>"><?php echo escape($menuPage['title']); ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main class="site-content">

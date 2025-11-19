    </main>

    <footer class="site-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3><?php echo escape(getSetting('site_name')); ?></h3>
                    <p><?php echo escape(getSetting('site_tagline')); ?></p>
                </div>

                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="<?php echo BASE_URL; ?>/">Home</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/products/">Products</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/blog/">Blog</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h4>Legal</h4>
                    <ul class="footer-links">
                        <?php
                        $footerPages = db()->fetchAll("SELECT * FROM pages WHERE slug IN ('privacy-policy', 'affiliate-disclosure', 'terms-of-service') ORDER BY title ASC");
                        foreach ($footerPages as $page):
                        ?>
                            <li><a href="<?php echo BASE_URL; ?>/<?php echo $page['slug']; ?>"><?php echo escape($page['title']); ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="footer-section">
                    <h4>Categories</h4>
                    <ul class="footer-links">
                        <?php
                        $categories = array_slice(getCategories('products'), 0, 5);
                        foreach ($categories as $category):
                        ?>
                            <li><a href="<?php echo BASE_URL; ?>/products/?category=<?php echo urlencode($category); ?>"><?php echo escape($category); ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> <?php echo escape(getSetting('site_name')); ?>. All rights reserved.</p>
                <p class="disclaimer">
                    <strong>Affiliate Disclosure:</strong> As an Amazon Associate, we earn from qualifying purchases.
                    We may receive commissions from purchases made through links on this site.
                </p>
            </div>
        </div>
    </footer>

    <script src="<?php echo BASE_URL; ?>/assets/js/main.js"></script>
</body>
</html>

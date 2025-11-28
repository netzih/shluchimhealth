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
                        foreach ($footerPages as $footerPage):
                        ?>
                            <li><a href="<?php echo BASE_URL; ?>/<?php echo $footerPage['slug']; ?>"><?php echo escape($footerPage['title']); ?></a></li>
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
                    <strong>Affiliate Disclosure:</strong> As an Amazon Associate I earn from qualifying purchases.
                    This site may receive commissions from purchases made through links on this site, at no extra cost to you.
                </p>
                <p class="disclaimer" style="margin-top: 1rem; font-size: 0.9rem; color: #666;">
                    <strong>⚕️ Medical Disclaimer:</strong> The information on this website is for educational purposes only and is not intended as medical advice, diagnosis, or treatment.
                    Always consult a qualified healthcare professional before starting any supplement regimen or making changes to your health routine.
                    Individual results may vary.
                </p>
            </div>
        </div>
    </footer>

    <?php if (getSetting('enable_booking_widget') && getSetting('calcom_username')): ?>
    <!-- Floating Booking Button -->
    <div class="floating-booking-btn">
        <button onclick="openBookingModal()" class="booking-fab">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
            <span>Book Now</span>
        </button>
    </div>

    <style>
    .floating-booking-btn {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 1000;
    }

    .booking-fab {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem 1.5rem;
        background: #2563eb;
        color: white;
        border: none;
        border-radius: 50px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
        transition: all 0.3s ease;
    }

    .booking-fab:hover {
        background: #1d4ed8;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(37, 99, 235, 0.5);
    }

    .booking-fab svg {
        width: 20px;
        height: 20px;
    }

    @media (max-width: 768px) {
        .floating-booking-btn {
            bottom: 20px;
            right: 20px;
        }

        .booking-fab {
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
        }

        .booking-fab svg {
            width: 18px;
            height: 18px;
        }
    }
    </style>

    <script>
    function openBookingModal() {
        window.location.href = '<?php echo BASE_URL; ?>/booking';
    }
    </script>
    <?php endif; ?>

    <script src="<?php echo BASE_URL; ?>/assets/js/main.js"></script>
</body>
</html>

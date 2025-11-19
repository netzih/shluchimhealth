<?php
require_once 'config.php';
require_once 'database.php';
require_once 'functions.php';

// Check if Cal.com is configured
$calcomUsername = getSetting('calcom_username');
$calcomEventType = getSetting('calcom_event_type') ?: '30min';

// SEO
$pageTitle = 'Book a Consultation';
$seoTitle = 'Book a Health Consultation | ' . getSetting('site_name');
$seoDescription = 'Schedule a personalized health consultation to discuss your wellness goals, supplement recommendations, and get expert guidance on your health journey.';

include 'includes/header.php';
?>

<div class="booking-page">
    <div class="container">
        <div class="booking-header">
            <h1>Book a Consultation</h1>
            <p class="booking-intro">
                Schedule a personalized health consultation to discuss your wellness goals, get supplement recommendations,
                and receive expert guidance tailored to your unique needs.
            </p>
        </div>

        <?php if (empty($calcomUsername)): ?>
            <div class="booking-not-configured">
                <div class="alert alert-info">
                    <h3>Consultations Coming Soon!</h3>
                    <p>We're setting up our booking system. Please check back soon or contact us directly for consultation inquiries.</p>
                    <a href="<?php echo BASE_URL; ?>/contact" class="btn btn-primary">Contact Us</a>
                </div>
            </div>
        <?php else: ?>
            <div class="booking-content">
                <div class="booking-benefits">
                    <h2>What to Expect</h2>
                    <ul class="benefits-list">
                        <li>
                            <strong>Personalized Recommendations</strong>
                            <p>Get supplement and wellness advice tailored to your specific health goals and conditions.</p>
                        </li>
                        <li>
                            <strong>Expert Guidance</strong>
                            <p>Receive evidence-based recommendations backed by research and years of experience.</p>
                        </li>
                        <li>
                            <strong>Product Insights</strong>
                            <p>Learn which products are worth your investment and how to choose quality supplements.</p>
                        </li>
                        <li>
                            <strong>Follow-Up Support</strong>
                            <p>Schedule follow-up consultations to track progress and adjust recommendations as needed.</p>
                        </li>
                    </ul>
                </div>

                <div class="booking-calendar">
                    <h2>Choose Your Time</h2>

                    <!-- Cal.com Inline Embed -->
                    <div class="cal-inline-container">
                        <div
                            class="cal-inline"
                            data-cal-link="<?php echo escape($calcomUsername); ?>/<?php echo escape($calcomEventType); ?>"
                            data-cal-config='{"layout":"month_view","theme":"light"}'>
                        </div>
                    </div>
                </div>
            </div>

            <div class="booking-faq">
                <h2>Frequently Asked Questions</h2>

                <div class="faq-item">
                    <h3>How long is a consultation?</h3>
                    <p>Standard consultations are 30 minutes, giving us enough time to discuss your health goals and create a personalized plan.</p>
                </div>

                <div class="faq-item">
                    <h3>How do I prepare for my consultation?</h3>
                    <p>Please have a list of your current supplements, medications, and any specific health concerns you'd like to address. If you have recent lab work, that can be helpful too.</p>
                </div>

                <div class="faq-item">
                    <h3>Will I receive recommendations after the call?</h3>
                    <p>Yes! You'll receive a follow-up email with personalized supplement recommendations and any resources discussed during our consultation.</p>
                </div>

                <div class="faq-item">
                    <h3>Can I schedule a follow-up?</h3>
                    <p>Absolutely! Follow-up consultations help us track your progress and make adjustments as needed. You can book follow-ups at any time.</p>
                </div>
            </div>

            <div class="booking-disclaimer">
                <p><strong>Disclaimer:</strong> Our consultations provide educational information and general wellness guidance. They are not a substitute for professional medical advice, diagnosis, or treatment. Always consult with your physician before making changes to your health regimen, especially if you have existing medical conditions or take medications.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.booking-page {
    padding: 3rem 0;
}

.booking-header {
    text-align: center;
    max-width: 800px;
    margin: 0 auto 3rem;
}

.booking-header h1 {
    font-size: 2.5rem;
    color: #1a202c;
    margin-bottom: 1rem;
}

.booking-intro {
    font-size: 1.125rem;
    color: #4a5568;
    line-height: 1.75;
}

.booking-not-configured {
    max-width: 600px;
    margin: 3rem auto;
}

.booking-content {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 3rem;
    margin-bottom: 3rem;
}

@media (max-width: 968px) {
    .booking-content {
        grid-template-columns: 1fr;
    }
}

.booking-benefits {
    background: #f7fafc;
    padding: 2rem;
    border-radius: 8px;
    height: fit-content;
}

.booking-benefits h2 {
    font-size: 1.5rem;
    margin-bottom: 1.5rem;
    color: #2d3748;
}

.benefits-list {
    list-style: none;
    padding: 0;
}

.benefits-list li {
    margin-bottom: 1.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid #e2e8f0;
}

.benefits-list li:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.benefits-list strong {
    display: block;
    color: #2563eb;
    font-size: 1.125rem;
    margin-bottom: 0.5rem;
}

.benefits-list p {
    color: #4a5568;
    margin: 0;
    line-height: 1.6;
}

.booking-calendar {
    background: white;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.booking-calendar h2 {
    font-size: 1.5rem;
    margin-bottom: 1.5rem;
    color: #2d3748;
}

.cal-inline-container {
    min-height: 600px;
}

.booking-faq {
    max-width: 800px;
    margin: 3rem auto;
    padding: 2rem;
    background: #f7fafc;
    border-radius: 8px;
}

.booking-faq h2 {
    font-size: 1.75rem;
    margin-bottom: 2rem;
    color: #2d3748;
    text-align: center;
}

.faq-item {
    margin-bottom: 2rem;
}

.faq-item:last-child {
    margin-bottom: 0;
}

.faq-item h3 {
    font-size: 1.25rem;
    color: #2563eb;
    margin-bottom: 0.75rem;
}

.faq-item p {
    color: #4a5568;
    line-height: 1.6;
    margin: 0;
}

.booking-disclaimer {
    max-width: 800px;
    margin: 2rem auto 0;
    padding: 1.5rem;
    background: #fef3c7;
    border-left: 4px solid #f59e0b;
    border-radius: 4px;
}

.booking-disclaimer p {
    margin: 0;
    color: #78350f;
    font-size: 0.875rem;
    line-height: 1.6;
}

.alert {
    padding: 2rem;
    border-radius: 8px;
    text-align: center;
}

.alert-info {
    background: #eff6ff;
    border: 1px solid #bfdbfe;
}

.alert-info h3 {
    color: #1e40af;
    margin-bottom: 1rem;
}

.alert-info p {
    color: #1e3a8a;
    margin-bottom: 1.5rem;
}
</style>

<!-- Cal.com Embed Script -->
<script>
(function (C, A, L) {
  let p = function (a, ar) { a.q.push(ar); };
  let d = C.document;
  C.Cal = C.Cal || function () {
    let cal = C.Cal;
    let ar = arguments;
    if (!cal.loaded) {
      cal.ns = {};
      cal.q = cal.q || [];
      d.head.appendChild(d.createElement("script")).src = A;
      cal.loaded = true;
    }
    if (ar[0] === L) {
      const api = function () { p(api, arguments); };
      const namespace = ar[1];
      api.q = api.q || [];
      typeof namespace === "string" ? (cal.ns[namespace] = api) && p(api, ar) : p(cal, ar);
      return;
    }
    p(cal, ar);
  };
})(window, "https://app.cal.com/embed/embed.js", "init");

Cal("init", {origin:"https://cal.com"});

// Optional: UI settings
Cal("ui", {
  "styles": {"branding": {"brandColor": "#2563eb"}},
  "hideEventTypeDetails": false,
  "layout": "month_view"
});
</script>

<?php include 'includes/footer.php'; ?>

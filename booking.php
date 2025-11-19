<?php
require_once 'config.php';
require_once 'database.php';
require_once 'functions.php';

// Check if Cal.com is configured
$calcomUsername = getSetting('calcom_username');
$calcomNewClientEvent = getSetting('calcom_event_type') ?: '30min';
$calcomFollowupEvent = getSetting('calcom_followup_event_type') ?: 'follow-up';

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
                    <h2>Choose Your Consultation Type</h2>

                    <!-- Booking Type Selector -->
                    <div class="booking-tabs">
                        <button class="booking-tab active" onclick="switchTab('new-client')">
                            <div class="tab-icon">🌟</div>
                            <div class="tab-title">New Client</div>
                            <div class="tab-desc">First-time consultation</div>
                        </button>
                        <button class="booking-tab" onclick="switchTab('follow-up')">
                            <div class="tab-icon">🔄</div>
                            <div class="tab-title">Follow-Up</div>
                            <div class="tab-desc">Returning client check-in</div>
                        </button>
                    </div>

                    <!-- Calendar Wrapper for positioning -->
                    <div class="calendars-wrapper">
                        <!-- New Client Calendar -->
                        <div id="new-client-calendar" class="calendar-container active">
                            <div class="calendar-header">
                                <h3>New Client Consultation</h3>
                                <p>Perfect for first-time clients ready to start their wellness journey</p>
                            </div>
                            <div
                                class="cal-inline"
                                id="cal-new-client"
                                style="width:100%;height:600px;overflow:scroll">
                            </div>
                        </div>

                        <!-- Follow-Up Calendar -->
                        <div id="follow-up-calendar" class="calendar-container">
                            <div class="calendar-header">
                                <h3>Follow-Up Consultation</h3>
                                <p>For returning clients to review progress and adjust recommendations</p>
                            </div>
                            <div
                                class="cal-inline"
                                id="cal-follow-up"
                                style="width:100%;height:600px;overflow:scroll">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="booking-faq">
                <h2>Frequently Asked Questions</h2>

                <div class="faq-item">
                    <h3>What's the difference between New Client and Follow-Up?</h3>
                    <p><strong>New Client:</strong> Comprehensive initial consultation where we discuss your health history, current concerns, and create a personalized plan.<br>
                    <strong>Follow-Up:</strong> Shorter session for existing clients to review progress, discuss adjustments, and answer questions.</p>
                </div>

                <div class="faq-item">
                    <h3>How long is each consultation?</h3>
                    <p>Consultation lengths vary by type. You'll see the duration when selecting your appointment time.</p>
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
                    <h3>Can I reschedule or cancel?</h3>
                    <p>Yes, you can reschedule or cancel your appointment using the link in your confirmation email.</p>
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

.booking-tabs {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 2rem;
}

.booking-tab {
    background: #f7fafc;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    padding: 1.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: center;
}

.booking-tab:hover {
    border-color: #2563eb;
    background: #eff6ff;
}

.booking-tab.active {
    background: #2563eb;
    border-color: #2563eb;
    color: white;
}

.tab-icon {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.tab-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.tab-desc {
    font-size: 0.875rem;
    opacity: 0.8;
}

.calendars-wrapper {
    position: relative;
    min-height: 700px;
}

.calendar-container {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease;
}

.calendar-container.active {
    position: relative;
    opacity: 1;
    pointer-events: auto;
}

.calendar-header {
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #e2e8f0;
}

.calendar-header h3 {
    font-size: 1.25rem;
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.calendar-header p {
    color: #4a5568;
    margin: 0;
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

<script>
// Cal.com embed initialization
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

// Initialize both calendars on page load
// Cal.com works better when calendars are initialized immediately, even if hidden
Cal("inline", {
  elementOrSelector: "#cal-new-client",
  calLink: "<?php echo escape($calcomUsername); ?>/<?php echo escape($calcomNewClientEvent); ?>",
  layout: "month_view",
  config: {
    theme: "light"
  }
});

Cal("inline", {
  elementOrSelector: "#cal-follow-up",
  calLink: "<?php echo escape($calcomUsername); ?>/<?php echo escape($calcomFollowupEvent); ?>",
  layout: "month_view",
  config: {
    theme: "light"
  }
});

// Tab switching
function switchTab(type) {
    // Update tabs
    const tabs = document.querySelectorAll('.booking-tab');
    tabs.forEach(tab => tab.classList.remove('active'));
    event.target.closest('.booking-tab').classList.add('active');

    // Update calendars
    document.getElementById('new-client-calendar').classList.remove('active');
    document.getElementById('follow-up-calendar').classList.remove('active');

    if (type === 'new-client') {
        document.getElementById('new-client-calendar').classList.add('active');
    } else {
        document.getElementById('follow-up-calendar').classList.add('active');
    }
}
</script>

<?php include 'includes/footer.php'; ?>

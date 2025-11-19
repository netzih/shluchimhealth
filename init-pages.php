<?php
/**
 * Initialize required pages (Privacy Policy, Affiliate Disclosure, etc.)
 * Run this once: php init-pages.php
 */

require_once 'config.php';
require_once 'database.php';
require_once 'functions.php';

echo "=== Initializing Required Pages ===\n\n";

$pages = [
    [
        'title' => 'Privacy Policy',
        'slug' => 'privacy-policy',
        'content' => '<h2>Privacy Policy</h2>

<p>Last updated: ' . date('F j, Y') . '</p>

<h3>Introduction</h3>
<p>At ' . getSetting('site_name') . ', we are committed to protecting your privacy. This Privacy Policy explains how we collect, use, and safeguard your information when you visit our website.</p>

<h3>Information We Collect</h3>
<p>We may collect the following types of information:</p>
<ul>
    <li><strong>Usage Data:</strong> We collect information about how you interact with our website, including pages visited, time spent on pages, and navigation paths.</li>
    <li><strong>Cookies:</strong> We use cookies and similar tracking technologies to enhance your browsing experience.</li>
</ul>

<h3>How We Use Your Information</h3>
<p>We use the information we collect to:</p>
<ul>
    <li>Improve our website and user experience</li>
    <li>Analyze website traffic and trends</li>
    <li>Provide relevant content and recommendations</li>
</ul>

<h3>Third-Party Services</h3>
<p>We use third-party services such as Amazon Associates and other affiliate programs. These services may collect information about your interactions with our site and product links.</p>

<h3>Your Rights</h3>
<p>You have the right to access, correct, or delete your personal information. Please contact us if you wish to exercise these rights.</p>

<h3>Changes to This Policy</h3>
<p>We may update this Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page.</p>

<h3>Contact Us</h3>
<p>If you have any questions about this Privacy Policy, please contact us.</p>',
        'show_in_menu' => 0,
        'menu_order' => 90
    ],
    [
        'title' => 'Affiliate Disclosure',
        'slug' => 'affiliate-disclosure',
        'content' => '<h2>Affiliate Disclosure</h2>

<p>Last updated: ' . date('F j, Y') . '</p>

<h3>Amazon Associates Program</h3>
<p>' . getSetting('site_name') . ' is a participant in the Amazon Services LLC Associates Program, an affiliate advertising program designed to provide a means for sites to earn advertising fees by advertising and linking to Amazon.com and affiliated sites.</p>

<h3>How Affiliate Links Work</h3>
<p>When you click on an affiliate link and make a purchase, we may earn a commission at no additional cost to you. This helps support our website and allows us to continue providing valuable content and recommendations.</p>

<h3>Our Recommendations</h3>
<p>We only recommend products that we genuinely believe in and think will provide value to our readers. Our affiliate relationships do not influence our honest opinions and reviews.</p>

<h3>Other Affiliate Programs</h3>
<p>In addition to Amazon Associates, we may participate in affiliate programs with other retailers and service providers, including but not limited to:</p>
<ul>
    <li>iHerb</li>
    <li>Direct manufacturer affiliate programs</li>
    <li>Other health and wellness retailers</li>
</ul>

<h3>Your Support</h3>
<p>When you make a purchase through our affiliate links, you are helping to support ' . getSetting('site_name') . ' at no extra cost to you. We appreciate your support!</p>

<h3>Transparency</h3>
<p>We are committed to being transparent about our affiliate relationships. All affiliate links on our website are clearly marked, and we always disclose when content contains affiliate links.</p>

<h3>Questions</h3>
<p>If you have any questions about our affiliate relationships or this disclosure, please contact us.</p>',
        'show_in_menu' => 0,
        'menu_order' => 91
    ],
    [
        'title' => 'About Us',
        'slug' => 'about',
        'content' => '<h2>About ' . getSetting('site_name') . '</h2>

<p>Welcome to ' . getSetting('site_name') . ', your trusted source for health and wellness product recommendations.</p>

<h3>Our Mission</h3>
<p>Our mission is to help you make informed decisions about health and wellness products by providing honest, detailed reviews and recommendations. We carefully research and evaluate products to ensure we only recommend items we truly believe in.</p>

<h3>What We Do</h3>
<p>We provide:</p>
<ul>
    <li><strong>In-Depth Product Reviews:</strong> Comprehensive analysis of health supplements and wellness products</li>
    <li><strong>Educational Content:</strong> Informative articles about health, nutrition, and wellness</li>
    <li><strong>Comparison Tools:</strong> Multiple purchasing options from various retailers</li>
    <li><strong>Expert Insights:</strong> Evidence-based recommendations and guidance</li>
</ul>

<h3>Why Trust Us</h3>
<p>We are committed to:</p>
<ul>
    <li>Providing honest, unbiased reviews</li>
    <li>Thoroughly researching products before recommending them</li>
    <li>Being transparent about our affiliate relationships</li>
    <li>Putting your health and wellness first</li>
</ul>

<h3>Our Values</h3>
<ul>
    <li><strong>Integrity:</strong> We only recommend products we believe in</li>
    <li><strong>Transparency:</strong> We clearly disclose all affiliate relationships</li>
    <li><strong>Quality:</strong> We prioritize high-quality, effective products</li>
    <li><strong>Education:</strong> We strive to provide valuable, informative content</li>
</ul>

<h3>Get in Touch</h3>
<p>We love hearing from our readers! If you have questions, suggestions, or feedback, please don\'t hesitate to contact us.</p>',
        'show_in_menu' => 1,
        'menu_order' => 10
    ],
    [
        'title' => 'Contact',
        'slug' => 'contact',
        'content' => '<h2>Contact Us</h2>

<p>We\'d love to hear from you! Whether you have questions about our products, feedback about our content, or just want to say hello, feel free to reach out.</p>

<h3>Get in Touch</h3>
<p>You can contact us through the following methods:</p>

<h4>General Inquiries</h4>
<p>For general questions, product inquiries, or feedback, please email us.</p>

<h4>Partnership Opportunities</h4>
<p>Interested in partnering with ' . getSetting('site_name') . '? We\'re always open to collaboration opportunities with brands that align with our values.</p>

<h4>Media & Press</h4>
<p>For media inquiries or press-related questions, please contact our media team.</p>

<h3>Response Time</h3>
<p>We typically respond to all inquiries within 24-48 hours during business days. Please note that response times may be longer during weekends and holidays.</p>

<h3>Follow Us</h3>
<p>Stay updated with our latest product reviews, health tips, and wellness insights by following us on social media.</p>',
        'show_in_menu' => 1,
        'menu_order' => 20
    ]
];

foreach ($pages as $pageData) {
    // Check if page already exists
    $exists = db()->fetch('SELECT id FROM pages WHERE slug = :slug', ['slug' => $pageData['slug']]);

    if ($exists) {
        echo "⚠ Page '{$pageData['title']}' already exists. Skipping...\n";
        continue;
    }

    $pageData['created_at'] = date('Y-m-d H:i:s');
    $pageData['updated_at'] = date('Y-m-d H:i:s');
    $pageData['seo_title'] = $pageData['title'];
    $pageData['seo_description'] = getExcerpt($pageData['content']);

    $id = db()->insert('pages', $pageData);

    if ($id) {
        echo "✓ Created page: {$pageData['title']}\n";
    } else {
        echo "✗ Failed to create page: {$pageData['title']}\n";
    }
}

echo "\n=== Pages Initialization Complete ===\n";
echo "Total pages in database: " . db()->count('pages') . "\n";

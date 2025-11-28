#!/usr/bin/env php
<?php
/**
 * Fix Amazon Associates Compliance Issues
 *
 * This script automatically fixes critical compliance issues found by the verification script.
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';

if (php_sapi_name() !== 'cli') {
    die("This script must be run from the command line.\n");
}

echo "\n╔════════════════════════════════════════════════════════════════╗\n";
echo "║         FIXING AMAZON ASSOCIATES COMPLIANCE ISSUES            ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

$fixed = 0;
$issues = 0;

// ============================================================
// 1. UPDATE AFFILIATE DISCLOSURE PAGE
// ============================================================
echo "1. Updating Affiliate Disclosure Page...\n";
echo str_repeat("-", 70) . "\n";

$affiliatePage = db()->fetch('SELECT * FROM pages WHERE slug = "affiliate-disclosure"');

if ($affiliatePage) {
    $content = $affiliatePage['content'];

    // Check if it has the exact Amazon wording
    if (stripos($content, 'As an Amazon Associate I earn from qualifying purchases') === false) {
        // Prepend the required statement
        $newContent = "<div style='background: #e3f2fd; padding: 20px; margin-bottom: 30px; border-left: 4px solid #2196F3; border-radius: 4px;'>\n";
        $newContent .= "<p style='margin: 0; font-size: 1.1rem;'><strong>📢 Required Disclosure:</strong></p>\n";
        $newContent .= "<p style='margin: 10px 0 0 0; font-size: 1.1rem;'><strong>As an Amazon Associate I earn from qualifying purchases.</strong></p>\n";
        $newContent .= "</div>\n\n";
        $newContent .= $content;

        db()->query(
            'UPDATE pages SET content = :content, updated_at = CURRENT_TIMESTAMP WHERE id = :id',
            ['content' => $newContent, 'id' => $affiliatePage['id']]
        );

        echo "✓ Added required Amazon Associate statement to Affiliate Disclosure\n";
        $fixed++;
    } else {
        echo "✓ Affiliate Disclosure already has correct Amazon statement\n";
    }
} else {
    echo "✗ Affiliate Disclosure page not found\n";
    $issues++;
}

echo "\n";

// ============================================================
// 2. CREATE TERMS OF SERVICE PAGE
// ============================================================
echo "2. Creating Terms of Service Page...\n";
echo str_repeat("-", 70) . "\n";

$termsPage = db()->fetch('SELECT * FROM pages WHERE slug = "terms-of-service"');

if (!$termsPage) {
    $termsContent = <<<'HTML'
<h1>Terms of Service</h1>

<p><strong>Last Updated:</strong> November 28, 2025</p>

<h2>1. Acceptance of Terms</h2>
<p>By accessing and using this website, you accept and agree to be bound by the terms and provisions of this agreement. If you do not agree to these terms, please do not use this website.</p>

<h2>2. Use of Website</h2>
<p>This website is for informational and educational purposes only. You may use this website for lawful purposes only and in accordance with these Terms of Service.</p>

<h3>You agree NOT to:</h3>
<ul>
    <li>Use the website in any way that violates any applicable federal, state, local, or international law or regulation</li>
    <li>Reproduce, duplicate, copy, or resell any part of the website without our express written permission</li>
    <li>Use automated systems or software to extract data from this website for commercial purposes ("scraping")</li>
    <li>Attempt to gain unauthorized access to any portion of the website or any systems or networks connected to the website</li>
</ul>

<h2>3. Intellectual Property Rights</h2>
<p>The content on this website, including but not limited to text, graphics, logos, images, and software, is the property of Shluchim Health or its content suppliers and is protected by copyright and other intellectual property laws.</p>

<h2>4. Affiliate Links & Disclosures</h2>
<p>This website contains affiliate links. When you click on links to various merchants on this site and make a purchase, this can result in this site earning a commission. We are a participant in the Amazon Services LLC Associates Program, an affiliate advertising program designed to provide a means for sites to earn advertising fees by advertising and linking to Amazon.com.</p>

<p><strong>As an Amazon Associate I earn from qualifying purchases.</strong></p>

<h2>5. Medical Disclaimer</h2>
<div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 20px; margin: 20px 0;">
    <p><strong>⚠️ IMPORTANT HEALTH & MEDICAL DISCLAIMER:</strong></p>

    <p><strong>Not Medical Advice:</strong> The information provided on this website is for educational and informational purposes only and is not intended as medical advice, diagnosis, or treatment. We are not licensed healthcare professionals.</p>

    <p><strong>Consult Your Doctor:</strong> Always seek the advice of your physician or other qualified health provider with any questions you may have regarding a medical condition, supplement regimen, or health concerns. Never disregard professional medical advice or delay in seeking it because of something you have read on this website.</p>

    <p><strong>Individual Results May Vary:</strong> The effectiveness of supplements and health products can vary significantly between individuals. What works for one person may not work for another.</p>

    <p><strong>No Warranties:</strong> We make no representations or warranties of any kind regarding the accuracy, completeness, or suitability of any information, products, or services mentioned on this website.</p>

    <p><strong>Supplement Safety:</strong> Before starting any new supplement or wellness regimen:</p>
    <ul>
        <li>Consult with your healthcare provider, especially if you have pre-existing conditions</li>
        <li>Inform your doctor of all medications and supplements you are taking</li>
        <li>Be aware of potential interactions and side effects</li>
        <li>Follow recommended dosages on product labels</li>
    </ul>

    <p><strong>Emergency Situations:</strong> If you think you may have a medical emergency, call your doctor or 911 immediately. This website does not recommend or endorse any specific tests, physicians, products, procedures, opinions, or other information mentioned on the site.</p>
</div>

<h2>6. Product Information</h2>
<p>We strive to provide accurate product information and descriptions. However, we do not warrant that product descriptions, pricing, or other content on this website is accurate, complete, reliable, current, or error-free. Manufacturers may change product specifications without notice.</p>

<p>All product purchases are made directly with third-party retailers (such as Amazon). We are not responsible for product quality, shipping, returns, or customer service related to purchases made through our affiliate links.</p>

<h2>7. User-Generated Content</h2>
<p>Any comments, reviews, or other content you submit to this website may be used by us for any purpose without compensation to you. We reserve the right to remove any content that we deem inappropriate, offensive, or in violation of these terms.</p>

<h2>8. Limitation of Liability</h2>
<p>To the fullest extent permitted by law, Shluchim Health and its owners, employees, and affiliates shall not be liable for any indirect, incidental, special, consequential, or punitive damages, or any loss of profits or revenues, whether incurred directly or indirectly, or any loss of data, use, goodwill, or other intangible losses resulting from:</p>

<ul>
    <li>Your access to or use of (or inability to access or use) the website</li>
    <li>Any conduct or content of any third party on the website</li>
    <li>Any content obtained from the website</li>
    <li>Unauthorized access, use, or alteration of your transmissions or content</li>
    <li>Use of any products or services purchased through affiliate links on this website</li>
</ul>

<h2>9. Indemnification</h2>
<p>You agree to defend, indemnify, and hold harmless Shluchim Health and its officers, directors, employees, and agents from and against any claims, liabilities, damages, judgments, awards, losses, costs, expenses, or fees arising out of or relating to your violation of these Terms of Service or your use of the website.</p>

<h2>10. External Links</h2>
<p>This website may contain links to third-party websites or services that are not owned or controlled by Shluchim Health. We have no control over and assume no responsibility for the content, privacy policies, or practices of any third-party websites or services.</p>

<h2>11. Privacy Policy</h2>
<p>Your use of this website is also governed by our Privacy Policy. Please review our Privacy Policy to understand our practices.</p>

<h2>12. Changes to Terms</h2>
<p>We reserve the right to modify or replace these Terms of Service at any time. We will provide notice of any material changes by updating the "Last Updated" date at the top of this page. Your continued use of the website after such changes constitutes your acceptance of the new terms.</p>

<h2>13. Governing Law</h2>
<p>These Terms of Service shall be governed by and construed in accordance with the laws of the United States, without regard to its conflict of law provisions.</p>

<h2>14. Severability</h2>
<p>If any provision of these Terms of Service is held to be invalid or unenforceable, such provision shall be struck and the remaining provisions shall be enforced to the fullest extent under law.</p>

<h2>15. Entire Agreement</h2>
<p>These Terms of Service, together with our Privacy Policy and Affiliate Disclosure, constitute the entire agreement between you and Shluchim Health regarding your use of this website.</p>

<h2>Contact Us</h2>
<p>If you have any questions about these Terms of Service, please contact us through our website.</p>
HTML;

    $result = db()->insert('pages', [
        'title' => 'Terms of Service',
        'slug' => 'terms-of-service',
        'content' => $termsContent,
        'seo_title' => 'Terms of Service - Shluchim Health',
        'seo_description' => 'Terms of Service for Shluchim Health. Review our terms governing the use of our website, affiliate disclosures, medical disclaimers, and legal notices.',
        'show_in_menu' => 0,
        'menu_order' => 99
    ]);

    if ($result) {
        echo "✓ Created Terms of Service page with medical disclaimer\n";
        $fixed++;
    } else {
        echo "✗ Failed to create Terms of Service page\n";
        $issues++;
    }
} else {
    echo "✓ Terms of Service page already exists\n";
}

echo "\n";

// ============================================================
// 3. ADD MEDICAL DISCLAIMER TO FOOTER
// ============================================================
echo "3. Checking Medical Disclaimer in Footer...\n";
echo str_repeat("-", 70) . "\n";

$footerFile = __DIR__ . '/includes/footer.php';
$footerContent = file_get_contents($footerFile);

if (stripos($footerContent, 'medical advice') === false &&
    stripos($footerContent, 'consult') === false) {

    echo "⚠ Footer doesn't have medical disclaimer\n";
    echo "  Manual action needed: Add medical disclaimer to footer.php\n";
    echo "  Or users can see it on Terms of Service page\n";
} else {
    echo "✓ Footer already contains medical-related disclaimer language\n";
}

echo "\n";

// ============================================================
// 4. FIX BLOG POST DATES
// ============================================================
echo "4. Fixing Blog Post Published Dates...\n";
echo str_repeat("-", 70) . "\n";

$postsWithoutDates = db()->fetchAll(
    'SELECT id, title FROM posts WHERE published_at IS NULL OR published_at = ""'
);

if (!empty($postsWithoutDates)) {
    foreach ($postsWithoutDates as $post) {
        // Set to current date
        db()->query(
            'UPDATE posts SET published_at = CURRENT_TIMESTAMP WHERE id = :id',
            ['id' => $post['id']]
        );
        echo "✓ Set published date for: {$post['title']}\n";
        $fixed++;
    }
} else {
    echo "✓ All posts have published dates\n";
}

echo "\n";

// ============================================================
// SUMMARY
// ============================================================
echo str_repeat("=", 70) . "\n";
echo "SUMMARY:\n";
echo str_repeat("=", 70) . "\n";
echo "✓ Fixed: $fixed items\n";
echo "✗ Issues remaining: $issues items\n\n";

if ($issues === 0) {
    echo "🎉 All automatic fixes applied successfully!\n\n";
    echo "REMAINING MANUAL TASKS:\n";
    echo "1. Write 5 more blog posts (you have 5, need 10 minimum)\n";
    echo "2. Optionally add medical disclaimer to footer.php\n\n";
    echo "Run verification again: php verify_amazon_compliance.php\n";
} else {
    echo "⚠ Some issues could not be fixed automatically.\n";
    echo "Please review the output above and fix manually.\n";
}

echo "\n";

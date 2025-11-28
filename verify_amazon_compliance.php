#!/usr/bin/env php
<?php
/**
 * Amazon Associates Compliance Verification Script
 *
 * This script checks your site for Amazon Associates compliance requirements.
 * Run this BEFORE submitting your Amazon application.
 *
 * Usage: php verify_amazon_compliance.php
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/functions.php';

// Check if running from command line
if (php_sapi_name() !== 'cli') {
    die("This script must be run from the command line.\n");
}

$issues = [];
$warnings = [];
$passed = [];

echo "\n";
echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║     AMAZON ASSOCIATES COMPLIANCE VERIFICATION                 ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

// ============================================================
// 1. CHECK LEGAL PAGES
// ============================================================
echo "📋 CHECKING LEGAL PAGES...\n";
echo str_repeat("-", 70) . "\n\n";

$requiredPages = [
    'privacy-policy' => 'Privacy Policy',
    'affiliate-disclosure' => 'Affiliate Disclosure',
    'terms-of-service' => 'Terms of Service'
];

foreach ($requiredPages as $slug => $title) {
    $page = db()->fetch('SELECT * FROM pages WHERE slug = :slug', ['slug' => $slug]);

    if ($page) {
        $contentLength = strlen(strip_tags($page['content']));
        if ($contentLength > 500) {
            echo "✓ {$title} exists ({$contentLength} characters)\n";
            $passed[] = "{$title} page exists";

            // Check specific content for privacy policy
            if ($slug === 'privacy-policy') {
                $content = strtolower($page['content']);
                if (stripos($content, 'amazon') !== false) {
                    echo "  ✓ Mentions Amazon\n";
                } else {
                    $warnings[] = "Privacy Policy should mention Amazon Associates";
                    echo "  ⚠ Should mention Amazon Associates\n";
                }

                if (stripos($content, 'cookie') !== false) {
                    echo "  ✓ Mentions cookies\n";
                } else {
                    $warnings[] = "Privacy Policy should mention cookies";
                    echo "  ⚠ Should mention cookies/tracking\n";
                }
            }

            // Check affiliate disclosure
            if ($slug === 'affiliate-disclosure') {
                $content = $page['content'];
                if (stripos($content, 'As an Amazon Associate') !== false) {
                    echo "  ✓ Contains Amazon Associate disclosure\n";
                } else {
                    $issues[] = "Affiliate Disclosure must contain 'As an Amazon Associate I earn from qualifying purchases'";
                    echo "  ✗ Missing required Amazon Associate statement\n";
                }
            }
        } else {
            $issues[] = "{$title} exists but content is too short ({$contentLength} chars, need 500+)";
            echo "✗ {$title} exists but too short ({$contentLength} characters)\n";
        }
    } else {
        $issues[] = "{$title} page does not exist (slug: {$slug})";
        echo "✗ {$title} MISSING - Create at /admin/page-edit.php\n";
    }
    echo "\n";
}

// ============================================================
// 2. CHECK BLOG CONTENT
// ============================================================
echo "\n📝 CHECKING BLOG CONTENT...\n";
echo str_repeat("-", 70) . "\n\n";

$totalPosts = db()->count('posts', 'status = "published"');
echo "Total published posts: {$totalPosts}\n";

if ($totalPosts >= 10) {
    echo "✓ You have {$totalPosts} posts (Amazon requires 10+)\n";
    $passed[] = "Sufficient blog posts ({$totalPosts})";
} else {
    $issues[] = "Only {$totalPosts} published posts (Amazon requires at least 10)";
    echo "✗ Need " . (10 - $totalPosts) . " more posts (Amazon requires 10 minimum)\n";
}

// Check most recent post
$recentPost = db()->fetch(
    'SELECT title, published_at FROM posts WHERE status = "published" ORDER BY published_at DESC LIMIT 1'
);

if ($recentPost) {
    $postDate = new DateTime($recentPost['published_at']);
    $now = new DateTime();
    $daysSince = $now->diff($postDate)->days;

    echo "\nMost recent post: \"{$recentPost['title']}\"\n";
    echo "Published: {$recentPost['published_at']} ({$daysSince} days ago)\n";

    if ($daysSince <= 60) {
        echo "✓ Recent post within 60 days (Amazon requirement)\n";
        $passed[] = "Recent content (posted {$daysSince} days ago)";
    } else {
        $warnings[] = "Latest post is {$daysSince} days old (Amazon prefers posts within 60 days)";
        echo "⚠ Latest post is {$daysSince} days old\n";
        echo "  Recommendation: Publish a new post soon\n";
    }
}

// ============================================================
// 3. CHECK AFFILIATE DISCLOSURES
// ============================================================
echo "\n\n💬 CHECKING AFFILIATE DISCLOSURES...\n";
echo str_repeat("-", 70) . "\n\n";

// Check footer disclosure
$footerFile = __DIR__ . '/includes/footer.php';
$footerContent = file_get_contents($footerFile);

if (stripos($footerContent, 'As an Amazon Associate I earn from qualifying purchases') !== false) {
    echo "✓ Footer contains correct Amazon disclosure\n";
    $passed[] = "Correct footer disclosure";
} elseif (stripos($footerContent, 'As an Amazon Associate') !== false) {
    $warnings[] = "Footer has Amazon disclosure but may not be exact wording";
    echo "⚠ Footer has disclosure but verify exact wording\n";
    echo "  Required: 'As an Amazon Associate I earn from qualifying purchases'\n";
} else {
    $issues[] = "Footer missing Amazon Associate disclosure";
    echo "✗ Footer missing Amazon disclosure\n";
}

// Check product page disclosure
$productFile = __DIR__ . '/product.php';
$productContent = file_get_contents($productFile);

if (stripos($productContent, 'affiliate') !== false || stripos($productContent, 'commission') !== false) {
    echo "✓ Product pages contain affiliate disclosure\n";
    $passed[] = "Product page disclosures present";
} else {
    $warnings[] = "Product pages may be missing affiliate disclosures";
    echo "⚠ Product pages should have affiliate disclosures\n";
}

// Check blog post disclosure
$postFile = __DIR__ . '/post.php';
$postContent = file_get_contents($postFile);

if (stripos($postContent, 'affiliate-disclosure') !== false ||
    stripos($postContent, 'As an Amazon Associate') !== false) {
    echo "✓ Blog posts contain affiliate disclosure\n";
    $passed[] = "Blog post disclosures present";
} else {
    $warnings[] = "Blog posts should have affiliate disclosures";
    echo "⚠ Blog posts should have affiliate disclosures\n";
}

// ============================================================
// 4. CHECK AFFILIATE LINKS
// ============================================================
echo "\n\n🔗 CHECKING AFFILIATE LINKS...\n";
echo str_repeat("-", 70) . "\n\n";

// Count affiliate links
$totalLinks = db()->count('product_urls');
echo "Total product affiliate links: {$totalLinks}\n";

// Check for Amazon links
$amazonLinks = db()->fetchAll(
    'SELECT COUNT(*) as count FROM product_urls WHERE url LIKE "%amazon.com%"'
);
$amazonCount = $amazonLinks[0]['count'] ?? 0;

echo "Amazon affiliate links: {$amazonCount}\n";

if ($amazonCount > 0) {
    echo "✓ Site has Amazon affiliate links\n";
    $passed[] = "Amazon affiliate links present ({$amazonCount})";
} else {
    $warnings[] = "No Amazon affiliate links found - add some before applying";
    echo "⚠ No Amazon links found - add products before applying\n";
}

// Check for current affiliate tag
$currentTag = db()->fetch(
    'SELECT setting_value FROM settings WHERE setting_key = "amazon_tag"'
);

if ($currentTag && !empty($currentTag['setting_value'])) {
    $tag = $currentTag['setting_value'];
    echo "\nAmazon affiliate tag in settings: {$tag}\n";

    // Check if links use this tag
    $linksWithTag = db()->fetchAll(
        'SELECT COUNT(*) as count FROM product_urls WHERE url LIKE :tag',
        ['tag' => '%' . $tag . '%']
    );
    $linkCount = $linksWithTag[0]['count'] ?? 0;

    echo "Links using this tag: {$linkCount} / {$amazonCount}\n";

    if ($linkCount > 0) {
        echo "✓ Affiliate tag is being used in links\n";
        $passed[] = "Affiliate tag implemented";
    } else {
        $warnings[] = "Affiliate tag in settings but not in links - run update script";
        echo "⚠ Tag not found in links - run: php replace_affiliate_tag.php old new\n";
    }
}

// Check for short links (not allowed)
$shortLinks = db()->fetchAll(
    'SELECT url FROM product_urls WHERE url LIKE "%amzn.to%" OR url LIKE "%bit.ly%" OR url LIKE "%tinyurl%"'
);

if (empty($shortLinks)) {
    echo "✓ No prohibited link shorteners found\n";
    $passed[] = "No link shorteners used";
} else {
    $issues[] = "Found " . count($shortLinks) . " short links - Amazon prohibits link shorteners";
    echo "✗ Found " . count($shortLinks) . " short links (amzn.to, bit.ly, etc.)\n";
    echo "  Amazon PROHIBITS link shorteners - must use full amazon.com URLs\n";
}

// Check rel attributes
if (stripos($productContent, 'rel="nofollow') !== false) {
    echo "✓ Product links use proper rel attributes\n";
    $passed[] = "Proper rel attributes on links";
} else {
    $warnings[] = "Verify affiliate links have rel='nofollow noopener sponsored'";
    echo "⚠ Verify links have rel='nofollow noopener sponsored'\n";
}

// ============================================================
// 5. CHECK FOR HEALTH SITE REQUIREMENTS
// ============================================================
echo "\n\n⚕️  CHECKING HEALTH SITE SPECIFIC REQUIREMENTS...\n";
echo str_repeat("-", 70) . "\n\n";

// Check for medical disclaimer
$disclaimerFound = false;

// Check footer
if (stripos($footerContent, 'medical advice') !== false ||
    stripos($footerContent, 'consult') !== false) {
    echo "✓ Footer contains medical disclaimer\n";
    $passed[] = "Medical disclaimer in footer";
    $disclaimerFound = true;
}

// Check for disclaimer page
$disclaimerPage = db()->fetch('SELECT * FROM pages WHERE slug LIKE "%disclaimer%"');
if ($disclaimerPage) {
    echo "✓ Dedicated disclaimer page exists\n";
    $passed[] = "Disclaimer page exists";
    $disclaimerFound = true;
}

if (!$disclaimerFound) {
    $issues[] = "CRITICAL: Health site needs medical disclaimer (not medical advice, consult doctor, etc.)";
    echo "✗ CRITICAL: No medical disclaimer found!\n";
    echo "  Health sites MUST state you're not providing medical advice\n";
    echo "  Recommend users consult healthcare professionals\n";
}

// ============================================================
// SUMMARY
// ============================================================
echo "\n\n";
echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║                      COMPLIANCE SUMMARY                        ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

echo "✓ Passed: " . count($passed) . " checks\n";
echo "⚠ Warnings: " . count($warnings) . " items\n";
echo "✗ Issues: " . count($issues) . " critical problems\n\n";

if (!empty($issues)) {
    echo "🔴 CRITICAL ISSUES - MUST FIX BEFORE APPLYING:\n";
    echo str_repeat("-", 70) . "\n";
    foreach ($issues as $i => $issue) {
        echo ($i + 1) . ". " . $issue . "\n";
    }
    echo "\n";
}

if (!empty($warnings)) {
    echo "⚠️  WARNINGS - RECOMMENDED TO FIX:\n";
    echo str_repeat("-", 70) . "\n";
    foreach ($warnings as $i => $warning) {
        echo ($i + 1) . ". " . $warning . "\n";
    }
    echo "\n";
}

if (empty($issues)) {
    if (empty($warnings)) {
        echo "🎉 CONGRATULATIONS! Your site appears ready for Amazon Associates!\n\n";
        echo "Next steps:\n";
        echo "1. Review the full checklist in AMAZON_COMPLIANCE_CHECKLIST.md\n";
        echo "2. Manually verify all legal pages are complete\n";
        echo "3. Apply at: https://affiliate-program.amazon.com/\n\n";
    } else {
        echo "✅ No critical issues found! Fix the warnings above to improve your chances.\n\n";
        echo "You can apply to Amazon Associates, but addressing warnings will help.\n";
        echo "Apply at: https://affiliate-program.amazon.com/\n\n";
    }
} else {
    echo "❌ Fix the critical issues above before applying to Amazon Associates.\n";
    echo "Your application will likely be denied if these aren't addressed.\n\n";
}

echo "For detailed guidance, see: AMAZON_COMPLIANCE_CHECKLIST.md\n\n";

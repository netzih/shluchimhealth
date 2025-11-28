<?php
require_once 'config.php';
require_once 'database.php';
require_once 'functions.php';

// Get slug from URL
$slug = $_GET['slug'] ?? '';

if (empty($slug)) {
    header('HTTP/1.0 404 Not Found');
    include '404.php';
    exit;
}

// Get post
$post = db()->fetch('SELECT * FROM posts WHERE slug = :slug AND status = "published"', ['slug' => $slug]);

if (!$post) {
    header('HTTP/1.0 404 Not Found');
    include '404.php';
    exit;
}

// Increment view count
db()->query('UPDATE posts SET views = views + 1 WHERE id = :id', ['id' => $post['id']]);

// Get related posts
$relatedPosts = [];
if ($post['category']) {
    $relatedPosts = db()->fetchAll(
        'SELECT * FROM posts WHERE category = :category AND id != :id AND status = "published" ORDER BY published_at DESC LIMIT 3',
        ['category' => $post['category'], 'id' => $post['id']]
    );
}

// SEO
$pageTitle = $post['seo_title'] ?: $post['title'];
$seoDescription = $post['seo_description'] ?: ($post['excerpt'] ?: getExcerpt($post['content']));
$ogImage = $post['featured_image'];

include 'includes/header.php';
?>

<article class="post-single">
    <div class="container">
        <div class="breadcrumb">
            <a href="<?php echo BASE_URL; ?>/">Home</a> /
            <a href="<?php echo BASE_URL; ?>/blog/">Blog</a> /
            <?php if ($post['category']): ?>
                <a href="<?php echo BASE_URL; ?>/blog/?category=<?php echo urlencode($post['category']); ?>"><?php echo escape($post['category']); ?></a> /
            <?php endif; ?>
            <span><?php echo escape($post['title']); ?></span>
        </div>

        <header class="post-header">
            <?php if ($post['category']): ?>
                <span class="post-category"><?php echo escape($post['category']); ?></span>
            <?php endif; ?>

            <h1><?php echo escape($post['title']); ?></h1>

            <div class="post-meta">
                <span>By <?php echo escape($post['author']); ?></span>
                <span><?php echo formatDate($post['published_at'], 'F j, Y'); ?></span>
                <span><?php echo number_format($post['views']); ?> views</span>
            </div>
        </header>

        <?php if ($post['featured_image']): ?>
            <div class="post-featured-image">
                <img src="<?php echo escape($post['featured_image']); ?>" alt="<?php echo escape($post['title']); ?>">
            </div>
        <?php endif; ?>

        <div class="affiliate-disclosure-box" style="background: #f0f7ff; border-left: 4px solid #2563eb; padding: 1.5rem; margin: 2rem 0; border-radius: 8px;">
            <p style="margin: 0; font-size: 0.95rem; color: #1e40af;">
                <strong>📢 Affiliate Disclosure:</strong> As an Amazon Associate I earn from qualifying purchases.
                This post may contain affiliate links, meaning we earn a commission if you make a purchase through our links, at no extra cost to you.
                We only recommend products we genuinely believe will benefit your health and wellness.
            </p>
        </div>

        <div class="post-content">
            <?php echo $post['content']; ?>
        </div>

        <?php if (getSetting('calcom_username')): ?>
        <div class="post-cta">
            <div class="cta-content">
                <h3>Need Personalized Guidance?</h3>
                <p>Get expert advice tailored to your unique health goals and needs. Book a one-on-one consultation to discuss supplement recommendations, wellness strategies, and more.</p>
                <a href="<?php echo BASE_URL; ?>/booking" class="btn btn-primary btn-lg">Schedule a Consultation</a>
            </div>
        </div>

        <style>
        .post-cta {
            margin: 3rem 0;
            padding: 3rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            text-align: center;
        }

        .cta-content h3 {
            color: white;
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .cta-content p {
            color: rgba(255, 255, 255, 0.95);
            font-size: 1.125rem;
            max-width: 600px;
            margin: 0 auto 2rem;
            line-height: 1.6;
        }

        .btn-lg {
            padding: 1rem 2rem;
            font-size: 1.125rem;
        }
        </style>
        <?php endif; ?>

        <footer class="post-footer">
            <p class="affiliate-disclaimer">
                <strong>Affiliate Disclosure:</strong> This post may contain affiliate links.
                If you click through and make a purchase, we may earn a commission at no additional cost to you.
                We only recommend products we genuinely believe in.
            </p>

            <div class="post-share">
                <p>Share this post:</p>
                <div class="share-buttons">
                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(BASE_URL . '/post.php?slug=' . $post['slug']); ?>&text=<?php echo urlencode($post['title']); ?>" target="_blank" class="btn btn-sm">Twitter</a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(BASE_URL . '/post.php?slug=' . $post['slug']); ?>" target="_blank" class="btn btn-sm">Facebook</a>
                </div>
            </div>
        </footer>

        <?php if (!empty($relatedPosts)): ?>
            <section class="related-posts">
                <h2>Related Articles</h2>
                <div class="posts-grid">
                    <?php foreach ($relatedPosts as $relPost): ?>
                        <article class="post-card">
                            <?php if ($relPost['featured_image']): ?>
                                <div class="post-image">
                                    <a href="<?php echo BASE_URL; ?>/blog/<?php echo $relPost['slug']; ?>">
                                        <img src="<?php echo escape($relPost['featured_image']); ?>" alt="<?php echo escape($relPost['title']); ?>">
                                    </a>
                                </div>
                            <?php endif; ?>

                            <div class="post-content">
                                <h3><a href="<?php echo BASE_URL; ?>/blog/<?php echo $relPost['slug']; ?>"><?php echo escape($relPost['title']); ?></a></h3>
                                <p class="post-excerpt"><?php echo escape($relPost['excerpt'] ?: getExcerpt($relPost['content'])); ?></p>
                                <a href="<?php echo BASE_URL; ?>/blog/<?php echo $relPost['slug']; ?>" class="read-more">Read More →</a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>
    </div>
</article>

<?php include 'includes/footer.php'; ?>

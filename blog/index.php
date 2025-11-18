<?php
require_once '../config.php';
require_once '../database.php';
require_once '../functions.php';

$pageTitle = 'Blog';

// Filter
$category = $_GET['category'] ?? '';

// Build query
$where = 'status = "published"';
$params = [];

if ($category) {
    $where .= ' AND category = :category';
    $params['category'] = $category;
    $pageTitle = $category . ' - Blog';
}

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = (int)getSetting('posts_per_page', 12);
$totalPosts = db()->count('posts', $where, $params);
$pagination = paginate($totalPosts, $perPage, $page);

// Get posts
$posts = db()->fetchAll(
    "SELECT * FROM posts WHERE {$where} ORDER BY published_at DESC LIMIT :limit OFFSET :offset",
    array_merge($params, ['limit' => $perPage, 'offset' => $pagination['offset']])
);

// Get featured post
$featuredPost = db()->fetch('SELECT * FROM posts WHERE status = "published" AND featured = 1 ORDER BY published_at DESC LIMIT 1');

$categories = getCategories('posts');

include '../includes/header.php';
?>

<div class="page-header">
    <div class="container">
        <h1><?php echo escape($pageTitle); ?></h1>
        <p>Discover expert insights, tips, and recommendations for better health and wellness.</p>
    </div>
</div>

<div class="blog-page">
    <div class="container">
        <?php if ($featuredPost && !$category): ?>
            <article class="featured-post">
                <?php if ($featuredPost['featured_image']): ?>
                    <div class="featured-image">
                        <a href="<?php echo BASE_URL; ?>/blog/<?php echo $featuredPost['slug']; ?>">
                            <img src="<?php echo escape($featuredPost['featured_image']); ?>" alt="<?php echo escape($featuredPost['title']); ?>">
                        </a>
                    </div>
                <?php endif; ?>

                <div class="featured-content">
                    <span class="badge badge-featured">Featured</span>
                    <?php if ($featuredPost['category']): ?>
                        <span class="post-category"><?php echo escape($featuredPost['category']); ?></span>
                    <?php endif; ?>

                    <h2><a href="<?php echo BASE_URL; ?>/blog/<?php echo $featuredPost['slug']; ?>"><?php echo escape($featuredPost['title']); ?></a></h2>

                    <p class="post-excerpt"><?php echo escape($featuredPost['excerpt'] ?: getExcerpt($featuredPost['content'], 250)); ?></p>

                    <div class="post-meta">
                        <span>By <?php echo escape($featuredPost['author']); ?></span>
                        <span><?php echo formatDate($featuredPost['published_at']); ?></span>
                    </div>

                    <a href="<?php echo BASE_URL; ?>/blog/<?php echo $featuredPost['slug']; ?>" class="btn btn-primary">Read Article</a>
                </div>
            </article>
        <?php endif; ?>

        <div class="blog-layout">
            <div class="blog-main">
                <?php if (empty($posts)): ?>
                    <div class="empty-state">
                        <h3>No posts found</h3>
                        <p>Check back soon for new content!</p>
                    </div>
                <?php else: ?>
                    <div class="posts-grid">
                        <?php foreach ($posts as $post): ?>
                            <article class="post-card">
                                <?php if ($post['featured_image']): ?>
                                    <div class="post-image">
                                        <a href="<?php echo BASE_URL; ?>/blog/<?php echo $post['slug']; ?>">
                                            <img src="<?php echo escape($post['featured_image']); ?>" alt="<?php echo escape($post['title']); ?>">
                                        </a>
                                    </div>
                                <?php endif; ?>

                                <div class="post-content">
                                    <?php if ($post['category']): ?>
                                        <span class="post-category"><?php echo escape($post['category']); ?></span>
                                    <?php endif; ?>

                                    <h3><a href="<?php echo BASE_URL; ?>/blog/<?php echo $post['slug']; ?>"><?php echo escape($post['title']); ?></a></h3>

                                    <p class="post-excerpt"><?php echo escape($post['excerpt'] ?: getExcerpt($post['content'])); ?></p>

                                    <div class="post-meta">
                                        <span><?php echo formatDate($post['published_at']); ?></span>
                                        <span><?php echo number_format($post['views']); ?> views</span>
                                    </div>

                                    <a href="<?php echo BASE_URL; ?>/blog/<?php echo $post['slug']; ?>" class="read-more">Read More →</a>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>

                    <?php echo renderPagination($pagination, BASE_URL . '/blog/' . ($category ? '?category=' . urlencode($category) : '')); ?>
                <?php endif; ?>
            </div>

            <aside class="blog-sidebar">
                <?php if (!empty($categories)): ?>
                    <div class="sidebar-widget">
                        <h3>Categories</h3>
                        <ul class="category-list">
                            <li><a href="<?php echo BASE_URL; ?>/blog/" <?php echo !$category ? 'class="active"' : ''; ?>>All Posts</a></li>
                            <?php foreach ($categories as $cat): ?>
                                <li>
                                    <a href="?category=<?php echo urlencode($cat); ?>" <?php echo $category === $cat ? 'class="active"' : ''; ?>>
                                        <?php echo escape($cat); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="sidebar-widget">
                    <h3>About</h3>
                    <p><?php echo escape(getSetting('site_description')); ?></p>
                </div>
            </aside>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

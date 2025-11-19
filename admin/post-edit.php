<?php
require_once '../config.php';
require_once '../database.php';
require_once '../functions.php';

requireLogin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$post = null;

if ($id) {
    $post = db()->fetch('SELECT * FROM posts WHERE id = :id', ['id' => $id]);
    if (!$post) {
        setFlash('error', 'Post not found.');
        redirect(BASE_URL . '/admin/posts.php');
    }
}

$pageTitle = $id ? 'Edit Post' : 'New Post';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = $_POST['content'] ?? '';
    $excerpt = trim($_POST['excerpt'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $status = $_POST['status'] ?? 'draft';
    $featured = isset($_POST['featured']) ? 1 : 0;
    $seoTitle = trim($_POST['seo_title'] ?? '');
    $seoDescription = trim($_POST['seo_description'] ?? '');

    if (empty($title) || empty($content)) {
        setFlash('error', 'Title and content are required.');
    } else {
        $slug = $id ? generateUniqueSlug($title, 'posts', $id) : generateUniqueSlug($title, 'posts');

        // Generate excerpt if not provided
        if (empty($excerpt)) {
            $excerpt = getExcerpt($content);
        }

        $data = [
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'excerpt' => $excerpt,
            'category' => $category,
            'status' => $status,
            'featured' => $featured,
            'seo_title' => $seoTitle ?: $title,
            'seo_description' => $seoDescription ?: $excerpt,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($id) {
            db()->update('posts', $data, 'id = :id', ['id' => $id]);
            setFlash('success', 'Post updated successfully.');
        } else {
            $data['published_at'] = ($status === 'published') ? date('Y-m-d H:i:s') : null;
            $newId = db()->insert('posts', $data);
            setFlash('success', 'Post created successfully.');
            redirect(BASE_URL . '/admin/post-edit.php?id=' . $newId);
        }

        redirect(BASE_URL . '/admin/post-edit.php?id=' . ($id ?: $newId));
    }
}

$categories = getCategories('posts');

include 'header.php';
?>

<div class="admin-page">
    <div class="page-header">
        <h1><?php echo $pageTitle; ?></h1>
        <a href="posts.php" class="btn btn-secondary">← Back to Posts</a>
    </div>

    <form method="POST" class="post-form">
        <div class="form-row">
            <div class="form-main">
                <div class="form-group">
                    <label for="title">Title *</label>
                    <input type="text" id="title" name="title" value="<?php echo escape($post['title'] ?? ''); ?>" required class="form-control-lg">
                </div>

                <div class="form-group">
                    <label for="content">Content *</label>
                    <textarea id="content" name="content" class="wysiwyg"><?php echo escape($post['content'] ?? ''); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="excerpt">Excerpt</label>
                    <textarea id="excerpt" name="excerpt" rows="3" class="form-control"><?php echo escape($post['excerpt'] ?? ''); ?></textarea>
                    <small>Leave empty to auto-generate from content</small>
                </div>
            </div>

            <div class="form-sidebar">
                <div class="sidebar-section">
                    <h3>Publish</h3>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-control">
                            <option value="draft" <?php echo ($post['status'] ?? 'draft') === 'draft' ? 'selected' : ''; ?>>Draft</option>
                            <option value="published" <?php echo ($post['status'] ?? '') === 'published' ? 'selected' : ''; ?>>Published</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="featured" <?php echo ($post['featured'] ?? 0) ? 'checked' : ''; ?>>
                            Featured Post
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        <?php echo $id ? 'Update Post' : 'Create Post'; ?>
                    </button>
                </div>

                <div class="sidebar-section">
                    <h3>Category</h3>
                    <div class="form-group">
                        <input type="text" name="category" list="categories" value="<?php echo escape($post['category'] ?? ''); ?>" placeholder="Enter or select category" class="form-control">
                        <datalist id="categories">
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo escape($cat); ?>">
                            <?php endforeach; ?>
                        </datalist>
                    </div>
                </div>

                <div class="sidebar-section">
                    <h3>SEO</h3>

                    <div class="form-group">
                        <label for="seo_title">SEO Title</label>
                        <input type="text" id="seo_title" name="seo_title" value="<?php echo escape($post['seo_title'] ?? ''); ?>" class="form-control">
                        <small>Leave empty to use post title</small>
                    </div>

                    <div class="form-group">
                        <label for="seo_description">SEO Description</label>
                        <textarea id="seo_description" name="seo_description" rows="3" class="form-control"><?php echo escape($post['seo_description'] ?? ''); ?></textarea>
                        <small>Leave empty to use excerpt</small>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
// Initialize TinyMCE
tinymce.init({
    selector: '.wysiwyg',
    height: 500,
    menubar: false,
    plugins: [
        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
        'insertdatetime', 'media', 'table', 'help', 'wordcount'
    ],
    toolbar: 'undo redo | formatselect | fontfamily fontsize | bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | code | removeformat | help',
    font_family_formats: 'System Default=-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; Arial=arial, helvetica, sans-serif; Courier New=courier new, courier, monospace; Georgia=georgia, palatino, serif; Times New Roman=times new roman, times, serif; Trebuchet MS=trebuchet ms, geneva, sans-serif; Verdana=verdana, geneva, sans-serif',
    font_size_formats: '8px 10px 12px 14px 16px 18px 20px 24px 28px 32px 36px 48px 64px',
    content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; font-size: 16px; line-height: 1.6; }'
});
</script>

<?php include 'footer.php'; ?>

<?php
require_once '../config.php';
require_once '../database.php';
require_once '../functions.php';

requireLogin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$page = null;

if ($id) {
    $page = db()->fetch('SELECT * FROM pages WHERE id = :id', ['id' => $id]);
    if (!$page) {
        setFlash('error', 'Page not found.');
        redirect(BASE_URL . '/admin/pages.php');
    }
}

$pageTitle = $id ? 'Edit Page' : 'New Page';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = $_POST['content'] ?? '';
    $showInMenu = isset($_POST['show_in_menu']) ? 1 : 0;
    $menuOrder = (int)($_POST['menu_order'] ?? 0);
    $seoTitle = trim($_POST['seo_title'] ?? '');
    $seoDescription = trim($_POST['seo_description'] ?? '');

    if (empty($title) || empty($content)) {
        setFlash('error', 'Title and content are required.');
    } else {
        $slug = $id ? generateUniqueSlug($title, 'pages', $id) : generateUniqueSlug($title, 'pages');

        $data = [
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'show_in_menu' => $showInMenu,
            'menu_order' => $menuOrder,
            'seo_title' => $seoTitle ?: $title,
            'seo_description' => $seoDescription ?: getExcerpt($content),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($id) {
            db()->update('pages', $data, 'id = :id', ['id' => $id]);
            setFlash('success', 'Page updated successfully.');
        } else {
            $newId = db()->insert('pages', $data);
            setFlash('success', 'Page created successfully.');
            redirect(BASE_URL . '/admin/page-edit.php?id=' . $newId);
        }

        redirect(BASE_URL . '/admin/page-edit.php?id=' . ($id ?: $newId));
    }
}

include 'header.php';
?>

<div class="admin-page">
    <div class="page-header">
        <h1><?php echo $pageTitle; ?></h1>
        <a href="pages.php" class="btn btn-secondary">← Back to Pages</a>
    </div>

    <form method="POST" class="post-form">
        <div class="form-row">
            <div class="form-main">
                <div class="form-group">
                    <label for="title">Title *</label>
                    <input type="text" id="title" name="title" value="<?php echo escape($page['title'] ?? ''); ?>" required class="form-control-lg">
                </div>

                <div class="form-group">
                    <label for="content">Content *</label>
                    <textarea id="content" name="content" class="wysiwyg"><?php echo escape($page['content'] ?? ''); ?></textarea>
                </div>
            </div>

            <div class="form-sidebar">
                <div class="sidebar-section">
                    <h3>Settings</h3>

                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="show_in_menu" <?php echo ($page['show_in_menu'] ?? 1) ? 'checked' : ''; ?>>
                            Show in Navigation Menu
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="menu_order">Menu Order</label>
                        <input type="number" id="menu_order" name="menu_order" value="<?php echo escape($page['menu_order'] ?? 0); ?>" class="form-control">
                        <small>Lower numbers appear first</small>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        <?php echo $id ? 'Update Page' : 'Create Page'; ?>
                    </button>
                </div>

                <div class="sidebar-section">
                    <h3>SEO</h3>

                    <div class="form-group">
                        <label for="seo_title">SEO Title</label>
                        <input type="text" id="seo_title" name="seo_title" value="<?php echo escape($page['seo_title'] ?? ''); ?>" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="seo_description">SEO Description</label>
                        <textarea id="seo_description" name="seo_description" rows="3" class="form-control"><?php echo escape($page['seo_description'] ?? ''); ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
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

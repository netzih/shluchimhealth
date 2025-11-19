<?php
require_once '../config.php';
require_once '../database.php';
require_once '../functions.php';

requireLogin();

$pageTitle = 'Pages';

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    db()->delete('pages', 'id = :id', ['id' => $id]);
    setFlash('success', 'Page deleted successfully.');
    redirect(BASE_URL . '/admin/pages.php');
}

// Get all pages
$pages = db()->fetchAll('SELECT * FROM pages ORDER BY menu_order ASC, title ASC');

include 'header.php';
?>

<div class="admin-page">
    <div class="page-header">
        <h1>Pages</h1>
        <a href="page-edit.php" class="btn btn-primary">Add New Page</a>
    </div>

    <?php if (empty($pages)): ?>
        <div class="empty-state">
            <h3>No pages yet</h3>
            <p>Create pages for your site like About, Contact, Privacy Policy, etc.</p>
            <a href="page-edit.php" class="btn btn-primary">Create First Page</a>
        </div>
    <?php else: ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Slug</th>
                    <th>Menu</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pages as $page): ?>
                    <tr>
                        <td><strong><a href="page-edit.php?id=<?php echo $page['id']; ?>"><?php echo escape($page['title']); ?></a></strong></td>
                        <td><code><?php echo escape($page['slug']); ?></code></td>
                        <td><?php echo $page['show_in_menu'] ? '✓ Yes' : '✗ No'; ?></td>
                        <td><?php echo formatDate($page['created_at'], 'M j, Y'); ?></td>
                        <td class="actions">
                            <a href="page-edit.php?id=<?php echo $page['id']; ?>" class="btn btn-sm">Edit</a>
                            <a href="<?php echo BASE_URL; ?>/<?php echo $page['slug']; ?>" target="_blank" class="btn btn-sm">View</a>
                            <a href="?delete=<?php echo $page['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>

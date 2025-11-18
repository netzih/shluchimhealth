<?php
// Utility functions

/**
 * Generate a URL-friendly slug from a string
 */
function slugify($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);

    if (empty($text)) {
        return 'n-a';
    }

    return $text;
}

/**
 * Generate a unique slug for a table
 */
function generateUniqueSlug($text, $table, $id = null) {
    $slug = slugify($text);
    $originalSlug = $slug;
    $counter = 1;

    while (true) {
        $sql = "SELECT id FROM {$table} WHERE slug = :slug";
        $params = ['slug' => $slug];

        if ($id) {
            $sql .= " AND id != :id";
            $params['id'] = $id;
        }

        $exists = db()->fetch($sql, $params);

        if (!$exists) {
            return $slug;
        }

        $slug = $originalSlug . '-' . $counter;
        $counter++;
    }
}

/**
 * Sanitize output for HTML
 */
function escape($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Get a setting value
 */
function getSetting($key, $default = '') {
    $setting = db()->fetch('SELECT setting_value FROM settings WHERE setting_key = :key', ['key' => $key]);
    return $setting ? $setting['setting_value'] : $default;
}

/**
 * Update a setting value
 */
function updateSetting($key, $value) {
    $exists = db()->fetch('SELECT id FROM settings WHERE setting_key = :key', ['key' => $key]);

    if ($exists) {
        return db()->update('settings',
            ['setting_value' => $value, 'updated_at' => date('Y-m-d H:i:s')],
            'setting_key = :key',
            ['key' => $key]
        );
    } else {
        return db()->insert('settings', [
            'setting_key' => $key,
            'setting_value' => $value,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Require login
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . BASE_URL . '/admin/login.php');
        exit;
    }
}

/**
 * Get current user
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }

    return db()->fetch('SELECT * FROM users WHERE id = :id', ['id' => $_SESSION['user_id']]);
}

/**
 * Redirect
 */
function redirect($url, $statusCode = 302) {
    header('Location: ' . $url, true, $statusCode);
    exit;
}

/**
 * Format date
 */
function formatDate($date, $format = 'F j, Y') {
    if (empty($date)) {
        return '';
    }
    return date($format, strtotime($date));
}

/**
 * Truncate text
 */
function truncate($text, $length = 150, $suffix = '...') {
    if (strlen($text) <= $length) {
        return $text;
    }

    return substr($text, 0, $length) . $suffix;
}

/**
 * Get excerpt from content
 */
function getExcerpt($content, $length = 200) {
    $content = strip_tags($content);
    return truncate($content, $length);
}

/**
 * Pagination helper
 */
function paginate($totalItems, $itemsPerPage, $currentPage = 1) {
    $totalPages = ceil($totalItems / $itemsPerPage);
    $currentPage = max(1, min($totalPages, $currentPage));
    $offset = ($currentPage - 1) * $itemsPerPage;

    return [
        'total_items' => $totalItems,
        'total_pages' => $totalPages,
        'current_page' => $currentPage,
        'items_per_page' => $itemsPerPage,
        'offset' => $offset,
        'has_prev' => $currentPage > 1,
        'has_next' => $currentPage < $totalPages,
        'prev_page' => $currentPage - 1,
        'next_page' => $currentPage + 1
    ];
}

/**
 * Generate pagination HTML
 */
function renderPagination($pagination, $baseUrl) {
    if ($pagination['total_pages'] <= 1) {
        return '';
    }

    $html = '<div class="pagination">';

    if ($pagination['has_prev']) {
        $html .= '<a href="' . $baseUrl . '?page=' . $pagination['prev_page'] . '" class="pagination-prev">&laquo; Previous</a>';
    }

    $html .= '<span class="pagination-info">Page ' . $pagination['current_page'] . ' of ' . $pagination['total_pages'] . '</span>';

    if ($pagination['has_next']) {
        $html .= '<a href="' . $baseUrl . '?page=' . $pagination['next_page'] . '" class="pagination-next">Next &raquo;</a>';
    }

    $html .= '</div>';

    return $html;
}

/**
 * Get all categories
 */
function getCategories($type = 'products') {
    if ($type === 'products') {
        $categories = db()->fetchAll('SELECT DISTINCT category FROM products WHERE category IS NOT NULL ORDER BY category');
    } else {
        $categories = db()->fetchAll('SELECT DISTINCT category FROM posts WHERE category IS NOT NULL ORDER BY category');
    }

    return array_column($categories, 'category');
}

/**
 * Flash message helper
 */
function setFlash($type, $message) {
    $_SESSION['flash'][$type] = $message;
}

function getFlash($type) {
    if (isset($_SESSION['flash'][$type])) {
        $message = $_SESSION['flash'][$type];
        unset($_SESSION['flash'][$type]);
        return $message;
    }
    return null;
}

function hasFlash($type) {
    return isset($_SESSION['flash'][$type]);
}

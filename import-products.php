<?php
/**
 * Import products from products.json
 * Run this from command line: php import-products.php
 */

require_once 'config.php';
require_once 'database.php';
require_once 'functions.php';

echo "=== Product Import ===\n\n";

// Check if products.json exists
if (!file_exists(__DIR__ . '/products.json')) {
    die("Error: products.json not found!\n");
}

// Read JSON file
echo "Reading products.json...\n";
$json = file_get_contents(__DIR__ . '/products.json');
$products = json_decode($json, true);

if (!$products || !is_array($products)) {
    die("Error: Invalid JSON format!\n");
}

echo "Found " . count($products) . " products.\n\n";

$db = Database::getInstance();
$imported = 0;
$updated = 0;
$errors = 0;

foreach ($products as $productData) {
    try {
        $id = $productData['id'] ?? null;
        $category = $productData['category'] ?? '';
        $title = $productData['title'] ?? '';
        $description = $productData['description'] ?? '';
        $image = $productData['image'] ?? '';
        $tags = $productData['tags'] ?? [];
        $urls = $productData['urls'] ?? [];

        if (empty($id) || empty($title)) {
            echo "⚠ Skipping product with missing ID or title\n";
            $errors++;
            continue;
        }

        // Generate slug
        $slug = generateUniqueSlug($title, 'products', $id);

        // Check if product exists
        $existing = $db->fetch('SELECT id FROM products WHERE id = :id', ['id' => $id]);

        $data = [
            'id' => $id,
            'category' => $category,
            'title' => $title,
            'description' => $description,
            'image' => $image,
            'slug' => $slug,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($existing) {
            // Update existing product
            $db->update('products', $data, 'id = :id', ['id' => $id]);

            // Delete old URLs and tags
            $db->delete('product_urls', 'product_id = :id', ['id' => $id]);
            $db->delete('product_tags', 'product_id = :id', ['id' => $id]);

            $updated++;
        } else {
            // Insert new product
            $data['created_at'] = date('Y-m-d H:i:s');
            $db->insert('products', $data);
            $imported++;
        }

        // Insert URLs
        $order = 0;
        foreach ($urls as $urlData) {
            if (isset($urlData['name']) && isset($urlData['url'])) {
                $db->insert('product_urls', [
                    'product_id' => $id,
                    'name' => $urlData['name'],
                    'url' => $urlData['url'],
                    'display_order' => $order++
                ]);
            }
        }

        // Insert tags
        foreach ($tags as $tag) {
            if (!empty($tag)) {
                $db->insert('product_tags', [
                    'product_id' => $id,
                    'tag' => $tag
                ]);
            }
        }

        echo "✓ " . $title . "\n";

    } catch (Exception $e) {
        echo "✗ Error importing product: " . $e->getMessage() . "\n";
        $errors++;
    }
}

echo "\n=== Import Complete ===\n";
echo "Imported: $imported new products\n";
echo "Updated: $updated existing products\n";
echo "Errors: $errors\n";
echo "\nTotal products in database: " . $db->count('products') . "\n";

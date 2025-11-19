<?php
/**
 * Reset admin password to admin123
 * Run this: php reset-admin.php
 */

require_once 'config.php';
require_once 'database.php';

echo "=== Reset Admin User ===\n\n";

$db = Database::getInstance()->getConnection();

// Create a proper password hash
$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "New password hash: $hash\n\n";

// Check if admin user exists
$stmt = $db->prepare('SELECT id, username FROM users WHERE username = :username');
$stmt->execute(['username' => 'admin']);
$user = $stmt->fetch();

if ($user) {
    echo "Found existing admin user (ID: {$user['id']})\n";
    echo "Updating password...\n";

    $stmt = $db->prepare('UPDATE users SET password = :password WHERE username = :username');
    $stmt->execute([
        'password' => $hash,
        'username' => 'admin'
    ]);

    echo "✓ Password updated successfully!\n";
} else {
    echo "Admin user not found. Creating new admin user...\n";

    $stmt = $db->prepare('INSERT INTO users (username, password, email, role, created_at) VALUES (:username, :password, :email, :role, :created_at)');
    $stmt->execute([
        'username' => 'admin',
        'password' => $hash,
        'email' => 'admin@shluchimhealth.com',
        'role' => 'admin',
        'created_at' => date('Y-m-d H:i:s')
    ]);

    echo "✓ Admin user created successfully!\n";
}

echo "\n=== Login Credentials ===\n";
echo "Username: admin\n";
echo "Password: admin123\n";
echo "\nAdmin Panel: " . BASE_URL . "/admin/\n";
echo "\n⚠️  Please change this password after logging in!\n";

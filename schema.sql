-- Shluchim Health Database Schema
-- SQLite Database for easy deployment

-- Products table
CREATE TABLE IF NOT EXISTS products (
    id INTEGER PRIMARY KEY,
    category VARCHAR(100),
    title VARCHAR(255) NOT NULL,
    description TEXT,
    image VARCHAR(500),
    slug VARCHAR(255) UNIQUE NOT NULL,
    featured BOOLEAN DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Product affiliate URLs
CREATE TABLE IF NOT EXISTS product_urls (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    product_id INTEGER NOT NULL,
    name VARCHAR(100) NOT NULL,
    url TEXT NOT NULL,
    display_order INTEGER DEFAULT 0,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Product tags
CREATE TABLE IF NOT EXISTS product_tags (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    product_id INTEGER NOT NULL,
    tag VARCHAR(100) NOT NULL,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Blog posts
CREATE TABLE IF NOT EXISTS posts (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    content TEXT NOT NULL,
    excerpt TEXT,
    featured_image VARCHAR(500),
    category VARCHAR(100),
    author VARCHAR(100) DEFAULT 'Admin',
    status VARCHAR(20) DEFAULT 'published',
    seo_title VARCHAR(255),
    seo_description TEXT,
    featured BOOLEAN DEFAULT 0,
    views INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    published_at DATETIME
);

-- Static pages
CREATE TABLE IF NOT EXISTS pages (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    content TEXT NOT NULL,
    seo_title VARCHAR(255),
    seo_description TEXT,
    show_in_menu BOOLEAN DEFAULT 1,
    menu_order INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Admin users
CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    role VARCHAR(50) DEFAULT 'admin',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Settings
CREATE TABLE IF NOT EXISTS settings (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Indexes for better performance
CREATE INDEX IF NOT EXISTS idx_products_category ON products(category);
CREATE INDEX IF NOT EXISTS idx_products_slug ON products(slug);
CREATE INDEX IF NOT EXISTS idx_posts_slug ON posts(slug);
CREATE INDEX IF NOT EXISTS idx_posts_status ON posts(status);
CREATE INDEX IF NOT EXISTS idx_posts_category ON posts(category);
CREATE INDEX IF NOT EXISTS idx_pages_slug ON pages(slug);
CREATE INDEX IF NOT EXISTS idx_product_urls_product_id ON product_urls(product_id);
CREATE INDEX IF NOT EXISTS idx_product_tags_product_id ON product_tags(product_id);

-- Insert default admin user (username: admin, password: admin123 - CHANGE THIS!)
-- Note: Password will be properly hashed when running reset-admin.php
INSERT OR IGNORE INTO users (username, password, email, role)
VALUES ('admin', '', 'admin@shluchimhealth.com', 'admin');

-- Insert default settings
INSERT OR IGNORE INTO settings (setting_key, setting_value) VALUES
('site_name', 'Shluchim Health'),
('site_tagline', 'Your trusted source for health and wellness products'),
('site_description', 'Discover the best health supplements and wellness products with expert reviews and recommendations.'),
('posts_per_page', '12'),
('products_per_page', '24'),
('amazon_tag', 'shluchimhealt-20'),
('logo_url', 'https://www.shluchimassist.com/wp-content/uploads/2021/05/Sassist-Logo.png');

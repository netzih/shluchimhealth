# Shluchim Health - Amazon Affiliate Blog

A professional, Amazon Associates-compliant PHP website for health and wellness product recommendations with a complete admin panel and WYSIWYG content management.

## Features

### ✨ Key Features
- **Amazon Associates Compliant**: Built to meet Amazon's program requirements
- **Multiple Affiliate Sources**: Support for Amazon, iHerb, and direct manufacturer links
- **Admin Panel**: Full-featured admin with WYSIWYG editor (Jodit)
- **Blog System**: SEO-optimized blog for high-quality content
- **Product Management**: Import and manage products from JSON
- **Responsive Design**: Mobile-friendly, modern interface
- **SEO Optimized**: Meta tags, clean URLs, proper structure
- **Required Legal Pages**: Privacy Policy, Affiliate Disclosure, About, Contact

### 🛠️ Tech Stack
- **Backend**: PHP 7.4+ with SQLite database
- **Frontend**: Vanilla JavaScript, modern CSS3
- **Admin**: Jodit WYSIWYG editor (open-source, no API key required)
- **Server**: Apache with mod_rewrite

## Installation

### Prerequisites
- PHP 7.4 or higher
- Apache web server with mod_rewrite enabled
- SQLite extension for PHP (usually enabled by default)

### Step-by-Step Installation

#### 1. Upload Files
Upload all files to your web server's public directory (e.g., `public_html`, `www`, or `htdocs`)

#### 2. Set Permissions
```bash
chmod 755 setup.php
chmod 755 import-products.php
chmod 755 init-pages.php
chmod 777 .  # Temporary for database creation
```

#### 3. Initialize Database
Run the setup script from command line:
```bash
php setup.php
```

Or visit `https://yourdomain.com/setup.php` in your browser.

**Default Admin Credentials:**
- Username: `admin`
- Password: `admin123`

⚠️ **IMPORTANT**: Change the default password immediately after first login!

#### 4. Import Products
```bash
php import-products.php
```

This will import all products from `products.json` into the database.

#### 5. Initialize Required Pages
```bash
php init-pages.php
```

This creates:
- Privacy Policy
- Affiliate Disclosure
- About Us
- Contact

#### 6. Secure the Site
```bash
chmod 755 .
chmod 600 database.db
```

#### 7. Configure Settings
1. Login to admin panel: `https://yourdomain.com/admin/`
2. Go to Settings
3. Update:
   - Site Name
   - Site Tagline
   - Logo URL
   - Amazon Associate Tag (your Amazon affiliate ID)

#### 8. Update .htaccess (if needed)
If your site is in a subdirectory, update the `RewriteBase` in `.htaccess`:
```apache
RewriteBase /your-subdirectory/
```

## Usage

### Admin Panel Access
- **URL**: `https://yourdomain.com/admin/`
- **Default Login**: admin / admin123

### Admin Features

#### Dashboard
View statistics and recent content

#### Blog Posts
- Create/edit posts with WYSIWYG editor
- Set featured posts
- Categorize content
- SEO settings (meta title, description)
- Draft/Published status

#### Products
- Browse all products
- Filter by category
- Search products
- Edit product details
- Manage multiple affiliate links per product

#### Pages
- Create static pages
- Set menu visibility
- Manage menu order
- SEO optimization

#### Settings
- Site configuration
- Display settings
- Amazon affiliate tag
- Change admin password

### Creating Quality Content (Amazon Compliance)

To stay compliant with Amazon Associates:

1. **Write Detailed Reviews**: Don't just list products - provide valuable insights
2. **Compare Products**: Help customers make informed decisions
3. **Include Personal Experience**: Share why you recommend products
4. **Regular Updates**: Keep content fresh and relevant
5. **Proper Disclosure**: Always disclose affiliate relationships (automatically included)

### Import/Update Products

To update your product catalog:

1. Edit `products.json` with your products
2. Run: `php import-products.php`
3. Or use Admin Panel → Products → Import from JSON

## File Structure

```
shluchimhealth/
├── admin/                  # Admin panel
│   ├── login.php
│   ├── index.php          # Dashboard
│   ├── posts.php          # Blog management
│   ├── products.php       # Product management
│   ├── pages.php          # Page management
│   └── settings.php       # Site settings
├── assets/
│   ├── css/
│   │   ├── style.css      # Frontend styles
│   │   └── admin.css      # Admin styles
│   └── js/
│       ├── main.js        # Frontend scripts
│       └── admin.js       # Admin scripts
├── includes/
│   ├── header.php         # Site header
│   └── footer.php         # Site footer
├── blog/                  # Blog pages
├── products/              # Product pages
├── index.php              # Homepage
├── product.php            # Product detail page
├── post.php               # Blog post page
├── page.php               # Static page viewer
├── config.php             # Configuration
├── database.php           # Database class
├── functions.php          # Utility functions
├── schema.sql             # Database schema
├── setup.php              # Setup script
├── import-products.php    # Product import script
├── init-pages.php         # Initialize pages
├── products.json          # Product data
├── .htaccess             # URL rewriting
└── README.md             # This file
```

## URL Structure

- Homepage: `/`
- Products: `/products/`
- Product Detail: `/product/product-slug`
- Blog: `/blog/`
- Blog Post: `/blog/post-slug`
- Static Page: `/page/page-slug`
- Admin: `/admin/`

## Customization

### Colors & Branding

Edit `assets/css/style.css` and modify the CSS variables:

```css
:root {
    --primary-color: #2563eb;
    --secondary-color: #10b981;
    --accent-color: #f59e0b;
    /* ... */
}
```

### Logo

1. Upload your logo to a publicly accessible URL
2. Admin Panel → Settings → Logo URL
3. Update the URL

### Amazon Associate Tag

1. Admin Panel → Settings
2. Update "Amazon Associate Tag" with your tracking ID
3. Products with Amazon links will automatically use your tag

## Security

### Recommended Security Measures

1. **Change Default Password**: Immediately after installation
2. **Database Protection**: The `.htaccess` blocks direct access to `database.db`
3. **File Permissions**: Set appropriate permissions (644 for files, 755 for directories)
4. **SSL Certificate**: Use HTTPS (strongly recommended)
5. **Regular Updates**: Keep PHP and server software updated
6. **Backup**: Regularly backup `database.db` and uploaded files

### Backup

Backup these important files:
```bash
database.db
products.json
uploads/
```

## Troubleshooting

### "404 Not Found" on all pages
- Enable mod_rewrite in Apache
- Check .htaccess file is uploaded
- Verify Apache AllowOverride is enabled

### "Database connection failed"
- Check PHP has SQLite extension enabled
- Verify database.db has proper permissions

### Products not showing
- Run `php import-products.php`
- Check products.json is valid JSON

### Admin login not working
- Verify database was created properly
- Check database.db exists and is readable

### CSS/JS not loading
- Check file paths in config.php
- Verify BASE_URL is correct
- Check file permissions

## Support & Development

### Product JSON Format

```json
[
    {
        "id": 123456,
        "category": "Supplements",
        "title": "Product Name",
        "description": "Product description",
        "image": "https://example.com/image.jpg",
        "tags": ["tag1", "tag2"],
        "urls": [
            {
                "name": "Amazon",
                "url": "https://amazon.com/..."
            },
            {
                "name": "iHerb",
                "url": "https://iherb.com/..."
            }
        ]
    }
]
```

## Amazon Associates Compliance

This website includes:
- ✅ Proper affiliate disclosure on all pages
- ✅ Privacy Policy
- ✅ Clear indication of affiliate relationships
- ✅ Quality content structure for reviews
- ✅ Multiple retailer options
- ✅ Professional design and user experience

### Tips for Amazon Approval

1. **Create Quality Content**: Write detailed blog posts about products
2. **Add Multiple Posts**: Have at least 5-10 quality blog posts
3. **Regular Updates**: Post new content regularly
4. **Proper Disclosure**: Clearly state affiliate relationships (auto-included)
5. **Professional Design**: Use the clean, modern design provided
6. **About Page**: Fill out with information about your site's mission

## License

This project is provided as-is for use with your affiliate marketing business.

## Credits

Built for Shluchim Health
Powered by PHP, SQLite, and Jodit

---

## Quick Start Checklist

- [ ] Upload files to server
- [ ] Run `php setup.php`
- [ ] Run `php import-products.php`
- [ ] Run `php init-pages.php`
- [ ] Login to admin panel
- [ ] Change default password
- [ ] Update site settings
- [ ] Add Amazon Associate Tag
- [ ] Update About page with your info
- [ ] Create 5-10 quality blog posts
- [ ] Apply/reapply to Amazon Associates
- [ ] Start promoting your site!

---

**Need Help?** Check the troubleshooting section or review the code comments for guidance.

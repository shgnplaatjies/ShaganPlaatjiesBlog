# Quick Reference Guide

Complete reference for common commands, ACF usage, custom blocks, and troubleshooting.

## Table of Contents

- [Common Commands](#common-commands)
- [Advanced Custom Fields (ACF)](#advanced-custom-fields-acf)
- [Custom Blocks](#custom-blocks)
- [Troubleshooting](#troubleshooting)
- [Tips & Best Practices](#tips--best-practices)

---

## Common Commands

### Theme Development

```bash
# Navigate to theme
cd app/bedrock/web/app/themes/shaganplaatjies

# Development build (with source maps)
npm run dev

# Watch mode (auto-rebuild on changes) - USE THIS DAILY
npm run watch

# Production build (minified, optimized)
npm run production

# Run linting
npm run lint

# Fix linting issues
npm run lint:fix
```

### WordPress (WP-CLI)

```bash
# In Local by Flywheel: right-click site → "Open Site Shell"

# Database operations
wp db export backup.sql                       # Export database
wp db import backup.sql                       # Import database
wp db optimize                                # Optimize database
wp db check                                   # Check database connection

# URL updates (after database import)
wp search-replace 'https://livesite.co.za' 'http://your-local-site-name.local' --all-tables
wp search-replace 'https://staging.yourwebsite.com' 'http://your-local-site-name.local' --all-tables

# Plugin management
wp plugin list                                # List all plugins
wp plugin install advanced-custom-fields --activate
wp plugin activate plugin-name
wp plugin deactivate plugin-name
wp plugin update-all

# Theme management
wp theme list                                 # List all themes
wp theme activate shaganplaatjies

# Post management
wp post list --post_type=post                 # List posts
wp post delete $(wp post list --post_type='revision' --format=ids) --force  # Delete revisions

# Cache management
wp cache flush                                # Flush object cache
wp transient delete-all                       # Delete all transients
wp rewrite flush                              # Flush rewrite rules

# Create admin user
wp user create admin admin@example.com --role=administrator --user_pass=password

# Check site health
wp core check-update                          # Check for WordPress updates
wp core version                               # Show WordPress version
```

### Git Workflow

```bash
# Check status
git status

# Stage changes
git add .
git add specific-file.php

# Commit changes
git commit -m "Description of changes"

# Push to GitHub (triggers auto-build)
git push origin main

# Pull latest changes (after GitHub Actions completes)
git pull origin main

# Create new branch
git checkout -b feature/new-feature

# Switch branches
git checkout main

# View commit history
git log --oneline -10
```

### Composer (PHP Dependencies)

```bash
# Navigate to Bedrock root
cd app/bedrock

# Install dependencies
composer install

# Install specific package
composer require wpackagist-plugin/advanced-custom-fields

# Update dependencies
composer update

# Dump autoload (fix class loading issues)
composer dump-autoload
```

---

## Advanced Custom Fields (ACF)

### Installation

```bash
# Via Composer (in app/bedrock)
composer require wpackagist-plugin/advanced-custom-fields

# Activate via WP-CLI
wp plugin activate advanced-custom-fields
```

### Helper Functions

#### Check if ACF is Active

```php
if (ShaganPlaatjies\is_acf_active()) {
    // ACF is available
}
```

#### Get Field with Fallback

```php
// Function signature: get_field_safe($field_name, $post_id = false, $default = '')

$title = ShaganPlaatjies\get_field_safe('hero_title', get_the_ID(), 'Default Title');
$email = ShaganPlaatjies\get_field_safe('contact_email', 'option', 'info@example.com');
```

#### Get Theme Option

```php
// From ACF Options Page
$site_email = ShaganPlaatjies\get_theme_option('contact_email', 'default@example.com');
$primary_color = ShaganPlaatjies\get_theme_option('primary_color', '#0ea5e9');
```

#### Get Repeater Field

```php
$items = ShaganPlaatjies\get_repeater_field('portfolio_items', get_the_ID());

if (!empty($items)) {
    foreach ($items as $item) {
        echo $item['title'];
        echo $item['description'];
    }
}
```

### Common Field Types

#### Text Field

```php
$title = get_field('title');
echo esc_html($title);
```

#### Image Field

```php
$image = get_field('featured_image');
if ($image) {
    echo '<img src="' . esc_url($image['url']) . '" alt="' . esc_attr($image['alt']) . '">';
}
```

#### Repeater Field

```php
if (have_rows('team_members')) {
    while (have_rows('team_members')) {
        the_row();
        $name = get_sub_field('member_name');
        $role = get_sub_field('member_role');
        echo '<p>' . esc_html($name) . ' - ' . esc_html($role) . '</p>';
    }
}
```

#### Flexible Content

```php
if (have_rows('page_sections')) {
    while (have_rows('page_sections')) {
        the_row();
        $layout = get_row_layout();

        if ($layout === 'hero') {
            // Render hero section
        } elseif ($layout === 'content') {
            // Render content section
        }
    }
}
```

### JSON Sync

Field groups are automatically saved to `acf-config/` directory for version control.

**Export:**
1. WordPress Admin → Custom Fields
2. Select field group
3. Automatically saved as JSON in `acf-config/`

**Import:**
- ACF automatically imports JSON files from `acf-config/` on page load

---

## Custom Blocks

### Register a Block

In `app/blocks.php`:

```php
register_block_type('your-theme-name/feature-box', [
    'render_callback' => __NAMESPACE__ . '\\render_feature_box_block',
    'attributes' => [
        'title' => ['type' => 'string', 'default' => ''],
        'description' => ['type' => 'string', 'default' => ''],
    ],
]);
```

### Render Callback

```php
function render_feature_box_block($attributes, $content) {
    $title = $attributes['title'] ?? '';
    $description = $attributes['description'] ?? '';

    ob_start();
    ?>
    <div class="feature-box bg-white rounded-lg p-6 shadow-md">
        <?php if ($title) : ?>
            <h3 class="text-xl font-bold mb-4"><?php echo esc_html($title); ?></h3>
        <?php endif; ?>

        <?php if ($description) : ?>
            <p class="text-secondary-700"><?php echo wp_kses_post($description); ?></p>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}
```

### Block Templates

Create templates in `templates/blocks/`:

```blade
{{-- templates/blocks/hero.blade.php --}}
<section class="hero bg-primary-600 text-white py-24">
    <div class="container">
        <h1 class="text-5xl font-bold mb-4">
            {{ get_field('hero_title') ?? 'Welcome' }}
        </h1>

        @if ($subtitle = get_field('hero_subtitle'))
            <p class="text-xl">{{ $subtitle }}</p>
        @endif
    </div>
</section>
```

### ACF Blocks

```php
// Register ACF-powered block
register_block_type('your-theme-name/testimonials', [
    'render_callback' => __NAMESPACE__ . '\\render_testimonials_block',
]);

function render_testimonials_block($block) {
    $testimonials = get_field('testimonials', 'block_' . $block['id']);

    if (empty($testimonials)) {
        return '';
    }

    // Render testimonials...
}
```

---

## Troubleshooting

### Local Development Issues

#### Styles Not Loading

```bash
# Solution 1: Build assets
cd app/bedrock/web/app/themes/shaganplaatjies
npm run dev

# Verify dist/ folder exists with CSS/JS files
ls -la dist/

# Solution 2: Clear browser cache
# Press Ctrl+Shift+Delete (Windows) or Cmd+Shift+Delete (Mac)
```

#### Database Connection Error

```bash
# Check .env file matches Local by Flywheel credentials
# In Local → Database tab, verify:
DB_HOST=localhost:10006  # Note: May use custom port
DB_NAME=local
DB_USER=root
DB_PASSWORD=root
DB_PREFIX=wphc_  # Match table prefix from imported database
```

#### White Screen / PHP Error

```bash
# Enable debugging in .env
WP_DEBUG=true
WP_DEBUG_DISPLAY=true
WP_DEBUG_LOG=true

# Check error log
# Local → right-click site → View Logs
# Or check: app/bedrock/web/wp-content/debug.log
```

#### "Cannot Redeclare Function" Error

```bash
# Solution: Dump autoload
cd app/bedrock
composer dump-autoload
```

#### npm run watch Fails

```bash
# Solution 1: Check Node version (need 16+)
node --version

# Solution 2: Reinstall dependencies
rm -rf node_modules package-lock.json
npm install

# Solution 3: Clear npm cache
npm cache clean --force
npm install
```

### Deployment Issues

#### GitHub Actions Failing

**Check workflow logs:**
1. GitHub → Actions tab
2. Click failed workflow
3. Expand failed step
4. Read error message

**Common fixes:**
```bash
# Fix syntax errors locally first
npm run lint
npm run production  # Test build locally

# Then push again
git add .
git commit -m "Fix build errors"
git push origin main
```

#### FTP Deploy Timeout

Increase timeout in GitHub Secrets:
- `FTP_TIMEOUT`: 30000 → 60000

#### Changes Not Showing on Staging

```bash
# 1. Check GitHub Actions completed successfully
# 2. Clear browser cache
# 3. Check browser console (F12) for errors
# 4. Verify files uploaded via FTP

# Hard refresh browser
# Windows: Ctrl+Shift+R
# Mac: Cmd+Shift+R
```

### ACF Issues

#### ACF Fields Not Showing

```bash
# 1. Check field group location rules
# 2. Verify field group is published
# 3. Clear cache: wp cache flush
# 4. Check user has correct role permissions
```

#### ACF Function Undefined

```php
// Always check if ACF is active
if (function_exists('get_field')) {
    $value = get_field('field_name');
}

// Or use helper function
if (ShaganPlaatjies\is_acf_active()) {
    $value = get_field('field_name');
}
```

#### JSON Not Syncing

```bash
# 1. Ensure acf-config/ directory exists and is writable
cd app/bedrock/web/app/themes/shaganplaatjies
mkdir -p acf-config
chmod 755 acf-config

# 2. Resave field group in WordPress admin
# 3. Check for valid JSON syntax
```

---

## Tips & Best Practices

### Development

✅ **Always use `npm run watch`** during active development
✅ **Test locally before pushing** to avoid breaking staging
✅ **Commit frequently** with descriptive messages
✅ **One feature per commit** for easier rollback
✅ **Sync database periodically** from live for real content
✅ **Check browser console** (F12) for JavaScript errors
✅ **Use WP-CLI** for database operations instead of GUI

### Code Quality

✅ **Escape output:** `esc_html()`, `esc_url()`, `wp_kses_post()`
✅ **Sanitize input:** `sanitize_text_field()`, `sanitize_email()`
✅ **Validate data** before using
✅ **Use namespaces** to avoid function conflicts
✅ **Document complex code** with comments
✅ **Follow WordPress coding standards**
✅ **Run linting** before committing: `npm run lint`

### ACF

✅ **Use snake_case** for field names: `hero_title`, `cta_button_text`
✅ **Add field instructions** for admin users
✅ **Set default values** where appropriate
✅ **Use conditional logic** to show/hide fields
✅ **Choose correct return formats** (Image Array vs URL, etc.)
✅ **Group related fields** in logical sections
✅ **Commit JSON files** to version control
✅ **Use helper functions** with fallbacks: `get_field_safe()`

### Custom Blocks

✅ **Sanitize all attributes** before output
✅ **Escape output** with `esc_html()`, `wp_kses_post()`
✅ **Provide fallback values** for missing data
✅ **Use semantic HTML** for accessibility
✅ **Keep blocks focused** - one purpose per block
✅ **Add help text** for content editors
✅ **Test in block editor** before deployment
✅ **Use Blade templates** for cleaner code

### Deployment

✅ **Monitor GitHub Actions** after every push
✅ **Test on staging** before production
✅ **Create backups** before major changes
✅ **Deploy during low-traffic** periods
✅ **Clear cache** after deployment
✅ **Verify critical functionality** after deploy
✅ **Keep staging and production** content structures aligned

### Performance

✅ **Optimize images** before uploading
✅ **Use caching plugins** on production
✅ **Minimize database queries** in templates
✅ **Use transients** for expensive operations
✅ **Lazy load images** when possible
✅ **Minify assets** in production (done automatically)
✅ **Monitor page load times**

### Security

✅ **Never commit `.env` files** to Git
✅ **Use strong passwords** for all accounts
✅ **Keep plugins updated** regularly
✅ **Set correct file permissions** (755 for dirs, 644 for files)
✅ **Disable file editing** in production (.env: `DISALLOW_FILE_EDIT=true`)
✅ **Use HTTPS** everywhere
✅ **Regular backups** (automated via cPanel)

## Quick Lookups

### File Locations

```
Local environment:
  C:\Portfolio\ShaganPlaatjies Wordpress Theme\

Local site in Local by Flywheel:
  C:\Users\YourName\Local Sites\shaganplaatjies\

Theme folder:
  app/bedrock/web/app/themes/your-theme-name/

Templates:
  app/bedrock/web/app/themes/your-theme-name/templates/

Styles (source):
  app/bedrock/web/app/themes/your-theme-name/resources/css/

Compiled styles:
  app/bedrock/web/app/themes/your-theme-name/dist/css/

ACF JSON:
  app/bedrock/web/app/themes/your-theme-name/acf-config/
```

### URLs

```
Local: http://your-local-site-name.local
Local Admin: http://your-local-site-name.local/wp/wp-admin

Staging: https://staging.yourwebsite.com
Staging Admin: https://staging.yourwebsite.com/wp-admin

GitHub Actions: https://github.com/[your-repo]/actions
```

### Default Credentials (Local)

```
Database:
  Host: localhost:10006
  Name: local
  User: root
  Password: root

WordPress Admin:
  (Use credentials from imported database)
```

## Additional Resources

- **WordPress Codex:** https://codex.wordpress.org/
- **WP-CLI Documentation:** https://wp-cli.org/
- **ACF Documentation:** https://www.advancedcustomfields.com/resources/
- **Tailwind CSS Docs:** https://tailwindcss.com/docs
- **Laravel Mix Docs:** https://laravel-mix.com/docs/

## Getting Help

1. **Check error logs** (Local → View Logs or cPanel → Error Log)
2. **Review documentation** in `docs/` folder
3. **Check GitHub Actions** logs if deployment fails
4. **Search WordPress Codex** for WordPress-specific issues
5. **Create GitHub issue** with detailed error description

---

**Remember:** Always test locally, deploy to staging, then push to production. Never skip testing!

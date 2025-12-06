# Theme Configuration Guide

This theme is designed to be generic and configurable. All theme-specific names and identifiers are managed through environment variables.

## Quick Setup

### 1. Configure Theme Identity

Edit your `.env` file (local) or `.env.production` (server) and set:

```env
# Theme Configuration
THEME_KEY=your-theme-name          # Slug/identifier (lowercase, hyphens)
THEME_NAME='Your Theme Name'       # Display name (proper case)
```

### 2. Update style.css

Edit `app/bedrock/web/app/themes/shaganplaatjies/style.css`:

```css
/*
Theme Name: Your Theme Name        ← Update this
Theme URI: https://yourwebsite.com ← Update this
Description: A modern, custom WordPress theme built with Roots Sage, Tailwind CSS, and Laravel Mix
Version: 1.0.0
Author: Your Name                  ← Update this
Author URI: https://yourwebsite.com ← Update this
License: MIT
License URI: https://opensource.org/licenses/MIT
Text Domain: your-theme-name       ← Match THEME_KEY
Domain Path: /resources/languages
Requires at least: 6.0
Requires PHP: 8.0
*/
```

### 3. Update package.json (Optional)

Edit `app/bedrock/web/app/themes/shaganplaatjies/package.json`:

```json
{
  "name": "your-theme-name",       // Match THEME_KEY
  "description": "Your theme description",
  "author": "Your Name",
  ...
}
```

## Environment Variables

### THEME_KEY

**Purpose:** Theme identifier/slug used throughout the application

**Format:**
- Lowercase
- Hyphens for spaces
- No special characters

**Examples:**
- `my-awesome-theme`
- `company-name-theme`
- `portfolio-2024`

**Used in:**
- Asset handles (CSS/JS enqueuing)
- JavaScript object names
- WordPress action hooks
- Nonce generation
- Text domain (translations)

**Default:** `your-theme-name`

### THEME_NAME

**Purpose:** Human-readable theme display name

**Format:**
- Proper case
- Spaces allowed
- Single quotes recommended (for special characters)

**Examples:**
- `'My Awesome Theme'`
- `'Company Name Theme'`
- `'Portfolio 2024'`

**Used in:**
- Theme configuration access
- Display purposes (if implemented in templates)
- Documentation

**Default:** `'Your Theme Name'`

## How It Works

The theme uses a configuration helper function in `functions.php`:

```php
function get_theme_config($key) {
    $defaults = [
        'key' => 'your-theme-name',
        'name' => 'Your Theme Name',
    ];

    $config = [
        'key' => env('THEME_KEY', $defaults['key']),
        'name' => env('THEME_NAME', $defaults['name']),
    ];

    return $config[$key] ?? $defaults[$key];
}
```

### Asset Enqueuing Example

```php
function enqueue_assets() {
    $theme_key = get_theme_config('key');

    wp_enqueue_style(
        $theme_key . '-main',  // Handle: 'your-theme-name-main'
        get_template_directory_uri() . '/dist/css/app.css',
        [],
        $theme_version
    );
}
```

### JavaScript Localization Example

```php
wp_localize_script(
    $theme_key . '-main',
    str_replace('-', '_', $theme_key),  // 'your_theme_name'
    [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce($theme_key . '-nonce'),
    ]
);
```

## Namespace

The PHP namespace is set to `ShaganPlaatjies` by default. To change:

1. **Find and replace** `namespace ShaganPlaatjies;` in:
   - `functions.php`
   - `app/setup.php`
   - `app/acf-helpers.php`
   - `app/blocks.php`

2. **Use PascalCase** for namespace (e.g., `MyAwesomeTheme`, `CompanyNameTheme`)

3. **Match in all files** for consistency

## Text Domain

The text domain (used for translations) should match `THEME_KEY`.

**Current:** `your-theme-name`

**Update in:**
- `style.css` (Text Domain field)
- All translation function calls in templates

**Example:**
```php
<?php esc_html_e('Home', 'your-theme-name'); ?>
```

## Complete Configuration Checklist

- [ ] Set `THEME_KEY` in `.env` (local)
- [ ] Set `THEME_NAME` in `.env` (local)
- [ ] Set `THEME_KEY` in `.env.production` (server)
- [ ] Set `THEME_NAME` in `.env.production` (server)
- [ ] Update `style.css` header comment
- [ ] Update `package.json` name and author
- [ ] Update namespace (optional but recommended)
- [ ] Test locally: `npm run watch`
- [ ] Commit changes: `git add . && git commit -m "Configure theme identity"`
- [ ] Deploy: `git push origin main`

## Examples

### Example 1: Portfolio Theme

**.env:**
```env
THEME_KEY=john-portfolio
THEME_NAME='John Doe Portfolio'
```

**style.css:**
```css
/*
Theme Name: John Doe Portfolio
Author: John Doe
Text Domain: john-portfolio
*/
```

**Namespace:**
```php
namespace JohnPortfolio;
```

### Example 2: Business Theme

**.env:**
```env
THEME_KEY=acme-corp-theme
THEME_NAME='Acme Corporation Theme'
```

**style.css:**
```css
/*
Theme Name: Acme Corporation Theme
Author: Acme Corp
Text Domain: acme-corp-theme
*/
```

**Namespace:**
```php
namespace AcmeCorpTheme;
```

### Example 3: Blog Theme

**.env:**
```env
THEME_KEY=tech-blog-2024
THEME_NAME='Tech Blog 2024'
```

**style.css:**
```css
/*
Theme Name: Tech Blog 2024
Author: Tech Blogger
Text Domain: tech-blog-2024
*/
```

**Namespace:**
```php
namespace TechBlog2024;
```

## Important Notes

1. **Consistency:** Keep `THEME_KEY`, text domain, and package.json name consistent
2. **Deployment:** Update both local and production `.env` files
3. **Git:** Never commit `.env` files (they're in .gitignore)
4. **Rebuild:** Run `npm run production` after changing configuration
5. **Clear cache:** Clear WordPress cache after updating theme identity

## Troubleshooting

### Assets not loading

**Cause:** THEME_KEY mismatch between environments

**Fix:**
```bash
# Check .env file
grep THEME_KEY .env

# Verify in functions.php
var_dump(get_theme_config('key'));

# Rebuild assets
npm run production
```

### Translation strings not working

**Cause:** Text domain doesn't match THEME_KEY

**Fix:** Update all `esc_html_e()` and `esc_html__()` calls to use correct text domain

### Namespace errors

**Cause:** Namespace not updated in all files

**Fix:** Search and replace namespace in:
- functions.php
- app/setup.php
- app/acf-helpers.php
- app/blocks.php

## Support

For additional help:
- Check [README.md](README.md)
- Review [docs/LOCAL-SETUP.md](docs/LOCAL-SETUP.md)
- See [docs/REFERENCE.md](docs/REFERENCE.md)

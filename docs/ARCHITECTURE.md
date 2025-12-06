# Theme Architecture

Detailed documentation of the theme structure and architecture.

## Directory Structure

```
app/bedrock/web/app/themes/your-theme-name/
├── app/                           # PHP application code
│   ├── setup.php                  # Theme setup and configuration
│   ├── acf-helpers.php            # Advanced Custom Fields helpers
│   └── blocks.php                 # Custom block registration
├── resources/                     # Asset source files
│   ├── css/
│   │   ├── app.css               # Main stylesheet (Tailwind CSS)
│   │   └── editor.css            # Block editor styles
│   ├── js/
│   │   ├── app.js                # Main JavaScript entry point
│   │   ├── __tests__/            # Jest tests
│   │   └── utils/                # Utility functions
│   └── languages/                # Translation files
├── templates/                     # Blade PHP templates
│   ├── app.blade.php             # Main layout wrapper
│   ├── header.blade.php          # Header component
│   ├── footer.blade.php          # Footer component
│   ├── page.blade.php            # Main page template
│   ├── partials/                 # Reusable components
│   ├── blocks/                   # Custom block templates
│   └── pages/                    # Specific page templates
├── dist/                          # Compiled assets (generated)
│   ├── css/
│   │   ├── app.css               # Compiled main styles
│   │   └── editor.css            # Compiled editor styles
│   └── js/
│       └── app.js                # Bundled JavaScript
├── acf-config/                    # ACF field group JSON files
├── tests/                         # PHP tests
│   ├── Unit/                      # Unit tests
│   └── Feature/                   # Feature tests
├── scripts/                       # Utility scripts
│   └── db-migrations/             # Database migration scripts
├── functions.php                  # Theme initialization
├── index.php                      # Template dispatcher
├── style.css                      # WordPress theme header
├── theme.json                     # Block editor configuration
├── package.json                   # Node.js dependencies
├── webpack.mix.js                 # Laravel Mix configuration
├── tailwind.config.js             # Tailwind CSS configuration
├── jest.config.js                 # Jest testing configuration
├── phpunit.xml                    # PHPUnit configuration
├── .eslintrc.json                 # ESLint configuration
├── .prettierrc.json               # Prettier configuration
└── .babelrc                       # Babel transpilation configuration
```

## Core Concepts

### 1. Theme Initialization (functions.php)

The theme bootstrap happens in `functions.php`:

1. Loads Composer autoloader if available
2. Calls `bootstrap()` function which loads core modules:
   - `app/setup.php` - Theme registration and WordPress hooks
   - `app/acf-helpers.php` - ACF utility functions
   - `app/blocks.php` - Custom block registration
3. Registers stylesheet and JavaScript enqueue
4. Initializes localization for translations

### 2. PHP Theme Setup (app/setup.php)

Handles all WordPress theme registration:

- **Theme Support**: Adds support for WordPress features (post thumbnails, responsive embeds, etc.)
- **Navigation Menus**: Registers custom menu locations
- **Custom Post Types**: Define custom post types
- **Custom Taxonomies**: Define custom taxonomies
- **Image Sizes**: Register custom image sizes for responsive images
- **Theme Constants**: Define theme path and URL constants

### 3. Advanced Custom Fields (acf-helpers.php)

Utility functions for ACF integration:

- `is_acf_active()` - Check if ACF is installed
- `get_field_safe()` - Get ACF field with fallback
- `get_theme_option()` - Retrieve theme-wide settings
- `get_repeater_field()` - Get repeater field rows
- `get_theme_color()` - Get color settings
- `register_acf_options_pages()` - Create theme options pages

### 4. Custom Blocks (blocks.php)

Register and manage custom Gutenberg blocks:

- Block type registration with render callbacks
- Block category registration
- Block editor asset management
- Block editor restrictions (allowed block types)

## Template System

### Blade Templating

The theme uses Blade templates (PHP-based templating from Laravel):

- **app.blade.php** - Main layout wrapper
- **header.blade.php** - Site header with navigation
- **footer.blade.php** - Site footer with widgets
- **page.blade.php** - Main page/post template
- **templates/pages/** - Specific page templates (single.php, archive.php, etc.)

### Template Inheritance

```blade
@extends('templates.app')

@section('content')
    <!-- Page content here -->
@endsection
```

### Reusable Components

Partials go in `templates/partials/`:

```blade
@include('templates.partials.card')
@include('templates.partials.post-grid', ['posts' => $posts])
```

## Asset Compilation

### Build System (Laravel Mix)

**webpack.mix.js** configures the build process:

1. **CSS Processing**
   - Tailwind CSS entry point
   - PostCSS transformations
   - Autoprefixer for vendor prefixes
   - Minification in production

2. **JavaScript Bundling**
   - ES6+ transpilation via Babel
   - Module bundling with Webpack
   - Source maps in development
   - Minification in production

3. **Asset Versioning**
   - Cache-busting in production
   - manifest.json generated for reference

### CSS Architecture (Tailwind)

**tailwind.config.js** customizes Tailwind:

- Custom color palette
- Typography settings
- Spacing and layout
- Border radius presets
- Plugins: Typography, Forms, Aspect Ratio

### JavaScript Architecture

**resources/js/app.js** provides:

- DOM initialization
- Mobile menu handling
- Scroll effects
- Accessibility features
- Form behavior

## Testing Strategy

### PHP Testing (PHPUnit)

Configuration: **phpunit.xml**

- Unit tests: Test individual functions
- Feature tests: Test integrated functionality
- Location: `tests/Unit/` and `tests/Feature/`

### JavaScript Testing (Jest)

Configuration: **jest.config.js**

- Unit tests for app functions
- Location: `resources/js/__tests__/`
- Coverage tracking enabled

### Code Quality

- **ESLint** - JavaScript linting
- **Prettier** - Code formatting
- **PHP CodeSniffer** - PHP linting

## Deployment

### Asset Compilation for Production

```bash
npm run production
```

This creates optimized, minified assets in `dist/` directory.

### Database Migrations

Use WP-CLI scripts in `scripts/db-migrations/`:

- `export-database.sh` - Export DB backup
- `import-database.sh` - Import DB backup

### CI/CD Pipeline

GitHub Actions workflow (`.github/workflows/test.yml`):

1. Runs PHP tests and CodeSniffer
2. Runs JavaScript tests and linting
3. Builds production assets
4. Checks for security vulnerabilities

## Configuration

### Environment Variables (.env)

```env
WP_ENV=development
WP_HOME=http://shaganplaatjies-theme.local
DB_NAME=shaganplaatjies_db
THEME_CONTACT_EMAIL=info@shaganplaatjies.co.za
```

### Block Editor Configuration (theme.json)

Defines:

- Color palette
- Typography settings
- Layout presets
- Custom spacing
- Block-specific styles

## Development Workflow

### 1. Create Feature Branch

```bash
git checkout -b feature/new-feature
```

### 2. Build Assets During Development

```bash
cd app/bedrock/web/app/themes/shaganplaatjies
npm run watch
```

### 3. Run Tests

```bash
npm run test
```

### 4. Commit and Push

```bash
git add .
git commit -m "Add new feature"
git push origin feature/new-feature
```

### 5. Create Pull Request

- GitHub Actions runs automatically
- Tests must pass before merge
- Code review required

### 6. Deploy to Production

See [DEPLOYMENT.md](./DEPLOYMENT.md) for deployment process.

## Best Practices

### PHP

- Use namespaces to avoid conflicts
- Sanitize and validate all user input
- Use WordPress hooks for extensibility
- Document functions with PHPDoc comments

### JavaScript

- Use ES6+ syntax
- Keep functions modular and testable
- Use data attributes for DOM interactions
- Follow ARIA guidelines for accessibility

### CSS

- Use Tailwind utility classes
- Avoid writing custom CSS unless necessary
- Use CSS variables for theming
- Mobile-first responsive design

### Templates

- Use proper semantic HTML
- Implement accessibility standards
- Use descriptive class names
- Keep templates DRY with includes

## Extending the Theme

### Adding a Custom Post Type

In `app/setup.php`:

```php
register_post_type('custom-post', [
    'labels' => ['name' => 'Custom Posts'],
    'public' => true,
    'supports' => ['title', 'editor', 'thumbnail'],
    'menu_icon' => 'dashicons-files',
]);
```

### Creating a Custom Block

In `templates/blocks/custom-block.blade.php`:

```blade
<div class="custom-block bg-white rounded-lg p-6">
    <h2>{{ $attributes['title'] ?? 'Default Title' }}</h2>
</div>
```

### Adding ACF Fields

1. Create field group in WordPress admin
2. Export as JSON to `acf-config/` directory
3. Use in templates with `get_field()` helper

## Performance Optimization

1. **Assets**: Minified and versioned in production
2. **Caching**: Set appropriate cache headers
3. **Images**: Use responsive images with `srcset`
4. **CSS/JS**: Lazy load non-critical assets
5. **Database**: Optimize queries with WordPress transients

## Security

- All user input sanitized
- CSRF protection via nonces
- SQL injection prevention via prepare()
- XSS prevention via escaping functions
- Regular dependency updates via composer and npm

## Support & Documentation

- [Roots Bedrock Docs](https://roots.io/bedrock/docs/)
- [Roots Sage Docs](https://roots.io/sage/docs/)
- [Tailwind CSS Docs](https://tailwindcss.com/docs)
- [WordPress Plugin Handbook](https://developer.wordpress.org/plugins/)
- [WordPress Theme Handbook](https://developer.wordpress.org/themes/)

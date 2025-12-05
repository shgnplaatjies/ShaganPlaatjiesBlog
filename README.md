# Shagan Plaatjies WordPress Theme

A modern, professional WordPress theme fully managed through Git repository. Built with Roots Bedrock, Roots Sage, Tailwind CSS, and Laravel Mix.

![PHP](https://img.shields.io/badge/PHP-8.0+-blue)
![Node.js](https://img.shields.io/badge/Node.js-16+-green)
![WordPress](https://img.shields.io/badge/WordPress-6.0+-blue)
![License](https://img.shields.io/badge/License-MIT-green)

## Features

✨ **Modern Stack**
- [Roots Bedrock](https://roots.io/bedrock/) - WordPress boilerplate with Composer
- [Roots Sage](https://roots.io/sage/) - Modern WordPress theme framework
- [Tailwind CSS](https://tailwindcss.com/) - Utility-first CSS framework
- [Laravel Mix](https://laravel-mix.com/) - Simple asset compilation

🎨 **Advanced Customization**
- [Advanced Custom Fields (ACF)](https://www.advancedcustomfields.com/) - Custom field management
- Custom Gutenberg blocks with ACF integration
- Block editor configuration (theme.json)
- Flexible page builder patterns

🧪 **Developer Tools**
- PHPUnit testing framework
- Jest JavaScript testing
- ESLint for code quality
- Prettier for code formatting
- GitHub Actions CI/CD pipeline

📱 **Responsive & Accessible**
- Mobile-first responsive design
- WCAG 2.1 accessibility standards
- Semantic HTML structure
- Keyboard navigation support

⚙️ **Professional Development**
- Environment-based configuration (.env)
- Database migration scripts
- Asset versioning for caching
- Comprehensive documentation

## Prerequisites

- **PHP** 8.0 or higher
- **Node.js** 16+ with npm
- **Composer** - PHP dependency manager
- **Local by Flywheel** - Local development environment
- **WP-CLI** - WordPress command-line tools
- **Git** - Version control

## Quick Start

### 1. Clone the Repository

```bash
git clone <repository-url>
cd "ShaganPlaatjies Wordpress Theme"
```

### 2. Set Up Environment

```bash
cp .env.example .env
# Edit .env with your local configuration
```

### 3. Install Dependencies

```bash
# Install PHP dependencies
cd app/bedrock
composer install

# Install Node.js dependencies
cd ../web/app/themes/shaganplaatjies
npm install
```

### 4. Set Up WordPress

```bash
cd ../../..
wp db create
wp core install --url=http://shaganplaatjies-theme.local --title="Shagan Plaatjies" --admin_user=admin --admin_email=admin@example.com
wp theme activate shaganplaatjies
```

### 5. Build Assets

```bash
cd app/bedrock/web/app/themes/shaganplaatjies
npm run dev
```

## Development Workflow

### Start Development Server

Use Local by Flywheel to start your WordPress site, then watch for asset changes:

```bash
cd app/bedrock/web/app/themes/shaganplaatjies
npm run watch
```

### Available Commands

```bash
# Asset compilation
npm run dev               # Development build
npm run watch             # Watch for changes
npm run production        # Production build (minified)

# Code quality
npm run lint              # Run ESLint
npm run lint:fix          # Fix ESLint issues
npm run format            # Format code with Prettier

# Testing
npm run test              # Run all tests
npm run jest              # Run Jest tests
npm run jest:watch        # Watch Jest tests
composer test             # Run PHPUnit tests

# Database
./scripts/db-migrations/export-database.sh   # Export database
./scripts/db-migrations/import-database.sh   # Import database
```

## Documentation

- **[SETUP.md](docs/SETUP.md)** - Complete setup and configuration guide
- **[ARCHITECTURE.md](docs/ARCHITECTURE.md)** - Theme structure and design patterns
- **[ACF.md](docs/ACF.md)** - Advanced Custom Fields integration guide
- **[BLOCKS.md](docs/BLOCKS.md)** - Custom Gutenberg blocks documentation
- **[DEPLOYMENT.md](docs/DEPLOYMENT.md)** - Deployment to staging and production

## Technology Stack

### Backend
- PHP 8.0+
- WordPress 6.0+
- Roots Bedrock - Modern WordPress structure
- Advanced Custom Fields (ACF) - Content management
- Composer - PHP dependency management

### Frontend
- Tailwind CSS 3 - Utility-first CSS
- Laravel Mix 6 - Asset compilation
- ES6+ JavaScript - Modern JavaScript features
- Alpine.js - Lightweight reactive framework (optional)

### Development & Testing
- Node.js 16+ - JavaScript runtime
- npm 8+ - Node package manager
- PHPUnit 9 - PHP testing framework
- Jest - JavaScript testing framework
- ESLint - JavaScript linting
- Prettier - Code formatting
- GitHub Actions - CI/CD automation

## Configuration

### Environment Variables

Edit `.env` file to configure:

```env
WP_ENV=development
WP_HOME=http://shaganplaatjies-theme.local
DB_NAME=shaganplaatjies_db
DB_USER=root
DB_PASSWORD=root

# Theme options
THEME_CONTACT_EMAIL=info@shaganplaatjies.co.za
```

See `.env.example` for all available options.

## Testing

### Run All Tests

```bash
npm run test
```

## Deployment

For detailed deployment instructions, see [DEPLOYMENT.md](docs/DEPLOYMENT.md).

## Database Management

### Export Database

```bash
# macOS/Linux
./scripts/db-migrations/export-database.sh

# Windows PowerShell
.\scripts\db-migrations\export-database.ps1
```

### Import Database

```bash
# macOS/Linux
./scripts/db-migrations/import-database.sh database-backup.sql

# Windows PowerShell
.\scripts\db-migrations\import-database.ps1 -Filename "database-backup.sql"
```

## License

MIT License

## Author

**Shagan Plaatjies**
- Website: [shaganplaatjies.co.za](https://www.shaganplaatjies.co.za)

## Support

For issues or questions:

1. Check the [documentation](docs/)
2. Create a GitHub issue with detailed description

---

**Version:** 1.0.0
**Last Updated:** 2024-11-17
**PHP Required:** 8.0+
**WordPress Required:** 6.0+

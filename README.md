# WordPress Theme - Modern Development Workflow

A modern WordPress theme repository with automated build pipeline for shared hosting deployment.

![PHP](https://img.shields.io/badge/PHP-8.0+-blue)
![Node.js](https://img.shields.io/badge/Node.js-16+-green)
![WordPress](https://img.shields.io/badge/WordPress-6.0+-blue)

## Quick Start

### For Local Development
1. Install: Local by Flywheel, Node.js 16+, Composer
2. Read: [docs/LOCAL-SETUP.md](docs/LOCAL-SETUP.md)
3. Clone repo, run `npm install` and `composer install`
4. Create local WordPress site, sync database from live
5. Run `npm run watch` and start developing

### For Deployment
1. Make changes locally
2. `git push origin main` (GitHub auto-builds assets)
3. Wait 1-2 minutes for GitHub Actions
4. `git pull origin main` (download compiled assets)
5. Upload theme folder via SFTP to staging
6. Softaculous: staging → production

## Architecture Overview

```
┌──────────────────────────────────────────────────────────────────┐
│                     LOCAL DEVELOPMENT                            │
│  • Bedrock WordPress structure (modern, organized)               │
│  • Local by Flywheel environment                                 │
│  • npm run watch (auto-compile on changes)                       │
│  • Sync database from live periodically                          │
└────────────────────────────┬─────────────────────────────────────┘
                             │
                    git push origin main
                             │
                             ↓
┌──────────────────────────────────────────────────────────────────┐
│                    GITHUB (Auto-Build)                           │
│  • GitHub Actions workflow triggered                             │
│  • npm install → npm run production                              │
│  • Compiles Tailwind CSS → dist/css/app.css                      │
│  • Bundles JavaScript → dist/js/app.js                           │
│  • Auto-deploys theme to staging via FTP                         │
└────────────────────────────┬─────────────────────────────────────┘
                             │
                    Auto-deploy via FTP
                             │
                             ↓
┌──────────────────────────────────────────────────────────────────┐
│              STAGING (Traditional WordPress)                     │
│  • Standard WordPress structure (Softaculous install)            │
│  • Theme deployed to /wp-content/themes/your-theme-name/         │
│  • Test changes before production                                │
└────────────────────────────┬─────────────────────────────────────┘
                             │
              Softaculous staging → prod
                             │
                             ↓
┌──────────────────────────────────────────────────────────────────┐
│             PRODUCTION (Traditional WordPress)                   │
│  • Managed via Softaculous in cPanel                             │
│  • Plugins/Core/DB managed in WordPress admin                    │
│  • Theme synced from staging                                     │
└──────────────────────────────────────────────────────────────────┘
```

## Repository Structure

```
ShaganPlaatjies Wordpress Theme/
├── app/bedrock/                      # Bedrock WordPress (local only)
│   ├── .env.example                  # Local environment template
│   ├── web/
│   │   ├── app/
│   │   │   └── themes/your-theme-name/    # Theme source code
│   │   │       ├── resources/              # Source files (edit these)
│   │   │       │   ├── css/app.css         # Tailwind CSS
│   │   │       │   └── js/app.js           # JavaScript
│   │   │       ├── dist/                   # Compiled assets (auto-generated)
│   │   │       ├── templates/              # Page templates
│   │   │       ├── app/                    # PHP helpers
│   │   │       ├── functions.php           # Theme initialization
│   │   │       ├── webpack.mix.js          # Build configuration
│   │   │       ├── tailwind.config.js      # Tailwind config
│   │   │       └── package.json            # Node dependencies
│   │   └── wp/                             # WordPress core
│   └── vendor/                       # PHP dependencies (Composer)
├── .github/workflows/                # GitHub Actions
│   └── build-and-deploy.yml          # Auto-build and deploy
├── docs/                             # Documentation
│   ├── LOCAL-SETUP.md                # Local development guide
│   ├── ARCHITECTURE.md               # Theme structure
│   └── REFERENCE.md                  # Commands and tips
├── .env.production.example           # Production config template
├── README.md                         # This file
├── WORKFLOW.md                       # Workflow diagram
└── DEPLOYMENT.md                     # Deployment guide
```

## Technology Stack

### Local Development
- **Roots Bedrock** - Modern WordPress boilerplate (local only)
- **Local by Flywheel** - Local development environment
- **WP-CLI** - WordPress command-line tools

### Theme
- **PHP 8.0+** - Server-side logic
- **Tailwind CSS 3** - Utility-first CSS framework
- **Laravel Mix 6** - Asset compilation (Webpack wrapper)
- **ES6+ JavaScript** - Modern JavaScript
- **Advanced Custom Fields (ACF)** - Custom field management

### Build & Deployment
- **Node.js 16+** - JavaScript runtime
- **Composer** - PHP dependency management
- **GitHub Actions** - Automated CI/CD pipeline
- **FTP Deploy** - Automated staging deployment

### Production
- **Traditional WordPress** - Standard WordPress structure (via Softaculous)
- **Shared Hosting** - cPanel with SFTP access
- **Softaculous** - Staging → production pushes

## Key Concepts

### 1. Dual Architecture
- **Local:** Uses Bedrock structure for better developer experience
- **Staging/Production:** Uses traditional WordPress structure for shared hosting compatibility

### 2. Pre-Compiled Assets
- GitHub Actions automatically compiles CSS/JS on every push
- Compiled assets (dist/) committed to Git
- No build tools needed on server

### 3. Hybrid Deployment
- **Theme:** Fully source-controlled, auto-deployed via GitHub → FTP
- **Plugins/Core/Database:** Managed via WordPress admin, pushed via Softaculous

### 4. Environment Configuration
- Local: `.env` file in `app/bedrock/`
- Production: Managed via cPanel (traditional wp-config.php)

## Prerequisites

### For Local Development
- **Local by Flywheel** - https://localwp.com/
- **Node.js 16+** - https://nodejs.org/ (LTS version)
- **Composer** - https://getcomposer.org/

### For Deployment
- **Git** - Version control
- **SFTP Client** - WinSCP, Cyberduck, or FileZilla
- **cPanel Access** - For database credentials

### What You Already Have
- Live WordPress on shared hosting (staging + production via Softaculous)
- This theme repository

## Getting Started

### First-Time Setup

1. **Install required tools** (one-time)
   ```bash
   # Download and install:
   # - Local by Flywheel
   # - Node.js 16+
   # - Composer
   ```

2. **Clone and install dependencies**
   ```bash
   git clone <repository-url>
   cd "ShaganPlaatjies Wordpress Theme"

   cd app/bedrock
   composer install

   cd web/app/themes/shaganplaatjies
   npm install
   ```

3. **Set up local environment**
   - Follow [docs/LOCAL-SETUP.md](docs/LOCAL-SETUP.md) for complete guide
   - Create local WordPress site in Local by Flywheel
   - Configure `.env` file with local database credentials
   - Import database from live site
   - Build theme assets

4. **Test deployment**
   - Make a test change
   - Push to GitHub
   - Verify GitHub Actions builds successfully
   - Check theme auto-deploys to staging

## Daily Development Workflow

```bash
# 1. Start Local by Flywheel → Start your site

# 2. Navigate to theme directory
cd app/bedrock/web/app/themes/shaganplaatjies

# 3. Start watch mode (auto-compile on file changes)
npm run watch

# 4. Make changes to theme files
# - Edit resources/css/app.css
# - Edit resources/js/app.js
# - Edit templates/*.php
# - Changes auto-compile and reload

# 5. Test locally at http://your-local-site-name.local

# 6. Commit and push
git add .
git commit -m "Description of changes"
git push origin main

# 7. GitHub Actions automatically:
# - Runs tests
# - Builds production assets
# - Deploys theme to staging

# 8. Check staging site to verify changes

# 9. When ready: Softaculous staging → production
```

## Common Commands

### Theme Development
```bash
# Navigate to theme
cd app/bedrock/web/app/themes/shaganplaatjies

# Development build (with source maps)
npm run dev

# Watch mode (auto-rebuild on changes)
npm run watch

# Production build (minified, optimized)
npm run production

# Run linting
npm run lint
```

### WordPress (WP-CLI)
```bash
# In Local by Flywheel: right-click site → "Open Site Shell"

# Import database
wp db import /path/to/backup.sql

# Update URLs after import
wp search-replace 'https://livesite.co.za' 'http://your-local-site-name.local' --all-tables

# Install/activate plugin
wp plugin install advanced-custom-fields --activate

# Flush rewrite rules (fix permalinks)
wp rewrite flush
```

### Git Workflow
```bash
# Check status
git status

# Commit changes
git add .
git commit -m "Description"
git push origin main

# Pull latest changes
git pull origin main
```

## Documentation

### Essential Guides
- **[docs/LOCAL-SETUP.md](docs/LOCAL-SETUP.md)** - Complete local development setup
- **[DEPLOYMENT.md](DEPLOYMENT.md)** - Deployment to staging and production
- **[WORKFLOW.md](WORKFLOW.md)** - Visual workflow diagram

### Reference
- **[docs/ARCHITECTURE.md](docs/ARCHITECTURE.md)** - Theme structure and code organization
- **[docs/REFERENCE.md](docs/REFERENCE.md)** - Commands, tips, and troubleshooting

## Deployment

### Automated Staging Deployment

Every push to `main` branch:
1. GitHub Actions builds production assets
2. Theme auto-deploys to staging via FTP
3. Check https://staging.yourwebsite.com

See [DEPLOYMENT.md](DEPLOYMENT.md) for complete deployment guide.

### Production Deployment

Use Softaculous in cPanel to push staging → production.

## Troubleshooting

### Styles not loading locally
```bash
cd app/bedrock/web/app/themes/shaganplaatjies
npm run dev
```
Check `dist/` folder exists with compiled CSS/JS.

### Database connection error
Check `.env` matches Local by Flywheel credentials:
- In Local → Database tab
- Update DB_HOST, DB_NAME, DB_USER, DB_PASSWORD

### White screen after theme activation
1. Enable debug in `.env`: `WP_DEBUG=true`
2. Check error log: Local → right-click site → View Logs
3. Ensure Composer dependencies installed: `composer install`

### GitHub Actions failing
1. Check workflow status in GitHub Actions tab
2. Review error logs
3. Common issues: syntax errors, missing dependencies

### Changes not showing on staging
1. Wait for GitHub Actions to complete (1-2 minutes)
2. Clear browser cache (Ctrl+Shift+Delete)
3. Check browser DevTools Console (F12) for errors
4. Verify FTP deployment completed successfully

## Rules

### DO
✅ Edit files locally, test, then deploy
✅ Use `npm run watch` for local development
✅ Push to Git regularly
✅ Test on staging before production
✅ Sync database from live periodically
✅ Use WP-CLI for database operations
✅ Check GitHub Actions status after pushing

### DON'T
❌ Edit theme files directly on server
❌ Upload `node_modules/` folder to server
❌ Upload `.git/` folder to server
❌ Commit `.env` files to Git
❌ Run `npm install` on server
❌ Run `composer install` on production
❌ Skip testing on staging

## Support

### Getting Help
1. Check documentation in `docs/` folder
2. Review error logs (Local or cPanel)
3. Check GitHub Actions workflow logs
4. Create GitHub issue with detailed description

### File Locations
- **Local environment:** `C:\Portfolio\ShaganPlaatjies Wordpress Theme\`
- **Local site:** `C:\Users\YourName\Local Sites\shaganplaatjies\`
- **Staging:** FTP to `staging.yourwebsite.com`
- **Production:** Managed via Softaculous

## Project Status

**Version:** 1.0.0
**Last Updated:** 2024-12-06
**PHP Required:** 8.0+
**WordPress Required:** 6.0+
**Node.js Required:** 16+

## Author

**Your Name**
Website: [yourwebsite.com](https://www.yourwebsite.com)

## License

MIT License

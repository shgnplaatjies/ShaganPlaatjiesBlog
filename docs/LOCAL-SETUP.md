# Local Development Setup Guide

This guide walks you through setting up a local WordPress development environment that syncs with your existing live WordPress site on shared hosting.

## What You're Setting Up

You'll create a local WordPress development environment where you can:
- Make theme changes safely without affecting your live site
- Test new features before deploying
- Pull the latest database from staging/production to work with real content
- Build and compile theme assets (CSS/JavaScript)
- Push changes to GitHub which automatically builds and prepares for deployment

---

## Prerequisites

### What You Already Have
- ✅ Git installed
- ✅ SQL/MySQL installed
- ✅ Live WordPress site on shared hosting (staging + production)
- ✅ This theme repository

### What You Need to Install

1. **Local by Flywheel** (Free)
   - Local WordPress development environment
   - Includes PHP, MySQL, nginx/Apache
   - Easy database/site management
   - Download: https://localwp.com/

2. **Node.js 16+ and npm** (Free)
   - Required for building theme assets (CSS/JavaScript)
   - npm comes bundled with Node.js
   - Download: https://nodejs.org/ (get the LTS version)

3. **Composer** (Free)
   - PHP dependency manager
   - Required for Bedrock/WordPress installation
   - Download: https://getcomposer.org/download/

---

## Step-by-Step Setup

### Step 1: Install Required Tools

#### Install Local by Flywheel

1. Go to https://localwp.com/
2. Download Local (it's free, no credit card needed)
3. Install and launch the application
4. Create a free account (just email + password)

#### Install Node.js and npm

1. Go to https://nodejs.org/
2. Download the **LTS (Long Term Support)** version
3. Run the installer (accept all defaults)
4. Verify installation:
   ```bash
   node --version
   npm --version
   ```
   You should see version numbers like `v18.17.0` and `9.6.7`

#### Install Composer

**For Windows:**
1. Go to https://getcomposer.org/download/
2. Download and run `Composer-Setup.exe`
3. Follow the installer (it will find your PHP from Local by Flywheel)
4. Verify installation:
   ```bash
   composer --version
   ```

---

### Step 2: Clone the Repository

1. Open your terminal/command prompt
2. Navigate to where you want your local projects:
   ```bash
   cd C:\Users\YourName\Projects
   ```

3. Clone this repository:
   ```bash
   git clone [your-repo-url] your-local-site-name
   cd your-local-site-name
   ```

---

### Step 3: Install Theme Dependencies

Inside the theme directory, install both PHP and Node.js dependencies:

```bash
# Navigate to the theme directory
cd app\bedrock\web\app\themes\shaganplaatjies

# Install PHP dependencies (Bedrock, WordPress, plugins)
composer install

# Install Node.js dependencies (build tools, Tailwind, etc.)
npm install
```

This will take a few minutes. You'll see:
- `vendor/` folder created (PHP dependencies)
- `node_modules/` folder created (Node dependencies)

---

### Step 4: Create Local WordPress Site

1. **Open Local by Flywheel**

2. **Click "+ Add New Site"**

3. **Site Name:** `your-local-site-name`
   - This will create a local URL like `your-local-site-name.local`

4. **Choose Environment:**
   - PHP: **8.0** or **8.1** (match your hosting environment)
   - Web Server: **nginx** (recommended) or Apache
   - MySQL: **8.0**

5. **WordPress Setup:**
   - Username: `admin` (or whatever you prefer)
   - Password: (choose a simple local password)
   - Email: your email

6. **Click "Add Site"** - Local will set up WordPress

---

### Step 5: Connect Bedrock to Local Site

Local by Flywheel creates a traditional WordPress structure, but we're using Bedrock. We need to point Local to use our Bedrock installation instead.

1. **In Local, right-click your site → "Reveal in Finder/Explorer"**
   - This opens the Local site folder

2. **Note the path** - it's usually something like:
   ```
   C:\Users\YourName\Local Sites\shaganplaatjies
   ```   

3. **Replace Local's WordPress with Bedrock:**

   **Option A: Symbolic Link (Recommended)**
   ```bash
   # Delete Local's default WordPress folder
   rm -rf "C:\Users\YourName\Local Sites\your-local-site-name\app\public"

   # Create symbolic link to your Bedrock web folder
   mklink /D "C:\Users\YourName\Local Sites\your-local-site-name\app\public" "C:\Portfolio\ShaganPlaatjies Wordpress Theme\app\bedrock\web"
   ```

---

### Step 6: Configure Local Environment (.env)

Create a `.env` file in your Bedrock root (`app/bedrock/.env`):

1. **Copy the example:**
   ```bash
   cd app/bedrock
   cp .env.example .env
   ```

2. **Edit `.env` with your Local database credentials:**

   In Local, click on your site → **Database** tab to see:
   - Database Name
   - Database User
   - Database Password
   - Database Host

   Update your `.env`:
   ```env
   # Local Development Settings
   WP_ENV=development
   WP_HOME=http://your-local-site-name.local
   WP_SITEURL=${WP_HOME}/wp

   # Database (from Local by Flywheel - Database tab)
   DB_HOST=localhost
   DB_NAME=local
   DB_USER=root
   DB_PASSWORD=root

   # Security Keys (generate at https://roots.io/salts.html)
   AUTH_KEY='generate-new-key'
   SECURE_AUTH_KEY='generate-new-key'
   LOGGED_IN_KEY='generate-new-key'
   NONCE_KEY='generate-new-key'
   AUTH_SALT='generate-new-key'
   SECURE_AUTH_SALT='generate-new-key'
   LOGGED_IN_SALT='generate-new-key'
   NONCE_SALT='generate-new-key'

   # Debug (ALWAYS true in local development)
   WP_DEBUG=true
   WP_DEBUG_DISPLAY=true
   WP_DEBUG_LOG=true
   SCRIPT_DEBUG=true
   ```

3. **Generate Security Keys:**
   - Go to https://roots.io/salts.html
   - Copy the 8 generated keys
   - Replace the placeholder keys in your `.env`

---

### Step 7: Import Database from Live Site

Now sync your local database with your live staging or production database.

#### Export Database from Shared Hosting

1. **Login to cPanel** on your shared hosting

2. **Go to phpMyAdmin**

3. **Select your WordPress database**

4. **Click "Export" tab**
   - Method: **Quick**
   - Format: **SQL**
   - Click **"Go"**

5. **Save the file** as `live-database.sql`

#### Import to Local

**Method A: Using Local by Flywheel GUI (Easiest)**

1. In Local, right-click your site → **Open Site Shell**

2. Navigate to where you saved `live-database.sql`

3. Import:
   ```bash
   wp db import /path/to/live-database.sql
   ```

4. Update URLs to local:
   ```bash
   wp search-replace 'https://yourlivesite.co.za' 'http://your-local-site-name.local' --all-tables
   wp search-replace 'https://www.yourlivesite.co.za' 'http://your-local-site-name.local' --all-tables
   ```

**Method B: Using phpMyAdmin (Alternative)**

1. In Local, click your site → **Database** tab → **Adminer** button

2. Click **Import** → Choose `live-database.sql` → **Execute**

3. After import, update URLs:
   - Go to **wp_options** table
   - Find `siteurl` row → change value to `http://your-local-site-name.local`
   - Find `home` row → change value to `http://your-local-site-name.local`

---

### Step 8: Activate Your Theme

1. In Local, click **"WP Admin"** button to open WordPress admin

2. Login with the admin credentials you used when exporting the database
   - If you don't remember: use Local's **Database** tab → **Adminer** → go to `wp_users` table and reset the password

3. Go to **Appearance → Themes**

4. **Activate your theme** (the theme name from THEME_NAME in .env)

---

### Step 9: Build Theme Assets

Now compile your CSS and JavaScript:

```bash
# Navigate to theme directory
cd app\bedrock\web\app\themes\shaganplaatjies

# Build for development (includes source maps)
npm run dev

# Or start watch mode (rebuilds on file changes)
npm run watch
```

You should see output like:
```
✔ Compiled Successfully in 3842ms
┌────────────────────────────────────────┐
│                                        │
│   Laravel Mix v6.0.49                  │
│                                        │
└────────────────────────────────────────┘
✔ Compiled Successfully in 125ms
```

---

### Step 10: Test Your Local Site

1. **Visit your local site:** http://your-local-site-name.local

2. **Check the frontend:**
   - Does the site load?
   - Are styles applied (Tailwind CSS)?
   - Does navigation work?
   - Do images load?

3. **Check WordPress admin:**
   - Can you edit pages/posts?
   - Are ACF fields visible?
   - Can you customize theme settings?

4. **Test theme compilation:**
   - Edit `resources/css/app.css` → add a test style
   - Run `npm run watch`
   - Refresh your browser → see if changes appear

---

## Development Workflow

### Daily Workflow

1. **Start Local:** Open Local by Flywheel → Start your site

2. **Start watch mode:** (in theme directory)
   ```bash
   npm run watch
   ```

3. **Make changes:**
   - Edit PHP files in `app/`, `templates/`
   - Edit CSS in `resources/css/`
   - Edit JavaScript in `resources/js/`
   - Changes are automatically compiled by watch mode

4. **Test locally:** Refresh browser to see changes

5. **Commit and push:**
   ```bash
   git add .
   git commit -m "Add new feature"
   git push origin main
   ```

6. **GitHub Actions automatically:**
   - Runs tests
   - Builds production assets
   - Commits compiled files back to repo

7. **Deploy to hosting:**
   - See README-DEPLOY.txt for deployment steps

### Syncing Database from Live

Pull fresh database periodically (weekly or when content changes significantly):

```bash
# Export from cPanel phpMyAdmin → save as live-database.sql

# Import to local
wp db import /path/to/live-database.sql

# Update URLs
wp search-replace 'https://yourlivesite.co.za' 'http://your-local-site-name.local' --all-tables
```

### Syncing Uploads Folder

If you need images/media from live site:

1. **Download from hosting via SFTP:**
   - Path: `/public_html/app/uploads/`

2. **Copy to local:**
   - Path: `app/bedrock/web/app/uploads/`

Or use rsync (if you have SSH access):
```bash
rsync -avz user@yourhost.com:/public_html/app/uploads/ ./app/bedrock/web/app/uploads/
```

---

## Troubleshooting

### "Cannot redeclare function" errors

**Cause:** Composer autoload issue or duplicate function definitions

**Fix:**
```bash
composer dump-autoload
```

### Theme styles not loading

**Cause:** Assets not compiled or wrong path

**Fix:**
```bash
cd app/bedrock/web/app/themes/shaganplaatjies
npm run dev
```

Check `dist/` folder exists with `css/app.css` and `js/app.js`

### Database connection errors

**Cause:** Wrong credentials in `.env`

**Fix:**
1. In Local → **Database** tab → check credentials
2. Update `.env` with correct:
   - DB_NAME (usually `local`)
   - DB_USER (usually `root`)
   - DB_PASSWORD (usually `root`)
   - DB_HOST (usually `localhost`)

### "Call to undefined function get_field()"

**Cause:** ACF plugin not installed

**Fix:**
1. Install ACF plugin:
   ```bash
   wp plugin install advanced-custom-fields --activate
   ```
   Or download from WordPress.org and add to `plugins/` folder

### White screen after activating theme

**Cause:** PHP error or missing dependencies

**Fix:**
1. Enable debug mode in `.env`:
   ```env
   WP_DEBUG=true
   WP_DEBUG_DISPLAY=true
   ```

2. Check error log:
   - Local → right-click site → **View Logs**
   - Or check `app/bedrock/web/wp-content/debug.log`

3. Ensure Composer dependencies are installed:
   ```bash
   cd app/bedrock
   composer install
   ```

### npm run watch fails

**Cause:** Node version mismatch or missing dependencies

**Fix:**
```bash
# Verify Node.js version (need 16+)
node --version

# Reinstall dependencies
rm -rf node_modules package-lock.json
npm install

# Try again
npm run watch
```

### Local site very slow

**Cause:** Windows Defender scanning `node_modules/` or `vendor/`

**Fix:**
1. Add exclusions in Windows Security:
   - Open **Windows Security**
   - Go to **Virus & threat protection**
   - **Manage settings** → **Exclusions**
   - Add: `C:\Users\YourName\Local Sites`
   - Add: `C:\Portfolio\ShaganPlaatjies Wordpress Theme`

---

## Common Commands Reference

### WordPress (WP-CLI)

```bash
# In Local → right-click site → "Open Site Shell"

# Update WordPress core
wp core update

# Install/activate plugin
wp plugin install [plugin-name] --activate

# Export database
wp db export backup.sql

# Import database
wp db import backup.sql

# Search-replace URLs
wp search-replace 'old-url' 'new-url' --all-tables

# Create admin user
wp user create admin admin@example.com --role=administrator

# Flush rewrite rules (fix permalinks)
wp rewrite flush

# Clear cache
wp cache flush
```

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

# Run tests
npm test

# Verify deployment readiness
npm run deploy:verify
```

### Git Workflow

```bash
# Check status
git status

# Add changes
git add .

# Commit
git commit -m "Description of changes"

# Push to GitHub (triggers auto-build)
git push origin main

# Pull latest changes
git pull origin main

# Create new branch
git checkout -b feature/new-feature

# Switch branches
git checkout main
```

---

## Next Steps

Once your local environment is working:

1. **Read ARCHITECTURE.md** - Understand theme structure
2. **Read ACF.md** - Learn about custom fields
3. **Read BLOCKS.md** - Create custom blocks
4. **Make a test change** - Edit a template, see it locally
5. **Deploy to staging** - Test the full deployment workflow

---

## Getting Help

- **Theme structure:** docs/ARCHITECTURE.md
- **Deployment:** README-DEPLOY.txt or docs/DEPLOY-SIMPLE.md
- **ACF integration:** docs/ACF.md
- **Custom blocks:** docs/BLOCKS.md

If you get stuck, check the error logs in Local by Flywheel (right-click site → View Logs).

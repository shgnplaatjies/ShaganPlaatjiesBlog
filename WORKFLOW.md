# Development Workflow

Visual guide to the complete development and deployment workflow for your WordPress theme.

## Complete Workflow Diagram

```
┌─────────────────────────────────────────────────────────────────────┐
│                     LOCAL DEVELOPMENT                               │
│                   (Your Computer)                                   │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  Environment:                                                       │
│  • Bedrock WordPress structure                                     │
│  • Local by Flywheel                                               │
│  • Database synced from live                                       │
│                                                                     │
│  Development:                                                       │
│  1. Start Local by Flywheel → Start site                           │
│  2. cd app/bedrock/web/app/themes/shaganplaatjies                   │
│  3. npm run watch                                                   │
│  4. Edit files in resources/, templates/, app/                     │
│  5. Browser auto-refreshes with changes                            │
│  6. Test at http://your-local-site-name.local                           │
│                                                                     │
│  Commit:                                                            │
│  git add .                                                          │
│  git commit -m "Description"                                        │
│  git push origin main                 ────┐                         │
│                                           │                         │
└───────────────────────────────────────────┼─────────────────────────┘
                                            │
                                            │ Push to GitHub
                                            │
                                            ↓
┌─────────────────────────────────────────────────────────────────────┐
│                      GITHUB REPOSITORY                              │
│                   (Automated Build)                                 │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  GitHub Actions Workflow Triggered:                                │
│                                                                     │
│  Build Job:                                                         │
│  1. Checkout code                                                   │
│  2. Setup Node.js 18                                                │
│  3. npm install (in theme directory)                                │
│  4. npm run production                                              │
│     → Compiles Tailwind CSS → dist/css/app.css                     │
│     → Bundles JavaScript → dist/js/app.js                          │
│     → Creates mix-manifest.json                                    │
│  5. Setup Composer                                                  │
│  6. composer install --no-dev                                       │
│  7. Run linting (ESLint)                                            │
│                                                                     │
│  Deploy Job (on main branch only):                                 │
│  8. FTP Deploy to staging                                           │
│     → Connects to ftp.dextertuition.co.za                          │
│     → Uploads theme to /wp-content/themes/your-theme-name/         │
│     → Excludes: node_modules, .git, resources                      │
│                                                                     │
│  Workflow complete (1-2 minutes)        ────┐                    │
│                                                │                    │
└────────────────────────────────────────────────┼────────────────────┘
                                                 │
                                                 │ Auto-deploy via FTP
                                                 │
                                                 ↓
┌─────────────────────────────────────────────────────────────────────┐
│                     STAGING ENVIRONMENT                             │
│        https://staging.yourwebsite.com                   │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  Environment:                                                       │
│  • Traditional WordPress structure (Softaculous)                   │
│  • Theme in /wp-content/themes/your-theme-name/                    │
│  • Production database credentials                                 │
│                                                                     │
│  Testing:                                                           │
│  1. Visit staging URL                                               │
│  2. Verify styles load (dist/css/app.css)                          │
│  3. Check JavaScript functionality                                 │
│  4. Test responsive design                                          │
│  5. Check ACF fields (if used)                                      │
│  6. Browser console - no errors                                     │
│  7. Test forms and interactions                                     │
│                                                                     │
│  All tests pass                         ────┐                    │
│                                                │                    │
└────────────────────────────────────────────────┼────────────────────┘
                                                 │
                                                 │ Softaculous push
                                                 │ (when ready)
                                                 │
                                                 ↓
┌─────────────────────────────────────────────────────────────────────┐
│                   PRODUCTION ENVIRONMENT                            │
│                  https://yourdomain.co.za                           │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  Environment:                                                       │
│  • Traditional WordPress structure (Softaculous)                   │
│  • Theme deployed from staging                                     │
│  • Live database with production content                           │
│                                                                     │
│  Management:                                                        │
│  • Theme: Updated via staging → production push                    │
│  • Plugins: Managed in WordPress admin                             │
│  • Core: Managed in WordPress admin                                │
│  • Database: Managed via WordPress admin                           │
│  • Content: Created/edited in WordPress admin                      │
│                                                                     │
│  Deployment via Softaculous:                                       │
│  1. Login to cPanel                                                 │
│  2. Softaculous Apps Installer                                     │
│  3. Select staging installation                                     │
│  4. Clone/Migrate to production                                     │
│  5. Select what to push (files/database/plugins)                   │
│  6. Execute push                                                    │
│                                                                     │
│  Live site updated                                               │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

## Data Flow

### Code Flow
```
Local Changes
    ↓
Git Push
    ↓
GitHub Repository
    ↓
GitHub Actions (auto-build)
    ↓
Staging (auto-deploy via FTP)
    ↓
Production (manual push via Softaculous)
```

### Database Flow
```
Production Database
    ↓ (periodic export)
Local Database (for testing with real content)

Staging Database
    ↓ (via Softaculous)
Production Database
```

## Daily Development Workflow

### Morning: Start Development

```bash
# 1. Start Local by Flywheel
# - Open app
# - Click "Start" on your site

# 2. Open terminal, navigate to theme
cd app/bedrock/web/app/themes/shaganplaatjies

# 3. Start watch mode (auto-compile)
npm run watch

# 4. Open code editor
# Edit files in:
# - resources/css/app.css (styles)
# - resources/js/app.js (JavaScript)
# - templates/*.php (page templates)
# - app/*.php (PHP helpers)

# 5. Test locally
# Visit: http://your-local-site-name.local
# Make changes, watch compiles, browser refreshes
```

### Midday: Commit Progress

```bash
# Check what changed
git status

# Stage all changes
git add .

# Commit with message
git commit -m "Add hero section styling"

# Push to GitHub
git push origin main

# GitHub Actions automatically:
# - Builds production assets
# - Deploys to staging
# (Wait 1-2 min)
```

### Afternoon: Test on Staging

```
1. Visit https://staging.yourwebsite.com
2. Check changes applied correctly
3. Test on mobile/tablet (DevTools)
4. Verify no console errors
5. Test functionality
```

### End of Day: Push to Production (Optional)

```
If ready for production:

1. Login to cPanel
2. Softaculous → WordPress
3. Select staging installation
4. Clone/Migrate → Production
5. Push changes
6. Verify production site
```

## File Change Workflow

### CSS Changes

```bash
# 1. Edit Tailwind CSS
# File: resources/css/app.css

# 2. Watch mode auto-compiles
# Creates: dist/css/app.css

# 3. Local site refreshes automatically

# 4. Commit and push
git add resources/css/app.css
git commit -m "Update button styles"
git push origin main

# 5. GitHub Actions builds and deploys
# Staging gets updated dist/css/app.css
```

### JavaScript Changes

```bash
# 1. Edit JavaScript
# File: resources/js/app.js

# 2. Watch mode auto-compiles
# Creates: dist/js/app.js

# 3. Test locally

# 4. Commit and push
git add resources/js/app.js
git commit -m "Add mobile menu toggle"
git push origin main

# 5. GitHub Actions builds and deploys
# Staging gets updated dist/js/app.js
```

### PHP Template Changes

```bash
# 1. Edit PHP template
# File: templates/page.blade.php

# 2. Refresh local site
# (No compilation needed for PHP)

# 3. Test locally

# 4. Commit and push
git add templates/page.blade.php
git commit -m "Update page layout"
git push origin main

# 5. GitHub Actions deploys to staging
# Staging gets updated template
```

### Theme Functions Changes

```bash
# 1. Edit theme functions
# File: functions.php or app/setup.php

# 2. Test locally

# 3. Commit and push
git add functions.php
git commit -m "Add custom post type"
git push origin main

# 4. GitHub Actions deploys to staging
```

## Environment Comparison

| Aspect | Local | Staging | Production |
|--------|-------|---------|------------|
| **Structure** | Bedrock | Traditional WP | Traditional WP |
| **URL** | http://your-local-site-name.local | staging.yourwebsite.com | yourdomain.co.za |
| **Database** | Synced from live (periodic) | Production copy | Live data |
| **Purpose** | Development & testing | Pre-production testing | Live site |
| **Deployment** | Git clone | GitHub Actions (auto) | Softaculous (manual) |
| **Build Tools** | npm, Composer available | Pre-compiled only | Pre-compiled only |
| **Updates** | Direct file editing | Auto via FTP | Via Softaculous |

## What Gets Deployed Where

### To Staging (Automatic via GitHub Actions)

**Included:**
- ✅ Theme folder (`/wp-content/themes/your-theme-name/`)
  - Compiled CSS (`dist/css/`)
  - Compiled JavaScript (`dist/js/`)
  - PHP templates (`templates/`)
  - PHP functions (`app/`, `functions.php`)
  - Theme configuration (`style.css`, `theme.json`)

**Excluded:**
- ❌ Source files (`resources/` - not needed on server)
- ❌ Node modules (`node_modules/` - huge, not needed)
- ❌ Git files (`.git/`, `.gitignore`)
- ❌ Tests (`tests/`)
- ❌ Development config files

### To Production (Manual via Softaculous)

**Via Softaculous, you choose:**
- Theme files (from staging)
- Database (if content updated)
- Plugins (if plugins changed)
- Uploads (if media changed)

**Recommended approach:**
- Push theme files regularly
- Push database only when content structure changes
- Manage plugins directly in production admin

## Workflow for Different Scenarios

### Scenario 1: Styling Update

```
1. Edit: resources/css/app.css
2. npm run watch compiles to dist/css/app.css
3. Test: http://your-local-site-name.local
4. Commit and push
5. GitHub Actions deploys to staging
6. Test: https://staging.yourwebsite.com
7. Softaculous: staging → production (when ready)
```

### Scenario 2: New Page Template

```
1. Create: templates/new-template.blade.php
2. Test locally
3. Commit and push
4. GitHub Actions deploys to staging
5. Test on staging
6. Softaculous: staging → production
7. In production admin: Select template for page
```

### Scenario 3: Plugin Update

```
1. Test plugin on staging
2. Update plugin in staging WordPress admin
3. Test thoroughly
4. Softaculous: Push staging → production
   (Select "plugins" in push options)
```

### Scenario 4: Content Update

```
1. Edit content directly in production admin
   OR
2. Edit content on staging
3. Test on staging
4. Softaculous: Push database staging → production
   (Be careful - overwrites production content!)
```

### Scenario 5: Database Sync from Live

```
1. Export from production (cPanel → phpMyAdmin)
2. Import to local (WP-CLI)
3. Update URLs to local:
   wp search-replace 'https://production.com' 'http://your-local-site-name.local'
4. Develop with real content locally
```

## Monitoring and Verification

### After Pushing to GitHub

1. **GitHub Actions Tab**
   - Check workflow status
   - Look for green checkmark
   - View logs if failed

2. **Staging Site**
   - Visit URL
   - Check browser console (F12)
   - Verify styles load
   - Test functionality

3. **Error Logs**
   - cPanel → Error Log
   - Look for PHP warnings/errors

### After Pushing to Production

1. **Production Site**
   - Visit URL
   - Verify changes applied
   - Check mobile responsive

2. **Admin Dashboard**
   - Login to WordPress admin
   - Check for errors
   - Verify plugins active

3. **Performance**
   - Test page load speed
   - Check for broken links
   - Verify images load

## Rollback Procedures

### Rollback Staging

```bash
# Revert last commit
git revert HEAD
git commit -m "Revert changes"
git push origin main

# GitHub Actions deploys previous version
```

### Rollback Production

```
1. cPanel → Backup Wizard
2. Select recent backup (before change)
3. Restore files or database
4. Verify site works
```

### Emergency Rollback

```
1. SFTP to server
2. Upload previous theme version
3. Clear cache (if caching enabled)
4. Test site
```

## Best Practices

### Development

✅ Always use `npm run watch` during development
✅ Test locally before pushing
✅ Commit frequently with clear messages
✅ One feature per commit (easier to rollback)
✅ Sync database from live periodically

### Deployment

✅ Monitor GitHub Actions after each push
✅ Test on staging thoroughly
✅ Deploy to production during low-traffic periods
✅ Create backups before major changes
✅ Clear cache after deployment

### Code Quality

✅ Run `npm run lint` before committing
✅ Fix console errors locally
✅ Keep code organized and documented
✅ Follow WordPress coding standards
✅ Use descriptive variable names

## Common Questions

**Q: How long does deployment take?**
A: GitHub Actions: 1-2 minutes. Softaculous push: 30 seconds.

**Q: Can I skip staging?**
A: Not recommended. Always test on staging first.

**Q: What if GitHub Actions fails?**
A: Check logs, fix errors, push again. Or deploy manually via SFTP.

**Q: Can I edit files directly on staging?**
A: Yes, but changes will be overwritten on next push. Better to edit locally.

**Q: How often should I push to production?**
A: When features are complete and tested. No strict schedule.

**Q: What if production breaks?**
A: Rollback using cPanel backup or previous Git version.

**Q: Can I work on multiple features at once?**
A: Use Git branches for different features, merge to main when ready.

## Summary

Your workflow is optimized for simplicity:

1. **Develop locally** with instant feedback
2. **Push to Git** for automatic building
3. **Test on staging** automatically deployed
4. **Push to production** when ready via Softaculous

No manual build steps. No complex deployment scripts. Just code, commit, and deploy.

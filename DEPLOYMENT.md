# Deployment Guide

Simple guide for deploying your WordPress theme to staging and production environments.

## Overview

Your deployment workflow:
1. **Local** → Make changes, test with `npm run watch`
2. **GitHub** → Push code, GitHub Actions auto-builds assets
3. **Staging** → Auto-deploys via FTP, test changes
4. **Production** → Push from staging using Softaculous

## Automated Staging Deployment

Every push to the `main` branch automatically:
- Builds production assets (CSS/JS)
- Runs code quality checks
- Deploys theme to staging via FTP

### How It Works

```bash
# 1. Make changes locally
cd app/bedrock/web/app/themes/shaganplaatjies
npm run watch  # Test locally

# 2. Commit and push
git add .
git commit -m "Update theme styling"
git push origin main

# 3. GitHub Actions automatically:
# - Installs dependencies
# - Builds production assets
# - Deploys to staging via FTP
#
# Wait 1-2 minutes for workflow to complete

# 4. Check staging
# Visit: https://staging.yourwebsite.com
```

### Monitor Deployment

1. Go to GitHub repository
2. Click **Actions** tab
3. View latest workflow run
4. Check for green checkmark (success) or red X (failure)
5. Click workflow for detailed logs

### GitHub Actions Workflow

Located in `.github/workflows/build-and-deploy.yml`:

**Build Job:**
- Installs Node.js and Composer dependencies
- Runs `npm run production` to build assets
- Runs linting checks

**Deploy Job:**
- Deploys theme folder to staging via FTP
- Uses SamKirkland/FTP-Deploy-Action
- Only deploys on push to `main` branch

### FTP Deployment Configuration

Secrets configured in GitHub repository:
- `FTP_HOST`: staging.yourwebsite.com
- `FTP_USER`: deploy user credentials
- `FTP_PASS`: deploy user password
- `FTP_TIMEOUT`: 30000ms

Deployment settings:
- **Server directory:** `/wp-content/themes/your-theme-name/`
- **Local directory:** `./app/bedrock/web/app/themes/your-theme-name/`
- **Protocol:** FTPS (secure)
- **Excluded files:** `.git`, `node_modules`, `resources` (source files)

## Manual Staging Deployment

If you need to deploy manually or GitHub Actions fails:

### Prerequisites

- SFTP client (WinSCP, Cyberduck, FileZilla)
- Staging server credentials

### Steps

1. **Build assets locally**
   ```bash
   cd app/bedrock/web/app/themes/shaganplaatjies
   npm run production
   ```

2. **Connect via SFTP**
   ```
   Host: ftp.dextertuition.co.za
   Port: 22
   Protocol: FTPS
   Username: deploy@staging.yourwebsite.com
   Password: [from secrets]
   ```

3. **Upload theme folder**
   - Navigate to: `/wp-content/themes/`
   - Upload: `your-theme-name/` folder
   - Overwrite existing files

4. **Test staging**
   - Visit: https://staging.yourwebsite.com
   - Check styles load correctly
   - Test functionality

## Production Deployment

### Using Softaculous (Recommended)

Production is managed separately via Softaculous in cPanel. The workflow:

**Theme Updates:**
1. Changes auto-deploy to staging (as above)
2. Test on staging thoroughly
3. Use Softaculous to push staging → production

**Plugins/Core/Database:**
- Managed directly in WordPress admin on staging
- Pushed to production via Softaculous

### Softaculous Push Process

1. **Login to cPanel**
   - Go to your hosting cPanel
   - Navigate to Softaculous Apps Installer

2. **Select WordPress installation**
   - Find staging installation
   - Click **Clone/Migrate**

3. **Push to production**
   - Select production as destination
   - Choose what to push:
     - ✅ Files (includes theme updates)
     - ✅ Database (if content changed)
     - ✅ Plugins (if plugins changed)
   - Click **Push**

4. **Verify production**
   - Visit production URL
   - Test critical functionality
   - Check admin dashboard

### Manual Production Deployment

If Softaculous is not available:

```bash
# 1. Build production assets
cd app/bedrock/web/app/themes/shaganplaatjies
npm run production

# 2. Connect to production via SFTP
# Upload your-theme-name/ folder to:
# /public_html/wp-content/themes/your-theme-name/

# 3. Clear cache
# In WordPress admin: Clear any caching plugins
```

## Database Syncing

### Export from Staging/Production

**Via cPanel phpMyAdmin:**
1. Login to cPanel → phpMyAdmin
2. Select WordPress database
3. Click **Export** tab
4. Method: Quick, Format: SQL
5. Click **Go**
6. Save as `database-backup-YYYYMMDD.sql`

**Via WP-CLI:**
```bash
# SSH into server
ssh user@staging.yourwebsite.com

# Export database
cd /path/to/wordpress
wp db export ~/backups/staging-db-$(date +%Y%m%d).sql

# Download via SFTP from ~/backups/
```

### Import to Local

**Via Local by Flywheel:**
1. Right-click site → **Open Site Shell**
2. Run:
   ```bash
   wp db import /path/to/database-backup.sql

   # Update URLs to local
   wp search-replace 'https://staging.yourwebsite.com' 'http://your-local-site-name.local' --all-tables
   ```

**Via phpMyAdmin (in Local):**
1. Local → Database tab → Adminer
2. Import → Select SQL file → Execute
3. Update `wp_options` table:
   - `siteurl` → `http://your-local-site-name.local`
   - `home` → `http://your-local-site-name.local`

## Troubleshooting Deployment

### GitHub Actions Failing

**Check workflow logs:**
1. GitHub → Actions tab
2. Click failed workflow
3. Expand failed step
4. Read error message

**Common issues:**
- **Syntax errors:** Fix in code, push again
- **FTP timeout:** Increase `FTP_TIMEOUT` secret
- **FTP credentials wrong:** Update secrets in GitHub
- **npm install fails:** Check package.json dependencies

**Fix and retry:**
```bash
# Fix the issue locally
git add .
git commit -m "Fix deployment issue"
git push origin main

# Workflow automatically reruns
```

### Styles Not Loading on Staging

**Checklist:**
1. Did GitHub Actions complete successfully?
2. Is `dist/` folder present in deployment?
3. Clear browser cache (Ctrl+Shift+Delete)
4. Check browser DevTools Console (F12) for errors
5. Verify file paths in Network tab

**Manual check via SFTP:**
```
Connect to staging
Navigate to: /wp-content/themes/your-theme-name/
Verify folders exist:
  - dist/css/app.css
  - dist/js/app.js
  - dist/mix-manifest.json
```

### FTP Deployment Slow

**Causes:**
- Large `vendor/` folder
- Slow server connection
- Too many files changed

**Solutions:**
1. Increase FTP_TIMEOUT (current: 30000ms)
2. Use `exclude` in workflow to skip unnecessary files
3. Deploy only changed files manually via SFTP

### Database Import Errors

**Error: "Table prefix mismatch"**
```bash
# Check table prefix in database export
grep "CREATE TABLE" backup.sql | head -n 1

# Update .env to match
DB_PREFIX=wp_  # or wphc_ or whatever prefix found
```

**Error: "Access denied"**
```bash
# Verify database credentials
mysql -h localhost -u DB_USER -p
# Enter DB_PASSWORD when prompted
```

## Deployment Checklist

### Before Deploying

- [ ] All changes tested locally with `npm run watch`
- [ ] No console errors in browser DevTools
- [ ] Code committed with descriptive message
- [ ] No sensitive data in commits (passwords, API keys)

### After Deploying to Staging

- [ ] GitHub Actions workflow completed successfully
- [ ] Staging site loads without errors
- [ ] Styles applied correctly
- [ ] JavaScript functionality works
- [ ] No console errors
- [ ] Mobile responsive design intact
- [ ] Forms submit correctly (if applicable)
- [ ] ACF fields display properly (if used)

### Before Deploying to Production

- [ ] Staging thoroughly tested
- [ ] All stakeholders approved changes
- [ ] Database backup created
- [ ] Production backup created (via cPanel or plugin)
- [ ] Maintenance mode enabled (optional)

### After Deploying to Production

- [ ] Production site loads correctly
- [ ] Critical functionality tested
- [ ] Admin dashboard accessible
- [ ] No errors in error logs
- [ ] Caching cleared (if cache plugin used)
- [ ] Maintenance mode disabled (if enabled)

## Quick Reference

### Daily Deployment Workflow

```bash
# Local development
npm run watch
# ... make changes ...

# Deploy to staging
git add .
git commit -m "Description"
git push origin main
# Wait for GitHub Actions (1-2 min)

# Test on staging
# Visit: https://staging.yourwebsite.com

# Push to production (when ready)
# Use Softaculous: staging → production
```

### Important URLs

- **GitHub Actions:** https://github.com/[your-repo]/actions
- **Staging:** https://staging.yourwebsite.com
- **Production:** [Your production domain]

### FTP Credentials

Stored in GitHub Secrets:
- FTP_HOST
- FTP_USER
- FTP_PASS
- FTP_TIMEOUT

### File Locations

**Local:**
```
C:\Portfolio\ShaganPlaatjies Wordpress Theme\
└── app/bedrock/web/app/themes/your-theme-name/
```

**Staging (FTP):**
```
/wp-content/themes/your-theme-name/
```

**Production:**
```
/public_html/wp-content/themes/your-theme-name/
```

## Best Practices

### DO

✅ Test all changes on staging before production
✅ Use descriptive commit messages
✅ Monitor GitHub Actions after each push
✅ Create database backups before major changes
✅ Clear cache after deployment
✅ Keep staging and production in sync
✅ Review deployment logs regularly

### DON'T

❌ Deploy directly to production without testing
❌ Skip GitHub Actions and deploy manually
❌ Edit files directly on server
❌ Commit sensitive credentials
❌ Upload node_modules/ folder
❌ Deploy during high-traffic periods
❌ Deploy without backing up database

## Rollback Procedure

If deployment causes issues:

### Immediate Rollback

**Via Git:**
```bash
# Revert last commit
git revert HEAD
git push origin main

# GitHub Actions deploys previous version
```

**Via SFTP:**
1. Keep previous version locally
2. Upload previous `your-theme-name/` folder
3. Overwrite current version

### Database Rollback

**Via cPanel Backup:**
1. cPanel → Backup Wizard → Restore
2. Select recent backup
3. Restore database only

**Via WP-CLI:**
```bash
wp db import ~/backups/database-before-deploy.sql
```

### Full Site Restore

Use cPanel full backup or hosting provider's backup system.

## Support

If you encounter deployment issues:

1. **Check workflow logs** (GitHub Actions tab)
2. **Review error logs** (cPanel → Error Log)
3. **Check documentation** (docs/LOCAL-SETUP.md, docs/ARCHITECTURE.md)
4. **Test locally first** (isolate the issue)
5. **Create GitHub issue** (with logs and error messages)

## Next Steps

- **Local Setup:** See [docs/LOCAL-SETUP.md](docs/LOCAL-SETUP.md)
- **Architecture:** See [docs/ARCHITECTURE.md](docs/ARCHITECTURE.md)
- **Workflow Diagram:** See [WORKFLOW.md](WORKFLOW.md)

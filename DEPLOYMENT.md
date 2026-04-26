# Deployment Guide (No Composer Required)

This application runs on pure PHP with zero external dependencies. It's designed for quick deployment on any XAMPP, shared hosting, or PHP server.

## Quick Start (Local Development)

### Prerequisites
- PHP 8.2+ (works with both 8.2 and 8.3)
- MySQL 8.0+
- Apache with mod_rewrite enabled

### Installation

1. **Copy files to web root:**
   ```bash
   # On XAMPP Windows
   xcopy /E /I watch-commerce "C:\xampp\htdocs\watch-commerce"
   cd C:\xampp\htdocs\watch-commerce
   ```

2. **Configure environment:**
   ```bash
   # Windows PowerShell
   Copy-Item .env.example -Destination .env
   
   # Edit .env with your database credentials
   notepad .env
   ```

3. **Run database setup:**
   ```bash
   # Windows PowerShell
   & "C:\xampp\php\php.exe" scripts/migrate.php
   & "C:\xampp\php\php.exe" scripts/seed.php
   ```

4. **Access the application:**
   - Open browser: `http://localhost/watch-commerce/public/`
   - Admin login: `admin@watchcommerce.test` / `admin1234`

## File Structure

```
watch-commerce/
├── public/              # Web root - only expose this to the internet
│   ├── index.php       # Application entry point
│   └── .htaccess       # Apache rewrites
├── src/                # Application code (PSR-4 namespace: App\)
│   ├── autoload.php    # Built-in autoloader (NO COMPOSER NEEDED)
│   ├── Core/           # Framework core
│   ├── Infrastructure/ # Database, security, etc.
│   ├── Auth/           # Authentication
│   ├── Catalog/        # Watch catalog
│   ├── Cart/           # Shopping cart
│   ├── Order/          # Orders
│   ├── Sell/           # Sell watches
│   ├── Admin/          # Admin panel
│   └── Api/            # REST API
├── bootstrap/          # App initialization
├── config/             # Configuration files
├── views/              # HTML templates (PHP)
├── storage/            # Logs, cache, uploads
├── migrations/         # Database schema (SQL)
├── seeds/              # Sample data (SQL)
├── scripts/            # CLI utilities
├── .env                # Environment config (create from .env.example)
└── .env.example        # Environment template
```

## Deployment Steps

### Step 1: Upload Files
Upload entire `watch-commerce` directory to your server's web root. Only the `public/` directory needs to be accessible from the web.

### Step 2: Configure Web Server

**Apache (.htaccess already configured):**
```apache
# public/.htaccess handles routing automatically
# Just ensure mod_rewrite is enabled
```

**Nginx (alternative):**
```nginx
location /watch-commerce {
    try_files $uri $uri/ /watch-commerce/public/index.php?$query_string;
}
```

### Step 3: Set Environment Variables

Create `.env` file in the root directory:
```bash
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com/watch-commerce
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=watch_commerce
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
DB_CHARSET=utf8mb4
```

### Step 4: Initialize Database

SSH to your server and run:
```bash
cd /path/to/watch-commerce
php scripts/migrate.php    # Create tables
php scripts/seed.php       # Load sample data
```

### Step 5: Set Permissions (Unix/Linux)

```bash
chmod 755 public
chmod 755 storage
chmod 755 storage/logs
chmod 644 public/index.php
chmod 644 .env
```

### Step 6: Verify Installation

1. Visit `https://your-domain.com/watch-commerce/public/`
2. Login with: `admin@watchcommerce.test` / `admin1234`
3. Check admin dashboard

## Scripts Reference

### Migrate Database
```bash
php scripts/migrate.php
```
Creates all database tables from `migrations/001_init.sql`

### Load Sample Data
```bash
php scripts/seed.php
```
Loads sample watches, users, roles, and content

### Check Database Connection
```bash
php scripts/check_db.php
```
Verifies MySQL connection and database existence

### Verify Database Schema
```bash
php scripts/verify_db.php
```
Lists all tables and their row counts

## Troubleshooting

### "No tables found" after migration
- Check database credentials in `.env`
- Verify MySQL user has CREATE TABLE permissions
- Run `php scripts/check_db.php` to diagnose connection
- Run migration again with: `php scripts/migrate.php`

### 404 errors on catalog pages
- Verify Apache `mod_rewrite` is enabled
- Check `.htaccess` in `public/` directory exists
- Verify URL format: `/watch-commerce/public/watches` (not `/watch-commerce/watches`)

### CSRF token errors on forms
- Sessions may not be persisting; check `php.ini` session settings
- Ensure `storage/` directory is writable
- Verify cookies are enabled in browser

### Database locked errors
- May indicate concurrent writes; check for race conditions in order placement
- Migrations handle this with transaction and row-level locking on watches table

## Performance Notes

- No external dependencies = fast deployments and no dependency conflicts
- PSR-4 autoloading is simple and efficient
- Database queries use prepared statements for security
- Tailwind CSS loaded via CDN for minimal payload

## Security Checklist

- [ ] `.env` file is NOT in web root and NOT in version control
- [ ] Only `public/` directory is exposed to web
- [ ] Database user has minimal required permissions
- [ ] `APP_DEBUG=false` in production
- [ ] HTTPS enforced in production
- [ ] Admin credentials changed from defaults
- [ ] Log files in `storage/logs` are not web-accessible

## Updating/Rollback

Since there's no build process or vendor directory:
1. Upload new files directly
2. Run migrations if schema changed: `php scripts/migrate.php`
3. Restart PHP-FPM or Apache if needed

## Support

For issues, check:
- `storage/logs/` directory for error logs
- `.env` configuration matches your environment
- Database user permissions
- PHP 8.2+ required features being used

---

**Built for simplicity and reliability. No Composer, no external dependencies, just pure PHP.**

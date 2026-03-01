# WARP.md

This file provides guidance to WARP (warp.dev) when working with code in this repository.

## Project Overview

Certificate Monitor is a Laravel application that monitors SSL certificates and uptime for specified URLs. It uses the Spatie Laravel Uptime Monitor package as the core monitoring engine.

## Development Commands

### Setup
```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy environment file (if needed)
cp .env.example .env

# Generate application key
php artisan key:generate

# Create SQLite database
touch database/database.sqlite

# Run migrations
php artisan migrate
```

### Development Server
```bash
# Start all development services (server, queue, logs, and vite)
composer dev

# Or start services individually:
php artisan serve           # Start Laravel dev server (localhost:8000)
php artisan queue:listen     # Start queue worker
php artisan pail            # View logs in real-time
npm run dev                 # Start Vite dev server for assets
```

### Testing
```bash
# Run all tests
php artisan test
# Or
vendor/bin/phpunit

# Run specific test suite
vendor/bin/phpunit --testsuite=Feature
vendor/bin/phpunit --testsuite=Unit

# Run specific test file
vendor/bin/phpunit tests/Feature/ProfileTest.php
```

### Code Quality
```bash
# Run Laravel Pint (code style fixer)
vendor/bin/pint

# Run PHP CS Fixer
vendor/bin/php-cs-fixer fix

# Run PHPStan (static analysis, level 5)
vendor/bin/phpstan analyse

# Build assets for production
npm run build
```

### Monitoring Commands
```bash
# Import/sync monitors from import.json file
php artisan monitor:sync-file import.json --delete-missing

# Check certificate status for all enabled monitors
php artisan monitor:check-certificate

# Check uptime for all enabled monitors
php artisan monitor:check-uptime

# Test email notifications
php artisan notification:test
```

## Architecture

### Core Components

**Spatie Uptime Monitor Integration**: The application is built on top of `spatie/laravel-uptime-monitor`, which provides the core monitoring functionality. The `Monitor` model and monitoring commands come from this package.

**Scheduled Tasks**: Two scheduled tasks run automatically (defined in `routes/console.php`):
- `monitor:check-certificate` - runs daily
- `monitor:check-uptime` - runs every 5 minutes

**Authentication**: Uses Laravel Breeze for authentication. All routes require authentication except the login/register pages.

**Database**: SQLite by default (for Docker deployments). The `monitors` table stores URL configurations and monitoring results.

### Key Files

- `config/uptime-monitor.php` - Configuration for monitoring behavior, notification settings, and check intervals
- `routes/web.php` - Defines dashboard and monitor CRUD routes (all auth-protected)
- `app/Http/Controllers/DashboardController.php` - Displays all monitors
- `app/Http/Controllers/MonitorController.php` - Edit/update/delete monitors
- `entryfile.sh` - Docker entrypoint that handles initialization, migrations, and secret loading

### Docker Architecture

The application runs in a multi-container setup:
- **app**: PHP-FPM container running Laravel application and scheduled tasks (via Supervisor)
- **web**: Nginx container serving as the web server

The `entryfile.sh` script handles:
- Environment setup and Laravel key generation
- Database initialization (SQLite file creation)
- Initial monitor import from `import.json`
- Docker Swarm secrets loading for `MAIL_USERNAME` and `MAIL_PASSWORD`

### Notifications

Email notifications are sent for:
- Certificate expiring soon (default: 10 days before expiry)
- Certificate check failures
- Uptime check failures/recoveries

Configure via environment variables:
- `UPTIME_MONITOR_EMAIL` - recipient address
- `UPTIME_MONITOR_EXPIRE_DAYS` - days before expiry to send warning
- `MAIL_*` variables for SMTP configuration

## Frontend

- **Stack**: Vite + Tailwind CSS + Alpine.js
- **Assets**: `resources/js/app.js` and `resources/css/app.css`
- **Views**: Blade templates in `resources/views/`

# Operations Runbook

## Deploy (Linux + Nginx + PHP-FPM)

1. Provision PHP 8.3, extensions (`pdo_mysql`, `mbstring`, `json`), Nginx, MySQL 8.
2. Clone repository and install dependencies:
   - `composer install --no-dev --optimize-autoloader`
3. Configure `.env` and DB credentials.
4. Run migrations and seeders:
   - `php scripts/migrate.php`
   - `php scripts/seed.php`
5. Configure Nginx root to `public/`.
6. Restart PHP-FPM and Nginx.

## Monitoring and Error Tracking

- App logs are written to `storage/logs/app.log`.
- Add centralized log shipping (ELK, Loki, Datadog) in production.
- Configure uptime checks for `/` and `/api/v1/watches`.
- Alert on DB errors, payment callback failures, and elevated 5xx rates.

## Backup and Restore

- Daily full MySQL backup + 15-minute binlog backups.
- Keep 30-day retention for daily snapshots.
- Monthly restore drill to staging and checksum verification.

## Incident Response

- Switch site to read-only mode if inventory mismatch risk is detected.
- Reconcile watch status via `orders` and `order_items`.
- Replay outbox jobs that remain in `failed` state after remediation.

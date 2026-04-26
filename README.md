# Watch Commerce (Pure PHP)

Production-oriented luxury watch commerce platform using PHP 8.3, MySQL 8, Tailwind CSS, and PHPUnit.

## Quick Start

1. Copy `.env.example` to `.env` and set DB credentials.
2. Install dependencies:
   - `composer install`
3. Run migrations and seeders:
   - `composer migrate`
   - `composer seed`
4. Run local server:
   - `php -S localhost:8080 -t public`

## Architecture

- `src/Core`: app kernel, router, request/response, base controller.
- `src/Auth`: auth flows, RBAC and 2FA gate.
- `src/Catalog`: watch listing and product detail endpoints.
- `src/Cart`, `src/Order`: cart/checkout and order lifecycle.
- `src/Sell`: sell-watch intake and valuation acceptance flow.
- `src/Admin`: admin management screens and operations API.
- `src/Infrastructure`: DB, HTTP validation, security.

See `docs/architecture.md` and `docs/runbook.md` for details.
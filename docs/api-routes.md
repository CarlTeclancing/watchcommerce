# API and Web Routes

## Web Routes

- `GET /` home
- `GET /watches` catalog listing with query filters
- `GET /watches/{slug}` product detail
- `GET /register`, `POST /register`
- `GET /login`, `POST /login`
- `GET /2fa`, `POST /2fa`
- `POST /logout`
- `GET /cart`, `POST /cart/items`, `POST /cart/items/update`, `POST /cart/items/remove`
- `GET /checkout`, `POST /checkout`
- `GET /orders/{id}`
- `GET /sell`, `POST /sell`, `POST /sell/{id}/accept`
- `GET /blog/{slug}`, `GET /faq`

## Admin Routes (middleware: auth + admin)

- `GET /admin`
- `GET|POST /admin/inventory`
- `GET /admin/orders`, `POST /admin/orders/status`
- `GET /admin/sell-pipeline`, `POST /admin/sell-pipeline/quote`
- `GET /admin/cms`, `POST /admin/cms/page`
- `GET /admin/users`, `POST /admin/users/role`
- `GET /admin/reports/export`

## REST API

- `GET /api/v1/watches`
- `GET /api/v1/watches/{id}`
- `POST /api/v1/sell-requests`

## Validation and DTO/Form Request Pattern

- Input validation centralized in [src/Infrastructure/Http/Validator.php](src/Infrastructure/Http/Validator.php).
- Each controller declares required schema near the endpoint.
- Next step for scale: dedicated request DTO classes per use case (`CheckoutRequest`, `SellRequestDTO`, etc.).

## Authorization Policies/Gates

- Gate function: `Auth::can(permission)`.
- Route middleware maps:
  - `auth`: authenticated user required
  - `admin`: permission `admin.dashboard` required and 2FA completed

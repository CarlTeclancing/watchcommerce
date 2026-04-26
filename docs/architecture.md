# Architecture Document

## Assumptions

- Single deployable PHP web app with modular boundaries.
- MySQL 8 as system of record.
- No external blob/file storage service (media referenced by URL).
- Stripe-like payment integration is added via provider adapter in a later iteration.

## Bounded Contexts and Module Boundaries

- Identity and Access: registration, authentication, 2FA challenge, RBAC permissions, policy checks.
- Catalog: watch inventory publishing, listing, search/filter/sort, product detail rendering.
- Cart and Checkout: cart operations, checkout capture, order placement transaction.
- Orders and Payments: order lifecycle state changes, payment callbacks with idempotency keys.
- Sell and Valuation: intake form, valuation quote generation, acceptance and lifecycle tracking.
- CMS: pages, blog posts, FAQ content presentation and administration.
- Admin Operations: dashboards, inventory, orders, sell pipeline, users/roles, exports.
- Platform: routing, validation, security middleware, logging, migrations, operational scripts.

## Request Flow

1. Request enters [public/index.php](public/index.php).
2. [bootstrap/app.php](bootstrap/app.php) resolves container, DB, auth, router, and route map.
3. Router matches path and executes middleware gates (`auth`, `admin`).
4. Controller validates input, executes use-case logic, and returns HTML or JSON response.
5. Critical operations (`placeOrder`, quote updates) run inside DB transactions.
6. Responses are sent with proper status codes and redirects.

## Queue/Event Flow

- Outbox table: `event_outbox` persists domain events for async processing.
- Producer points:
  - order placed
  - payment status changed
  - sell request quoted/accepted
- Worker model:
  - a CLI worker polls pending outbox rows
  - processes notifications/webhooks/retries
  - marks `processed` or increments `attempts`
- This provides reliability and eventual consistency without requiring a broker on day one.

## Security Model

- Authentication: email/password with Argon2id hashing.
- 2FA: mandatory for admin-capable users before authorization checks.
- RBAC: `roles`, `permissions`, `role_permissions`, `user_roles` with permission gates.
- CSRF protection for auth forms and state-changing endpoints.
- SQL injection defenses via prepared statements only.
- Auditability via `audit_logs` table for privileged actions.
- OWASP controls:
  - input validation and normalization
  - centralized auth checks
  - output escaping in views
  - secure password storage
  - no sensitive secrets in code

## Scalability and Caching Strategy

- Nginx + PHP-FPM horizontal app scaling.
- MySQL read replicas for catalog-heavy queries.
- Query/index strategy implemented for brand/status/price/reference.
- HTTP caching:
  - cacheable catalog pages where safe
  - ETags/Last-Modified in future iteration
- Application cache plan:
  - introduce Redis for hot catalog/filter data and sessions
  - cache invalidation on inventory updates
- Async processing via outbox/worker for non-blocking operations.

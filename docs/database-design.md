# Database Design

## ERD-Level Entity List

- Identity and RBAC: users, roles, permissions, user_roles, role_permissions
- Commerce: watches, carts, cart_items, orders, order_items, payments
- Sell-side: sell_requests, sell_request_status_history
- CMS: pages, blog_posts, faqs
- Operations: audit_logs, event_outbox

## Key Constraints and Indexes

- Unique: users.email, watches.slug, orders.order_number, blog_posts.slug, pages.slug
- FKs enforce integrity across role mappings, order items, sell history
- Composite indexes:
  - watches(brand_slug, status)
  - orders(status)
  - sell_requests(status)
  - event_outbox(status, created_at)

## Status Enums and Transition Rules

- watches.status:
  - `draft -> published -> reserved -> sold`
  - `draft|published -> archived`
- orders.status:
  - `pending_payment -> paid -> processing -> shipped -> delivered`
  - `pending_payment|paid|processing -> cancelled`
  - `paid|delivered -> refunded`
- sell_requests.status:
  - `submitted -> under_review -> quoted -> quote_accepted -> received -> inspected -> paid_out -> closed`

## Migration Files

- Core schema: [migrations/001_init.sql](migrations/001_init.sql)

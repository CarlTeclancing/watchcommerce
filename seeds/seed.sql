INSERT INTO roles (key_name, title) VALUES
('customer', 'Customer'),
('inventory_manager', 'Inventory Manager'),
('content_manager', 'Content Manager'),
('admin', 'Administrator')
ON DUPLICATE KEY UPDATE title = VALUES(title);

INSERT INTO permissions (key_name, description) VALUES
('admin.dashboard', 'Access admin dashboard'),
('inventory.manage', 'Manage inventory listings'),
('orders.manage', 'Manage customer orders'),
('sell.manage', 'Manage sell pipeline'),
('cms.manage', 'Manage CMS pages and content'),
('users.manage', 'Manage users and roles'),
('reports.export', 'Export operational reports')
ON DUPLICATE KEY UPDATE description = VALUES(description);

INSERT INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id
FROM roles r
JOIN permissions p
WHERE r.key_name = 'admin'
ON DUPLICATE KEY UPDATE role_id = VALUES(role_id);

INSERT INTO users (name, email, password_hash, two_factor_enabled)
VALUES ('Platform Admin', 'admin@watchcommerce.test', '$argon2id$v=19$m=65536,t=4,p=1$NUN4Ukt2YlA2L2Y2WG9tYg$h6lYDgh9SkdeJ6rBE8QesQvIFx3qL2CH5PeDwZQ9MRA', 1)
ON DUPLICATE KEY UPDATE name = VALUES(name);

INSERT IGNORE INTO user_roles (user_id, role_id)
SELECT u.id, r.id
FROM users u
JOIN roles r ON r.key_name = 'admin'
WHERE u.email = 'admin@watchcommerce.test';

INSERT INTO watches
(slug, brand_slug, title, reference_number, condition_grade, year_of_production, hero_image_url, list_price, status, description_html)
VALUES
('rolex-submariner-126610ln', 'rolex', 'Rolex Submariner Date 126610LN', '126610LN', 'excellent', 2022, 'https://images.unsplash.com/photo-1523170335258-f5ed11844a49', 14500.00, 'published', 'Black dial, ceramic bezel, 41mm case.'),
('rolex-daytona-116500ln', 'rolex', 'Rolex Daytona 116500LN', '116500LN', 'very_good', 2021, 'https://images.unsplash.com/photo-1508057198894-247b23fe5ade', 28900.00, 'published', 'White dial, ceramic bezel, stainless steel bracelet.'),
('patek-nautilus-5711', 'patek-philippe', 'Patek Philippe Nautilus 5711', '5711/1A', 'good', 2018, 'https://images.unsplash.com/photo-1547996160-81dfa63595aa', 99000.00, 'published', 'Blue dial, integrated bracelet, iconic profile.')
ON DUPLICATE KEY UPDATE title = VALUES(title);

INSERT INTO blog_posts (slug, title, excerpt, body_html, status, published_at)
VALUES
('how-to-buy-preowned-rolex', 'How to Buy a Pre-Owned Rolex', 'A practical guide to evaluating condition, provenance, and value.', '<p>Review condition, verify references, and buy from trusted dealers.</p>', 'published', NOW())
ON DUPLICATE KEY UPDATE title = VALUES(title);

INSERT INTO faqs (question, answer, position)
VALUES
('Are all watches authenticated?', 'Yes. Every watch goes through our authentication workflow before listing.', 1),
('Do you offer returns?', 'Yes. We offer a limited inspection return window for eligible orders.', 2)
ON DUPLICATE KEY UPDATE answer = VALUES(answer);

CREATE TABLE IF NOT EXISTS users (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  two_factor_enabled TINYINT(1) NOT NULL DEFAULT 0,
  two_factor_secret VARCHAR(120) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS roles (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  key_name VARCHAR(80) NOT NULL UNIQUE,
  title VARCHAR(120) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS permissions (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  key_name VARCHAR(120) NOT NULL UNIQUE,
  description VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS user_roles (
  user_id BIGINT UNSIGNED NOT NULL,
  role_id BIGINT UNSIGNED NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (user_id, role_id),
  CONSTRAINT fk_user_roles_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_user_roles_role FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS role_permissions (
  role_id BIGINT UNSIGNED NOT NULL,
  permission_id BIGINT UNSIGNED NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (role_id, permission_id),
  CONSTRAINT fk_role_permissions_role FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
  CONSTRAINT fk_role_permissions_permission FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS watches (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  slug VARCHAR(190) NOT NULL UNIQUE,
  brand_slug VARCHAR(80) NOT NULL,
  title VARCHAR(190) NOT NULL,
  reference_number VARCHAR(120) NOT NULL,
  serial_number VARCHAR(120) NULL,
  condition_grade ENUM('new','excellent','very_good','good','fair') NOT NULL DEFAULT 'very_good',
  year_of_production SMALLINT UNSIGNED NULL,
  case_material VARCHAR(80) NULL,
  dial_color VARCHAR(50) NULL,
  movement VARCHAR(80) NULL,
  bracelet VARCHAR(120) NULL,
  power_reserve VARCHAR(80) NULL,
  includes_box_papers TINYINT(1) NOT NULL DEFAULT 0,
  hero_image_url VARCHAR(255) NULL,
  gallery_json JSON NULL,
  description_html MEDIUMTEXT NULL,
  list_price DECIMAL(12,2) NOT NULL,
  cost_basis DECIMAL(12,2) NULL,
  status ENUM('draft','published','reserved','sold','archived') NOT NULL DEFAULT 'draft',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_watches_brand_status (brand_slug, status),
  INDEX idx_watches_price (list_price),
  INDEX idx_watches_reference (reference_number)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS carts (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id BIGINT UNSIGNED NULL,
  session_id VARCHAR(120) NULL,
  status ENUM('active','converted','abandoned') NOT NULL DEFAULT 'active',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_carts_user (user_id),
  CONSTRAINT fk_carts_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS cart_items (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  cart_id BIGINT UNSIGNED NOT NULL,
  watch_id BIGINT UNSIGNED NOT NULL,
  quantity INT UNSIGNED NOT NULL DEFAULT 1,
  unit_price DECIMAL(12,2) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_cart_watch (cart_id, watch_id),
  CONSTRAINT fk_cart_items_cart FOREIGN KEY (cart_id) REFERENCES carts(id) ON DELETE CASCADE,
  CONSTRAINT fk_cart_items_watch FOREIGN KEY (watch_id) REFERENCES watches(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS orders (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id BIGINT UNSIGNED NOT NULL,
  order_number VARCHAR(50) NOT NULL UNIQUE,
  status ENUM('pending_payment','paid','processing','shipped','delivered','cancelled','refunded') NOT NULL,
  payment_status ENUM('unpaid','authorized','captured','failed','refunded') NOT NULL,
  shipping_name VARCHAR(120) NOT NULL,
  shipping_email VARCHAR(190) NOT NULL,
  shipping_address VARCHAR(255) NOT NULL,
  subtotal_amount DECIMAL(12,2) NOT NULL,
  total_amount DECIMAL(12,2) NOT NULL,
  currency CHAR(3) NOT NULL DEFAULT 'USD',
  idempotency_key VARCHAR(90) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_orders_user (user_id),
  INDEX idx_orders_status (status),
  CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS order_items (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id BIGINT UNSIGNED NOT NULL,
  watch_id BIGINT UNSIGNED NOT NULL,
  title_snapshot VARCHAR(190) NOT NULL,
  unit_price DECIMAL(12,2) NOT NULL,
  quantity INT UNSIGNED NOT NULL,
  line_total DECIMAL(12,2) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_order_items_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  CONSTRAINT fk_order_items_watch FOREIGN KEY (watch_id) REFERENCES watches(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS payments (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id BIGINT UNSIGNED NOT NULL,
  provider VARCHAR(50) NOT NULL,
  provider_ref VARCHAR(120) NULL,
  status ENUM('pending','authorized','captured','failed','refunded') NOT NULL,
  amount DECIMAL(12,2) NOT NULL,
  idempotency_key VARCHAR(90) NULL,
  payload_json JSON NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_provider_ref (provider, provider_ref),
  CONSTRAINT fk_payments_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS sell_requests (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id BIGINT UNSIGNED NULL,
  contact_name VARCHAR(120) NOT NULL,
  contact_email VARCHAR(190) NOT NULL,
  brand VARCHAR(80) NOT NULL,
  model_name VARCHAR(120) NOT NULL,
  reference_number VARCHAR(120) NOT NULL,
  condition_grade ENUM('excellent','very_good','good','fair') NOT NULL,
  asking_price DECIMAL(12,2) NULL,
  offered_price DECIMAL(12,2) NULL,
  status ENUM('submitted','under_review','quoted','quote_accepted','received','inspected','paid_out','closed') NOT NULL,
  quoted_at TIMESTAMP NULL,
  quote_accepted_at TIMESTAMP NULL,
  notes TEXT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_sell_status (status),
  CONSTRAINT fk_sell_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS sell_request_status_history (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  sell_request_id BIGINT UNSIGNED NOT NULL,
  from_status VARCHAR(50) NULL,
  to_status VARCHAR(50) NOT NULL,
  changed_by BIGINT UNSIGNED NULL,
  note VARCHAR(255) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_sell_history_request (sell_request_id),
  CONSTRAINT fk_sell_history_request FOREIGN KEY (sell_request_id) REFERENCES sell_requests(id) ON DELETE CASCADE,
  CONSTRAINT fk_sell_history_user FOREIGN KEY (changed_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS pages (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  slug VARCHAR(160) NOT NULL UNIQUE,
  title VARCHAR(190) NOT NULL,
  body_html MEDIUMTEXT NOT NULL,
  status ENUM('draft','published') NOT NULL DEFAULT 'draft',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS blog_posts (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  slug VARCHAR(160) NOT NULL UNIQUE,
  title VARCHAR(190) NOT NULL,
  excerpt VARCHAR(400) NULL,
  body_html MEDIUMTEXT NOT NULL,
  status ENUM('draft','published') NOT NULL DEFAULT 'draft',
  published_at TIMESTAMP NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS faqs (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  question VARCHAR(255) NOT NULL,
  answer TEXT NOT NULL,
  position INT UNSIGNED NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS audit_logs (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id BIGINT UNSIGNED NULL,
  action VARCHAR(120) NOT NULL,
  entity_type VARCHAR(80) NOT NULL,
  entity_id VARCHAR(80) NOT NULL,
  metadata_json JSON NULL,
  ip_address VARCHAR(45) NULL,
  user_agent VARCHAR(255) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_audit_entity (entity_type, entity_id),
  CONSTRAINT fk_audit_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS event_outbox (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  event_name VARCHAR(120) NOT NULL,
  payload_json JSON NOT NULL,
  status ENUM('pending','processed','failed') NOT NULL DEFAULT 'pending',
  attempts INT UNSIGNED NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  processed_at TIMESTAMP NULL,
  INDEX idx_outbox_status_created (status, created_at)
) ENGINE=InnoDB;

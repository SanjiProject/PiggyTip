CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(32) UNIQUE NOT NULL,
  email VARCHAR(191) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  display_name VARCHAR(80) NOT NULL,
  bio VARCHAR(160) DEFAULT NULL,
  avatar VARCHAR(255) DEFAULT NULL,
  slug VARCHAR(64) UNIQUE NOT NULL,
  primary_link_id INT DEFAULT NULL,
  is_active TINYINT(1) DEFAULT 1,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE links (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  label VARCHAR(80) NOT NULL,
  url TEXT NOT NULL,
  type ENUM('custom','paypal','binance','usdt_trc20','guide','other','cashapp','patreon','buymeacoffee','kofi','stripe') DEFAULT 'custom',
  sort_order INT DEFAULT 0,
  is_visible TINYINT(1) DEFAULT 1,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE wallets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  label VARCHAR(60) NOT NULL,
  chain VARCHAR(40) DEFAULT 'TRON',
  address VARCHAR(120) NOT NULL,
  payment_uri VARCHAR(255) DEFAULT NULL,
  is_visible TINYINT(1) DEFAULT 1,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE socials (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  platform VARCHAR(40) NOT NULL,
  url VARCHAR(255) NOT NULL,
  is_visible TINYINT(1) DEFAULT 1,
  sort_order INT DEFAULT 0,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE analytics_events (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  link_id INT DEFAULT NULL,
  event_type ENUM('pageview','click') NOT NULL,
  user_agent VARCHAR(191) DEFAULT NULL,
  ip VARBINARY(16) DEFAULT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX (user_id, link_id, event_type, created_at),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (link_id) REFERENCES links(id) ON DELETE CASCADE
) ENGINE=InnoDB;

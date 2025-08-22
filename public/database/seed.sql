-- Demo user and data
INSERT INTO users (username, email, password_hash, display_name, bio, slug)
VALUES (
  'demo',
  'demo@example.com',
  '$2y$10$9YpS6iF1d3C0i3dG4Qm3Ue7v4.5zGmJg0G7d5o3vAIfm3b8bWJ3Ae', -- password: password123
  'Demo User',
  'If you like my work, consider supporting!',
  'demo'
);

SET @uid = LAST_INSERT_ID();

INSERT INTO links (user_id, label, url, type, sort_order, is_visible) VALUES
(@uid, 'PayPal', 'https://paypal.me/example', 'paypal', 10, 1),
(@uid, 'Binance Pay', 'https://pay.binance.com/en/invoice?code=XXXX', 'binance', 20, 1),
(@uid, 'Donation Guide', 'https://example.com/guide', 'guide', 30, 1);

INSERT INTO wallets (user_id, label, chain, address, payment_uri, is_visible) VALUES
(@uid, 'USDT (TRC-20)', 'TRON', 'TCxxxxxxxxxxxxxxxxxxxx', 'tron:TCxxxxxxxxxxxxxxxxxxxx?amount=5', 1);

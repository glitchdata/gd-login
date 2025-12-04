USE gd_login_php;

INSERT INTO users (id, name, email, password, created_at) VALUES
  ('seed-user-1', 'Demo User', 'demo@example.com', '$2a$10$N9qo8uLOickgx2ZMRZoMyeIjZ2uVH54byzatoHtr1cGukdxbYF92.', '2024-01-01 00:00:00')
ON DUPLICATE KEY UPDATE
  name = VALUES(name),
  password = VALUES(password),
  created_at = VALUES(created_at);

-- Akun uji untuk Acara 20-27. Password admin: rahasia123 | password user: user12345
USE db_workshop;
INSERT INTO users (username, email, password, role) VALUES
 ('admin', 'admin@example.com', '$2y$10$/nvK367pFlmXuHUsIamOkuEIzmqwsNeR3cgK0/vu6A5kDe6ZgbNjK', 'admin'),
 ('mahasiswa1', 'mahasiswa1@example.com', '$2y$10$bgqIqhVK0nWpL.OKATPojevk7vJftyyhzCZscIdFHZJ2GaSIx9uOW', 'user')
ON DUPLICATE KEY UPDATE role = VALUES(role);
-- Jika akun 'admin' sudah ada dari acara 19, cukup jalankan:
-- UPDATE users SET role = 'admin' WHERE username = 'admin';

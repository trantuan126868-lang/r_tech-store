-- Database: r_tech_store
CREATE DATABASE IF NOT EXISTS r_tech_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE r_tech_store;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  email VARCHAR(255),
  password VARCHAR(255),
  role ENUM('user','admin') DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  price DECIMAL(12,2) NOT NULL DEFAULT 0,
  image VARCHAR(500),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS posts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255),
  content TEXT,
  author VARCHAR(100),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  name VARCHAR(255),
  phone VARCHAR(50),
  address TEXT,
  total DECIMAL(12,2),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT,
  product_id INT,
  quantity INT,
  price DECIMAL(12,2)
);

CREATE TABLE IF NOT EXISTS comments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  post_id INT,
  username VARCHAR(100),
  content TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS likes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  post_id INT,
  likes_count INT DEFAULT 0
);

-- Sample data: products (10 items)
INSERT INTO products (name, description, price, image) VALUES
('Smart Tivi NanoCell LG 4K 55 inch', 'Tivi LG chất lượng cao, hiển thị sắc nét với công nghệ NanoCell.', 11490000, 'assets/img/tv1.jpg'),
('Smart Tivi LG LED 4K 55 inch 2024', 'Mẫu tivi LED 4K mới nhất 2024 từ LG.', 10990000, 'assets/img/tv2.jpg'),
('Smart Tivi Samsung UHD 4K 55 INCH 2024', 'Tivi Samsung UHD 4K cho hình ảnh sống động.', 10590000, 'assets/img/tv3.jpg'),
('Google Tivi Coocaa 4K 55 inch', 'Tivi Coocaa tích hợp Google Assistant.', 6490000, 'assets/img/tv4.jpg'),
('Android Tivi AQUA UHD 4K 50 inch 2024', 'Android Tivi AQUA cho trải nghiệm thông minh.', 6990000, 'assets/img/tv5.jpg'),
('Tivi Xiaomi A Pro 4K 55 inch QLED 2026', 'Tivi QLED 4K 55 inch đến từ Xiaomi.', 10290000, 'assets/img/tv6.jpg'),
('Smart Tivi LG UHD 4K 55 inch 2025', 'Phiên bản mới 2025 của LG UHD 4K.', 10990000, 'assets/img/tv7.jpg'),
('Google Tivi AQUA QLED 4K 50 inch 2024', 'Tivi AQUA QLED 4K cho màu sắc rực rỡ.', 8590000, 'assets/img/tv8.jpg'),
('Google Tivi Sony 4K 55 inch', 'Tivi Sony cao cấp hỗ trợ Dolby Vision.', 26990000, 'assets/img/tv9.jpg'),
('Google Tivi Coocaa khung tranh QLED 4K 65 inch', 'Tivi QLED khung tranh độc đáo từ Coocaa.', 12990000, 'assets/img/tv10.jpg');

-- Sample post
INSERT INTO posts (title, content, author) VALUES
('Top 5 mẫu Tivi đáng mua nhất tháng 6', 'Nội dung demo bài viết ...', 'Admin');

-- Sample user (non-admin)
INSERT INTO users (username,email,password,role) VALUES
('user1','user1@example.com','\$2y\$10\$D9j3lQ2YzJqHkV1lqzF7LeeYkqQnC9Q6yH0vR2yY0mKxwFzQ9m6a2', 'user');
-- password above is bcrypt hash for 'password123'


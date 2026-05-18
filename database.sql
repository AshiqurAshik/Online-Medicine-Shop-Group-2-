CREATE DATABASE IF NOT EXISTS medicine_shop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE medicine_shop;



CREATE TABLE users (
id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(100),
email VARCHAR(100) UNIQUE,
password_hash VARCHAR(255),
role ENUM('admin','customer') DEFAULT 'customer',
profile_picture VARCHAR(255),
address TEXT,
phone VARCHAR(30),
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);





CREATE TABLE medicines (
id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(100),
category_id INT,
vendor_name VARCHAR(100),
price DECIMAL(10,2),
availability INT,
description TEXT,
image_path VARCHAR(255),
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(100),
category_type ENUM('liquid','solid'),
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE cart (
id INT AUTO_INCREMENT PRIMARY KEY,
user_id INT,
medicine_id INT,
quantity INT,
added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE orders (
id INT AUTO_INCREMENT PRIMARY KEY,
user_id INT,
total_amount DECIMAL(10,2),
shipping_address TEXT,
status ENUM('pending','accepted','rejected') DEFAULT 'pending',
payment_method VARCHAR(50),
order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE order_items (
id INT AUTO_INCREMENT PRIMARY KEY,
order_id INT,
medicine_id INT,
quantity INT,
unit_price DECIMAL(10,2)
);

CREATE TABLE payments (
id INT AUTO_INCREMENT PRIMARY KEY,
order_id INT,
amount DECIMAL(10,2),
payment_method VARCHAR(50),
transaction_id VARCHAR(100),
payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users(name,email,password_hash,role,address,phone)
VALUES(
'Admin',
'admin@gmail.com',
'admin123',
'admin',
'Dhaka',
'01700000000'
);

INSERT INTO categories (name, category_type)
VALUES ('Paracetamol', 'solid');

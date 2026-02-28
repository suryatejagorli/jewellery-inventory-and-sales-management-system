/* =====================================================
   JEWELLERY INVENTORY & SALES MANAGEMENT SYSTEM
   Database Name: jewellery_shop
   Description:
   This database manages jewellery products, customers,
   orders, payments, HUID system, and delivery tracking.
===================================================== */

CREATE DATABASE IF NOT EXISTS jewellery_shop;
USE jewellery_shop;

-- ==========================================
-- 1️⃣ ADMINS TABLE
-- Stores admin login credentials (hashed password)
-- ==========================================
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ==========================================
-- 2️⃣ CATEGORIES TABLE
-- Stores jewellery categories (Gold, Silver, Diamond)
-- ==========================================
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

-- ==========================================
-- 3️⃣ PRODUCTS TABLE
-- Stores all product details including HUID
-- ==========================================
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(150) NOT NULL,
    weight DECIMAL(10,2) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    image VARCHAR(255),
    description TEXT,
    featured TINYINT(1) DEFAULT 0,
    purity ENUM('916','999') DEFAULT NULL,
    huid_code VARCHAR(20) UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (category_id) 
    REFERENCES categories(id)
    ON DELETE CASCADE
);

-- ==========================================
-- 4️⃣ CUSTOMERS TABLE
-- Stores customer details during checkout
-- ==========================================
CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    email VARCHAR(150),
    address TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ==========================================
-- 5️⃣ ORDERS TABLE
-- Stores main order details
-- ==========================================
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    customer_name VARCHAR(150) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    address TEXT NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    subtotal DECIMAL(10,2) DEFAULT 0,
    gst_amount DECIMAL(10,2) DEFAULT 0,
    delivery_charge DECIMAL(10,2) DEFAULT 0,
    total_amount DECIMAL(10,2) DEFAULT 0,
    order_status VARCHAR(50) DEFAULT 'Pending',
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (customer_id)
    REFERENCES customers(id)
    ON DELETE CASCADE
);

-- ==========================================
-- 6️⃣ ORDER_ITEMS TABLE
-- Stores products inside each order
-- ==========================================
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,

    FOREIGN KEY (order_id)
    REFERENCES orders(id)
    ON DELETE CASCADE,

    FOREIGN KEY (product_id)
    REFERENCES products(id)
    ON DELETE CASCADE
);

-- ==========================================
-- 7️⃣ PAYMENTS TABLE
-- Stores payment transaction records
-- ==========================================
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    payment_status VARCHAR(50) DEFAULT 'Pending',
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (order_id)
    REFERENCES orders(id)
    ON DELETE CASCADE
);

-- ==========================================
-- 8️⃣ DELIVERED_ITEMS TABLE
-- Tracks delivered orders
-- ==========================================
CREATE TABLE delivered_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    delivered_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (order_id)
    REFERENCES orders(id)
    ON DELETE CASCADE
);

-- ==========================================
-- 9️⃣ ENQUIRIES TABLE
-- Stores customer help / contact messages
-- ==========================================
CREATE TABLE enquiries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150),
    email VARCHAR(150),
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ==========================================
-- 🔟 GOLD RATES TABLE
-- Stores daily gold rate updates
-- ==========================================
CREATE TABLE gold_rates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rate_per_gram DECIMAL(10,2) NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ==========================================
-- 1️⃣1️⃣ SETTINGS TABLE
-- Stores website configuration values
-- ==========================================
CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    site_name VARCHAR(150),
    site_email VARCHAR(150),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

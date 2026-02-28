/* ======================================================
   1️⃣ ADMIN MANAGEMENT TABLE
   Purpose: Handles admin authentication & system control
   ====================================================== */

-- Table: admins
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,   -- Unique admin ID
    username VARCHAR(50) NOT NULL UNIQUE,  -- Admin login username
    password VARCHAR(255) NOT NULL  -- Hashed password (bcrypt)
);


/* ======================================================
   2️⃣ PRODUCT CATEGORY TABLE
   Purpose: Stores jewellery categories (Gold, Silver, etc.)
   ====================================================== */

-- Table: categories
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,   -- Unique category ID
    name VARCHAR(100) NOT NULL UNIQUE   -- Category name
);


/* ======================================================
   3️⃣ CUSTOMERS TABLE
   Purpose: Stores customer information during checkout
   ====================================================== */

-- Table: customers
CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,         -- Unique customer ID
    name VARCHAR(150) NOT NULL,                  -- Customer full name
    phone VARCHAR(15) NOT NULL,                  -- 10-digit mobile number
    email VARCHAR(150) DEFAULT NULL,            -- Optional email
    address TEXT NOT NULL,                           -- Delivery address
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP  -- Record creation time
);



-- =========================================
-- 4️⃣ ORDERS TABLE
-- Stores complete order transaction details
-- =========================================

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,  
    -- Unique Order ID

    customer_id INT NOT NULL,  
    -- Reference to customers table

    customer_name VARCHAR(100) NOT NULL,
    -- Customer name at time of order

    phone VARCHAR(15) NOT NULL,
    -- Customer phone number

    address TEXT NOT NULL,
    -- Delivery address

    payment_method ENUM('Cash','UPI','EMI') NOT NULL,
    -- Selected payment method

    total_amount DECIMAL(10,2) NOT NULL,
    -- Final amount including GST & delivery

    subtotal DECIMAL(10,2) NOT NULL,
    -- Amount before GST & delivery

    gst_amount DECIMAL(10,2) DEFAULT 0,
    -- GST value

    delivery_charge DECIMAL(10,2) DEFAULT 0,
    -- Delivery fee

    order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    -- Date & time of order

    status ENUM('Paid','Unpaid') DEFAULT 'Paid',
    -- Payment status

    order_status ENUM('Pending','Delivered') DEFAULT 'Pending',
    -- Admin delivery confirmation

    order_source ENUM('Online','Offline') DEFAULT 'Online',
    -- Where order came from

    FOREIGN KEY (customer_id) REFERENCES customers(id)
    -- Linking with customers table
);




-- =========================================
-- 5️⃣ ORDER_ITEMS TABLE
-- Stores individual products inside each order
-- =========================================

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    -- Unique ID for each order item

    order_id INT NOT NULL,
    -- Links to orders table

    product_id INT NOT NULL,
    -- Links to products table

    quantity INT NOT NULL,
    -- Number of products purchased

    price DECIMAL(10,2) NOT NULL,
    -- Price per product at time of purchase

    FOREIGN KEY (order_id) REFERENCES orders(id)
        ON DELETE CASCADE,

    FOREIGN KEY (product_id) REFERENCES products(id)
        ON DELETE CASCADE
);










-- =========================================
-- 7️⃣ PRODUCTS TABLE
-- Stores all jewellery products
-- Includes HUID and Purity system
-- =========================================

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    -- Unique product ID

    category_id INT NOT NULL,
    -- Links product to category (Gold/Silver/Diamond)

    name VARCHAR(150) NOT NULL,
    -- Product name

    weight DECIMAL(10,2) NOT NULL,
    -- Weight in grams

    price DECIMAL(10,2) NOT NULL,
    -- Selling price

    stock INT NOT NULL DEFAULT 0,
    -- Available stock quantity

    image VARCHAR(255),
    -- Image file name

    description TEXT,
    -- Product description

    featured TINYINT(1) DEFAULT 0,
    -- Whether product appears on homepage

    purity ENUM('916','999') DEFAULT NULL,
    -- Gold purity (only for Gold products)

    huid_code VARCHAR(20) UNIQUE,
    -- Unique Hallmark ID (SBJ00101 format)

    FOREIGN KEY (category_id) REFERENCES categories(id)
        ON DELETE CASCADE
);




-- =========================================
-- 8️⃣ SETTINGS TABLE
-- Stores global configuration values
-- (GST percentage and delivery charge)
-- =========================================

CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    -- Unique settings ID

    gst_percent DECIMAL(5,2) NOT NULL DEFAULT 0,
    -- GST percentage applied to orders

    delivery_charge DECIMAL(10,2) NOT NULL DEFAULT 0
    -- Flat delivery charge applied to orders
);


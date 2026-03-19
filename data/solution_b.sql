USE my_shop;
GO

-- XÓA BẢNG
IF OBJECT_ID('order_details', 'U') IS NOT NULL DROP TABLE order_details;
IF OBJECT_ID('orders', 'U') IS NOT NULL DROP TABLE orders;
GO

-- ORDERS
CREATE TABLE orders (
    order_id INT IDENTITY(1,1) PRIMARY KEY,
    user_id INT NOT NULL,
    updated_at DATETIME NULL,
    created_at DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);
GO

-- ORDER_DETAILS
CREATE TABLE order_details (
    order_detail_id INT IDENTITY(1,1) PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    updated_at DATETIME NULL,
    created_at DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);
GO

-- SEED ORDERS
INSERT INTO orders (user_id) VALUES
(1),
(2),
(1),
(3);
GO

-- SEED ORDER_DETAILS
INSERT INTO order_details (order_id, product_id) VALUES
(1, 1),
(1, 4),
(2, 2),
(3, 3),
(3, 5),
(4, 1);
GO


-- =========================
-- SEED ORDERS
-- =========================
INSERT INTO orders (user_id, created_at, updated_at) VALUES
(1, GETDATE(), GETDATE()),  -- alice
(2, GETDATE(), GETDATE()),  -- bob
(1, GETDATE(), GETDATE()),  -- alice mua lần 2
(3, GETDATE(), GETDATE()),  -- charlie
(4, GETDATE(), GETDATE());  -- david
GO

-- =========================
-- SEED ORDER_DETAILS
-- =========================
INSERT INTO order_details (order_id, product_id, created_at, updated_at) VALUES
-- Order 1 (alice)
(1, 1, GETDATE(), GETDATE()),  -- Laptop
(1, 4, GETDATE(), GETDATE()),  -- Tai nghe

-- Order 2 (bob)
(2, 2, GETDATE(), GETDATE()),  -- iPhone

-- Order 3 (alice)
(3, 3, GETDATE(), GETDATE()),  -- Samsung
(3, 5, GETDATE(), GETDATE()),  -- Chuột

-- Order 4 (charlie)
(4, 1, GETDATE(), GETDATE()),  -- Laptop
(4, 2, GETDATE(), GETDATE()),  -- iPhone

-- Order 5 (david)
(5, 4, GETDATE(), GETDATE()),  -- Tai nghe
(5, 3, GETDATE(), GETDATE());  -- Samsung
GO
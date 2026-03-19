-- TẠO DATABASE (SQL Server)
IF NOT EXISTS (SELECT * FROM sys.databases WHERE name = 'my_shop')
BEGIN
    CREATE DATABASE my_shop;
END
GO

USE my_shop;
GO

-- XÓA BẢNG
IF OBJECT_ID('products', 'U') IS NOT NULL DROP TABLE products;
IF OBJECT_ID('users', 'U') IS NOT NULL DROP TABLE users;
GO

-- USERS
CREATE TABLE users (
    user_id INT IDENTITY(1,1) PRIMARY KEY,
    user_name VARCHAR(25) NOT NULL,
    user_email VARCHAR(55) NOT NULL UNIQUE,
    user_pass VARCHAR(255) NOT NULL,
    updated_at DATETIME NULL,
    created_at DATETIME DEFAULT GETDATE()
);
GO

-- PRODUCTS
CREATE TABLE products (
    product_id INT IDENTITY(1,1) PRIMARY KEY,
    product_name VARCHAR(255) NOT NULL,
    product_price FLOAT NOT NULL,
    product_description TEXT NOT NULL,
    updated_at DATETIME NULL,
    created_at DATETIME DEFAULT GETDATE()
);
GO

-- SEED USERS
INSERT INTO users (user_name, user_email, user_pass) VALUES
('alice', 'alice@gmail.com', '123456'),
('bob', 'bob@gmail.com', '123456'),
('charlie', 'charlie@gmail.com', '123456'),
('david', 'david@gmail.com', '123456'),
('emma', 'emma@gmail.com', '123456');
GO

-- SEED PRODUCTS
INSERT INTO products (product_name, product_price, product_description) VALUES
('Laptop Dell XPS 13', 25000000, 'Laptop cao cấp'),
('iPhone 15 Pro', 30000000, 'Điện thoại flagship'),
('Samsung Galaxy S24', 28000000, 'Android mạnh mẽ'),
('Tai nghe Sony XM5', 8000000, 'Chống ồn'),
('Chuột Logitech MX Master 3S', 2500000, 'Chuột cao cấp');
GO
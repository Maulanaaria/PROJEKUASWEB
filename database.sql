CREATE DATABASE statistik_buku;

USE statistik_buku;

CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    category VARCHAR(100),
    price DECIMAL(10, 2),
    stock INT
);

CREATE TABLE sales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    book_id INT,
    quantity INT,
    total_price DECIMAL(10, 2),
    sale_date DATE,
    FOREIGN KEY (book_id) REFERENCES books(id)
);

-- Contoh data buku
INSERT INTO books (title, category, price, stock) VALUES 
('Book A', 'Fiction', 100.00, 50),
('Book B', 'Non-Fiction', 150.00, 30),
('Book C', 'Fiction', 200.00, 20);

-- Contoh data penjualan
INSERT INTO sales (book_id, quantity, total_price, sale_date) VALUES 
(1, 3, 300.00, '2025-01-01'),
(2, 2, 300.00, '2025-01-02'),
(1, 1, 100.00, '2025-01-03'),
(3, 5, 1000.00, '2025-01-04');

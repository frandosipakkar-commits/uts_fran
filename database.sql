CREATE DATABASE IF NOT EXISTS my_store
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE my_store;

CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sku VARCHAR(40) NOT NULL UNIQUE,
    name VARCHAR(120) NOT NULL,
    category VARCHAR(80) NOT NULL,
    price DECIMAL(12,2) NOT NULL DEFAULT 0,
    stock INT NOT NULL DEFAULT 0,
    status ENUM('Aktif','Discontinued') NOT NULL DEFAULT 'Aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Akun awal: username admin, password admin123
INSERT INTO admins (username, password_hash)
SELECT 'admin', '$2y$12$up5.x0lGyPA8hnajyB9QSucu6jMvXxPYTbr0SRBB30J6r5g43zYk.'
WHERE NOT EXISTS (SELECT 1 FROM admins WHERE username = 'admin');

INSERT IGNORE INTO products (sku, name, category, price, stock, status) VALUES
('PRD-001','Air Mineral 600 ml','Minuman',4000,80,'Aktif'),
('PRD-002','Teh Botol 350 ml','Minuman',5000,65,'Aktif'),
('PRD-003','Kopi Susu Kaleng','Minuman',9000,40,'Aktif'),
('PRD-004','Jus Jeruk 250 ml','Minuman',7000,35,'Aktif'),
('PRD-005','Roti Cokelat','Makanan',6000,50,'Aktif'),
('PRD-006','Roti Keju','Makanan',7000,30,'Aktif'),
('PRD-007','Biskuit Cokelat','Makanan',8500,45,'Aktif'),
('PRD-008','Keripik Kentang','Makanan',10000,25,'Aktif'),
('PRD-009','Mi Instan Goreng','Makanan',3500,100,'Aktif'),
('PRD-010','Mi Instan Kuah','Makanan',3500,90,'Aktif'),
('PRD-011','Susu UHT 1 Liter','Minuman',18000,20,'Aktif'),
('PRD-012','Gula Pasir 1 kg','Sembako',17000,30,'Aktif'),
('PRD-013','Beras 5 kg','Sembako',75000,15,'Aktif'),
('PRD-014','Minyak Goreng 1 Liter','Sembako',18000,28,'Aktif'),
('PRD-015','Tepung Terigu 1 kg','Sembako',12000,22,'Aktif'),
('PRD-016','Sabun Mandi','Perawatan',5000,60,'Aktif'),
('PRD-017','Sampo 170 ml','Perawatan',16000,24,'Aktif'),
('PRD-018','Pasta Gigi','Perawatan',12000,32,'Aktif'),
('PRD-019','Deterjen 800 g','Rumah Tangga',19000,18,'Aktif'),
('PRD-020','Tisu Wajah','Rumah Tangga',9000,40,'Aktif'),
('PRD-021','Baterai AA Isi 2','Elektronik',11000,26,'Aktif'),
('PRD-022','Buku Tulis','Alat Tulis',5000,75,'Aktif'),
('PRD-023','Pulpen Hitam','Alat Tulis',3000,100,'Aktif'),
('PRD-024','Masker Medis Isi 10','Kesehatan',8000,20,'Aktif'),
('PRD-025','Pengharum Ruangan','Rumah Tangga',22000,12,'Aktif');

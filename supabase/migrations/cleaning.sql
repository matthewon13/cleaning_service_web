-- Database untuk Platform Cleaning Service
CREATE DATABASE cleaning_service;
USE cleaning_service;

-- Tabel Admin
CREATE TABLE admin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Katalog Jasa
CREATE TABLE services (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    duration INT NOT NULL COMMENT 'Durasi dalam jam',
    category ENUM('residential', 'commercial', 'deep-cleaning', 'maintenance') NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel Pesanan
CREATE TABLE bookings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    customer_name VARCHAR(100) NOT NULL,
    customer_email VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    service_id INT NOT NULL,
    address TEXT NOT NULL,
    booking_date DATE NOT NULL,
    booking_time TIME NOT NULL,
    status ENUM('requested', 'approved', 'in_progress', 'completed', 'cancelled') DEFAULT 'requested',
    notes TEXT,
    total_price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
);

-- Insert data admin default
INSERT INTO admin (username, password, email) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@cleaningservice.com');
-- Password: password

-- Insert data services contoh
INSERT INTO services (name, description, price, duration, category) VALUES 
('Pembersihan Rumah Standar', 'Pembersihan rutin untuk rumah tinggal meliputi menyapu, mengepel, dan membersihkan debu', 150000, 3, 'residential'),
('Pembersihan Kantor', 'Layanan pembersihan untuk kantor dan ruang kerja', 200000, 4, 'commercial'),
('Deep Cleaning Rumah', 'Pembersihan menyeluruh termasuk jendela, karpet, dan area yang sulit dijangkau', 350000, 6, 'deep-cleaning'),
('Maintenance Bulanan', 'Paket maintenance rutin bulanan untuk menjaga kebersihan', 500000, 8, 'maintenance');
-- ============================================
-- DATABASE SCHEMA FOR BLOOD BANK SYSTEM
-- Project: LifeFlow Blood Bank Management System
-- Version: 2.0
-- ============================================

-- Create Database
CREATE DATABASE IF NOT EXISTS blood_bank;
USE blood_bank;

-- ============================================
-- 1. USERS TABLE
-- ============================================
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'hospital', 'donor') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role)
);

-- ============================================
-- 2. BLOOD STOCK TABLE
-- ============================================
DROP TABLE IF EXISTS blood_stock;
CREATE TABLE blood_stock (
    id INT PRIMARY KEY AUTO_INCREMENT,
    blood_group VARCHAR(5) UNIQUE NOT NULL,
    units_available INT DEFAULT 0,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_blood_group (blood_group)
);

-- ============================================
-- 3. BLOOD REQUESTS TABLE
-- ============================================
DROP TABLE IF EXISTS blood_requests;
CREATE TABLE blood_requests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    hospital_id INT NOT NULL,
    hospital_name VARCHAR(100) NOT NULL,
    blood_group VARCHAR(5) NOT NULL,
    units INT NOT NULL,
    patient_name VARCHAR(100) NOT NULL,
    reason TEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_hospital_id (hospital_id),
    INDEX idx_status (status),
    INDEX idx_blood_group (blood_group),
    FOREIGN KEY (hospital_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ============================================
-- 4. DONATIONS TABLE
-- ============================================
DROP TABLE IF EXISTS donations;
CREATE TABLE donations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    donor_id INT NOT NULL,
    donor_name VARCHAR(100) NOT NULL,
    blood_group VARCHAR(5) NOT NULL,
    units INT DEFAULT 1,
    donation_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_donor_id (donor_id),
    INDEX idx_donation_date (donation_date),
    FOREIGN KEY (donor_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ============================================
-- 5. DONATION REQUESTS TABLE
-- ============================================
DROP TABLE IF EXISTS donation_requests;
CREATE TABLE donation_requests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    donor_id INT NOT NULL,
    donor_name VARCHAR(100) NOT NULL,
    blood_group VARCHAR(5) NOT NULL,
    units INT DEFAULT 1,
    notes TEXT,
    scheduled_date DATE,
    status ENUM('pending', 'approved', 'completed', 'rejected') DEFAULT 'pending',
    request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_donor_id (donor_id),
    INDEX idx_status (status),
    FOREIGN KEY (donor_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ============================================
-- 6. CONTACT MESSAGES TABLE
-- ============================================
DROP TABLE IF EXISTS contact_messages;
CREATE TABLE contact_messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('unread', 'read', 'replied') DEFAULT 'unread',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_status (status)
);

-- ============================================
-- 7. ACTIVITY LOGS TABLE
-- ============================================
DROP TABLE IF EXISTS activity_logs;
CREATE TABLE activity_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    user_role ENUM('admin', 'hospital', 'donor'),
    action VARCHAR(100) NOT NULL,
    details TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user (user_id),
    INDEX idx_action (action),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- ============================================
-- INSERT SAMPLE DATA
-- ============================================

-- Insert Admin (password: admin123)
INSERT INTO users (name, email, phone, password, role) VALUES
('System Admin', 'admin@lifeflow.com', '1234567890', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insert Sample Hospital
INSERT INTO users (name, email, phone, password, role) VALUES
('City General Hospital', 'hospital@citygen.com', '9876543210', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'hospital');

-- Insert Sample Donors
INSERT INTO users (name, email, phone, password, role) VALUES
('John Doe', 'john@example.com', '5551234567', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor'),
('Jane Smith', 'jane@example.com', '5557654321', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor');

-- Insert Blood Stock
INSERT INTO blood_stock (blood_group, units_available) VALUES
('A+', 25), ('A-', 10), ('B+', 20), ('B-', 8),
('O+', 35), ('O-', 5), ('AB+', 12), ('AB-', 3);

-- Insert Sample Donations
INSERT INTO donations (donor_id, donor_name, blood_group, units, donation_date) VALUES
(3, 'John Doe', 'O+', 1, DATE_SUB(CURDATE(), INTERVAL 120 DAY)),
(3, 'John Doe', 'O+', 1, DATE_SUB(CURDATE(), INTERVAL 30 DAY)),
(4, 'Jane Smith', 'A+', 1, DATE_SUB(CURDATE(), INTERVAL 60 DAY));

-- ============================================
-- CREATE VIEWS FOR REPORTS
-- ============================================

-- Stock Status View
CREATE OR REPLACE VIEW vw_stock_status AS
SELECT 
    blood_group,
    units_available,
    CASE 
        WHEN units_available <= 2 THEN 'CRITICAL'
        WHEN units_available <= 5 THEN 'LOW'
        WHEN units_available <= 10 THEN 'MODERATE'
        ELSE 'AVAILABLE'
    END AS status
FROM blood_stock;

-- Donor Eligibility View
CREATE OR REPLACE VIEW vw_donor_eligibility AS
SELECT 
    u.id AS donor_id,
    u.name AS donor_name,
    u.email,
    MAX(d.donation_date) AS last_donation_date,
    DATEDIFF(CURDATE(), COALESCE(MAX(d.donation_date), '1970-01-01')) AS days_since_last,
    CASE 
        WHEN MAX(d.donation_date) IS NULL THEN 'ELIGIBLE'
        WHEN DATEDIFF(CURDATE(), MAX(d.donation_date)) >= 90 THEN 'ELIGIBLE'
        ELSE 'NOT_ELIGIBLE'
    END AS eligibility_status
FROM users u
LEFT JOIN donations d ON u.id = d.donor_id
WHERE u.role = 'donor'
GROUP BY u.id, u.name, u.email;

-- ============================================
-- SAMPLE QUERIES FOR TESTING
-- ============================================

-- SELECT * FROM vw_stock_status;
-- SELECT * FROM vw_donor_eligibility;
-- SELECT * FROM blood_requests;
-- SELECT * FROM donation_requests;

-- ============================================
-- END OF SCHEMA
-- ============================================
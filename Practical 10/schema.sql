-- ====================================================================
-- Practical 10: Database Schema for Secure User Authentication & Session Management
-- Student Name: Bhavy Gol
-- Student ID: 25CS016
-- Course: ITUE203 - WEB DEVELOPMENT FRAMEWORKS
-- Academic Year: 2026-27 | Semester: 3rd
-- Institute: Chandubhai S. Patel Institute of Technology (CSPIT)
-- Department: Department of Computer Science & Engineering
-- ====================================================================

CREATE DATABASE IF NOT EXISTS user_registration;
USE user_registration;

-- 1. Users Table with Role-Based Access & Remember-Me Fields
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'admin') NOT NULL DEFAULT 'student',
    last_login DATETIME NULL,
    remember_token_hash VARCHAR(64) NULL,
    remember_token_expiry DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Initial Sample Records (Matching Test Cases & phpMyAdmin Screenshot)
-- Seed credentials:
-- Student account: student01@gmail.com / student123 (role: student)
-- Admin account:   admin@gmail.com / admin123 (role: admin)
INSERT INTO users (id, username, email, password, role, last_login, remember_token_hash, remember_token_expiry, created_at)
VALUES 
(
    1,
    'student01',
    'student01@gmail.com',
    '$2y$12$iWDqA5DGlCIqZdrbuSHHj./E7JysbLTICoReUX2EYGUvXrxCl2Z/e',
    'student',
    '2026-10-14 13:16:11',
    NULL,
    NULL,
    '2026-10-14 13:00:00'
),
(
    2,
    'admin01',
    'admin@gmail.com',
    '$2y$12$9wwf00RRN68ate.0DLiiP.MiF7hvKxZoVBUI6ElV5V5xpN47rXDLe',
    'admin',
    '2026-10-14 13:18:29',
    NULL,
    NULL,
    '2026-10-14 13:00:00'
)
ON DUPLICATE KEY UPDATE 
    role = VALUES(role),
    password = VALUES(password),
    last_login = VALUES(last_login);

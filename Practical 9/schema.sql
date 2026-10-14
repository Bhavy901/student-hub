-- ====================================================================
-- Practical 9: Database Schema for Secure User Registration
-- Student Name: Bhavy Gol
-- Student ID: 25CS016
-- Course: ITUE203 - WEB DEVELOPMENT FRAMEWORKS
-- Department: Department of Computer Science & Engineering (CSPIT)
-- ====================================================================

CREATE DATABASE IF NOT EXISTS user_registration;
USE user_registration;

-- 1. Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Audit Logs Table (tracks registration activities)
CREATE TABLE IF NOT EXISTS audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    action VARCHAR(100) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Initial Sample Records (Matching Lab Manual Screenshot J)
INSERT INTO users (username, email, password, created_at) VALUES 
('student01', 'student01@gmail.com', '$2y$10$efLPico.QXwEYYzvfrgE.p.hMoM.15ll0hFuhAxspcke1BF06iRrC', '2026-10-07 13:29:20'),
('Bhavy', 'bhavygol@gmail.com', '$2y$10$spl/4JC/SMfeAC3VddCaOfoWpgc502fTAWe8FGgnAllWhlQ.CMva', '2026-10-07 13:31:09')
ON DUPLICATE KEY UPDATE id=id;

-- Practical 14: Admin Dashboard with Role-Based Access Control, Dynamic Menu, and Audit Log
-- Database Schema: dashboard_db

CREATE DATABASE IF NOT EXISTS dashboard_db;
USE dashboard_db;

-- 1. Dynamic Navigation Menu Table (RBAC restricted)
CREATE TABLE IF NOT EXISTS menu_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(50) NOT NULL,
    link VARCHAR(100) NOT NULL,
    min_role ENUM('user', 'editor', 'admin') DEFAULT 'user'
);

-- 2. System Users Table with Roles and Status
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    role ENUM('user', 'editor', 'admin') NOT NULL DEFAULT 'user',
    status ENUM('Active', 'Inactive') NOT NULL DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. Students Table for Dashboard Metric Tracking
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    department VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 4. Events Table
CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    event_date DATE NOT NULL,
    location VARCHAR(100) NOT NULL,
    status ENUM('Open', 'Closed') DEFAULT 'Open'
);

-- 5. Event Registrations Table
CREATE TABLE IF NOT EXISTS registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_name VARCHAR(100) NOT NULL,
    event_title VARCHAR(150) NOT NULL,
    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 6. Items Table for Inventory CRUD
CREATE TABLE IF NOT EXISTS items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    price DECIMAL(10,2) NOT NULL
);

-- 7. Audit Logs Table for Administrative Traceability
CREATE TABLE IF NOT EXISTS audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_role VARCHAR(20) NOT NULL DEFAULT 'admin',
    action VARCHAR(50) NOT NULL,
    details TEXT NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Seed Data: Menu Items
INSERT INTO menu_items (title, link, min_role) VALUES
('Dashboard', '#dashboard', 'user'),
('View Inventory', '#inventory', 'user'),
('Manage Content', '#content', 'editor'),
('Audit Logs', '#audit', 'admin'),
('User Management', '#users', 'admin'),
('System Settings', '#settings', 'admin');

-- Seed Data: Users
INSERT INTO users (username, email, role, status) VALUES
('admin_bhavy', 'bhavy.admin@charusat.edu', 'admin', 'Active'),
('editor_patel', 'editor.patel@charusat.edu', 'editor', 'Active'),
('student_rahul', 'rahul.student@charusat.edu', 'user', 'Active'),
('guest_user', 'guest@charusat.edu', 'user', 'Inactive');

-- Seed Data: Students
INSERT INTO students (name, email, department) VALUES
('Bhavy Gol', '25cs016@charusat.edu', 'CSE'),
('Aarav Mehta', 'aarav@charusat.edu', 'IT'),
('Diya Shah', 'diya@charusat.edu', 'CSE'),
('Rohan Joshi', 'rohan@charusat.edu', 'ECE'),
('Ananya Dave', 'ananya@charusat.edu', 'AI & ML');

-- Seed Data: Events
INSERT INTO events (title, event_date, location, status) VALUES
('Tech Fest 2026', '2026-11-28', 'Main Auditorium', 'Open'),
('Web Dev Hackathon', '2026-12-05', 'CSE Lab 3', 'Open'),
('AI Conference', '2026-12-15', 'Seminar Hall A', 'Closed');

-- Seed Data: Registrations
INSERT INTO registrations (student_name, event_title) VALUES
('Bhavy Gol', 'Web Dev Hackathon'),
('Aarav Mehta', 'Tech Fest 2026'),
('Diya Shah', 'Web Dev Hackathon'),
('Ananya Dave', 'AI Conference');

-- Seed Data: Items
INSERT INTO items (name, category, price) VALUES
('Laptop Pro', 'Electronics', 1200.00),
('Wireless Mouse', 'Accessories', 25.50),
('Mechanical Keyboard', 'Accessories', 85.00);

-- Seed Data: Initial Audit Logs
INSERT INTO audit_logs (user_role, action, details, ip_address) VALUES
('admin', 'SYSTEM_INIT', 'Database schema and seed records initialized', '127.0.0.1'),
('admin', 'LOGIN', 'Admin authenticated into control panel', '127.0.0.1'),
('admin', 'ROLE_SWITCH', 'Switched view context to administrative mode', '127.0.0.1');

-- Practical 15: Final Integration, Testing, Documentation, GitHub Submission, and Local Deployment
-- Master Integrated Database Schema: studenthub_db

CREATE DATABASE IF NOT EXISTS studenthub_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE studenthub_db;

-- System Integration Modules Registry Table
CREATE TABLE IF NOT EXISTS modules_registry (
    id INT AUTO_INCREMENT PRIMARY KEY,
    module_name VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    practical_ref VARCHAR(20) NOT NULL,
    status ENUM('Active', 'Tested', 'Deployed') DEFAULT 'Deployed',
    endpoint_url VARCHAR(150) NOT NULL,
    last_verified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Seed Data: All 15 Integrated Practicals
INSERT INTO modules_registry (module_name, category, practical_ref, status, endpoint_url) VALUES
('HTML5 Semantic University Portal', 'Frontend Core', 'Practical 1', 'Deployed', '../Practical 1/'),
('CSS3 Responsive Styling & Design Tokens', 'Frontend Styling', 'Practical 2', 'Deployed', '../practical 2/'),
('Interactive DOM JavaScript Events', 'Frontend Scripting', 'Practical 3', 'Deployed', '../Practical 3/'),
('Form Validation & Regex Pattern Engine', 'Client Validation', 'Practical 4', 'Deployed', '../Practical 4/'),
('JavaScript Dynamic Registration Module', 'DOM Manipulation', 'Practical 5', 'Deployed', '../Practical 5/'),
('PHP Form Processing & Sticky Fields', 'Backend Basics', 'Practical 6', 'Deployed', '../Practical 6/'),
('File Handling & JSON/CSV Persistence', 'File I/O', 'Practical 7', 'Deployed', '../Practical 7/'),
('Cookie Management & User Preferences', 'State Tracking', 'Practical 8', 'Deployed', '../Practical 8/'),
('Secure User Registration & BCRYPT Hashing', 'Authentication', 'Practical 9', 'Deployed', '../Practical 9/'),
('Session Management & RBAC Protection', 'Session Security', 'Practical 10', 'Deployed', '../Practical 10/'),
('Student CRUD & Dynamic SQL Filter Engine', 'Database CRUD', 'Practical 11', 'Deployed', '../Practical 11/'),
('Event CRUD with Secure Poster File Upload', 'File Upload CRUD', 'Practical 12', 'Deployed', '../Practical 12/'),
('PHP RESTful JSON API & AJAX/Fetch CRUD', 'REST API & SPA', 'Practical 13', 'Deployed', '../Practical 13/'),
('Admin Dashboard, RBAC & Audit Log Trail', 'Admin & Auditing', 'Practical 14', 'Deployed', '../Practical 14/'),
('Master StudentHub Portal & Virtual Host Deployment', 'Full Integration', 'Practical 15', 'Deployed', './index.php');

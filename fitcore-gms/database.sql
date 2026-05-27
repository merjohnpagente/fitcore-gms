-- FitCore GMS Database
-- Run this file in phpMyAdmin or MySQL CLI before starting the API

CREATE DATABASE IF NOT EXISTS fitcore_gms;
USE fitcore_gms;

CREATE TABLE IF NOT EXISTS members (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    full_name   VARCHAR(100)  NOT NULL,
    email       VARCHAR(100)  NOT NULL UNIQUE,
    phone       VARCHAR(20)   NOT NULL,
    plan        ENUM('Basic', 'Standard', 'Premium') NOT NULL DEFAULT 'Basic',
    status      ENUM('Active', 'Inactive', 'Suspended') NOT NULL DEFAULT 'Active',
    start_date  DATE          NOT NULL,
    end_date    DATE          NOT NULL,
    created_at  TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Sample data
INSERT INTO members (full_name, email, phone, plan, status, start_date, end_date) VALUES
('Juan dela Cruz',   'juan@email.com',   '09171234567', 'Premium',  'Active',    '2025-01-01', '2025-12-31'),
('Maria Santos',     'maria@email.com',  '09181234567', 'Standard', 'Active',    '2025-02-01', '2025-08-01'),
('Pedro Reyes',      'pedro@email.com',  '09191234567', 'Basic',    'Inactive',  '2024-12-01', '2025-06-01');

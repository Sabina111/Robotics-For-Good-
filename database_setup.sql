-- Create the database
CREATE DATABASE IF NOT EXISTS robotics_registration;

-- Use the database
USE robotics_registration;

-- Create the registrations table
CREATE TABLE IF NOT EXISTS registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    middle_name VARCHAR(50),
    last_name VARCHAR(50) NOT NULL,
    date_of_birth DATE NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    ticket_type VARCHAR(20) NOT NULL,
    district VARCHAR(50) NOT NULL,
    school_college VARCHAR(100) NOT NULL,
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
); 
<?php
// Email Configuration
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'aiforgood2024.ran@gmail.com');
define('SMTP_PASSWORD', ''); // You'll need to add your Gmail app password here
define('SMTP_FROM_EMAIL', 'aiforgood2024.ran@gmail.com');
define('SMTP_FROM_NAME', 'Robotics For Good');

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');  // Change this to your database username
define('DB_PASS', '');      // Change this to your database password
define('DB_NAME', 'robotics_registration');

// Create database connection
try {
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Create table if not exists
try {
    $sql = "CREATE TABLE IF NOT EXISTS registrations (
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
    )";
    
    $conn->exec($sql);
} catch(PDOException $e) {
    die("Table creation failed: " . $e->getMessage());
}
?> 
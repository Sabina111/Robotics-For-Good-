<?php
require_once 'config.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Testing Database Connection</h2>";

try {
    // Test database connection
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p style='color: green;'>✓ Database connection successful!</p>";

    // Check if registrations table exists
    $table_exists = $pdo->query("SHOW TABLES LIKE 'registrations'")->rowCount() > 0;
    if ($table_exists) {
        echo "<p style='color: green;'>✓ Registrations table exists</p>";
        
        // Get table structure
        $columns = $pdo->query("DESCRIBE registrations")->fetchAll(PDO::FETCH_ASSOC);
        echo "<h3>Table Structure:</h3>";
        echo "<pre>";
        print_r($columns);
        echo "</pre>";
    } else {
        echo "<p style='color: red;'>✗ Registrations table does not exist</p>";
        echo "<p>Creating table...</p>";
        
        // Create table
        $sql = "CREATE TABLE IF NOT EXISTS registrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            first_name VARCHAR(100) NOT NULL,
            middle_name VARCHAR(100),
            last_name VARCHAR(100) NOT NULL,
            date_of_birth DATE NOT NULL,
            email VARCHAR(255) NOT NULL,
            phone VARCHAR(20) NOT NULL,
            ticket_type VARCHAR(50) NOT NULL,
            district VARCHAR(100) NOT NULL,
            school_college VARCHAR(255) NOT NULL,
            message TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        $pdo->exec($sql);
        echo "<p style='color: green;'>✓ Table created successfully!</p>";
    }

} catch(PDOException $e) {
    echo "<p style='color: red;'>✗ Connection failed: " . $e->getMessage() . "</p>";
}
?> 
CREATE DATABASE robotics_for_good;
USE robotics_for_good;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

<?php
// Allow CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-Requested-With');
header('Content-Type: application/json');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Database connection settings
$host = 'localhost';
$dbname = 'robotics_for_good';
$username = 'root';
$password = '';

// Create a new PDO connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Check if the form is submitted
if (isset($_POST['register'])) {
    // Get form data
    $user_username = $_POST['username'];
    $user_email = $_POST['email'];
    $user_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Simple validation: check if passwords match
    if ($user_password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!');</script>";
        exit;
    }

    // Sanitize input to avoid XSS attacks (for security)
    $user_username = htmlspecialchars($user_username);
    $user_email = htmlspecialchars($user_email);

    // Hash the password for security (recommended for storing passwords)
    $hashed_password = password_hash($user_password, PASSWORD_DEFAULT);

    // Prepare the SQL query to insert the new user
    $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
    
    // Prepare the statement
    $stmt = $pdo->prepare($sql);

    // Bind the parameters
    $stmt->bindParam(':username', $user_username);
    $stmt->bindParam(':email', $user_email);
    $stmt->bindParam(':password', $hashed_password);

    // Execute the statement
    try {
        $stmt->execute();
        echo "<script>alert('Registration successful!');</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>

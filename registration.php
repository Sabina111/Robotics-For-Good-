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
    die("Connection failed: " . $e->getMessage());
}

// Check if the form is submitted
if (isset($_POST['register'])) {
    // Get form data
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'] ?? '';
    $last_name = $_POST['last_name'];
    $date_of_birth = $_POST['date_of_birth'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $ticket_type = $_POST['ticket_type'];
    $district = $_POST['district'];
    $school_college = $_POST['school_college'];
    $message = $_POST['message'] ?? '';

    // Sanitize input
    $first_name = htmlspecialchars($first_name);
    $middle_name = htmlspecialchars($middle_name);
    $last_name = htmlspecialchars($last_name);
    $email = htmlspecialchars($email);
    $phone = htmlspecialchars($phone);
    $ticket_type = htmlspecialchars($ticket_type);
    $district = htmlspecialchars($district);
    $school_college = htmlspecialchars($school_college);
    $message = htmlspecialchars($message);

    // Prepare the SQL query
    $sql = "INSERT INTO registrations (first_name, middle_name, last_name, date_of_birth, email, phone, ticket_type, district, school_college, message) 
            VALUES (:first_name, :middle_name, :last_name, :date_of_birth, :email, :phone, :ticket_type, :district, :school_college, :message)";
    
    $stmt = $pdo->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam(':middle_name', $middle_name);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':date_of_birth', $date_of_birth);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':ticket_type', $ticket_type);
    $stmt->bindParam(':district', $district);
    $stmt->bindParam(':school_college', $school_college);
    $stmt->bindParam(':message', $message);

    try {
        $stmt->execute();
        echo json_encode(['success' => true, 'message' => 'Registration successful!']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>

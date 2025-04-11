<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to check PHP version
function checkPHPVersion() {
    $required = '7.4.0';
    $current = PHP_VERSION;
    return version_compare($current, $required, '>=');
}

// Function to check if MySQL is installed
function checkMySQL() {
    return extension_loaded('pdo_mysql');
}

// Function to check if mail function is available
function checkMail() {
    return function_exists('mail');
}

// Function to test database connection
function testDatabase($host, $user, $pass, $dbname) {
    try {
        $pdo = new PDO("mysql:host=$host", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Create database if it doesn't exist
        $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname");
        $pdo->exec("USE $dbname");
        
        return true;
    } catch(PDOException $e) {
        return false;
    }
}

// Function to test email configuration
function testEmail($smtp_host, $smtp_port, $smtp_user, $smtp_pass) {
    require_once 'vendor/autoload.php';
    
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    
    try {
        $mail->isSMTP();
        $mail->Host = $smtp_host;
        $mail->Port = $smtp_port;
        $mail->SMTPAuth = true;
        $mail->Username = $smtp_user;
        $mail->Password = $smtp_pass;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db_host = $_POST['db_host'] ?? '';
    $db_user = $_POST['db_user'] ?? '';
    $db_pass = $_POST['db_pass'] ?? '';
    $db_name = $_POST['db_name'] ?? '';
    $smtp_host = $_POST['smtp_host'] ?? '';
    $smtp_port = $_POST['smtp_port'] ?? '';
    $smtp_user = $_POST['smtp_user'] ?? '';
    $smtp_pass = $_POST['smtp_pass'] ?? '';
    
    // Test database connection
    if (testDatabase($db_host, $db_user, $db_pass, $db_name)) {
        // Create config file
        $config_content = "<?php
define('DB_HOST', '$db_host');
define('DB_USER', '$db_user');
define('DB_PASS', '$db_pass');
define('DB_NAME', '$db_name');
define('SMTP_HOST', '$smtp_host');
define('SMTP_PORT', $smtp_port);
define('SMTP_USER', '$smtp_user');
define('SMTP_PASS', '$smtp_pass');
define('SMTP_FROM_EMAIL', '$smtp_user');
define('SMTP_FROM_NAME', 'Robotics For Good');

try {
    \$pdo = new PDO(\"mysql:host=\" . DB_HOST . \";dbname=\" . DB_NAME, DB_USER, DB_PASS);
    \$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException \$e) {
    die(\"Connection failed: \" . \$e->getMessage());
}
";
        file_put_contents('config.php', $config_content);
        
        // Import database structure
        $sql = file_get_contents('database_setup.sql');
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
        $pdo->exec($sql);
        
        echo "<div class='success'>Configuration completed successfully!</div>";
    } else {
        echo "<div class='error'>Database connection failed. Please check your credentials.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Robotics For Good - Setup</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="password"],
        input[type="number"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background: #0056b3;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .requirements {
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Robotics For Good - Setup</h1>
        
        <div class="requirements">
            <h2>System Requirements</h2>
            <p>PHP Version (>= 7.4.0): <?php echo checkPHPVersion() ? '✓' : '✗'; ?></p>
            <p>MySQL Extension: <?php echo checkMySQL() ? '✓' : '✗'; ?></p>
            <p>Mail Function: <?php echo checkMail() ? '✓' : '✗'; ?></p>
        </div>
        
        <form method="POST">
            <h2>Database Configuration</h2>
            <div class="form-group">
                <label for="db_host">Database Host:</label>
                <input type="text" id="db_host" name="db_host" value="localhost" required>
            </div>
            <div class="form-group">
                <label for="db_user">Database Username:</label>
                <input type="text" id="db_user" name="db_user" required>
            </div>
            <div class="form-group">
                <label for="db_pass">Database Password:</label>
                <input type="password" id="db_pass" name="db_pass" required>
            </div>
            <div class="form-group">
                <label for="db_name">Database Name:</label>
                <input type="text" id="db_name" name="db_name" value="robotics_registration" required>
            </div>
            
            <h2>SMTP Configuration</h2>
            <div class="form-group">
                <label for="smtp_host">SMTP Host:</label>
                <input type="text" id="smtp_host" name="smtp_host" value="smtp.gmail.com" required>
            </div>
            <div class="form-group">
                <label for="smtp_port">SMTP Port:</label>
                <input type="number" id="smtp_port" name="smtp_port" value="587" required>
            </div>
            <div class="form-group">
                <label for="smtp_user">SMTP Username (Email):</label>
                <input type="text" id="smtp_user" name="smtp_user" required>
            </div>
            <div class="form-group">
                <label for="smtp_pass">SMTP Password (App Password):</label>
                <input type="password" id="smtp_pass" name="smtp_pass" required>
            </div>
            
            <button type="submit">Save Configuration</button>
        </form>
        
        <div style="margin-top: 20px;">
            <h3>Next Steps:</h3>
            <ol>
                <li>Fill in the database credentials above</li>
                <li>For Gmail SMTP:
                    <ul>
                        <li>Enable 2-Step Verification in your Google Account</li>
                        <li>Generate an App Password in Google Account settings</li>
                        <li>Use the App Password in the SMTP Password field</li>
                    </ul>
                </li>
                <li>Click "Save Configuration" to set up the system</li>
                <li>After setup, you can delete this file for security</li>
            </ol>
        </div>
    </div>
</body>
</html> 
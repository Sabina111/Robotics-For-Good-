<?php
require_once 'config.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Test email configuration
echo "Testing email configuration...<br>";

// Test basic mail function
$to = SMTP_FROM_EMAIL;
$subject = "Test Email from Robotics For Good";
$message = "This is a test email to verify the mail server configuration.";
$headers = "From: " . SMTP_FROM_EMAIL . "\r\n";
$headers .= "Reply-To: " . SMTP_FROM_EMAIL . "\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// Try to send email
$mail_sent = mail($to, $subject, $message, $headers);

if ($mail_sent) {
    echo "Test email sent successfully!<br>";
} else {
    echo "Failed to send test email.<br>";
    echo "Please check your mail server configuration:<br>";
    echo "SMTP Host: " . SMTP_HOST . "<br>";
    echo "SMTP Port: " . SMTP_PORT . "<br>";
    echo "From Email: " . SMTP_FROM_EMAIL . "<br>";
}

// Display PHP mail configuration
echo "<br>PHP Mail Configuration:<br>";
echo "mail.add_x_header: " . ini_get('mail.add_x_header') . "<br>";
echo "mail.log: " . ini_get('mail.log') . "<br>";
echo "sendmail_path: " . ini_get('sendmail_path') . "<br>";
?> 
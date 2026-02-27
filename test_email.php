<?php
/**
 * Email Test Script
 * Use this to verify your SMTP settings
 */

require_once 'config/config.php';
require_once 'includes/Database.php';
require_once 'includes/Helpers.php';
require_once 'includes/Email.php';

echo "<h2>📧 Edluxury Email System Test</h2>";

if (SMTP_PASSWORD === 'YOUR_GMAIL_APP_PASSWORD' || empty(SMTP_PASSWORD)) {
    echo "<p style='color:red;'>❌ <strong>Error:</strong> You haven't updated your SMTP_PASSWORD in config/config.php yet.</p>";
    echo "<p>Please follow the instructions to get an App Password from your Google Account.</p>";
    exit;
}

$email = new Email();
$testRecipient = 'edluxury32@gmail.com'; // Testing by sending to yourself
$subject = "Test Email from Edluxury Store";
$body = "
    <h1>It Works! 🎉</h1>
    <p>If you are reading this, your SMTP settings are correctly configured.</p>
    <p>Your store will now automatically send:</p>
    <ul>
        <li>Order confirmations to customers</li>
        <li>New order alerts to you (admin)</li>
        <li>Shipping updates</li>
    </ul>
    <br>
    <p>Sent at: " . date('Y-m-d H:i:s') . "</p>
";

echo "<p>Attempting to send a test email to <strong>$testRecipient</strong>...</p>";

try {
    $sent = $email->send($testRecipient, $subject, $body);

    if ($sent) {
        echo "<h3 style='color:green;'>✅ SUCCESS! Email sent successfully.</h3>";
        echo "<p>Please check your inbox (and spam folder) at edluxury32@gmail.com.</p>";
    } else {
        echo "<h3 style='color:red;'>❌ FAILED. The email was not sent.</h3>";
        echo "<p><strong>Possible reasons:</strong></p>";
        echo "<ul>
                <li>Incorrect App Password</li>
                <li>SMTP Port 587 is blocked by your firewall/antivirus</li>
                <li>XAMPP is missing SSL extensions (check php.ini)</li>
              </ul>";
    }
} catch (Exception $e) {
    echo "<p style='color:red;'><strong>Exception:</strong> " . $e->getMessage() . "</p>";
}

echo "<br><a href='index.php'>← Back to Homepage</a>";

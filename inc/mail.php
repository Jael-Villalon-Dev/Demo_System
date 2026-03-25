<?php
require_once __DIR__ . '/../config/mail.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../phpmailer/src/Exception.php';
require_once __DIR__ . '/../phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/../phpmailer/src/SMTP.php';

function sendPasswordResetEmail(string $recipientEmail, string $recipientName, string $resetUrl): bool {
    $subject = 'Password Reset Request - CoinBase Demo';
    
    $htmlBody = "
    <html>
    <body style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
        <h2 style='color: #1538c5;'>Reset Your Password</h2>
        <p>Hi " . htmlspecialchars($recipientName) . ",</p>
        <p>We received a request to reset your password. Click the link below (valid for 1 hour):</p>
        <p><a href='" . htmlspecialchars($resetUrl) . "' style='background-color: #2e6ffe; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;'>Reset Password</a></p>
        <p>Or copy this link:</p>
        <p><code>" . htmlspecialchars($resetUrl) . "</code></p>
        <hr style='border: none; border-top: 1px solid #eee; margin: 20px 0;'>
        <p style='color: #666; font-size: 12px;'>If you didn't request this, please ignore this email.</p>
        <p style='color: #666; font-size: 12px;'>Best regards,<br/>CoinBase Team</p>
    </body>
    </html>
    ";
    
    $textBody = "Reset Your Password\n\n" .
                "Hi " . $recipientName . ",\n\n" .
                "Click here to reset your password (valid 1 hour):\n" .
                $resetUrl . "\n\n" .
                "If you didn't request this, ignore this email.";
    
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = MAIL_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = MAIL_USERNAME;
        $mail->Password = MAIL_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = MAIL_PORT;
        
        // Recipients
        $mail->setFrom(MAIL_FROM_EMAIL, MAIL_FROM_NAME);
        $mail->addAddress($recipientEmail, $recipientName);
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $htmlBody;
        $mail->AltBody = $textBody;
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("PHPMailer error: " . $mail->ErrorInfo);
        return false;
    }
}


<?php
// Include the Composer autoloader
require 'vendor/autoload.php'; // This will load the PHPMailer files

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.papertale.shop';  // Your SMTP server (replace with actual server)
    $mail->SMTPAuth = true;
    $mail->Username = 'info@papertale.shop'; // Your email address
    $mail->Password = 'Pass@34545.';  // Your email password (or use an app password if 2FA is enabled)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587; // Usually 587 for TLS

    //Recipients
    $mail->setFrom('rimsha@papertale.shop', 'PaperTales');
    $mail->addAddress('rimsha@papertale.shop'); // Add your email address to receive the email

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Payment Proof Uploaded';
    $mail->Body    = 'A new payment proof has been uploaded. Order ID: 12345.';

    // Send email
    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>

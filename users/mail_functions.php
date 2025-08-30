<?php
// mail_functions.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; 

function sendVerificationEmail($email, $verificationToken) {
    $mail = new PHPMailer(true);

    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host = 'mail.apitickets.com'; 
        $mail->SMTPAuth = true;
        $mail->Username = 'info@apitickets.com'; 
        $mail->Password = 'apitickets@123'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; 
        $mail->Port = 465;

        // Email content
        $mail->setFrom('info@apitickets.com', 'API Tickets'); 
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Email Verification';
        $mail->Body = "Please verify your email by clicking the link below: 
                       <a href='https://apitickets.com/users/verify.php?token=$verificationToken'>Verify Email</a>";

        // Send the email
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false; 
    }
}
?>

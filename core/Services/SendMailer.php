<?php

namespace Core\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Core\AbstractCore;

class SendMailer extends AbstractCore {

    public function send($email, $body, $header = '') {

        $mail = new PHPMailer(true);
        $fromAddress  = SMTP_USER_NAME;
        $fromPassword = SMTP_USER_PASSWORD;

//        ini_set('error_reporting', E_ALL);
//        ini_set('display_errors', 1);
//        ini_set('display_startup_errors', 1);

        try {
            // Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;             // Enable verbose debug output
            $mail->CharSet   = "utf-8";
            $mail->isSMTP();                                      // Send using SMTP
            $mail->Host       = SMTP_HOST_NAME;                   // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                             // Enable SMTP authentication
            $mail->Username   = $fromAddress;                     // SMTP username
            $mail->Password   = $fromPassword;                          // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
            $mail->Port       = 587;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom($fromAddress, 'Mail Sender');
            $mail->addAddress($email, 'New User');     // Add a recipient

//            $mail->addAddress('ellen@example.com');               // Name is optional
//            $mail->addReplyTo('info@example.com', 'Information');
//            $mail->addCC('cc@example.com');
//            $mail->addBCC('bcc@example.com');

            // Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $header;
            $mail->Body    = $body;
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
            $mail->send();

            //echo 'Message has been sent';
        } catch (\Exception $e) {
            return false;
            //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

        return true;
    }


}
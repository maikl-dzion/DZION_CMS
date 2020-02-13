<?php

namespace Core\Services;

use Core\Kernel\AbstractCore;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


class SendMailer extends AbstractCore {

    protected $mail;
    protected $fromAddress;
    protected $fromPassword;

    public function __construct(){
        parent::__construct();

        $this->mail = new PHPMailer(true);
        $this->fromAddress  = \Core\Kernel\ConstContainer::SMTP_SERVER_NAME;
        $this->fromPassword = \Core\Kernel\ConstContainer::SMTP_USER_PASSWORD;
    }

    public function send($email, $body, $header = '') {

        $mail = $this->mail;

        $fromAddress  = $this->fromAddress;
        $fromPassword = $this->fromPassword;

//        ini_set('error_reporting', E_ALL);
//        ini_set('display_errors', 1);
//        ini_set('display_startup_errors', 1);

        try {
            // Server settings
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;             // Enable verbose debug output
            $mail->CharSet   = "utf-8";
            $mail->isSMTP();                                      // Send using SMTP
            $mail->Host       = SMTP_SERVER_NAME;                  // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                             // Enable SMTP authentication
            $mail->Username   = $fromAddress;                     // SMTP username
            $mail->Password   = $fromPassword;                          // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
            $mail->Port       = SMPT_SERVER_PORT;                       // TCP port to connect to
            $mail->SMTPKeepAlive = true;
            $mail->Mailer = 'smtp'; // don't change the quotes!

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
            $result = $mail->send();
            $message =  'Message has been sent';
            return 1;

        } catch (\Exception $e) {
            $message = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return false;
        }

        return true;
    }


}
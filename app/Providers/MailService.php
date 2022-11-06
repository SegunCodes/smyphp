<?php

namespace App\Providers;

use SmyPhp\Core\Application;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once Application::$ROOT_DIR."/vendor/autoload.php";

class MailService{

    public function Mail($subject, $recipient_email, $recipient_name, $email_template, $email_variables = null){
        //PHPMailer Object
        $mail = new PHPMailer();

        //Enable SMTP debugging. 
        $mail->SMTPDebug = 0;                               
        //Set PHPMailer to use SMTP.
        $mail->isSMTP(true); 
        //Set SMTP host name           
        $mail->Host = $_ENV['MAIL_HOST'] ;
        //Set this to true if SMTP host requires authentication to send email
        $mail->SMTPAuth = true;                          
        //Provide username and password     
        $mail->Username = $_ENV['MAIL_FROM_ADDRESS'];                 
        $mail->Password = $_ENV['MAIL_PASSWORD'];                          
        //If SMTP requires TLS encryption then set it
        $mail->SMTPSecure = $_ENV['MAIL_ENCRYPTION'];                         
        //Set TCP port to connect to 
        $mail->Port = $_ENV['MAIL_PORT']; 

        //From email address and name
        $mail->From = $_ENV['MAIL_FROM_ADDRESS'];
        $mail->FromName = $_ENV['MAIL_FROM_NAME'];

        //To address and name
        $mail->addAddress($recipient_email, $recipient_name);

        //Send HTML or Plain Text email
        $mail->isHTML(true);

        //Email subject
        $mail->Subject = $subject;

        //uncomment the next lines if mail will be sent from this file
        // $mail->Body = "<i>Mail body in HTML</i>";
        // $mail->AltBody = "This is the plain text version of the email content";

        $body = file_get_contents($email_template);
        if($email_variables  != null && is_array($email_variables)) {
            foreach ($email_variables as $key => $value) {
                $body = str_replace("{{{$key}}}", $value, $body);
            }
        }

        $mail->Body = $body;
        
        if (!$mail->send()) {
            $status = false;
            throw new \Exception($mail->ErrorInfo, 1);
        }else{
            $status = true;
        }

        return $status; 
    }

}
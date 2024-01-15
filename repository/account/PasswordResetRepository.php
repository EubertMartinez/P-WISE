<?php 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require './../../vendor/autoload.php';

class PasswordResetRepository extends ReposittoryHelper {
    private $data;

    public function __construct() {
        $this->data = new UpdateData();
    }

    public function SendPasswordResetEmailEmail($email) {
        $result = $this->GetUserDataByEmail($email);
        
        if (count($result) < 1) {
            return "Email not found!";
        } else {
            $this->SendEmailNotification($email, $result[0]['firstname'], $result[0]['lastname'], $result[0]['id']);
            return "success";
        }
    }

    private function GetUserDataByEmail($email) {
        $result = $this->data->GetUserDataByEmail($email);
        return $this->GetDataAsArray($result);
    }

    private function SendEmailNotification($email, $firstname, $lastname, $id) {
        $mail = new PHPMailer(true);
        
        $reset_link="https://pwisebudget.com/auth/passwordReset.php?id=";

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      
            $mail->isSMTP();                                            
            $mail->Host       = 'smtp.gmail.com'; 
            $mail->SMTPAuth   = true;                               
            $mail->Username   = 'pwisebudget@gmail.com';
            $mail->Password   = 'taphalgwadbpdeft';                              
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;          
            $mail->Port       = 465;                                   

            //Recipients
            $mail->setFrom('pwisebudget@gmail.com', 'PWISE');
            $mail->addAddress($email, $firstname.' '.$lastname);


            //Content
            $mail->isHTML(true);                                 
            $mail->Subject = 'PWise Password Reset Link';
            $mail->Body    = 'Hi '.$firstname.' '.$lastname.', <br><br>Here is your password reset link '."<br><br><a href=\"$reset_link$id\">Click here</a> to reset your password and login your account.";
            $mail->AltBody = '';

            $mail->send();
            // echo 'Message has been sent';
        } catch (Exception $e) {
            // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

    }
}
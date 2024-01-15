<?php 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require './../../vendor/autoload.php';

class RegisterRepository extends ReposittoryHelper {
    private $data;

    public function __construct() {
        $this->data = new RegisterData();
    }

    public function Register($accountType, $firstname, $lastname, $email, $username, $mobile, $password) {
        if ($this->GetUserByEmailOrUsername($email, $username))
            return "The email or username you are trying to register is already exists.";

        // $passwordHash = md5($password);
        $passwordHash = $password;

        $id = $this->data->Register($accountType, $firstname, $lastname, $email, $username, $mobile, $passwordHash);

        if ($id > 0) {
            $this->SendEmailNotification($email, $firstname, $lastname, $username, $password, $id);
            return 'success';
        }

        return "Unable to register account!";
    }

    private function GetUserByEmailOrUsername($email, $username) {
        $result = $this->data->GetUserByEmailOrUsername($email, $username); 
        return $result->num_rows > 0; 
    }

    private function SendEmailNotification($email, $firstname, $lastname, $username, $password, $id) {
        $mail = new PHPMailer(true);

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
            $mail->Subject = 'PWise Account Created';
            $mail->Body    = 'Hi '.$firstname.' '.$lastname.', <br><br>Your account has been successfully created! below are the details of your credentials for you to login to your account. <br><br>Usernem: '.$username.'<br>Password: '.$password."<br><br><a href=\"$this->base_url$id\">Click here</a> to verify and login your account.";
            $mail->AltBody = '';

            $mail->send();
            // echo 'Message has been sent';
        } catch (Exception $e) {
            // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

    }
}

?>
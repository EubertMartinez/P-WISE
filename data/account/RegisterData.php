<?php

class RegisterData extends DataHelper {
    public function Register($accountType, $firstname, $lastname, $email, $username, $mobile, $password) {
        $accountType = $this->EscapeString($accountType);
        $firstname   = $this->EscapeString($firstname);
        $lastname    = $this->EscapeString($lastname);
        $email       = $this->EscapeString($email);
        $username    = $this->EscapeString($username);
        $mobile      = $this->EscapeString($mobile);
        $password    = $this->EscapeString($password);

        $sql = "INSERT INTO users(account_type, firstname, lastname, email, username, mobile, password) 
                VALUES ('$accountType','$firstname','$lastname','$email','$username','$mobile','$password')";
        
        return $this->ExecuteNonQueryReturnsInsertedId($sql);
    }

    public function GetUserByEmailOrUsername($email, $username) {
        $email    = $this->EscapeString($email);
        $username = $this->EscapeString($username);

        $sql = "SELECT * FROM users WHERE email='$email' OR username='$username'";
        return $this->SelectData($sql);
    }
}

?>
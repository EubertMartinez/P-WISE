<?php

class LoginData extends DataHelper {
    public function Login($username, $password) {
        $username = $this->EscapeString($username);
        $password = $this->EscapeString($password);

        $sql = "SELECT * 
                    FROM users 
                    WHERE username='$username' 
                        AND password='$password'
                        AND archived = 0
                        AND is_verified = 1";

        return $this->SelectData($sql);
    } 
}

?>
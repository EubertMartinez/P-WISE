<?php

class UpdateData extends DataHelper {
    public function GetUserData($id) {
        $id  = $this->EscapeString($id);
        $sql = "SELECT * FROM users WHERE id = '$id'";

        return $this->SelectData($sql);
    }

    public function GetUserDataByEmail($email) {
        $email = $this->EscapeString($email);
        $sql   = "SELECT * FROM users WHERE email = '$email'";

        return $this->SelectData($sql);
    }

    public function UpdateProfile($id, $firstname, $lastname, $mobile) {
        $id        = $this->EscapeString($id);
        $firstname = $this->EscapeString($firstname);
        $lastname  = $this->EscapeString($lastname);
        $mobile    = $this->EscapeString($mobile);

        $sql = "UPDATE users
                    SET firstname = '$firstname'
                        , lastname = '$lastname'
                        , mobile = '$mobile'
                    WHERE id = '$id'";

        return $this->ExecuteNonQuery($sql);
    } 

    public function UpdatePassword($id, $password) {
        $id       = $this->EscapeString($id);
        $password = $this->EscapeString($password);

        $sql = "UPDATE users
                    SET password = '$password'
                    WHERE id = '$id'";

        return $this->ExecuteNonQuery($sql);
    }
}

?>
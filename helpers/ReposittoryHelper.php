<?php

class ReposittoryHelper {
    public $base_url = "https://pwisebudget.com/auth/verify_account.php?id=";
    public $reset_password_url = "https://pwisebudget.com/auth/passwordReset.php?id=";

    public function GetDataAsArray($array) {
        $data = [];

        if ($array == null) 
            return $data;

        while($row = $array->fetch_assoc()) {
            array_push($data, $row);
        }

        return $data;
    }
}

?>
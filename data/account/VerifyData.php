<?php

class VerifyData extends DataHelper {
    public function VerfityAccount($id) {
        $id = $this->EscapeString($id);

        $sql = "UPDATE users SET is_verified = 1 WHERE id = '$id'";
        return $this->ExecuteNonQuery($sql);
    } 
}

?>
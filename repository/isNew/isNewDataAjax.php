<?php

class isNewDataAjax extends DataHelper {

    public function isNewUser($id, $isNew)
    {
        $id = $this->EscapeString($id);
        $isNew = $this->EscapeString($isNew);

        $sql = "UPDATE users SET is_new = '$isNew' WHERE id = '$id'";
        $result = $this->ExecuteNonQuery($sql);

        if($result) {

            $_SESSION['is_new'] = 0;
            return [
                'status' => 1,
            ];
        } else {
            return [
                'status' => 0,
            ];
        }

    }

}



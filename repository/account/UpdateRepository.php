<?php 

class UpdateRepository extends ReposittoryHelper {
    private $data;

    public function __construct() {
        $this->data = new UpdateData();
    }

    public function GetUserData($id) {
        $result = $this->data->GetUserData($id);
        return $this->GetDataAsArray($result);
    }

    public function UpdateProfile($id, $firstname, $lastname, $mobile) {
        return $this->data->UpdateProfile($id, $firstname, $lastname, $mobile); 
    }

    public function UpdatePassword($id, $password) {
        return $this->data->UpdatePassword($id, $password); 
    }

    
}

?>
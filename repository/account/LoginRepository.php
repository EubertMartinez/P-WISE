<?php 

class LoginRepository extends ReposittoryHelper {
    private $data;

    public function __construct() {
        $this->data = new LoginData();
    }

    public function Login($email, $password) {
        // $password = md5($password);

        $result = $this->data->Login($email, $password); 
        return $this->GetDataAsArray($result);
    }
}

?>
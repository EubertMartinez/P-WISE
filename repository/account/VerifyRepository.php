<?php 

class VerifyRepository extends ReposittoryHelper {
    private $data;

    public function __construct() {
        $this->data = new VerifyData();
    }

    public function VerfityAccount($id) {
        return $this->data->VerfityAccount($id); 
    }
}

?>
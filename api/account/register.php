<?php

require_once './../../helpers/ApiIndex.php';
require_once './../../data/account/RegisterData.php';
require_once './../../repository/account/RegisterRepository.php';

$data = new RegisterRepository();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accountType = $_POST['accountType'];
    $firstname   = $_POST['firstname'];
    $lastname    = $_POST['lastname'];
    $email       = $_POST['email'];
    $username    = $_POST['username'];
    $mobile      = $_POST['mobile'];
    $password    = $_POST['password'];

    echo $data->Register($accountType, $firstname, $lastname, $email, $username, $mobile, $password);
}

?>
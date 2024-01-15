<?php

require_once './../../helpers/ApiIndex.php';
require_once './../../data/account/UpdateData.php';
require_once './../../repository/account/PasswordResetRepository.php';

$data = new PasswordResetRepository();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    echo $data->SendPasswordResetEmailEmail($email);
}

?>
<?php

session_start();

require_once './../../helpers/ApiIndex.php';
require_once './../../data/account/UpdateData.php';
require_once './../../repository/account/UpdateRepository.php';

$data = new UpdateRepository();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $phone = $_POST['phone'];

    echo $data->UpdateProfile($_SESSION['user_id'], $fname, $lname, $phone);
}

?>
<?php

session_start();

require_once './../../helpers/ApiIndex.php';
require_once './../../data/account/UpdateData.php';
require_once './../../repository/account/UpdateRepository.php';

$data = new UpdateRepository();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = isset($_POST['id']) ? $_POST['id'] : (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0);
    $password = $_POST['password'];

    echo $data->UpdatePassword($id, $password);
}

?>
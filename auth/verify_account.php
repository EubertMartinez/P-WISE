<?php

require_once './../helpers/DatabaseConfiguration.php';
require_once './../helpers/DataHelper.php';
require_once './../helpers/ReposittoryHelper.php';

require_once './../data/account/VerifyData.php';
require_once './../repository/account/VerifyRepository.php';

$id   = isset($_GET['id']) ? $_GET['id'] : 0;
$data = new VerifyRepository();

$result = $data->VerfityAccount($id);

if ($result === TRUE) {
    header('location: verified.php');
} else {
    header('location: accountNotFound.php');
}

?>
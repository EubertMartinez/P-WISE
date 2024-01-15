<?php

session_start();

require_once './../../helpers/ApiIndex.php';
require_once './../../data/budget/SavingsData.php';
require_once './../../repository/budget/SavingsRepository.php';

$data = new SavingsRepository();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id     = $_POST['id'];
    $amount = $_POST['amount'];
    $cycle  = $_POST['cycle_id'];

    echo $data->AddSavings($id, $amount, $cycle);
    
}

?>
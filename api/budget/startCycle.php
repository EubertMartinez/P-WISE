<?php

session_start();

require_once './../../helpers/ApiIndex.php';
require_once './../../data/budget/AmountData.php';
require_once './../../repository/budget/AmountRepository.php';

$data = new AmountRepository();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $parent_id = $_SESSION['parent_id'];
    $id = $_SESSION['user_id'];
    $start = $_POST['start'];
    $end = $_POST['end'];

    echo $data->StartCycle($parent_id, $id, $start, $end);
}

?>
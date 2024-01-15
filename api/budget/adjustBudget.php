<?php

session_start();

require_once './../../helpers/ApiIndex.php';
require_once './../../data/budget/AmountData.php';
require_once './../../repository/budget/AmountRepository.php';

$data = new AmountRepository();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id       = $_POST['id'];
    $parent   = $_POST['parent'];
    $cycle    = $_POST['cycle'];
    $category = $_POST['category'];
    $amount   = $_POST['amount'];

    echo $data->AdjustBudgetLimit($id, $parent, $cycle, $category, $amount, $_SESSION['user_id']);
}

?>
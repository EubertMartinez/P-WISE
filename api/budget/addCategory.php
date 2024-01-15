<?php

session_start();

require_once './../../helpers/ApiIndex.php';
require_once './../../data/budget/ExpensesData.php';
require_once './../../repository/budget/ExpensesRepository.php';

$data = new ExpensesRepository();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $parent_id   = $_SESSION['parent_id'];
    $id          = $_SESSION['user_id'];
    $priority    = $_POST['priority'];
    $category    = $_POST['category'];

    echo $data->AddExpensesCategory($parent_id, $id, $priority, $category);
}

?>
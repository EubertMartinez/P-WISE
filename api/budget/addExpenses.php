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
    $newCategory = $_POST['newCategory'];
    $expenses    = $_POST['expenses'];
    $newExpenses = $_POST['newExpenses'];
    $amount      = $_POST['amount'];
    $cycle_id    = $_POST['cycle_id'];

    $is_overspent = $_POST['is_overspent'];

    echo $data->AddExpenses($parent_id, $id, $priority, $category, $newCategory, $expenses, $newExpenses, $amount, $cycle_id, $is_overspent);
}

?>
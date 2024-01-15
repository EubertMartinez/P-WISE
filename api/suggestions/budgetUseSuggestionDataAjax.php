<?php

session_start();

require_once './../../helpers/ApiIndex.php';
require_once './../../repository/suggestions/BudgetSuggestionDataRepository.php';

$data = new BudgetUseSuggestionData();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['parentId'];
    $budget = $_POST['budget'];
    $result = $data->BudgetUseSuggestionDataBtn($id, $budget);

    if (is_array($result)) {
        $response = [
            'success' => true,
            'data' => $result,
        ];
    } else {
        $response = [
            'success' => false,
            'message' => $result,
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}


?>
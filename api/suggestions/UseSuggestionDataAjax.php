<?php

session_start();

require_once './../../helpers/ApiIndex.php';
require_once './../../repository/suggestions/UseSuggestionData.php';
require_once './../../repository/suggestions/SuggestionDataRepository.php';

$data = new UseSuggestionData();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['parentId'];
    $numberOfCycle = $_POST['numberOfCycle'];
    $is_customized = $_POST['is_customized'];

    $result = $data->UseSuggestionDataBtn($id, $numberOfCycle, $is_customized);

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
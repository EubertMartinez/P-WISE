<?php

session_start();

require_once './../../helpers/ApiIndex.php';
require_once './../../repository/isNew/isNewDataAjax.php';

$data = new isNewDataAjax();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['parentId'];
    $isNew = $_POST['is_new'];

    $result = $data->isNewUser($id, $isNew);

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
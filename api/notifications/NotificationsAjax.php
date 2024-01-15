<?php

session_start();

require_once './../../helpers/ApiIndex.php';
require_once './../../data/notifications/Notification.php';

$data = new Notification();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $account_type = $_POST['account_type'];
    $parent_id = $_POST['parent_id'];

    $result = $data->GetNotifications($id, $account_type, $parent_id);

    $response = [
        'success' => true,
        'data' => $result,
    ];

    header('Content-Type: application/json');
    echo json_encode($response);
}

?>
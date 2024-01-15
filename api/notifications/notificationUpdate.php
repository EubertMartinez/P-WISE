<?php

session_start();

require_once './../../helpers/ApiIndex.php';
require_once './../../data/notifications/Notification.php';

$data = new Notification();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $is_read = ($_POST['is_read'] == 1) ? 0 : 1;

    $result = $data->notificationUpdate($id, $is_read);

    $response = [
        'result' => true,
        'data' => $result,
    ];
    header('Content-Type: application/json');
    echo json_encode($response);
}

?>
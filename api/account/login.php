<?php


require_once './../../helpers/ApiIndex.php';
require_once './../../data/account/LoginData.php';
require_once './../../repository/account/LoginRepository.php';

$data = new LoginRepository();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = $data->Login($username, $password);

    if (count($result) === 0) {
        echo 'Email or password is not correct.';
    } else {
        session_start();
        $_SESSION['account_type'] = $result[0]['account_type'];
        $_SESSION['user_id']      = $result[0]['id'];
        $_SESSION['firstname']    = $result[0]['firstname'];
        $_SESSION['parent_id']    = $result[0]['account_type'] == 3 ? $result[0]['parent_id'] : $result[0]['id'];
        $_SESSION['parent_parent_id']    = $result[0]['account_type'] == 3 ? $result[0]['parent_id'] : null;
        $_SESSION['user_created_time_stamp'] = $result[0]['created'];
        $_SESSION['is_new'] = $result[0]['is_new'];

        echo 'success';
    }
}

?>
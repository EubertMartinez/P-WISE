<?php

session_start();

$user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : 0;
$role_id = isset($_SESSION["account_type"]) ? $_SESSION["account_type"] : 0;

// echo $role_id;

if ($user_id > 0 && $role_id > 0) {
    switch ($role_id) {
        case 1: 
            header("location: admin");
            break;
        case 2: 
            header("location: personal");
            break;
        case 3:
            header("location: member");
            break;
        default:
            header("location: auth/logout");
    }
} else {
    header("location: auth");
}

?>
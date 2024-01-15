<?php 

session_start();

$user_type = isset($_SESSION['account_type']) ? $_SESSION['account_type'] : 0;

if ((int)$user_type > 0) {
    header("location: /");
} else {
    header("location: logout.php");
}

?>
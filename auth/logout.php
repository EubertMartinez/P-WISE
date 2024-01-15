<?php

session_start();
session_destroy();
unset($_SESSION['account_type']);
unset($_SESSION['user_id']);
unset($_SESSION['firstname']);
unset($_SESSION['parent_id']);

header('location: login.php');

?>
<?php

if ($_SESSION['account_type'] != 3) {
    header('location: /auth/logout.php');
}

?>
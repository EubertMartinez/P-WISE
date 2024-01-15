<?php

require_once './../../helpers/ApiIndex.php';
require_once './../../data/member/MemberData.php';
require_once './../../repository/member/MemberRepository.php';

$data = new MemberRepository();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accountType = $_POST['accountType'];
    $parentId    = $_POST['parent_id'];
    $firstname   = $_POST['firstname'];
    $lastname    = $_POST['lastname'];
    $username    = $_POST['username'];
    $mobile      = $_POST['mobile'];
    $email       = $_POST['email'];

    echo $data->AddMember($accountType, $firstname, $lastname, $email, $username, $mobile, $parentId);
}   

?>
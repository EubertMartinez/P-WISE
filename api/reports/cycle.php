<?php

session_start();

require_once './../../helpers/ApiIndex.php';
require_once './../../data/reports/CycleData.php';
require_once './../../repository/reports/CycleRepository.php';

$data = new CycleRepository();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action   = $_POST['action'];
    $parentId = $_SESSION['parent_id'];

    if ($action == 'year') {
        echo $data->GetYearsByParentId($parentId);
    } 
    
    if ($action == 'month') {
        $year = $_POST['year'];
        echo $data->GetMonthsByYearAndParentId($parentId, $year);
    }

    if ($action == 'cycle') {
        $year  = $_POST['year'];
        $month = $_POST['month'];
        echo $data->GetCycleByMonthAndYearAndParentId($parentId, $year, $month);
    }

    if ($action == 'generate') {
        $cycle_id = $_POST['cycle_id'];
        
    }

    
}

?>
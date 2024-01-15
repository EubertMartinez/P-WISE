<?php

class CycleRepository extends ReposittoryHelper {
    private $data;

    public function __construct() {
        $this->data = new CycleData();
    }

    public function GetYearsByParentId($parentId) {
        $result = $this->data->GetYearsByParentId($parentId); 
        return $this->GetDataAsArray($result);
    } 

    public function GetMonthsByYearAndParentId($parentId, $year) {
        $result = $this->data->GetMonthsByYearAndParentId($parentId, $year); 
        return $this->GetDataAsArray($result);
    } 

    public function GetCycleByMonthAndYearAndParentId($parentId, $year, $month) {
        $result = $this->data->GetCycleByMonthAndYearAndParentId($parentId, $year, $month); 
        return $this->GetDataAsArray($result);
    } 

    public function GetCyclesByParentId($parentId) {
        $result = $this->data->GetCyclesByParentId($parentId); 
        return $this->GetDataAsArray($result);
    }

    public function GetCycleMonthsByParentId($parentId) {
        $result = $this->data->GetCycleMonthsByParentId($parentId); 
        return $this->GetDataAsArray($result);
    }

    public function GetCycleYearsByParentId($parentId) {
        $result = $this->data->GetCycleYearsByParentId($parentId); 
        return $this->GetDataAsArray($result);
    }

    public function GetCyclePercentageByCycleId($parentId, $cycleId) {
        $result = $this->data->GetCyclePercentageByCycleId($parentId, $cycleId);
        return $this->GetDataAsArray($result);
    }

    public function GetBudgetsByParentIdAndCycleId($parentId, $cycleId) {
        $result = $this->data->GetBudgetsByParentIdAndCycleId($parentId, $cycleId);
        return $this->GetDataAsArray($result);
    }

    public function GetSavingsByParentIdAndCycleId($parentId, $cycleId) {
        $result = $this->data->GetSavingsByParentIdAndCycleId($parentId, $cycleId); 
        return $this->GetDataAsArray($result);
    }

    public function GetExpensesByParentIdAndCycleId($parentId, $cycleId) {
        $result = $this->data->GetExpensesByParentIdAndCycleId($parentId, $cycleId); 
        return $this->GetDataAsArray($result);
    }
}

?>
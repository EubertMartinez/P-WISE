<?php 

class AmountRepository extends ReposittoryHelper {
    private $data;

    public function __construct() {
        $this->data = new AmountData();
    }

    public function AddAmount($id, $amount, $cycle) {
        $id = $this->data->AddAmount($id, $amount,  $cycle);
        return ($id > 0) ? 'success' : "Unable to add amount to budget account!";
    }

    public function GetTotalBudget($id) {
        $result = $this->data->GetTotalBudget($id); 
        return $this->GetDataAsArray($result);
    }

    public function GetCurrentCycleBudgetLog($parent_id) {
        $result = $this->data->GetCurrentCycleBudgetLog($parent_id); 
        return $this->GetDataAsArray($result);
    }

    public function GetCurrentCycleBudget($parent_id) {
        $result = $this->data->GetCurrentCycleBudget($parent_id); 
        $result = $this->GetDataAsArray($result);

        $budget = 0;

        foreach ($result as $item) {
            $budget = $budget + $item['budget'];
        }

        return $budget;
    }

    public function GetCycleBudgetByMonth($parentId, $month, $year) {
        $result = $this->data->GetCycleBudgetByMonth($parentId, $month, $year); 
        $result = $this->GetDataAsArray($result);
        
        $budget = 0;

        foreach ($result as $item) {
            $budget = $budget + $item['budget'];
        }

        return $budget;
    }

    public function GetCycleBudgetByDateRange($parentId, $start, $end) {
        $result = $this->data->GetCycleBudgetByDateRange($parentId, $start, $end); 
        $result = $this->GetDataAsArray($result);
        
        $budget = 0;

        foreach ($result as $item) {
            $budget = $budget + $item['budget'];
        }

        return $budget;
    }

    public function GetMonthlyBudgetLog($parentId, $month, $year) {
        $result = $this->data->GetMonthlyBudgetLog($parentId, $month, $year); 
        return $this->GetDataAsArray($result);
    }

    public function GetRangeBudgetLog($parentId, $start, $end) {
        $result = $this->data->GetRangeBudgetLog($parentId, $start, $end); 
        return $this->GetDataAsArray($result);
    }

    public function IsCycleStarted($parent_id) {
        $result = $this->data->IsCycleStarted($parent_id); 
        $result = $this->GetDataAsArray($result);
        return count($result) > 0;
    }

    public function StartCycle($parent_id, $id, $start, $end) {
        $result = $this->data->StartCycle($parent_id, $id, $start, $end); 
        return ($id > 0) ? 'success' : "Unable to start cycle!";
    }

    public function GetRemainingDays($parentId) {
        $result = $this->data->GetRemainingDays($parentId); 
        return $this->GetDataAsArray($result);
    }

    public function GetCurrentCycleId($parent_id) {
        $result = $this->data->IsCycleStarted($parent_id); 
        $result = $this->GetDataAsArray($result);
        return count($result) > 0 ? $result[0]['id'] : 0;
    }

    public function GetBudgetLimitsByParentIdAndCycleId($parentId, $cycleId) {
        $result = $this->data->GetBudgetLimitsByParentIdAndCycleId($parentId, $cycleId); 
        return $this->GetDataAsArray($result);
    }

    public function AdjustBudgetLimit($id, $parent, $cycle, $category, $amount, $userId) {
        $result = $this->data->AdjustBudgetLimit($id, $parent, $cycle, $category, $amount, $userId);
        return $result;
    }

}

?>
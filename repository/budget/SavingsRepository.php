<?php 

class SavingsRepository extends ReposittoryHelper {
    private $data;

    public function __construct() {
        $this->data = new SavingsData();
    }

    public function AddAmount($id, $amount, $cycle) {
        $id = $this->data->AddAmount($id, $amount,  $cycle);
        return ($id > 0) ? 'success' : "Unable to add amount to budget account!";
    }

    public function GetTotalBudget($id) {
        $result = $this->data->GetTotalBudget($id); 
        return $this->GetDataAsArray($result);
    }

    public function AddSavings($userId, $amount, $cycle) {
        $result = $this->data->AddSavings($userId, $amount, $cycle) === TRUE ? 'success' : $result;
        return $result;
    }

    public function AjustSavings($userId, $amount, $cycle) {
        $result = $this->data->AjustSavings($userId, $amount, $cycle) === TRUE ? 'success' : $result;
        return $result;
    }

    public function UpdateSavings($id, $amount) {
        $result = $this->data->UpdateSavings($id, $amount) === TRUE ? 'success' : $result;
        return $result;
    } 

    public function GetSavingsByParentId($parentId) {
        $result = $this->data->GetSavingsByParentId($parentId); 
        return $this->GetDataAsArray($result);
    }

    public function GetCycleSavings($cycleId) {
        $result = $this->data->GetCycleSavings($cycleId); 
        return $this->GetDataAsArray($result);
    }

    public function GetAdjustedSavingsByCycleId($cycleId) {
        $result = $this->data->GetAdjustedSavingsByCycleId($cycleId); 
        return $this->GetDataAsArray($result);
    }

    public function GetTotalAdjustedSavingsByParentId($parentId)  {
        $result = $this->data->GetTotalAdjustedSavingsByParentId($parentId); 
        return $this->GetDataAsArray($result);
    }
    
}

?>
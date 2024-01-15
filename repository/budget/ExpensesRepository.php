<?php 

class ExpensesRepository extends ReposittoryHelper {
    private $data;

    public function __construct() {
        $this->data = new ExpensesData();
    }

    public function GetPrioritiesLevel() {
        $result = $this->data->GetPrioritiesLevel(); 
        return $this->GetDataAsArray($result);
    }

    public function GetExpensesCategoryByParentId($parentId, $cycleId) {
        $result = $this->data->GetExpensesCategoryByParentId($parentId, $cycleId); 
        return $this->GetDataAsArray($result);
    }

    public function GetExpensesNameByExpensesParentId($parentId) {
        $result = $this->data->GetExpensesNameByExpensesParentId($parentId); 
        return $this->GetDataAsArray($result);
    }

    public function GetMonthlyExpensesByParentId($parentId) {
        $result = $this->data->GetMonthlyExpensesByParentId($parentId); 
        return $this->GetDataAsArray($result);
    }

    public function AddExpenses($parent_id, $id, $priority, $category, $newCategory, $expenses, $newExpenses, $amount, $cycle_id, $is_overspent) {
        if (!empty($newCategory)) {
            $result = $this->data->GetCategoryByNameAndParentId($parent_id, $newCategory);
            $count  = count($this->GetDataAsArray($result));
            
            if ($count > 0) {
                return "The new category that you are trying to add is already exists. Kindly check on other priority level.";
            } else {
                $category = $this->data->CreateNewCategory($parent_id, $id, $priority, $newCategory);
            }
        }

        if (!empty($newExpenses)) {
            $result = $this->data->GetExpensesByNameAndParentId($parent_id, $newExpenses);
            $count  = count($this->GetDataAsArray($result));
            
            if ($count > 0) {
                return "The new expenses that you are trying to add is already exists. Kindly check on other categories.";
            } else {
                $expenses = $this->data->CreateNewExpenses($parent_id, $id, $category, $newExpenses);
            }
        }

        $result = $this->data->AddExpenses($id, $expenses, $amount, $cycle_id);

        if ($is_overspent == 1) {
            // Perform a SELECT query to get the last inserted ID
            /*$conn = new mysqli("localhost", 'u739235026_pwise', '2j@^I4KXQL', 'u739235026_pwise');*/

            $conn = new mysqli("localhost", 'root', '', 'pwise');


            $selectQuery = "SELECT id FROM expenses WHERE amount = '$amount' AND expenses_name_id = '$expenses' AND cycle_id = '$cycle_id'";
            $resultSelect = $conn->query($selectQuery);

            if ($resultSelect) {
                $row = $resultSelect->fetch_assoc();
                $category_id = $row['id'];
                $sqlOverSpentOverAllBudget = "";
                 // $parent_parent_id = $_SESSION['parent_parent_id'];
                $this->data->OverSpent($id, $category_id, $expenses);
            }
        }

        return $result === TRUE ? 'success' : $result;
    }

    public function AddExpensesName($parent_id, $id, $category, $expenses) {
        $result = $this->data->GetExpensesByNameAndParentId($parent_id, $expenses);
        $count  = count($this->GetDataAsArray($result));
            
        if ($count > 0) {
            return "The expenses name that you are trying to add is already exists.";
        } else {
            return $this->data->CreateNewExpenses($parent_id, $id, $category, $expenses) > 0 ? 'success' : 'Unable to add new expenses name.';
        }
    }

    public function AddExpensesCategory($parent_id, $id, $priority, $category) {
        $result = $this->data->GetCategoryByNameAndParentId($parent_id, $category);
        $count  = count($this->GetDataAsArray($result));
            
        if ($count > 0) {
            return "The category that you are trying to add is already exists.";
        } else {
            return $this->data->CreateNewCategory($parent_id, $id, $priority, $category) > 0 ? 'success' : 'Unable to add new category.';
        }
    }

    public function GetExpensesByParentId($parentId) {
        $result = $this->data->GetExpensesByParentId($parentId); 
        return $this->GetDataAsArray($result);
    }

    public function BudgetLimitPercentageBasedOnOverAllBudget($parentId) {
        $result = $this->data->BudgetLimitPercentageBasedOnOverAllBudget($parentId);
        return $this->GetDataAsArray($result);
    }



    public function GetExpensesDetailsByParentId($parentId) {
        $result = $this->data->GetExpensesDetailsByParentId($parentId); 
        return $this->GetDataAsArray($result);
    }

    public function GetRemainingWalletAmount($parentId) {
        $result = $this->data->GetRemainingWalletAmount($parentId); 
        return $this->GetDataAsArray($result);
    }

    public function GetRecentSavingsAmount($parentId, $month, $year) {
        $result = $this->data->GetRecentSavingsAmount($parentId, $month, $year); 
        return $this->GetDataAsArray($result);
    }

    public function GetTotalSavingsAmount($parentId) {
        $result = $this->data->GetTotalSavingsAmount($parentId); 
        return $this->GetDataAsArray($result);
    }

}

?>
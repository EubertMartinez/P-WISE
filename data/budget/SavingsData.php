<?php

class SavingsData extends DataHelper {
    public function AddSavings($userId, $amount, $cycle) {
        $userId = $this->EscapeString($userId);
        $amount = $this->EscapeString($amount);
        $cycle  = $this->EscapeString($cycle);

        $sql = "INSERT INTO savings(amount, created_by, cycle_id) 
                VALUES ('$amount','$userId', '$cycle')";

        return $this->ExecuteNonQuery($sql);
    }

    public function UpdateSavings($id, $amount) {
        $id     = $this->EscapeString($id);
        $amount = $this->EscapeString($amount);

        $sql = "UPDATE savings
                    SET amount = '$amount'
                    WHERE id = '$id'";

        return $this->ExecuteNonQuery($sql);
    }

    public function AjustSavings($userId, $amount, $cycle) {
        $userId = $this->EscapeString($userId);
        $amount = $this->EscapeString($amount);
        $cycle  = $this->EscapeString($cycle);

        $sql = "INSERT INTO savings(amount, created_by, cycle_id, action, remarks) 
                VALUES ('$amount','$userId', '$cycle', 'ADJUST', 'Savings has been moved to buget')";

        return $this->ExecuteNonQuery($sql);
    }

    public function GetSavingsByParentId($parentId) {
        $parentId = $this->EscapeString($parentId);

        $sql = "SELECT  S.*
                        , CONCAT(U.firstname, ' ', U.lastname) AS user
                    FROM savings S
                    INNER JOIN users U
                        ON S.created_by = U.id
                    WHERE U.id = '$parentId' OR U.parent_id = '$parentId'";

        return $this->SelectData($sql);
    }

    public function GetCycleSavings($cycleId) {
        $cycleId = $this->EscapeString($cycleId);

        $sql = "SELECT IFNULL(SUM(amount), 0) AS cycle_saving
                    FROM savings
                    WHERE cycle_id = '$cycleId'
                        AND action = 'ADD'";

        return $this->SelectData($sql);
    }

    public function GetAdjustedSavingsByCycleId($cycleId) {
        $cycleId = $this->EscapeString($cycleId);

        $sql = "SELECT SUM(amount) AS adjusted_saving
                    FROM savings
                    WHERE cycle_id = '$cycleId'
                        AND action = 'ADJUST'";

        return $this->SelectData($sql);
    }

    public function GetTotalAdjustedSavingsByParentId($parentId) {
        $parentId = $this->EscapeString($parentId);

        $sql = "SELECT SUM(amount) AS adjusted_saving
                    FROM savings S
                    INNER JOIN users U
                        ON U.id = S.created_by
                    WHERE action = 'ADJUST'
                        AND (U.id = '$parentId' OR U.parent_id = '$parentId')";

        return $this->SelectData($sql);
    }
}

?>
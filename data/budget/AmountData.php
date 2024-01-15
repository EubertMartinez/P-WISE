<?php

class AmountData extends DataHelper {
    public function AddAmount($id, $amount, $cycle) {
        $id     = $this->EscapeString($id);
        $amount = $this->EscapeString($amount);
        $cycle  = $this->EscapeString($cycle);

        $sql = "INSERT INTO budgets(amount, created_by, cycle_id) 
                VALUES ('$amount','$id', '$cycle')";
        
        $insertedId = $this->ExecuteNonQueryReturnsInsertedId($sql);

        if ($insertedId == 0)
            return "Unable to add new member";

        $sql = "INSERT INTO activity_logs(type, activity, table_name, table_id, created_by)
                VALUES('ADD BUDGET', ' has been added to the budget.', 'budgets', '$insertedId', '$id')";

        $this->ExecuteNonQuery($sql);

        return $insertedId;
    }

    public function GetTotalBudget($id) {
        $id = $this->EscapeString($id);

        $sql = "SELECT IFNULL(SUM(B.amount), 0.00) AS budget
                    FROM budgets B 
                    INNER JOIN users U 
                        ON U.id = B.created_by
                    WHERE U.id = '$id'
                        OR U.parent_id = '$id'";

        return $this->SelectData($sql);
    }

    public function GetCurrentCycleBudget($parent_id) {
        $parent_id = $this->EscapeString($parent_id);
        $ddate = date('m/d/Y');
        $date  = new DateTime($ddate);
        $month = date("m", strtotime($date->format('Y-m-d')));
        $year  = date("Y", strtotime($date->format('Y-m-d')));

        $sql = "SELECT IFNULL(SUM(B.amount), 0.00) AS budget
                       , CASE
                            WHEN U.parent_id = 0 THEN U.id
                            ELSE U.parent_id
                       END AS parent_id
                    FROM budgets B
                    INNER JOIN cycle C ON C.id = B.cycle_id
                    INNER JOIN users U ON U.id = B.created_by
                    WHERE (DATE(CURRENT_DATE()) BETWEEN DATE(C.start) AND DATE(C.end))
                        AND (
                                U.id = '$parent_id'
                                OR U.parent_id = '$parent_id'
                            )
                    GROUP BY B.id";

        return $this->SelectData($sql);
    }

    public function GetCycleBudgetByMonth($parentId, $month, $year) {
        $parent_id = $this->EscapeString($parentId);
        $month     = $this->EscapeString($month);
        $year      = $this->EscapeString($year);

        $sql = "SELECT IFNULL(SUM(B.amount), 0.00) AS budget
                       , CASE
                            WHEN U.parent_id = 0 THEN U.id
                            ELSE U.parent_id
                       END AS parent_id
                    FROM budgets B
                    INNER JOIN users U ON U.id = B.created_by
                    WHERE MONTH(DATE(B.created)) = '$month'
                        AND YEAR(DATE(B.created)) = '$year'
                        AND (
                                U.id = '$parent_id'
                                OR U.parent_id = '$parent_id'
                            )
                    GROUP BY B.id";

        return $this->SelectData($sql);
    }

    public function GetCycleBudgetByDateRange($parentId, $start, $end) {
        $parentId = $this->EscapeString($parentId);
        $start    = $this->EscapeString($start);
        $end      = $this->EscapeString($end);

        $sql = "SELECT IFNULL(SUM(B.amount), 0.00) AS budget
                       , CASE
                            WHEN U.parent_id = 0 THEN U.id
                            ELSE U.parent_id
                       END AS parent_id
                    FROM budgets B
                    INNER JOIN users U ON U.id = B.created_by
                    WHERE (B.created BETWEEN DATE('$start') AND DATE('$end')) 
                        AND (
                                U.id = '$parentId'
                                OR U.parent_id = '$parentId'
                            )
                    GROUP BY B.id";

        return $this->SelectData($sql);
    }

    public function GetCurrentCycleBudgetLog($parent_id) {
        $parent_id = $this->EscapeString($parent_id);

        $sql = "SELECT  B.id
                        , B.amount
                        , B.created
                        , CONCAT(U.firstname, ' ', U.lastname) AS name
                    FROM budgets B
                    INNER JOIN cycle C ON C.id = B.cycle_id
                    INNER JOIN users U ON U.id = B.created_by
                    WHERE (DATE(CURRENT_DATE()) BETWEEN DATE(C.start) AND DATE(C.end))
                            AND (
                            U.id = '$parent_id'
                            OR U.parent_id = '$parent_id'
                        )
                    ORDER BY B.id";

        return $this->SelectData($sql);
    }

    public function GetMonthlyBudgetLog($parentId, $month, $year) {
        $parent_id = $this->EscapeString($parentId);
        $month     = $this->EscapeString($month);
        $year      = $this->EscapeString($year);

        $sql = "SELECT B.id
                       , B.amount
                       , B.created
                       , CONCAT(U.firstname, ' ', U.lastname) AS name
                    FROM budgets B
                    INNER JOIN users U 
                        ON U.id = B.created_by
                    WHERE MONTH(DATE(B.created)) = '$month'
                        AND YEAR(DATE(B.created)) = '$year'
                            AND (
                            U.id = '$parent_id'
                            OR U.parent_id = '$parent_id'
                        )
                    ORDER BY B.id";

        return $this->SelectData($sql);
    }

    public function GetRangeBudgetLog($parentId, $start, $end) {
        $parentId = $this->EscapeString($parentId);
        $start    = $this->EscapeString($start);
        $end      = $this->EscapeString($end);

        $sql = "SELECT B.id
                       , B.amount
                       , B.created
                       , CONCAT(U.firstname, ' ', U.lastname) AS name
                    FROM budgets B
                    INNER JOIN users U 
                        ON U.id = B.created_by
                    WHERE (DATE(B.created) BETWEEN DATE('$start') AND DATE('$end'))
                            AND (
                            U.id = '$parentId'
                            OR U.parent_id = '$parentId'
                        )
                    ORDER BY B.id";

        return $this->SelectData($sql);
    }

    public function IsCycleStarted($parent_id) {
        $parent_id = $this->EscapeString($parent_id);

        $sql = "SELECT *
                    FROM cycle
                    WHERE DATE(CURRENT_DATE()) BETWEEN DATE(start) AND DATE(end)
                        AND parent_id = '$parent_id'";

        return $this->SelectData($sql);
    }

    public function StartCycle($parent_id, $id, $start, $end) {
        $parent_id = $this->EscapeString($parent_id);
        $id = $this->EscapeString($id);
        $start = $this->EscapeString($start);
        $end = $this->EscapeString($end);

        $ddate = date('m/d/Y');
        $date  = new DateTime($ddate);
        $month = date("F", strtotime($date->format('Y-m-d')));
        $year  = date("Y", strtotime($date->format('Y-m-d')));

        $sql = "INSERT INTO cycle(month, year, start, end, parent_id)
                VALUES('$month', '$year', '$start', '$end', '$parent_id')";

        $insertedId = $this->ExecuteNonQueryReturnsInsertedId($sql);

        if ($insertedId == 0)
            return "Unable to start cycle";

        $sql = "INSERT INTO activity_logs(type, activity, table_name, table_id, created_by)
                VALUES('CYCLE STARTED', ' has been a new cycle for the month of $month $year.', 'cycle', '$insertedId', '$id')";

        $this->ExecuteNonQuery($sql);

        return $insertedId;
    }

    public function GetRemainingDays($parentId) {
        $parentId = $this->EscapeString($parentId);

        $sql = "SELECT start
                       , end
                       , (
                            SELECT DATEDIFF(DATE(end), CURRENT_DATE()) + 1
                         ) AS remaining_days
                    FROM cycle
                    WHERE parent_id = '$parentId'
                    ORDER BY id DESC
                    LIMIT 1;";

        return $this->SelectData($sql);
    }

    public function GetBudgetLimitsByParentIdAndCycleId($parentId, $cycleId) {
        $parentId = $this->EscapeString($parentId);
        $cycleId  = $this->EscapeString($cycleId);

        $sql = "SELECT 	EC.id
                        , EC.name
                        , IFNULL((
                            SELECT SUM(E.amount)
                                FROM expenses E 
                                INNER JOIN expenses_name EN 
                                    ON E.expenses_name_id = EN.id
                                WHERE EC.id = EN.expenses_category_id
                                    AND E.cycle_id = '$cycleId'
                        ), 0) AS expenses
                        , IFNULL((
                            SELECT BL.amount
                                FROM budget_limit BL
                                WHERE BL.cycle_id = '$cycleId'
                                    AND BL.expensese_category_id = EC.id
                        ), 0) AS limits
                        , IFNULL((
                            SELECT BL.id
                                FROM budget_limit BL
                                WHERE BL.cycle_id = '$cycleId'
                                    AND BL.expensese_category_id = EC.id
                        ), 0) AS limit_id
                    FROM expenses_categories EC
                    WHERE EC.parent_id = '$parentId';";

        return $this->SelectData($sql);
    }

    public function AdjustBudgetLimit($id, $parent, $cycle, $category, $amount, $userId) {
        $id       = $this->EscapeString($id);
        $parent   = $this->EscapeString($parent);
        $cycle    = $this->EscapeString($cycle);
        $category = $this->EscapeString($category);
        $amount   = $this->EscapeString($amount);
        $userId   = $this->EscapeString($userId);

        $sql = ($id == 0) 
             ? "INSERT INTO budget_limit(expensese_category_id, cycle_id, amount, created_by)
                VALUES ('$category', '$cycle', '$amount', '$userId')"
             : "UPDATE budget_limit
                    SET amount = '$amount'
                    WHERE id = '$id'";

        return $this->ExecuteNonQuery($sql) === TRUE ? 'success' : 'Unable to adjust budget.';
    }
}

?>
<?php

class CycleData extends DataHelper {

    public function GetYearsByParentId($parentId) {
        $parentId = $this->EscapeString($parentId);

        $sql = "SELECT DISTINCT YEAR(created) AS year 
                    FROM cycle 
                    WHERE parent_id='$parentId'";

        return $this->SelectData($sql);
    } 

    public function GetMonthsByYearAndParentId($parentId, $year) {
        $parentId = $this->EscapeString($parentId);
        $year     = $this->EscapeString($year);

        $sql = "SELECT DISTINCT MONTH(created) AS year 
                    FROM cycle 
                    WHERE parent_id='$parentId'
                        AND YEAR(created)='$year'";
                    
        return $this->SelectData($sql);
    } 

    public function GetCycleByMonthAndYearAndParentId($parentId, $year, $month) {
        $parentId = $this->EscapeString($parentId);
        $year     = $this->EscapeString($year);
        $month    = $this->EscapeString($month);

        $sql = "SELECT 	id
                        , YEAR(created) AS year
                        , MONTH(created) AS month
                        , start
                        , end
                    FROM cycle
                    WHERE parent_id='$parentId'
                        AND (
                                (
                                    MONTH(start)='$month'
                                    AND YEAR(start)='$year'
                                ) OR (
                                    MONTH(end)='$month'
                                    AND YEAR(end)='$year'
                                )
                            )";
                    
        return $this->SelectData($sql);
    } 

    public function GetCyclesByParentId($parentId) {
        $parentId = $this->EscapeString($parentId);

        $sql = "SELECT 	id
                        , YEAR(start)  AS start_year
                        , MONTH(start) AS start_month
                        , YEAR(end)    AS end_year
                        , MONTH(end)   AS end_month
                        , start
                        , end
                    FROM cycle
                    WHERE parent_id='$parentId'";

        return $this->SelectData($sql);
    }

    public function GetCycleMonthsByParentId($parentId) {
        $parentId = $this->EscapeString($parentId);

        $sql = "SELECT DISTINCT MONTH(start) as month
                    FROM cycle
                    WHERE parent_id='$parentId'
                UNION
                SELECT DISTINCT MONTH(end) as month
                    FROM cycle
                    WHERE parent_id='$parentId'";

        return $this->SelectData($sql);
    }

    public function GetCycleYearsByParentId($parentId) {
        $parentId = $this->EscapeString($parentId);
        
        $sql = "SELECT DISTINCT YEAR(start) as year
                    FROM cycle
                    WHERE parent_id='$parentId'
                UNION
                SELECT DISTINCT YEAR(end) as year
                    FROM cycle
                    WHERE parent_id='$parentId'";

        return $this->SelectData($sql);
    }

    public function GetCyclePercentageByCycleId($parentId, $cycleId) {
        $cycleId = $this->EscapeString($cycleId);
        $sql = "SELECT  EC.id
                        , EC.priority_level_id
                        , EC.name AS category
                        , (
                            SELECT SUM(E.amount)
                                FROM expenses E 
                            INNER JOIN expenses_name EN ON E.expenses_name_id = EN.id
                            INNER JOIN cycle C ON C.id = E.cycle_id
                                WHERE E.cycle_id = '$cycleId'
                                    AND EN.expenses_category_id = EC.id
                        ) AS amount
                        , (
                            (
                                (
                                    SELECT SUM(E.amount)
                                        FROM expenses E 
                                        INNER JOIN expenses_name EN ON E.expenses_name_id = EN.id
                                        WHERE E.cycle_id = '$cycleId'
                                            AND EN.expenses_category_id = EC.id
                                ) / (
                                        SELECT SUM(amount)
                                            FROM budgets B
                                            INNER JOIN users U ON U.id = B.created_by
                                            WHERE B.cycle_id = '$cycleId'
                                )
                            ) * 100
                        ) AS percentage
                    FROM expenses_categories EC WHERE parent_id = '$parentId'";

        return $this->SelectData($sql);
    }

    public function GetBudgetsByParentIdAndCycleId($parentId, $cycleId) {
        $parentId = $this->EscapeString($parentId);
        $cycleId  = $this->EscapeString($cycleId);

        $sql = "SELECT  U.* 
                        , ACT.name AS role
                        , IFNULL((
                            SELECT SUM(amount)
                                FROM budgets B 
                                WHERE B.created_by = U.id
                                    AND B.cycle_id = '$cycleId'
                        ), 0.00) AS amount
                    FROM users U
                    INNER JOIN account_type ACT
                        ON ACT.id = U.account_type
                    WHERE U.id='$parentId' OR U.parent_id = '$parentId'";

        return $this->SelectData($sql);
    }

    public function GetSavingsByParentIdAndCycleId($parentId, $cycleId) {
        $parentId = $this->EscapeString($parentId);
        $cycleId  = $this->EscapeString($cycleId);

        $sql = "SELECT  U.* 
                        , ACT.name AS role
                        , IFNULL((
                            SELECT SUM(amount)
                                FROM savings S 
                                WHERE S.created_by = U.id
                                    AND S.cycle_id = '$cycleId'
                                    AND S.action = 'ADD'
                        ), 0.00) AS amount
                    FROM users U
                    INNER JOIN account_type ACT
                        ON ACT.id = U.account_type
                    WHERE U.id='$parentId' OR U.parent_id = '$parentId'";

        return $this->SelectData($sql);
    }

    public function GetExpensesByParentIdAndCycleId($parentId, $cycleId) {
        $parentId = $this->EscapeString($parentId);
        $cycleId  = $this->EscapeString($cycleId);

        $sql = "SELECT  E.id
                        , E.amount
                        , E.created
                        , EN.name AS expenses 
                        , EC.name AS category 
                        , PL.name AS priority
                        , CONCAT(U.firstname, ' ', U.lastname) AS user
                        , EC.id AS category_id
                    FROM expenses E 
                    INNER JOIN expenses_name EN ON EN.id = E.expenses_name_id
                    INNER JOIN expenses_categories EC ON EC.id = EN.expenses_category_id
                    INNER JOIN priority_level PL ON PL.id = EC.priority_level_id
                    INNER JOIN users U ON E.created_by = U.id
                    INNER JOIN cycle C ON C.id = E.cycle_id
                    WHERE (U.id = '$parentId' OR U.parent_id = '$parentId')
                        AND E.cycle_id = '$cycleId'
                    ORDER BY E.id DESC";

            return $this->SelectData($sql);
    }
}

?>
<?php

date_default_timezone_set('Asia/Manila');
class ExpensesData extends DataHelper {
    public function GetPrioritiesLevel() {
        $sql = "SELECT * FROM priority_level";
        return $this->SelectData($sql);
    }

    public function GetExpensesCategoryByParentId($parentId, $cycleId) {
        $parentId = $this->EscapeString($parentId);
        $cycleId  = $this->EscapeString($cycleId);

        $sql = "SELECT  EC.* 
                        , CONCAT(U.firstname, ' ', U.lastname) as user
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
                    INNER JOIN users U
                        ON EC.created_by = U.id
                    WHERE EC.parent_id = '$parentId'";
        return $this->SelectData($sql);
    }

    public function GetExpensesNameByExpensesParentId($parentId) {
        $parentId = $this->EscapeString($parentId);

        $sql = "SELECT  EN.* 
                        , CONCAT(U.firstname, ' ', U.lastname) as user
                        , EC.name AS category
                    FROM expenses_name EN
                    INNER JOIN expenses_categories EC 
                        ON EN.expenses_category_id = EC.id
                    INNER JOIN users U
                        ON EN.created_by = U.id
                    WHERE EC.parent_id = '$parentId'";
        return $this->SelectData($sql);
    }

    public function GetMonthlyExpensesByParentId($parentId) {
        $parentId = $this->EscapeString($parentId);
        $ddate = date('m/d/Y');
        $date  = new DateTime($ddate);
        $month = date("m", strtotime($date->format('Y-m-d')));
        $year  = date("Y", strtotime($date->format('Y-m-d')));

        $sql = "SELECT E.*
                       , EN.name AS expenses
                       , EC.name AS category
                       , PL.name AS priority
                       , CONCAT(U.firstname, ' ', U.lastname) AS name
                    FROM expenses E
                    INNER JOIN expenses_name EN ON EN.id = E.expenses_name_id
                    INNER JOIN expenses_categories EC ON EN.expenses_category_id = EC.id
                    INNER JOIN priority_level PL ON PL.id = EC.priority_level_id
                    INNER JOIN users U ON U.id = E.created_by
                    WHERE MONTH(DATE(E.created)) = '$month'
                        AND YEAR(DATE(E.created)) = '$year'
                        AND (
                                U.parent_id = '$parentId'
                                OR U.id = '$parentId'

                            )";
        return $this->SelectData($sql);
    }

    public function GetCategoryByNameAndParentId($parent_id, $newCategory) {
        $parent_id   = $this->EscapeString($parent_id);
        $newCategory = $this->EscapeString($newCategory);

        $sql = "SELECT * 
                    FROM expenses_categories
                    WHERE name = '$newCategory'
                        AND parent_id = '$parent_id'";

        return $this->SelectData($sql);
    }

    public function CreateNewCategory($parent_id, $id, $priority, $newCategory) {
        $parent_id   = $this->EscapeString($parent_id);
        $id          = $this->EscapeString($id);
        $priority    = $this->EscapeString($priority);
        $newCategory = $this->EscapeString($newCategory);

        $sql = "INSERT INTO expenses_categories(name, priority_level_id, parent_id, created_by)
                VALUES ('$newCategory', '$priority', '$parent_id', '$id')";

        return $this->ExecuteNonQueryReturnsInsertedId($sql);
    }

    public function GetExpensesByNameAndParentId($parent_id, $newExpenses) {
        $parent_id   = $this->EscapeString($parent_id);
        $newExpenses = $this->EscapeString($newExpenses);

        $sql = "SELECT * 
                    FROM expenses_name EN
                    INNER JOIN expenses_categories EC ON EC.id = EN.expenses_category_id
                    WHERE EN.name = '$newExpenses'
                        AND EC.parent_id = '$parent_id'";

        return $this->SelectData($sql);
    }

    public function CreateNewExpenses($parent_id, $id, $category, $newExpenses) {
        $parent_id   = $this->EscapeString($parent_id);
        $id          = $this->EscapeString($id);
        $category    = $this->EscapeString($category);
        $newExpenses = $this->EscapeString($newExpenses);

        $sql = "INSERT INTO expenses_name(name, expenses_category_id, created_by)
                VALUES ('$newExpenses', '$category', '$id')";

        return $this->ExecuteNonQueryReturnsInsertedId($sql);
    }

    public function OverSpent($id, $category_id, $expenses_name_id) {
        $id = $this->EscapeString($id);
        $category_id = $this->EscapeString($category_id);
        $expenses_name_id = $this->EscapeString($expenses_name_id);
        // $parent_parent_id = $this->EscapeString($parent_parent_id);
        $account_type =  $_SESSION['account_type'];
        // $currentDateTime = date('Y-m-d H:i:s');
        $sql = "";
        date_default_timezone_set('Asia/Manila');
        $dateTimeToday = date('Y-m-d H:i:s', time());
        $ddate = date('m/d/Y');
        $date  = new DateTime($ddate);
        $dateToday  = date("Y-m-d H:i:s", strtotime($date->format('Y-m-d')));

        switch ($account_type) {
            case 1:
                // If the user is a household (account_type = 1)
                // Notify household members and household
                $sql = "INSERT INTO notifications (user_id, sender_id, account_type, notification_type, cycle_id, expenses_id, expenses_name_id, is_read, created_at)
                        SELECT
                            users.id AS user_id, -- The user ID of household members
                            '$id' AS sender_id, -- The user ID of the household generating the overspending notification
                            users.account_type AS account_type, -- User type for household
                            'overspent' AS notification_type, -- 1 for overspending
                            0 AS cycle_id, -- The relevant financial cycle ID
                            '$category_id' AS expenses_id,
                            '$expenses_name_id' AS expenses_name_id,
                            FALSE AS is_read, -- Notification is initially unread
                            '$dateTimeToday' AS created_at
                        FROM users
                        WHERE
                            users.id = '$id' OR users.parent_id = '$id'";
                break;

            case 2:
                // If the user is a household (account_type = 2)
                // Notify itself
                $sql = "INSERT INTO notifications (user_id, sender_id, account_type, notification_type, cycle_id, expenses_id, expenses_name_id, is_read, created_at)
                        SELECT
                            users.id AS user_id, -- The user ID of household members
                            '$id' AS sender_id, -- The user ID of the household generating the overspending notification
                            users.account_type AS account_type, -- User type for household
                            'overspent' AS notification_type, -- 1 for overspending
                            0 AS cycle_id, -- The relevant financial cycle ID
                            '$category_id' AS expenses_id,
                            '$expenses_name_id' AS expenses_name_id,
                            FALSE AS is_read, -- Notification is initially unread
                            '$dateTimeToday' AS created_at
                        FROM users
                        WHERE
                            users.id = '$id'";
                break;

            case 3:
                // If the user is a member (account_type = 3)
                // Notify co-members and household
                $sql = "INSERT INTO notifications (user_id, sender_id, account_type, notification_type, cycle_id, expenses_id, expenses_name_id, is_read, created_at)
                        SELECT
                            users.id AS user_id, -- The user ID of household members and co-members
                            '$id' AS sender_id, -- The user ID of the member generating the overspending notification
                            users.account_type AS account_type, -- User type for member
                            'overspent' AS notification_type, -- 1 for overspending
                            0 AS cycle_id, -- The relevant financial cycle ID
                            '$category_id' AS expenses_id,
                            '$expenses_name_id' AS expenses_name_id,
                            FALSE AS is_read, -- Notification is initially unread
                            '$dateTimeToday' AS created_at
                        FROM users
                        WHERE
                            users.id = '$id' -- For the member himself
                            OR users.parent_id = (SELECT parent_id FROM users WHERE id = '$id') -- For co-members of the household
                            OR (users.account_type = 1 AND users.id = (SELECT parent_id FROM users WHERE id = '$id')); -- For the household itself
                        "; // For the member
                break;
            default:
                // Other cases can be handled here
                break;
        }
        $this->ExecuteNonQuery($sql);

    }

    public function AddExpenses($id, $expenses, $amount, $cycle_id ) {
        $id        = $this->EscapeString($id);
        $expenses  = $this->EscapeString($expenses);
        $amount    = $this->EscapeString($amount);
        $cycle_id  = $this->EscapeString($cycle_id);

        $sql = "INSERT INTO expenses(amount, expenses_name_id, created_by, cycle_id)
                VALUES ('$amount', '$expenses', '$id', '$cycle_id')";


        return $this->ExecuteNonQuery($sql);
    }

    public function GetExpensesByParentId($parentId) {
        $parentId = $this->EscapeString($parentId);

        $sql = "SELECT  EC.id
                        , EC.priority_level_id
                        , EC.name AS category
                        , (
                            SELECT SUM(E.amount)
                                FROM expenses E 
                            INNER JOIN expenses_name EN ON E.expenses_name_id = EN.id
                            INNER JOIN cycle C ON C.id = E.cycle_id
                                WHERE (DATE(CURRENT_DATE) BETWEEN DATE(C.start) AND DATE(C.end))
                                    AND EN.expenses_category_id = EC.id
                        ) AS amount
                        , (
                            (
                                (
                                    SELECT SUM(E.amount)
                                        FROM expenses E 
                                        INNER JOIN expenses_name EN ON E.expenses_name_id = EN.id
                                        INNER JOIN cycle C ON C.id = E.cycle_id
                                        WHERE (DATE(CURRENT_DATE()) BETWEEN DATE(C.start) AND DATE(C.end))
                                            AND EN.expenses_category_id = EC.id
                                ) / (
                                    SELECT SUM(B.amount + COALESCE(S.amount, 0))
                                        FROM budgets B
                                        INNER JOIN users U ON U.id = B.created_by
                                        INNER JOIN cycle C ON C.id = B.cycle_id
                                        LEFT JOIN savings S ON S.cycle_id = B.cycle_id
                                        WHERE (DATE(CURRENT_DATE()) BETWEEN DATE(C.start) AND DATE(C.end))
                                            AND (U.id = '$parentId' OR U.parent_id = '$parentId')
                                )
                            ) * 100
                        ) AS percentage
                    FROM expenses_categories EC
                    WHERE EC.parent_id = '$parentId'";

        return $this->SelectData($sql);
    }

    public function BudgetLimitPercentageBasedOnOverAllBudget($parentId)
    {
        $parentId = $this->EscapeString($parentId);

        $sql = "SELECT
                    EC.name AS category,
                    CASE
                        WHEN budget_limit.amount > 0 THEN (budget_limit.amount / overall_budget.total_amount) * 100
                        ELSE 0 -- Handle division by zero
                    END AS budget_limit_percentage_over_overall_budget
                FROM
                    budgets B
                INNER JOIN cycle C ON C.id = B.cycle_id
                INNER JOIN (
                    SELECT
                        B.cycle_id,
                        SUM(B.amount) + COALESCE(SUM(S.amount), 0) AS total_amount
                    FROM budgets B
                    LEFT JOIN savings S ON S.cycle_id = B.cycle_id
                    GROUP BY B.cycle_id
                ) AS overall_budget ON overall_budget.cycle_id = B.cycle_id
                INNER JOIN budget_limit ON budget_limit.cycle_id = B.cycle_id
                INNER JOIN users U ON B.created_by = U.id
                INNER JOIN expenses_categories EC ON EC.id = budget_limit.expensese_category_id
                WHERE
                    (U.id = '$parentId' OR U.parent_id = '$parentId')
                    AND (DATE(CURRENT_DATE()) BETWEEN DATE(C.start) AND DATE(C.end))
                GROUP BY EC.name, budget_limit_percentage_over_overall_budget;";

        return $this->SelectData($sql);
    }


    public function GetExpensesDetailsByParentId($parentId) {
        $parentId = $this->EscapeString($parentId);

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
                        AND (DATE(CURRENT_DATE()) BETWEEN DATE(C.start) AND DATE(C.end))
                    ORDER BY E.id DESC;";

        return $this->SelectData($sql);
    }

    /*public function GetRemainingWalletAmount($parentId) {
        $parentId = $this->EscapeString($parentId);
        $sql = "SELECT (
                    IFNULL((
                        SELECT SUM(B.amount + COALESCE(S.amount, 0))
                            FROM budgets B
                            INNER JOIN users U ON U.id = B.created_by
                            INNER JOIN cycle C ON C.id = B.cycle_id
                            LEFT JOIN savings S ON S.cycle_id = B.cycle_id
                            WHERE (DATE(CURRENT_DATE()) BETWEEN DATE(C.start) AND DATE(C.end))
                                AND (U.id = '$parentId' OR U.parent_id = '$parentId')
                    ), 0.00) - IFNULL((
                        SELECT SUM(E.amount)
                        FROM expenses E 
                        INNER JOIN expenses_name EN ON EN.id = E.expenses_name_id
                        INNER JOIN expenses_categories EC ON EC.id = EN.expenses_category_id
                        INNER JOIN cycle C ON C.id = E.cycle_id
                        WHERE EC.parent_id = '$parentId'
                            AND DATE(CURRENT_DATE()) BETWEEN DATE(C.start) AND DATE(C.end)
                    ), 0.00)
                ) AS wallet;";

        return $this->SelectData($sql);
    }*/

    public function GetRemainingWalletAmount($parentId) {
        $parentId = $this->EscapeString($parentId);
        $sql = "SELECT (
                    IFNULL((
                        SELECT SUM(B.amount)
                            FROM budgets B 
                            INNER JOIN users U ON U.id = B.created_by
                            INNER JOIN cycle C ON C.id = B.cycle_id
                            WHERE (U.parent_id = '$parentId' OR U.id = '$parentId')
                                AND DATE(CURRENT_DATE()) BETWEEN DATE(C.start) AND DATE(C.end)
                    ), 0.00) - IFNULL((
                        SELECT SUM(E.amount)
                        FROM expenses E 
                        INNER JOIN expenses_name EN ON EN.id = E.expenses_name_id
                        INNER JOIN expenses_categories EC ON EC.id = EN.expenses_category_id
                        INNER JOIN cycle C ON C.id = E.cycle_id
                        WHERE EC.parent_id = '$parentId'
                            AND DATE(CURRENT_DATE()) BETWEEN DATE(C.start) AND DATE(C.end)
                    ), 0.00)
                ) AS wallet;";

        return $this->SelectData($sql);
    }

    public function GetRecentSavingsAmount($parentId, $month, $year) {
        $parentId = $this->EscapeString($parentId);
        $sql = "SELECT id
                       , (
                            IFNULL((
                                SELECT SUM(B.amount)
                                    FROM budgets B 
                                    INNER JOIN users U ON U.id = B.created_by
                                    WHERE (U.parent_id = '$parentId' OR U.id = '$parentId')
                                        AND B.cycle_id = C.id
                                        
                            ), 0.00) - IFNULL((
                                SELECT SUM(E.amount)
                                FROM expenses E 
                                INNER JOIN expenses_name EN ON EN.id = E.expenses_name_id
                                INNER JOIN expenses_categories EC ON EC.id = EN.expenses_category_id
                                WHERE EC.parent_id = '$parentId'
                                    AND E.cycle_id = C.id
                            ), 0.00)
                            +
                            IFNULL((
                                SELECT SUM(S.amount)
                                FROM savings S
                                WHERE S.cycle_id = C.id
                            ), 0.00)
                       ) AS savings
                    FROM cycle C
                    WHERE NOT (DATE(CURRENT_DATE()) BETWEEN DATE(C.start) AND DATE(C.end))
          	        ORDER BY C.id DESC
          	        LIMIT 1;";

        return $this->SelectData($sql);
    }

    /*public function GetTotalSavingsAmount($parentId) {
        $parentId = $this->EscapeString($parentId);
        $user_id = $this->EscapeString($_SESSION['user_id']);
        $sql = "SELECT (
                    (
                        (
                            IFNULL((
                                SELECT SUM(B.amount)
                                    FROM budgets B
                                    INNER JOIN users U ON U.id = B.created_by
                                    INNER JOIN cycle C ON B.cycle_id = C.id
                                    WHERE (U.parent_id = '$parentId' OR U.id = '$parentId')
                                        AND NOT (DATE(CURRENT_DATE()) BETWEEN DATE(C.start) AND DATE(C.end))
                            ), 0.00) - IFNULL((
                                SELECT SUM(E.amount)
                                    FROM expenses E
                                    INNER JOIN expenses_name EN ON EN.id = E.expenses_name_id
                                    INNER JOIN expenses_categories EC ON EC.id = EN.expenses_category_id
                                    INNER JOIN cycle C ON C.id = E.cycle_id
                                    WHERE EC.parent_id = '$parentId'
                                        AND NOT (DATE(CURRENT_DATE()) BETWEEN DATE(C.start) AND DATE(C.end))
                            ), 0.00)
                        ) + IFNULL(
                            (
                                SELECT SUM(amount)
                                    FROM savings
                                        WHERE action = 'ADD' AND created_by = '$parentId' OR created_by = '$user_id'
                            ), 0.00)
                    ) - IFNULL((SELECT SUM(amount)
                                FROM savings
                                    WHERE action = 'ADJUST' AND created_by = '$parentId')
                    , 0.00)
                ) AS savings";

        return $this->SelectData($sql);
    }*/
    public function GetTotalSavingsAmount($parentId) {
        $parentId = $this->EscapeString($parentId);
        $sql = "SELECT (
                    (
                        (
                            IFNULL((
                                SELECT SUM(B.amount)
                                    FROM budgets B 
                                    INNER JOIN users U ON U.id = B.created_by
                                    INNER JOIN cycle C ON B.cycle_id = C.id
                                    WHERE (U.parent_id = '$parentId' OR U.id = '$parentId')
                                        AND NOT (DATE(CURRENT_DATE()) BETWEEN DATE(C.start) AND DATE(C.end))
                            ), 0.00) - IFNULL((
                                SELECT SUM(E.amount)
                                    FROM expenses E 
                                    INNER JOIN expenses_name EN ON EN.id = E.expenses_name_id
                                    INNER JOIN expenses_categories EC ON EC.id = EN.expenses_category_id
                                    INNER JOIN cycle C ON C.id = E.cycle_id
                                    WHERE EC.parent_id = '$parentId'
                                        AND NOT (DATE(CURRENT_DATE()) BETWEEN DATE(C.start) AND DATE(C.end))
                            ), 0.00)
                        ) + IFNULL(
                            (
                                SELECT SUM(amount)
                                    FROM savings
                                        WHERE action = 'ADD' AND created_by = '$parentId'
                            ), 0.00)
                    ) - IFNULL((SELECT SUM(amount)
                                FROM savings
                                    WHERE action = 'ADJUST' AND created_by = '$parentId')
                    , 0.00)
                ) AS savings";

        return $this->SelectData($sql);
    }
}

?>
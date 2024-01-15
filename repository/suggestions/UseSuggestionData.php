<?php

class UseSuggestionData extends DataHelper {

    public function UseSuggestionDataBtn($parentId, $numberOfCycle) {
        $parentId = $this->EscapeString($parentId);
        $numberOfCycle = $this->EscapeString($numberOfCycle);

        return $this->fetchSuggestions($parentId, $numberOfCycle);
    }

    private function fetchSuggestions($parentId, $numberOfCycle) {
        $parentId = $this->EscapeString($parentId);
        $numberOfCycle = $this->EscapeString($numberOfCycle);

        $sqlActiveTotalBudget = "SELECT
                            c.id AS cycle_id,
                            COALESCE(SUM(b.amount), 0) + COALESCE(SUM(s.amount), 0) AS active_total_budget
                        FROM cycle c
                        LEFT JOIN budgets b ON c.id = b.cycle_id
                        LEFT JOIN savings s ON s.cycle_id = c.id
                        WHERE parent_id = '$parentId' AND c.end > NOW()  -- Check for active cycles
                        ORDER BY c.end DESC
                        LIMIT 1;";
        $resultBudget = $this->SelectData($sqlActiveTotalBudget);
        $rowBudget = $resultBudget->fetch_assoc();

        $sql = "SELECT
                ROUND(SUM(high) / SUM(expenses) * 100, 2) AS high_percentage,
                ROUND(SUM(medium) / SUM(expenses) * 100, 2) AS medium_percentage,
                ROUND(SUM(low) / SUM(expenses) * 100, 2) AS low_percentage
            FROM (
                SELECT
                    COALESCE(SUM(CASE WHEN priority_level.name = 'High Priority' THEN expenses.amount ELSE 0 END), 0) AS high,
                    COALESCE(SUM(CASE WHEN priority_level.name = 'Medium Priority' THEN expenses.amount ELSE 0 END), 0) AS medium,
                    COALESCE(SUM(CASE WHEN priority_level.name = 'Low Priority' THEN expenses.amount ELSE 0 END), 0) AS low,
                    COALESCE(SUM(expenses.amount), 0) AS expenses
                FROM cycle
                INNER JOIN expenses ON cycle.id = expenses.cycle_id
                INNER JOIN expenses_name ON expenses_name.id = expenses.expenses_name_id
                INNER JOIN expenses_categories ON expenses_categories.id = expenses_name.expenses_category_id
                INNER JOIN priority_level ON priority_level.id = expenses_categories.priority_level_id
                
                WHERE cycle.parent_id = '$parentId' AND cycle.end < NOW()
                GROUP BY cycle.id
                ORDER BY cycle.end DESC
                LIMIT $numberOfCycle
            ) AS combined_data";

        $suggestions = [];
        $result = $this->SelectData($sql);

        // Fetch the first row
        $row = $result->fetch_assoc();

        // Check if the row exists
        if ($row) {
            $totalBudget = floatval($rowBudget['active_total_budget']);
            $highBudget = (floatval($row['high_percentage']) / 100) * $totalBudget;
            $mediumBudget = (floatval($row['medium_percentage']) / 100) * $totalBudget;
            $lowBudget = (floatval($row['low_percentage']) / 100) * $totalBudget;

            $getCategoryLimits = "
                SELECT
                    ec.id AS category_limit_id,
                    ec.name AS category_limit_name,
                    ec.priority_level_id AS category_limit_priority_level_id,
                    (
                        SELECT c.id
                        FROM cycle c
                        WHERE c.parent_id = '$parentId'
                        ORDER BY c.id DESC
                        LIMIT 1
                    ) AS cycle_cycle_id
                FROM
                    expenses_categories ec
                WHERE
                    ec.parent_id = '$parentId';
            ";

            $resultGetCategoryLimits = $this->SelectData($getCategoryLimits);
            $rowsCategoryLimits = $resultGetCategoryLimits->fetch_all(MYSQLI_ASSOC);
            $jsonData = json_encode($rowsCategoryLimits);
            $highCounter = 0;
            $mediumCounter = 0;
            $lowCounter = 0;
            foreach ($rowsCategoryLimits as $row2) {
                switch ($row2['category_limit_priority_level_id']) {
                    case 1:
                        $highCounter++;
                        break;
                    case 2:
                        $mediumCounter++;
                        break;
                    case 3:
                        $lowCounter++;
                        break;
                }
            }

            $budgets = array($highBudget, $mediumBudget, $lowBudget);
            $counters = array($highCounter, $mediumCounter, $lowCounter);
            $priorityLevels = array(1, 2, 3);

            foreach ($priorityLevels as $index => $priorityLevel) {
                $counter = $counters[$index];
                $budget = $budgets[$index];

                if ($counter > 0) {
                    $amountAfterDivision = round($budget / $counter, 2);

                    foreach ($rowsCategoryLimits as $row3) {
                        if ($row3['category_limit_priority_level_id'] == $priorityLevel) {
                            $categoryId = $row3['category_limit_id'];
                            $cycleId = $row3['cycle_cycle_id'];

                            // Check if the record already exists
                            $checkQuery = "SELECT * FROM budget_limit WHERE expensese_category_id = '$categoryId' AND cycle_id = '$cycleId'";
                            $result = $this->SelectData($checkQuery);

                            if ($result->num_rows > 0) {
                                // Update the existing record
                                $updateQuery = "UPDATE budget_limit SET amount = '$amountAfterDivision', created_by = '$parentId' WHERE expensese_category_id = '$categoryId' AND cycle_id = '$cycleId'";
                                $this->ExecuteNonQuery($updateQuery);
                            } else {
                                // Insert a new record
                                $insertQuery = "INSERT INTO budget_limit (expensese_category_id, cycle_id, amount, created_by) VALUES ('$categoryId', '$cycleId', '$amountAfterDivision', '$parentId')";
                                $this->ExecuteNonQuery($insertQuery);
                            }
                        }
                    }
                }
            }

            return [
                'high_counter' => $highCounter,
                'medium_counter' => $mediumCounter,
                'low_counter' => $lowCounter,
                'json_data' => $jsonData,
                'high_budget' => $highBudget,
                'medium_budget' => $mediumBudget,
                'low_budget' => $lowBudget,
                'category_limit_names' => array_column($rowsCategoryLimits, 'category_limit_name'),
                'category_limit_ids' => array_column($rowsCategoryLimits, 'category_limit_id'),
                'category_limit_priority_level_id' => array_column($rowsCategoryLimits, 'category_limit_priority_level_id'),
            ];

        } else {
            return [
                'high_budget' => 0,
                'medium_budget' => 0,
                'low_budget' => 0,
                'category_limits' => null
            ];
        }

    }



    public function CheckTotalNumberOfCyclesWithExpenses($parentId) {
        $parentId = $this->EscapeString($parentId);
        $sql = "SELECT COUNT(DISTINCT cycle.id) as cycle_count
        FROM cycle
        INNER JOIN expenses ON cycle.id = expenses.cycle_id
        WHERE cycle.parent_id = '$parentId'
        AND cycle.end < NOW()";

        $result = $this->SelectData($sql);

        if ($result) {
            $row = $result->fetch_assoc();
            return $row['cycle_count'];
        } else {
            return 0;
        }
    }

    public function isCustomizedCyclesOn($parentId) {
        $parentId = $this->escapeString($parentId);
        $sql = "SELECT is_customized, num_of_cycle FROM suggestion_settings WHERE user_id = '$parentId'";
        $result = $this->selectData($sql);


        if ($result->num_rows > 0) {
            return $result->fetch_assoc();

        } else {
            $insertSql = "INSERT INTO suggestion_settings (user_id) VALUES ('$parentId')";
            $result = $this->ExecuteNonQuery($insertSql);
            if ($result) {
                return;
            }
        }
    }
}

?>

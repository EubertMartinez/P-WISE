<?php

class BudgetUseSuggestionData extends DataHelper {

    public function BudgetUseSuggestionDataBtn($parentId, $budget) {
        $parentId = $this->EscapeString($parentId);
        $budget = $this->EscapeString($budget);

        $fifty_percent = str_replace(',','',$budget) * 0.5;
        $thirty_percent = str_replace(',','',$budget) * 0.3;
        $twenty_percent = str_replace(',','',$budget) * 0.2;

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

        $budgets = array($fifty_percent, $thirty_percent, $twenty_percent);
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
            'fifty_percent' => $fifty_percent,
            'thirty_percent' => $thirty_percent,
            'twenty_percent' => $twenty_percent,
            'category_limit_names' => array_column($rowsCategoryLimits, 'category_limit_name'),
            'category_limit_ids' => array_column($rowsCategoryLimits, 'category_limit_id'),
            'category_limit_priority_level_id' => array_column($rowsCategoryLimits, 'category_limit_priority_level_id'),
        ];

    }
}

?>

<?php

class SuggestionData extends DataHelper {

    public function NextCycleSuggestion($parentId, $numberOfCycle, $is_customized) {
        $parentId = $this->EscapeString($parentId);
        $numberOfCycle = $this->EscapeString($numberOfCycle);
        $is_customized = $this->EscapeString($is_customized);

        // Check if the user exists in the table
        $checkSql = "SELECT user_id FROM suggestion_settings WHERE user_id = '$parentId'";
        $result = $this->SelectData($checkSql);

        if ($result->num_rows > 0) {
            // User exists, update the 'is_customized' value
            $updateSql = "UPDATE suggestion_settings SET is_customized = '$is_customized', num_of_cycle = '$numberOfCycle' WHERE user_id = '$parentId'";
            $this->ExecuteNonQuery($updateSql);
        } else {
            // User doesn't exist, insert a new record
            $insertSql = "INSERT INTO suggestion_settings (user_id, is_customized, num_of_cycle) VALUES ('$parentId', '$is_customized', '$numberOfCycle')";
            $this->ExecuteNonQuery($insertSql);
        }



        $totalCyclesWithExpenses = $this->CheckTotalNumberOfCyclesWithExpenses($parentId);

        if ($totalCyclesWithExpenses > 2) {
            $suggestions = $this->fetchSuggestions($parentId, $numberOfCycle);
            return $suggestions;
        } else {
            return "Sorry, you're not eligible to receive suggestions yet. Please complete at least 3 cycles with expenses to become eligible.";
        }
    }


    private function fetchSuggestions($parentId, $numberOfCycle) {
        $parentId = $this->EscapeString($parentId);
        $numberOfCycle = $this->EscapeString($numberOfCycle);

        $sqlActiveTotalBudget = "SELECT
                        c.id AS cycle_id,
                        COALESCE(SUM(b.amount), 0) AS active_total_budget
                    FROM cycle c
                    LEFT JOIN budgets b ON c.id = b.cycle_id
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
                        $data = [
                            'high_percentage' => $row['high_percentage'],
                            'medium_percentage' => $row['medium_percentage'],
                            'low_percentage' => $row['low_percentage'],
                            'active_total_budget' => $rowBudget['active_total_budget']
                        ];

                        return $data;
                    } else {
                        return [
                            'high_percentage' => 0,
                            'medium_percentage' => 0,
                            'low_percentage' => 0,
                            'active_total_budget' => 0
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

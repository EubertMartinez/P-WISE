<?php

class NotificationEndedCycleAutoRunner extends DataHelper {
    public function AutoRunner(): bool|string
    {
        date_default_timezone_set('Asia/Manila');
        $dateTimeToday = date('Y-m-d H:i:s', time());
        $ddate = date('m/d/Y');
        $date  = new DateTime($ddate);
        $dateToday  = date("Y-m-d H:i:s", strtotime($date->format('Y-m-d')));

        $recentEndedCycleQuery = "SELECT c.id, c.start, c.end, c.parent_id, u.id AS user_id, u.account_type, c.parent_id AS cycle_parent_id, u.account_type AS user_account_type,
                                CASE
                                    WHEN expenses_amount = budget_amount THEN 'no_overspend'
                                    WHEN expenses_amount < budget_amount THEN 'savings'
                                    WHEN expenses_amount > budget_amount THEN 'cycle_overspent'
                                END AS cycleType,
                                budget_amount - expenses_amount AS savings
                            FROM cycle c
                            JOIN users u ON c.parent_id = u.id OR (u.account_type = 3 AND c.parent_id = u.parent_id)
                            LEFT JOIN (
                                SELECT cycle_id, SUM(amount) AS expenses_amount
                                FROM expenses
                                GROUP BY cycle_id
                            ) e ON c.id = e.cycle_id
                            LEFT JOIN (
                                SELECT cycle_id, SUM(amount) AS budget_amount
                                FROM budgets
                                GROUP BY cycle_id
                            ) b ON c.id = b.cycle_id
                            WHERE c.end < '$dateToday'";

        $recentEndedCycleResult = $this->SelectData($recentEndedCycleQuery);

        if ($recentEndedCycleResult->num_rows > 0) {
            while ($recentEndedCycleRow = $recentEndedCycleResult->fetch_assoc()) {
                $cycleId = $recentEndedCycleRow['id'];
                $cycleType = $recentEndedCycleRow['cycleType'];
                $totalSavings = $recentEndedCycleRow['savings'];
                $sender_id = $recentEndedCycleRow['cycle_parent_id'];
                $account_type = $recentEndedCycleRow['user_account_type'];

                // Check if the notification already exists
                $checkNotificationQuery = "SELECT * FROM notifications WHERE cycle_id = $cycleId AND notification_type = '$cycleType' LIMIT 1";
                $notificationExists = $this->SelectData($checkNotificationQuery)->num_rows > 0;

                if(!$notificationExists) {
                    switch ($account_type) {
                        case 1:
                            // If the user is a household (account_type = 1)
                            // Notify household members and household
                            $insertNotificationQuery2 = "INSERT INTO notifications (user_id, sender_id, account_type, notification_type, cycle_id, savings, is_read, created_at)
                    SELECT
                        users.id AS user_id, -- The ID of household members
                        '$sender_id' AS sender_id, -- The user ID of the household generating the notification
                        users.account_type AS account_type, -- User type for household
                        '$cycleType' AS notification_type, -- Use the cycleType variable
                        '$cycleId' AS cycle_id, -- The relevant financial cycle ID
                        '$totalSavings' AS savings,
                        FALSE AS is_read, -- Notification is initially unread
                        '$dateTimeToday' AS created_at
                    FROM users
                    WHERE
                        users.id = '$sender_id' OR users.parent_id = '$sender_id'";
                            break;

                        case 2:
                            // If the user is a personal (account_type = 2)
                            // Notify itself
                            $insertNotificationQuery2 = "INSERT INTO notifications (user_id, sender_id, account_type, notification_type, cycle_id, savings, is_read, created_at)
                    SELECT
                        users.id AS user_id, -- The ID of household members
                        '$sender_id' AS sender_id, -- The user ID of the household generating the notification
                        users.account_type AS account_type, -- User type for household
                        '$cycleType' AS notification_type, -- Use the cycleType variable
                        '$cycleId' AS cycle_id, -- The relevant financial cycle ID
                        '$totalSavings' AS savings,
                        FALSE AS is_read, -- Notification is initially unread
                        '$dateTimeToday' AS created_at
                    FROM users
                    WHERE
                        users.id = '$sender_id'";
                            break;

                        case 3:
                            // If the user is a member (account_type = 3)
                            // Notify co-members and household
                            $insertNotificationQuery2 = "INSERT INTO notifications (user_id, sender_id, account_type, notification_type, cycle_id, savings, is_read, created_at)
                    SELECT
                        users.id AS user_id, -- The ID of household members and co-members
                        '$sender_id' AS sender_id, -- The user ID of the member generating the notification
                        users.account_type AS account_type, -- User type for member
                        '$cycleType' AS notification_type, -- Use the cycleType variable
                        '$cycleId' AS cycle_id, -- The relevant financial cycle ID
                        '$totalSavings' AS savings,
                        FALSE AS is_read, -- Notification is initially unread
                        '$dateTimeToday' AS created_at
                    FROM users
                    WHERE
                        users.id = '$sender_id' -- For the member himself
                        OR users.parent_id = (SELECT parent_id FROM users WHERE id = '$sender_id') -- For co-members of the household
                        OR (users.account_type = 1 AND users.id = (SELECT parent_id FROM users WHERE id = '$sender_id')); -- For the household itself
                    "; // For the member
                            break;

                        default:
                            // Handle other cases here
                            break;
                    }

                    return $this->ExecuteNonQuery($insertNotificationQuery2);
                }
            }

        }
    }
}
<?php

class Notification extends DataHelper {

    public function GetNotifications($id, $account_type, $parent_id)
    {
        $id = $this->EscapeString($id);
        $account_type = $this->EscapeString($account_type);
        $parent_id = $this->EscapeString($parent_id);

        $sql = "SELECT
                    notifications.*, 
                    expenses_name.*, 
                    expenses_categories.*,
                    users.*,
                    cycle.*,
                        CASE
                            WHEN notifications.sender_id <> '$id' THEN users_sender.firstname
                            ELSE users.firstname
                        END AS firstname,
                        CASE
                            WHEN notifications.sender_id <> '$id' THEN users_sender.lastname
                            ELSE users.lastname
                        END AS lastname,
                    expenses_name.name AS expenses_name_name, 
                    expenses_categories.name AS expenses_category_name,
                    COUNT(DISTINCT notifications.id) AS total_notification,
                    notifications.id AS notification_id
                FROM notifications
                LEFT JOIN cycle ON cycle.parent_id = notifications.sender_id
                LEFT JOIN users ON users.id = notifications.user_id
                LEFT JOIN expenses_name ON expenses_name.id = notifications.expenses_name_id
                LEFT JOIN expenses_categories ON expenses_categories.id = expenses_name.expenses_category_id
                LEFT JOIN users AS users_sender ON notifications.sender_id = users_sender.id
                WHERE 
                    notifications.user_id = '$id'
                GROUP BY notifications.id
                ORDER BY notifications.created_at DESC";


        // 1. Get the recent ended cycle
        $unreadSql = "SELECT * FROM notifications WHERE user_id = '$id' AND is_read = 0;";
        $unreadSqlResult = $this->SelectData($unreadSql);

        $result = $this->SelectData($sql);

        if ($result->num_rows > 0) {
            $data = $result->fetch_all(MYSQLI_ASSOC);

            return [
                'data' => $data,
                'total_notification' => $result->num_rows,
                'total_unread' => $unreadSqlResult->num_rows, // Assuming all rows have the same total_unread value
            ];
        } else {
            return [
                'data' => 0,
                'total_notification' => 0,
                'total_unread' => 0,
            ];
        }

    }

    public function GetNotifications2($id, $account_type, $parent_id)
    {
        $id = $this->EscapeString($id);
        $account_type = $this->EscapeString($account_type);
        $parent_id = $this->EscapeString($parent_id);

        $sql = "SELECT
                    notifications.*, 
                    expenses_name.*, 
                    expenses_categories.*,
                    users.*,
                    cycle.*,
                        CASE
                            WHEN notifications.sender_id <> '$id' THEN users_sender.firstname
                            ELSE users.firstname
                        END AS firstname,
                        CASE
                            WHEN notifications.sender_id <> '$id' THEN users_sender.lastname
                            ELSE users.lastname
                        END AS lastname,
                    expenses_name.name AS expenses_name_name, 
                    expenses_categories.name AS expenses_category_name,
                    COUNT(DISTINCT notifications.id) AS total_notification,
                    notifications.id AS notification_id
                FROM notifications
                LEFT JOIN cycle ON cycle.parent_id = notifications.sender_id
                LEFT JOIN users ON users.id = notifications.user_id
                LEFT JOIN expenses_name ON expenses_name.id = notifications.expenses_name_id
                LEFT JOIN expenses_categories ON expenses_categories.id = expenses_name.expenses_category_id
                LEFT JOIN users AS users_sender ON notifications.sender_id = users_sender.id
                WHERE 
                    notifications.user_id = '$id'
                GROUP BY notifications.id
                ORDER BY notifications.created_at DESC";


        // 1. Get the recent ended cycle
        $unreadSql = "SELECT * FROM notifications WHERE user_id = '$id' AND is_read = 0;";
        $unreadSqlResult = $this->SelectData($unreadSql);

        $result = $this->SelectData($sql);

        if ($result->num_rows > 0) {

            return $result;
        } else {
            return 0;
        }

    }

    public function notificationUpdate($id, $is_read) {

        $id = $this->EscapeString($id);
        $is_read = $this->EscapeString($is_read);

        $sql = "UPDATE notifications SET is_read = '$is_read' WHERE id = '$id'";
        $result = $this->ExecuteNonQuery($sql);

        if($result) {
            return [
                'status' => 1,
            ];
        } else {
            return [
                'status' => 0,
            ];
        }
    }
}


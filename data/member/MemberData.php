<?php

class MemberData extends DataHelper {
    public function AddMember($accountType, $firstname, $lastname, $email, $username, $mobile, $password, $parentId) {
        $accountType = $this->EscapeString($accountType);
        $firstname   = $this->EscapeString($firstname);
        $lastname    = $this->EscapeString($lastname);
        $email       = $this->EscapeString($email);
        $username    = $this->EscapeString($username);
        $mobile      = $this->EscapeString($mobile);
        $password    = $this->EscapeString($password);
        $parentId    = $this->EscapeString($parentId);

        $sql = "INSERT INTO users(account_type, firstname, lastname, email, username, mobile, password, parent_id) 
                VALUES ('$accountType','$firstname','$lastname','$email','$username','$mobile','$password', '$parentId')";
        
        $insertedId = $this->ExecuteNonQueryReturnsInsertedId($sql);

        if ($insertedId == 0)
            return "Unable to add new member";

        $sql = "INSERT INTO activity_logs(type, activity, table_name, table_id, created_by)
                VALUES('ADD MEMBER', ' has been added as a new member.', 'users', '$insertedId', '$parentId')";

        $this->ExecuteNonQuery($sql);

        return $insertedId;
    }

    public function GetUserByEmailOrUsername($email, $username) {
        $email    = $this->EscapeString($email);
        $username = $this->EscapeString($username);

        $sql = "SELECT * FROM users WHERE email='$email' OR username='$username'";
        return $this->SelectData($sql);
    }

    public function GetMembers($id) {
        $ddate = date('m/d/Y');
        $date  = new DateTime($ddate);
        $month = date("m", strtotime($date->format('Y-m-d')));
        $year  = date("Y", strtotime($date->format('Y-m-d')));

        $id  = $this->EscapeString($id);
        $sql = "SELECT  U.* 
                        , ACT.name AS role
                        , IFNULL((
                            SELECT SUM(amount)
                                FROM budgets B 
                                INNER JOIN cycle C ON C.id = B.cycle_id
                                WHERE B.created_by = U.id
                                    AND (DATE(CURRENT_DATE()) BETWEEN DATE(C.start) AND DATE(C.end))
                        ), 0.00) AS amount
                    FROM users U
                    INNER JOIN account_type ACT
                        ON ACT.id = U.account_type
                    WHERE U.id='$id' OR U.parent_id = '$id'";

        return $this->SelectData($sql);
    }

    public function GetMembersByMonth($id, $month, $year) {
        $month = $this->EscapeString($month);
        $year  = $this->EscapeString($year);
        $id    = $this->EscapeString($id);

        $sql = "SELECT  U.* 
                        , ACT.name AS role
                        , IFNULL((
                            SELECT SUM(amount)
                                FROM budgets
                                WHERE created_by = U.id
                                    AND MONTH(DATE(created)) = '$month'
                                    AND YEAR(DATE(created)) = '$year'
                        ), 0.00) AS amount
                    FROM users U
                    INNER JOIN account_type ACT
                        ON ACT.id = U.account_type
                    WHERE U.id='$id' OR U.parent_id = '$id'";

        return $this->SelectData($sql);
    }

    public function GetMembersByRange($id, $start, $end) {
        $id = $this->EscapeString($id);
        $start = $this->EscapeString($start);
        $end   = $this->EscapeString($end);

        $sql = "SELECT  U.* 
                        , ACT.name AS role
                        , IFNULL((
                            SELECT SUM(amount)
                                FROM budgets
                                WHERE created_by = U.id
                                    AND (DATE(created) BETWEEN DATE('$start') AND DATE('$end'))
                        ), 0.00) AS amount
                    FROM users U
                    INNER JOIN account_type ACT
                        ON ACT.id = U.account_type
                    WHERE U.id='$id' OR U.parent_id = '$id'";

        return $this->SelectData($sql);
    }

    public function GetMembersActivityLogs($id) {
        $id  = $this->EscapeString($id);
        $sql = "SELECT  AL.*
                        , CONCAT(U.firstname, ' ', U.lastname) AS name
                        , CONCAT(
                            (CASE
                                WHEN AL.table_name = 'users' 
                                    THEN (
                                            SELECT CONCAT(U.firstname, ' ', U.lastname) 
                                            FROM users U WHERE id = AL.table_id
                                        )
                                WHEN AL.table_name = 'budgets' 
                                    THEN (
                                            CONCAT(
                                                'The amount of ', (
                                                    SELECT B.amount 
                                                    FROM budgets B WHERE id = AL.table_id
                                                ), ' pesos'
                                            )
                                        )
                                ELSE ''
                            END), ' ', AL.activity
                        ) AS description
                    FROM activity_logs AL
                    INNER JOIN users U 
                        ON U.id = AL.created_by
                    WHERE U.parent_id = '$id'
                        OR U.id = '$id'
                    ORDER BY AL.id DESC";

        return $this->SelectData($sql);
    }
}

?>
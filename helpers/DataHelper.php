<?php
class DataHelper extends DatabaseConfiguration {
    public function EscapeString($data) {
        return mysqli_real_escape_string($this->conn, $data);
    }

    public function SelectData($sql) {
        return $this->conn->query($sql);

    }

    public function ExecuteNonQuery($sql) {
        return $this->conn->query($sql) === TRUE ? TRUE : "Error: " . $sql . "<br>" . $this->conn->error;
    }

    public function ExecuteNonQueryReturnsBoolean($sql) {
        return $this->conn->query($sql) === TRUE;
    }

    public function ExecuteNonQueryReturnsInsertedId($sql) {
        return $this->conn->query($sql) === TRUE ? $this->conn->insert_id : 0;
    }
}

?>
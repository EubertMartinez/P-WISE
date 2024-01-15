<?php

class DatabaseConfiguration {
    /*private $servername = "localhost";
    private $username   = "u739235026_pwise";
    private $password   = "2j@^I4KXQL";
    private $database   = "u739235026_pwise";*/
    private $servername = "localhost";
    private $username   = "root";
    private $password   = "";
    private $database   = "pwise";
    private $port = 3306;
    protected $conn;

    public function __construct() {

        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->database);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }
}

?>
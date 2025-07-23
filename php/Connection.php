<?php
class Database {
    private $host = "localhost";
    private $user = "root";
    private $password = "";
    private $dbName = "ceylonconnect";
    public $connection;

    public function __construct() {
        $this->connectDB();
    }

    private function connectDB() {
        $this->connection = new mysqli($this->host, $this->user, $this->password, $this->dbName);

        if ($this->connection->connect_error) {
            die("Database Connection failed: " . $this->connection->connect_error);
        }
    }

    public function getConnection() {
        return $this->connection;
    }

    public function closeConnection() {
        if ($this->connection) {
            $this->connection->close();
        }
    }
}
?>

<?php
    class Database{
        private $host = "localhost";
        private $username = "root";
        private $password = "";
        private $database = "lesson";
        private $conn;

        public function connection_database() {
            $this->conn = null;
            try {
                $this->conn = new PDO("mysql:host=$this->host;dbname=$this->database", $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                // echo "Connected successfully";
            } catch(PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
            return $this->conn;
        }

        // public function select($table){
        //     $stmt = $this->conn->prepare($table);
        //     $stmt->execute();
        //     return $stmt;
        // }
        // public function store($table, $data) {
        //     $columns = implode(", ", array_keys($data));
        //     $values = ":" . implode(", :", array_keys($data));
        //     $query = "INSERT INTO $table ($columns) VALUES ($values)";
        //     $stmt = $this->conn->prepare($query);
        //     $stmt->execute($data);
        //     return $this->conn->lastInsertId(); // returns the ID of the newly inserted row
        // }
        // public function update($table, $data, $where) {
        //     $set = [];
        //     foreach ($data as $key => $value) {
        //         $set[] = "$key=:$key";
        //     }
        //     $set = implode(", ", $set);
        //     $query = "UPDATE $table SET $set WHERE $where";
        //     $stmt = $this->conn->prepare($query);
        //     $stmt->execute($data);
        //     return $stmt->rowCount(); // returns the number of affected rows
        // }
        // public function delete($table, $where) {
        //     $query = "DELETE FROM $table WHERE $where";
        //     $stmt = $this->conn->prepare($query);
        //     $stmt->execute();
        //     return $stmt->rowCount(); // returns the number of affected rows
        // }

    }

?>
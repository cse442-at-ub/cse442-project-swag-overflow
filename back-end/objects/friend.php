<?php

class Friend {
    
    private $conn;
    private $table_name = "friends";

    // friend properties
    public $username;
    public $friend;
    public $request;

    // database connection as constructor
    public function __construct($db){
        $this->conn = $db;
    }

    function request($data) {
        // check if user requested exists
        $user_query = "SELECT * FROM user WHERE username = ?";
    
        // prepare query statement
        $user_stmt = $this->conn->prepare($user_query);

        // bind id of product to be updated
        $user_stmt->bindParam(1, $data->username);
    
        // execute query
        $user_stmt->execute();
    
        // get retrieved row
        $row = $user_stmt->fetch(PDO::FETCH_ASSOC);

        if (empty($row)) {
            return "error-dne";
        } else {
            // now check if a request has already been sent
            $check_request_query = "SELECT * FROM $this->table_name WHERE username = ? AND request_from = ?";

            // prepare query statement
            $check_request_stmt = $this->conn->prepare($check_request_query);

            // bind parameters
            $check_request_stmt->bindParam(1, $data->username);
            $check_request_stmt->bindParam(2, $data->request);
        
            // execute query
            $check_request_stmt->execute();

            if ($check_request_stmt->rowCount() > 0) {
                return "error-ae";
            } else {
                // query to insert record
                $query = "INSERT INTO " . $this->table_name . " SET username=:username, request_from=:request";

                // prepare query
                $stmt = $this->conn->prepare($query);

                // sanitize
                $this->username=htmlspecialchars(strip_tags($this->username));
                $this->request=htmlspecialchars(strip_tags($this->request));

                // bind values
                $stmt->bindParam(":username", $this->username);
                $stmt->bindParam(":request", $this->request);

                // execute query
                if($stmt->execute()){
                    return "success";
                } else {
                    return "error";
                }
            }
            
        }
        
    }

    function accept($data) {
        // check if user requested exists
        $user_query = "SELECT * FROM user WHERE username = ?";
    
        // prepare query statement
        $user_stmt = $this->conn->prepare($user_query);

        // bind id of product to be updated
        $user_stmt->bindParam(1, $data->username);
    
        // execute query
        $user_stmt->execute();
    
        // get retrieved row
        $row = $user_stmt->fetch(PDO::FETCH_ASSOC);

        if (empty($row)) {
            return "error-dne";
        } else {
            // now confirm if a request has already been sent
            $check_request_query = "SELECT * FROM $this->table_name WHERE username = ? AND request_from = ?";

            // prepare query statement
            $check_request_stmt = $this->conn->prepare($check_request_query);

            // bind parameters
            $check_request_stmt->bindParam(1, $data->username);
            $check_request_stmt->bindParam(2, $data->friend);
        
            // execute query
            $check_request_stmt->execute();

            if ($check_request_stmt->rowCount() == 0) {
                return "error-nr";
            } else {
                // query to insert record
                $query = "INSERT INTO " . $this->table_name . " SET username=:username, friend=:friend";

                // prepare query
                $stmt = $this->conn->prepare($query);

                // sanitize
                $this->username=htmlspecialchars(strip_tags($this->username));
                $this->friend=htmlspecialchars(strip_tags($this->friend));

                // bind values
                $stmt->bindParam(":username", $this->username);
                $stmt->bindParam(":friend", $this->friend);

                // execute query
                $stmt->execute();

                // delete previous friend request since it's now been accepted
                $delete_query = "DELETE FROM $this->table_name WHERE username = ? AND request_from = ?";

                // prepare query statement
                $delete_stmt = $this->conn->prepare($delete_query);

                // bind parameters
                $delete_stmt->bindParam(1, $data->username);
                $delete_stmt->bindParam(2, $data->friend);
            
                // execute query
                if ($delete_stmt->execute()) {
                    return "success";
                }
                
                return "error";
            }
        }
    }

}

?>
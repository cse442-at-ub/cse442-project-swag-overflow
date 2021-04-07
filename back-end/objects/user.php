<?php

class User {

    // db connection
    private $conn;
    private $table_name = "user";

    // user properties
    public $firstname;
    public $lastname;
    public $dob;
    public $username;
    public $email;
    public $password;

    // database connection as constructor
    public function __construct($db){
        $this->conn = $db;
    }

    // read products
    function read() {
        // select all query
        $query = "SELECT * FROM user";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

}

?>
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

    // read users
    function read() {
        // select all query
        $query = "SELECT * FROM user";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // create user
    function register() {

        // check if user already exists
        $check_query = "SELECT * FROM user WHERE username='$this->username'";

        // prepare query
        $check_stmt = $this->conn->prepare($check_query);

        // sanitize
        $this->username=htmlspecialchars(strip_tags($this->username));

        // execute
        $check_stmt->execute();
        
        if ($check_stmt->rowCount() > 0) {
            return "error-tkn";
        }
    
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    firstname=:firstname, lastname=:lastname, dob=:dob, username=:username, email=:email, password=:password";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->firstname=htmlspecialchars(strip_tags($this->firstname));
        $this->lastname=htmlspecialchars(strip_tags($this->lastname));
        $this->dob=htmlspecialchars(strip_tags($this->dob));
        $this->username=htmlspecialchars(strip_tags($this->username));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->password=htmlspecialchars(strip_tags($this->password));

        // salt and hash
        $this->password = password_hash($this->password,PASSWORD_DEFAULT);
    
        // bind values
        $stmt->bindParam(":firstname", $this->firstname);
        $stmt->bindParam(":lastname", $this->lastname);
        $stmt->bindParam(":dob", $this->dob);
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
    
        // execute query
        if($stmt->execute()){
            return "success";
        }
    
        return "error";
        
    }

    // sign in user
    function signin($data) {
    
        // query to read single record
        $query = "SELECT * FROM user WHERE username = ?";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind id of product to be updated
        $stmt->bindParam(1, $data->username);
    
        // execute query
        $stmt->execute();
    
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (empty($row)) {
            return false;
        } else {
            if (password_verify($data->password, $row["password"])) {
                // set values to object properties
                $this->firstname = $row['firstname'];
                $this->lastname = $row['lastname'];
                $this->dob = $row['dob'];
                $this->username = $row['username'];
                $this->email = $row['email'];
                $this->password = $row['password'];
                return true;
            } else {
                // set password to sepecified value so program can differentiate between no user and mismatching password
                $this->password = "DNM"; 
                return false;
            }
        }  
    }

}

?>
<?php

class Event {

    // db connection
    private $conn;
    private $table_name = "user_events";

    // event properties
    public $username;
    public $email;
    public $event_name;
    public $event_type;
    public $event_start_day;
    public $event_start_time;
    public $event_end_day;
    public $event_end_time;
    public $event_description;
    public $event_location;
    public $event_attendee_username;
    public $event_attendee_email;

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

    function create() {

        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    username=:username, email=:email, event_name=:event_name, event_type=:event_type, event_start_day=:event_start_day, 
                    event_start_time=:event_start_time, event_end_day=:event_end_day, event_end_time=:event_end_time,
                    event_description=:event_description, event_location=:event_location, 
                    event_attendee_username=:event_attendee_username, event_attendee_email=:event_attendee_email";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->username=htmlspecialchars(strip_tags($this->username));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->event_name=htmlspecialchars(strip_tags($this->event_name));
        $this->event_type=htmlspecialchars(strip_tags($this->event_type));
        $this->event_start_day=htmlspecialchars(strip_tags($this->event_start_day));
        $this->event_start_time=htmlspecialchars(strip_tags($this->event_start_time));
        $this->event_end_day=htmlspecialchars(strip_tags($this->event_end_day));
        $this->event_end_time=htmlspecialchars(strip_tags($this->event_end_time));
        $this->event_description=htmlspecialchars(strip_tags($this->event_description));
        $this->event_location=htmlspecialchars(strip_tags($this->event_location));
        $this->event_attendee_username=htmlspecialchars(strip_tags($this->event_attendee_username));
        $this->event_attendee_email=htmlspecialchars(strip_tags($this->event_attendee_email));
    
        // bind values
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":event_name", $this->event_name);
        $stmt->bindParam(":event_type", $this->event_type);
        $stmt->bindParam(":event_start_day", $this->event_start_day);
        $stmt->bindParam(":event_start_time", $this->event_start_time);
        $stmt->bindParam(":event_end_day", $this->event_end_day);
        $stmt->bindParam(":event_end_time", $this->event_end_time);
        $stmt->bindParam(":event_description", $this->event_description);
        $stmt->bindParam(":event_location", $this->event_location);
        $stmt->bindParam(":event_attendee_username", $this->event_attendee_username);
        $stmt->bindParam(":event_attendee_email", $this->event_attendee_email);
    
        // execute query
        if($stmt->execute()){
            return "success";
        }
    
        return "error";
    }

}

?>
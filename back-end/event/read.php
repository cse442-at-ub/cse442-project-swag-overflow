<?php
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    // include database and object files
    include_once '../config/database.php';
    include_once '../objects/user.php';
    
    // instantiate database and product object
    $database = new Database();
    $db = $database->getConnection();

    // initialize object
    $event = new Event($db);

    // query products
    // TODO: provide a username to read and it will return all records associated with that username
    $stmt = $event->read();
    $num = $stmt->rowCount();

        // check if more than 0 record found
        if($num>0) {
    
        // products array
        $event_arr=array();
        $event_arr["records"]=array();
    
        // retrieve our table contents
        // fetch() is faster than fetchAll()
        // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            // extract row
            // this will make $row['name'] to
            // just $name only
            extract($row);
    
            $event_item = array(
                "username" => $username,
                "email" => $email,
                "event_name" => $event_name,
                "event_start_day" => $event_start_day,
                "event_start_time" => $event_start_time,
                "event_end_day" => $event_end_day,
                "event_end_time" => $event_end_time,
                "event_description" => $event_description,
                "event_location" => $event_location,
                "event_attendee_username" => $event_attendee_username,
                "event_attendee_email" => $event_attendee_email
            );
    
            array_push($event_arr["records"], $event_item);
        }
    
        // set response code - 200 OK
        http_response_code(200);

        echo json_encode($users_arr);

        // $url = 'https://www-student.cse.buffalo.edu/CSE442-542/2021-Spring/cse-442m/front-end/dist/';
        // header("Location: $url");
    } else {
        // set response code - 404 Not found
        http_response_code(404);
        
        // tell the user no events found
        echo json_encode(
            array("message" => "No events found.")
        );
    }

?>
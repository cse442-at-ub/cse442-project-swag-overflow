<?php
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    // get database connection
    include_once '../config/database.php';
    $database = new Database();
    $db = $database->getConnection();
    
    // instantiate user object
    include_once '../objects/event.php';
    $event = new Event($db);

    // get posted data
    $data = json_decode(file_get_contents("php://input"))->data;

    $url = 'https://www-student.cse.buffalo.edu/CSE442-542/2021-Spring/cse-442m/front-end/dist/';

    // make sure data is not empty for all fields
    if (
        !empty($data->username) &&
        !empty($data->email) &&
        !empty($data->event_name) &&
        !empty($data->event_type) && 
        !empty($data->event_start_day) &&
        !empty($data->event_start_time) &&
        !empty($data->event_end_day) &&
        !empty($data->event_end_time) &&
        !empty($data->event_description) &&
        !empty($data->event_location) &&
        !empty($data->event_attendee_username) &&
        !empty($data->event_attendee_email)
    ) {
        // set user property values
        $event->username = $data->username;
        $event->email = $data->email;
        $event->event_name = $data->event_name;
        $event->event_type = $data->event_type;
        $event->event_start_day = $data->event_start_day;
        $event->event_start_time = $data->event_start_time;
        $event->event_end_day = $data->event_end_day;
        $event->event_end_time = $data->event_end_time;
        $event->event_description = $data->event_description;
        $event->event_location = $data->event_location;
        $event->event_attendee_username = $data->event_attendee_username;
        $event->event_attendee_email = $data->event_attendee_email;

        $code = $event->create();

        echo($code);

        if ($code == "success") {

            // set response code - 201 created
            http_response_code(201);

            // tell the user
            echo json_encode(array("message" => "Event was successfully created."));

            // send back data
            header("message: success");
            header("username: $event->username");
            header("email: $event->email");
            header("event_name: $event->event_name");
            header("event_type: $event->event_type");
            header("event_start_day: $event->event_start_day");
            header("event_start_time: $event->event_start_time");
            header("event_end_day: $event->event_end_day");
            header("event_end_time: $event->event_end_time");
            header("event_description: $event->event_description");
            header("event_location: $event->event_location");
            header("event_attendee_username: $event->event_attendee_username");
            header("event_attendee_email: $event->event_attendee_email");
            // header("Location: $url");

        } else {
            // other error
            header("message: error");
            header("Location: $url");
    
            // tell the user
            echo json_encode(array("message" => "Unable to create event."));

            // set response code - 503 service unavailable
            http_response_code(503);
        }
    } 
    // tell the user data is incomplete
    else{
        header("message: error-in");
        header("Location: $url");
    
        // tell the user
        echo json_encode(array("message" => "Unable to create event. Data is incomplete."));

        // set response code - 400 bad request
        http_response_code(400);
    }
?>
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

    // instantiate friend object
    include_once '../objects/friend.php';
    $friend = new Friend($db);

    // get posted data
    $data = json_decode(file_get_contents("php://input"))->data;

    $url = 'https://www-student.cse.buffalo.edu/CSE442-542/2021-Spring/cse-442m/front-end/dist/';

    // make sure data is not empty for all fields
    if (
        !empty($data->username) &&
        !empty($data->request)
    ) {
        // set friend property values
        $friend->username = $data->username;
        $friend->request = $data->request;

        $code = $friend->request();

        if ($code == "success") {
            // set response code - 201 created
            http_response_code(201);

            // tell the user
            echo json_encode(array("message" => "Friend request successfully sent to $friend->username."));

            // send back data
            header("message: success");
            header("request: $friend->request");
        } else {
            // something went wrong

            if ($code == "error-dne") {
                // user does not exist

                header("message: error-dne");
                // header("Location: $url");
                echo json_encode(array("message" => "User does not exist."));
                // set response code - 404 Not found
                http_response_code(404);
            } else if ($code == "error-ae") {
                // request has already been sent

                // header("Location: $url");
                echo json_encode(array("message" => "A request to this user has already been sent."));
                // set response code - 400 Bad request
                http_response_code(400);
            } else {
                // unknown error on our end

                header("message: error");
                echo json_encode(array("message" => "Something went wrong!"));
                // header("Location: $url");
                http_response_code(503);
            }
        }
    } else {
        // tell the user the data is incomplete
        header("message: error-in");
        header("Location: $url");
    
        // tell the user
        echo json_encode(array("message" => "Unable to send request. Data is incomplete."));

        // set response code - 400 bad request
        http_response_code(400);
    }
?>
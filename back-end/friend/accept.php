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
        !empty($data->friend)
    ) {
        // set event property values
        $friend->username = $data->username;
        $friend->friend = $data->friend;

        $code = $friend->accept();

        if ($code == "success") {
            // set response code - 201 created
            http_response_code(201);

            // tell the user
            // echo json_encode(array("message" => "You successfully accepted $friend->friend's request."));

            // send back data
            header("message: success");
            // header("accepted: $friend->friend");
        } else {
            // something went wrong

            if ($code == "error-nr") {
                // no request to accept

                // unknown error on our end

                header("message: error");
                echo json_encode(array("message" => "No previous request existed to accept."));
                // header("Location: $url");
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
        // header("Location: $url");
    
        // tell the user
        echo json_encode(array("message" => "Unable to send request. Data is incomplete."));

        // set response code - 400 bad request
        http_response_code(400);
    }


?>
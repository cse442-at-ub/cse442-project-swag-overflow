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
        !empty($data->request) && 
        !empty($data->friend)
    ) {
        // set friend property values
        $friend->username = $data->username;
        $friend->request = $data->request;
        $friend->friend = $friend->friend;

        $code = $friend->read();

        if ($code == "success") {

        } else {
            
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
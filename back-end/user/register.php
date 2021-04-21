<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../config/database.php';
  
// instantiate user object
include_once '../objects/user.php';
  
$database = new Database();
$db = $database->getConnection();
  
$user = new User($db);
  
// get posted data
$data = json_decode(file_get_contents("php://input"))->data;
// $data = $_POST["data"];
// echo $data;

$url = 'https://www-student.cse.buffalo.edu/CSE442-542/2021-Spring/cse-442m/front-end/dist/';
  
// make sure data is not empty for all fields
if (
    !empty($data->firstname) &&
    !empty($data->lastname) &&
    !empty($data->dob) &&
    !empty($data->username) &&
    !empty($data->email) &&
    !empty($data->password)
) {
  
    // set user property values
    $user->firstname = $data->firstname;
    $user->lastname = $data->lastname;
    $user->dob = $data->dob;
    $user->username = $data->username;
    $user->email = $data->email;
    $user->password = $data->password;

    $code = $user->register();
  
    // create the user
    if($code == "success"){
  
        // set response code - 201 created
        http_response_code(201);

        // tell the user
        echo json_encode(array("message" => "User was successfully created."));

        header("Access-Control-Allow-Origin: *");
        header("message: success");
        header("firstname: $user->firstname");
        header("lastname: $user->lastname");
        header("username: $user->username");
        header("Location: $url");
    }
  
    // if unable to create the user, tell the user
    else {
        
        if ($code == "error-tkn") {
            // user already exists
            header("Access-Control-Allow-Origin: *");
            header("message: error-tkn");
            header("Location: $url");
    
            // tell the user
            echo json_encode(array("message" => "Username already exists."));

            // set response code - 409 Conflict
            http_response_code(409);
        } else {
            // other error
            header("Access-Control-Allow-Origin: *");
            header("message: error");
            header("Location: $url");
    
            // tell the user
            echo json_encode(array("message" => "Unable to create user."));

            // set response code - 503 service unavailable
            http_response_code(503);
        }
    }
}
  
// tell the user data is incomplete
else{
    header("Access-Control-Allow-Origin: *");
    header("message: error-in");
    header("Location: $url");
  
    // tell the user
    echo json_encode(array("message" => "Unable to create user. Data is incomplete."));

    // set response code - 400 bad request
    http_response_code(400);
}
?>
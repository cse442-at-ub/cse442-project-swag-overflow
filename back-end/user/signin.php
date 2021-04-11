<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once '../config/database.php';
include_once '../objects/user.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare user object
$user = new User($db);
  
// get posted data
$data = json_decode(file_get_contents("php://input"));
  
// read the details of user to be edited
$successful = $user->signin($data);
  
if($successful){
    // create array
    $user_arr = array(
        "firstname" =>  $user->firstname,
        "lastname" => $user->lastname,
        "dob" => $user->dob,
        "username" => $user->username,
        "email" => $user->email,
        "password" => $user->password
    );
  
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo "Successfully signed in.\n"; // TODO 
    echo json_encode($user_arr);
}
  
else {
    // set response code - 404 Not found
    http_response_code(404);
    
    if ($user->password == "DNM") {
        // tell the user the password Does Not Match
        echo json_encode(array("message" => "Password does not match."));
    } else {
        // tell the user user does not exist
        echo json_encode(array("message" => "User does not exist."));
    }
}
?>
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
        $friend->friend = $data->friend;

        $code = $friend->read();

        if ($code == "error-bf") {
            // request was formatted incorrectly 

            header("message: error-bf");
            echo json_encode(array("message" => "Data was formatted incorrectly."));
            // header("Location: $url");
            http_response_code(500);
        } else if ($code == "error") {
            // unknown error on our end

            header("message: error");
            echo json_encode(array("message" => "Something went wrong!"));
            // header("Location: $url");
            http_response_code(503);
        } else {
            // work with data and return it

            // requests or friends array
            $data_arr=array();
            $data_arr["records"]=array();

            // retrieve our table contents
            // fetch() is faster than fetchAll()
            // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
            while ($row = $code->fetch(PDO::FETCH_ASSOC)){

                if ($data->request == "true") {
                    array_push($data_arr["records"], $row['request']);
                } else {
                    // echo $row;
                    array_push($data_arr["records"], $row['friend']);
                }
            }

            $str = json_encode($data_arr);
            header("data: $str");

            // set response code - 200 OK
            http_response_code(200);

            echo json_encode($data_arr);
            
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
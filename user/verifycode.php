<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include_once '../config/database.php';
include_once '../objects/user.php';

$database = new Database();
$db = $database->getConnection();
$UserManager = new UserManager($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if(
	!empty($data->Vericode) && !empty($data->UserId)
){

	$code = htmlspecialchars(strip_tags($data->Vericode));
	$userID = htmlspecialchars(strip_tags($data->UserId));
       
    $stmt = $UserManager->verifycode($code,$userID);
    $num = $stmt->rowCount();
                
    if($num > 0 ){

			http_response_code(200);
			echo json_encode(array("message" => "Password Change Good."));
		
	  }else{

	http_response_code(503);
        echo json_encode(array("message" => "Incorrect code."));
	}
}else{

	http_response_code(400);
        echo json_encode(array("message" => "Incomplete Data"));
	}
?>
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
	!empty($data->Password) && !empty($data->UserId)
){

	$passWord = htmlspecialchars(strip_tags($data->Password));
	$userId= htmlspecialchars(strip_tags($data->UserId));
       
	if($UserManager->updatePassword($userId,$passWord)){

	
		http_response_code(200);
		echo json_encode(array("message" => "Password Change Successful."));
	}
	
	  else{

	http_response_code(503);
        echo json_encode(array("message" => "Unable to Change Password"));
	}
}else{

	http_response_code(400);
        echo json_encode(array("message" => "Incomplete Data"));
	}
?>
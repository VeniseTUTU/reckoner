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
	!empty($data->Organisation) &&
	!empty($data->Email) && !empty($data->Password) &&
	!empty($data->SDGs) && !empty($data->Address) && !empty($data->Country)
){
        $organisation = htmlspecialchars(strip_tags($data->Organisation));
	$email = htmlspecialchars(strip_tags($data->Email));
        $password = htmlspecialchars(strip_tags($data->Password));
	$sdgs = htmlspecialchars(strip_tags($data->SDGs));
	$address = htmlspecialchars(strip_tags($data->Address));
	$country = htmlspecialchars(strip_tags($data->Country));
	
		
	$userid='';
	for ($i = 0; $i < 8; $i++) {
	  $userid .= mt_rand('0','9');
	}
	
	$stmt = $UserManager->readUserByEmail($email);
        $num = $stmt->rowCount();
                
        if($num <= 0 ){  
       
       // create the product
	if($UserManager->createUser_Org($userid,$organisation,$email,$password,$sdgs,$address,$country)){

		
		http_response_code(200);
                echo json_encode(array("message" => "User was added."));
		
	}else{

	    http_response_code(503);
            echo json_encode(array("message" => "Unable to store User. Try again."));
	}
		
	}else{

	    http_response_code(503);
            echo json_encode(array("message" => "User Exists."));
	}
    
    }
	else{

	http_response_code(400);
        echo json_encode(array("message" => "Unable to store User. Data Incomplete."));
}
?>
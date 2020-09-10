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

if(
	!empty($data->userId)
){

        $UserId = htmlspecialchars(strip_tags($data->userId));
        $organisation = htmlspecialchars(strip_tags($data->Organisation));
	$sdgs = htmlspecialchars(strip_tags($data->SDGs));
	$address = htmlspecialchars(strip_tags($data->Address));
	$country = htmlspecialchars(strip_tags($data->Country));
	
	// create the product
	if($UserManager->updateUserr($UserId,$organisation,$sdgs,$address,$country)){
	      
		$Items=array();
		$Items["data"]=array();

		$stmt = $UserManager->readUser($UserId);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
	    extract($row);
	   
	    $item=array(
		"userId" => $userid,
		"orgname" => $orgname,
		"email" => $email,
		"address" => $address,
		"country" => $country,
		"sdgs" => $focusgoals,
		
        );
		 
		array_push($Items["data"], $item);
		
		
		http_response_code(200);
		echo json_encode($Items);
		
	}else{

	    http_response_code(503);
            echo json_encode(array("message" => "Unable to Update data. Try again."));
	}
		
}else{

	http_response_code(400);
        echo json_encode(array("message" => "Unable to store data."));
}	
    
   
?>
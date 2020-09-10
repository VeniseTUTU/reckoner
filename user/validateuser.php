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
	!empty($data->Email) &&
	!empty($data->Password)
){

	$user = htmlspecialchars(strip_tags($data->Email));
        $passWord = htmlspecialchars(strip_tags($data->Password));
	
	
        $stmt = $UserManager->validateUser($user);
        $num = $stmt->rowCount();
                
        if($num > 0 ){
	
	  $Items=array();
	        $Items["data"]=array();
		
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	          extract($row);
		  
		  $cleanPass= password_verify($passWord, $password);
		  if(($cleanPass) && ($user == $email)){
			
			
		   $item=array(
		   "UserId" => $userid,
		   
		   );

		   array_push($Items["data"], $item);
		   
		    http_response_code(200);
                  echo json_encode($Items);
		
		  }else{
			
		   http_response_code(503);
                   echo json_encode(array("message" => "Invalid User"));	
		  }
		}
	
	  }else{

	http_response_code(503);
        echo json_encode(array("message" => "User does not exist"));
	}
}else{

	http_response_code(400);
        echo json_encode(array("message" => "Incomplete Data"));
	}
?>
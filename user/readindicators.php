<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/user.php';

$database = new Database();
$db = $database->getConnection();

// initialize Item
$UserManager = new UserManager($db);

// query products
$userid = $_GET['userid'];


$stmt = $UserManager->readUser($userid);
$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0){

	$Items=array();
	$Items["data"]=array();

	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	extract($row);
	   
	$focusgoals = explode(',', $focusgoals);
	
	foreach($focusgoals as $focusgoal){
		
	     $stmtt = $UserManager->readUserIndicators($focusgoal);
	     while($roww = $stmtt->fetch(PDO::FETCH_ASSOC)){
		extract($roww);
		
		$indicator = $indicator.' | '.$factor;
		
		$item=array(
		"indicator" => $indicator,
		"indicatorlabel" => $indicatorlabel,
		
               	);
		 
		array_push($Items["data"], $item);
	      }
	}
	
        http_response_code(200);

	// show items data in json format
	echo json_encode($Items);
}else{

	// set response code - 404 Not found
	http_response_code(404);

	echo json_encode(
		array("message" => "No user found.")
	);
}
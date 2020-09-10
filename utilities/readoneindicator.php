<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/utilities.php';

$database = new Database();
$db = $database->getConnection();

// initialize Item
$UtilitiesManager = new UtilitiesManager($db);

// query products
$indicatorvalue = $_GET['indicator'];


$stmt = $UtilitiesManager->readOneIndicator($indicatorvalue);
$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0){

	$Items=array();
	$Items["data"]=array();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		
		extract($row);
		
		$indicator = $indicator.' | '.$factor;
		
		$item=array(
		"indicator" => $indicator,
				
               	);
		 
		array_push($Items["data"], $item);
	      }
	
	
        http_response_code(200);
        echo json_encode($Items);
}else{

	// set response code - 404 Not found
	http_response_code(404);

	echo json_encode(
		array("message" => "No Indicator found.")
	);
}
?>
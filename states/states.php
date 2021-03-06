<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/report.php';

$database = new Database();
$db = $database->getConnection();

// initialize Item
$ReportManager = new ReportManager($db);

$state = $_GET['country'];

$stmt = $ReportManager->extractstates($state);
$num = $stmt->rowCount();

// check if more than 0 record found
if($num > 0){

	$Items=array();
	$Items["data"]=array();

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	   extract($row);
	   
            $item=array(
	       "state" => $states,
	       "label" => $states,
	       );

		array_push($Items["data"], $item);
	}

	// set response code - 200 OK
	http_response_code(200);

	// show items data in json format
	echo json_encode($Items);
}else{

	http_response_code(404);

	echo json_encode(
		array("message" => "NO DATA")
	);
}



?>
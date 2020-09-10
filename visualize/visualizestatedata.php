<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/visualize.php';

$database = new Database();
$db = $database->getConnection();

// initialize Item
$VisualizeManager = new VisualizeManager($db);


// query products
$state = $_GET['state'];
$indicator = $_GET['indicator'];

$stmt = $VisualizeManager->stateSDGindicator($state,$indicator);
$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0){

	$Items=array(); //$Items=array();
	$Items["data"]=array(); //$Items["data"]=array();

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	   extract($row);
	   
	   $value = round($value);
	   
          $item=array(
	       "year" => $year,
	       "value" => $value,
	       "label" => $label,
	       "state" => $states,
               	);

		array_push($Items["data"], $item);
	}

	// set response code - 200 OK
	http_response_code(200);

	// show items data in json format
	echo json_encode($Items);
}else{

	// set response code - 404 Not found
	http_response_code(404);

	// tell the user no products found
	echo json_encode(
		array("message" => "No country found.")
	);
}
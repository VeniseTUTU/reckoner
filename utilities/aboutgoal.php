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

$goal = $_GET['goal'];

$stmt = $UtilitiesManager->readAboutGoal($goal);
$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0){

	$Items=array();
	$Items["data"]=array();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		extract($row);
		
		$item=array(
		"goalid" => $goalid,
		"goalno" => $goalno,
		"goalname" => $goalname,
		"shortdesc" => $shortdesc,
		"bannerlink" => $bannerlink,
		"preamble" => $preamble,
		"matterlink" => $matterlink,
		"extmatlink" => $extmatlink,
		"extinfolink" => $extinfolink,
		"infograph" => $infograph,
		"facts" => $facts,
		"targets" => $targets,
		);
		 
		array_push($Items["data"], $item);
	      }
	
	
        http_response_code(200);

	// show items data in json format
	echo json_encode($Items);
}else{

	// set response code - 404 Not found
	http_response_code(404);

	echo json_encode(
		array("message" => "No goals found.")
	);
}
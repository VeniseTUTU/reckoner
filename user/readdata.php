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


$stmt = $UserManager->readUserDataHistory($userid);
$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0){

	$Items=array();
	$Items["data"]=array();
     
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	extract($row);
	   
	        
		$formatteDate = date('F d, Y',$date);
		$date = $UserManager->dateFormat($date,$formatteDate);
	         $indicator = $indicator.' | '.$factor;
		  $goal = $goalabbrv.' - '.$goal;
	   
		$item=array(
		   "id" => $dataid,
		   "indicator" => $indicator,
		   "goal" => $goal,
		   "goalsub" => $goallabel,
		   "state" => $states,
		   "year" => $year,
		   "value" => $value,
		   "status" => $authourize,
		   "date" => $date,
		   );
		 
		array_push($Items["data"], $item);
	      }
	      
	      http_response_code(200);
	echo json_encode($Items);
       
}else{

	// set response code - 404 Not found
	http_response_code(404);

	echo json_encode(
		array("message" => "No data found.")
	);
}
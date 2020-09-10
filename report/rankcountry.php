<?php
// required headers
header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include_once '../config/database.php';
include_once '../objects/report.php';

$database = new Database();
$db = $database->getConnection();

// initialize Item
$ReportManager = new ReportManager($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

if(
	!empty($data->country)
){

$country= $data->country;
$stmtcont = $ReportManager->extractCountry($country);
$numm = $stmtcont->rowCount();
if($numm>0){
	
$stmt = $ReportManager->extractstates($country);
$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0){

	$Items=array();
	$Items["data"]=array(); 

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	   extract($row);
	   

$goal1 = $ReportManager->stateSDGgoal($states,'goal_1');
$goal2 = $ReportManager->stateSDGgoal($states,'goal_2');
$goal3 = $ReportManager->stateSDGgoal($states,'goal_3');
$goal4 = $ReportManager->stateSDGgoal($states,'goal_4');
$goal5 = $ReportManager->stateSDGgoal($states,'goal_5');
$goal6 = $ReportManager->stateSDGgoal($states,'goal_6');
$goal7 = $ReportManager->stateSDGgoal($states,'goal_7');
$goal8 = $ReportManager->stateSDGgoal($states,'goal_8');
$goal9 = $ReportManager->stateSDGgoal($states,'goal_9');
$goal10 = $ReportManager->stateSDGgoal($states,'goal_10');
$goal11 = $ReportManager->stateSDGgoal($states,'goal_11');
$goal12 = $ReportManager->stateSDGgoal($states,'goal_12');
$goal13 = $ReportManager->stateSDGgoal($states,'goal_13');
$goal14 = $ReportManager->stateSDGgoal($states,'goal_14');
$goal15 = $ReportManager->stateSDGgoal($states,'goal_15');
$goal16 = $ReportManager->stateSDGgoal($states,'goal_16');
$goal17 = $ReportManager->stateSDGgoal($states,'goal_17');


$composite = array($goal1,$goal2,$goal3,$goal4,$goal5,$goal6,$goal7,$goal8,$goal9,$goal10,$goal11,$goal12,$goal13,$goal14,$goal15,$goal16,$goal17);
$composite= array_filter($composite);
if( sizeof($composite) <=0){
 $composite = 0;	
}else{
$composite =  array_sum($composite)/count($composite);	
}

	    
          $item=array(
	       "state" => $states,
	       "abrv" => $abbrv,
	       "composite" => $composite,
	       "value" => $composite,
	       "region" => $region,
               "goal1" => $goal1, 
               "goal2" => $goal2, 
               "goal3" => $goal3,
	       "goal4" => $goal4, 
               "goal5" => $goal5,
               "goal6" => $goal6,
	       "goal7" => $goal7,
	       "goal8" => $goal8,
	       "goal9" => $goal9,
	       "goal10" => $goal10,
               "goal11" => $goal11,
	       "goal12" => $goal12,
               "goal13" => $goal13,
               "goal14" => $goal14,
	       "goal15" => $goal15,
	       "goal16" => $goal16,
	       "goal17" => $goal17
		);

		array_push($Items["data"], $item);
	}

	// set response code - 200 OK
	http_response_code(200);

	// show items data in json format
	echo json_encode($Items);
}else{

	http_response_code(404);

	// tell the user no products found
	echo json_encode(
		array("message" => "NO DATA.")
	);
}

}else{
	http_response_code(404);

	echo json_encode(
		array("message" => "NO COUNTRY DATA.")
	);
}

}else{
	http_response_code(400);
        // tell the user
	echo json_encode(array("message" => "Data is Incomplete."));
}

?>
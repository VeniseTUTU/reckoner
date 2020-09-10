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
	!empty($data->Indicator) &&
	!empty($data->Goal)
){
        $updateid = htmlspecialchars(strip_tags($data->UpadateId));
	$indicatorr = htmlspecialchars(strip_tags($data->Indicator));
        $goal = htmlspecialchars(strip_tags($data->Goal));
	$state = htmlspecialchars(strip_tags($data->State));
	$year = htmlspecialchars(strip_tags($data->Year));
	$value = htmlspecialchars(strip_tags($data->Value));
	$bearer = htmlspecialchars(strip_tags($data->Bearer));
	$country = htmlspecialchars(strip_tags($data->Country));
	$label = htmlspecialchars(strip_tags($data->Label));
		
	$dataid='';
	for ($i = 0; $i < 12; $i++) {
	  $dataid .= mt_rand('0','9');
	}
	
	$rawindicator = $indicatorr;
	list($indicator,$factor) = explode('|', $rawindicator);
	$indicator = trim($indicator);
	
	$stmt = $UserManager->readOneIndicator($indicator);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$indicatorlabel= $row['indicatorlabel'];
        
                        
	if(strlen($updateid) <= 0){
       
       	if($UserManager->createUserData($dataid,$indicatorlabel,$goal,$state,$year,$label,$value,$bearer,$country)){

		$stm = $UserManager->readOneData($dataid);
                $numm = $stm->rowCount();
		
		if($numm > 0){
		
		$Items=array();
	        $Items["data"]=array();
		
		while ($row = $stm->fetch(PDO::FETCH_ASSOC)){
	          extract($row);
		  
		  $indicator = $indicator.' | '.$factor;
		  $goal = $goalabbrv.' - '.$goal;
	   
		$item=array(
		   "id" => $dataid,
		   "indicator" => $indicator,
		   "goal" => $goal,
		   "state" => $states,
		   "year" => $year,
		   "value" => $value,
		   "status" => $authourize,
		   );

		array_push($Items["data"], $item);	
		
		}
		
		http_response_code(200);
                echo json_encode($Items);
		
		}
		
	}else{

	    http_response_code(503);
            echo json_encode(array("message" => "Unable to store data."));
	}
    
    }else{
        


		if($UserManager->EditUserData($updateid,$indicatorlabel,$goal,$state,$year,$label,$value,$bearer,$country)){

			$stm = $UserManager->readOneData($updateid);
			$numm = $stm->rowCount();
			
			if($numm > 0){
			
			$Items=array();
			$Items["data"]=array();
			
			while ($row = $stm->fetch(PDO::FETCH_ASSOC)){
			  extract($row);
			  
			  $indicator = $indicator.' | '.$factor;
			  $goal = $goalabbrv.' - '.$goal;
		   
			$item=array(
			   "id" => $updateid,
			   "indicator" => $indicator,
			   "goal" => $goal,
			   "state" => $states,
			   "year" => $year,
			   "value" => $value,
			   "status" => $authourize,
			   );
	
			array_push($Items["data"], $item);	
			
			}
			
			http_response_code(200);
			echo json_encode($Items);
			
			}
			
			
		}	

	}
			
	
    
	
    
}else{

	http_response_code(400);
        echo json_encode(array("message" => "Unable to store data."));
}
?>
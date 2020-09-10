<?php
class ReportManager{

	// database connection and table name
	private $conn;
	private $states_table = "states";
        private $datasets_table = "datasets";
	

	// object properties
	public $country;
	public $state;
	public $goal;
	

	// constructor with $db as database connection
	public function __construct($db){
		$this->conn = $db;
	}
        
        // read all states
        function extractstates($country){

	// select all query
	$query = "SELECT distinct
	            states,abbrv,region
		FROM
		    ". $this->states_table."
                WHERE country = '$country'
		ORDER BY
		    date DESC";
		
	// prepare query statement
	$stmt = $this->conn->prepare($query);

	// execute query
	$stmt->execute();

	return $stmt;
		}
		
	function getStateIndicatorValue($states,$indicator){

        $query = "SELECT 
	        AVG(value) AS value
	FROM
	    " . $this->datasets_table. " 
        WHERE states = '$states' AND indicator = '$indicator'
	GROUP BY states
	ORDER BY
	    date DESC";
	    
	$stmt = $this->conn->prepare($query);
    	$stmt->execute();
	$testcount=$stmt->rowCount();
	
   foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $rec){
	$stateValue = "{$rec['value']}";
		
   } 

   return $stateValue;

	}//end of getStateIndicatorValue() function 
	
	function extractCountry($country){

	// select all query
	$query = "SELECT distinct
	            country
		FROM
		    " . $this->datasets_table. " 
                WHERE country = '$country'
		LIMIT 0,1";
		
	// prepare query statement
	$stmt = $this->conn->prepare($query);

	// execute query
	$stmt->execute();

	return $stmt;
        }
	
	function stateSDGgoal($state,$goal){

	// select all query
	$query = "SELECT 
	            states,value,country,indicator,direction,nationaltarget,nationalsignal,unmaxtarget,unmaxsignal
		FROM
		    " . $this->datasets_table. " 
                WHERE states = '$state' AND goal = '$goal'
		ORDER BY
		    date DESC";
		
	$stmt = $this->conn->prepare($query);
    	$stmt->execute();
        $num = $stmt->rowCount();
	
if($num>0){

$NomarlizedValues=array();


while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	
	$States=array();
	extract($row);
	
	$queryy = "SELECT 
	        states, AVG(value) AS value
	FROM
	    " . $this->datasets_table. " 
        WHERE country = '$country' AND indicator = '$indicator'
	GROUP BY states
	ORDER BY
	    date DESC";
	    
	$stmtt = $this->conn->prepare($queryy);
    	$stmtt->execute();
	$testcount=$stmtt->rowCount();
	
   foreach ($stmtt->fetchAll(PDO::FETCH_ASSOC) as $rec){
	$stateValue = "{$rec['value']}";
	array_push($States, $stateValue);
	
   }

$indicatorValue = $this->getStateIndicatorValue($state,$indicator);

switch($direction){
   case 'increasing':

rsort($States); //sort array from hightest to lowest value;
$States= array_filter($States);
$minValue = end($States);  // extract last value in the array sequence
$maxValue =array_slice($States,0,3); //extract first three values from array
$maxValue =  array_sum($maxValue)/count($maxValue);
if($nationalsignal == 'ok'){
$maXValue = $nationaltarget;
}elseif($unmaxsignal == 'ok'){
$maXValue = $unmaxtarget;
}else{
$maXValue = $maxValue;
}

if($indicatorValue >= $maXValue){
  $normalizedValue = 100;
}else{
 $normalize = ($indicatorValue - $minValue)/($maXValue - $minValue) * 100;
 $normalizedValue = round($normalize);
}
array_push($NomarlizedValues, $normalizedValue);

   break;
   case 'decreasing':

sort($States); //sort array from lowest to highest value;
$States= array_filter($States);
$maxValue = end($States);  // extract last value in the array sequence
$targetValue =array_slice($States,0,3); //extract first three values from array
$targetValue =  array_sum($targetValue)/count($targetValue);
if($nationalsignal == 'ok'){
$targeTValue = $nationaltarget;
}elseif($unmaxsignal == 'ok'){
$targeTValue = $unmaxtarget;
}else{
$targeTValue = $targetValue;
}

if($indicatorValue <= $targetValue){
  $normalizedValue = 100;
}else{
 $normalize = (1-($indicatorValue - $targeTValue)/($maxValue - $targeTValue)) * 100;
 $normalizedValue = round($normalize);	
}
array_push($NomarlizedValues, $normalizedValue);

   break;
}

}

$goalValue = array_sum($NomarlizedValues)/count($NomarlizedValues);


}else{
$goalValue = 0;
}

	return $goalValue;
        }
}	
?>	
	
	
	
	
	
	
	
	
	
	
	
	
	

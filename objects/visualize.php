<?php
class VisualizeManager{

	// database connection and table name
	private $conn;
	private $states_table = "states";
        private $datasets_table = "datasets";
	

	
	// constructor with $db as database connection
	public function __construct($db){
		$this->conn = $db;
	}
       
	function countrySDGindicator($country,$indicator){

	// select all query
	$query = "SELECT 
	           users.*,AVG(datasets.value) AS value,datasets.country,datasets.indicator,datasets.year,datasets.label,datasets.authourize
		FROM
		    " . $this->datasets_table. "
		LEFT OUTER JOIN
                    users
		ON datasets.bearer = users.userid
                WHERE datasets.country = '$country' AND datasets.indicator = '$indicator' AND datasets.authourize = 'no'
		GROUP BY datasets.year
		ORDER BY datasets.date DESC
		
		";
		
	$stmt = $this->conn->prepare($query);
    	$stmt->execute();
        
	return $stmt;
        }
	
	function stateSDGindicator($state,$indicator){

	// select all query
	$query = "SELECT 
	            states,value,country,indicator,year,label
		FROM
		    " . $this->datasets_table. " 
                WHERE states = '$state' AND indicator = '$indicator' AND authourize = 'no'
		ORDER BY
		    date DESC";
		
	$stmt = $this->conn->prepare($query);
    	$stmt->execute();
        
	return $stmt;
        }
}	
?>	
	
	
	
	
	
	
	
	
	
	
	
	
	

<?php
class UtilitiesManager{

	// database connection and table name
	private $conn;
	private $users_table = "users";
    private $indicators_table = "indicators";
	private $states_table = "states";
	private $datasets_table = "datasets";
	private $aboutgoals_table = "aboutgoals";
	private $team_table = "team";
	private $volunteer_table = "volunteer";
	private $partner_table = "partner";
	
	public $itemId;
	
	// constructor with $db as database connection
	public function __construct($db){
		$this->conn = $db;
	}
	
	
	function readAllGoals(){

	// select all query
	$query = "SELECT
	            *
		FROM
		    " . $this->indicators_table. " 
                GROUP BY goalabbrv";
		
	$stmt = $this->conn->prepare($query);
    	$stmt->execute();
        
	return $stmt;
        }// end readAllGoals()
	
	function readAllCountries(){

	// select all query
	$query = "SELECT
	            *
		FROM
		    " . $this->states_table. " 
                GROUP BY country";
		
	$stmt = $this->conn->prepare($query);
    	$stmt->execute();
        
	return $stmt;
        }// end readAllGoals()
	
	function readIndicators($focusgoal){

	// select all query
	$query = "SELECT
	            *
		FROM
		    " . $this->indicators_table. "
                WHERE goallabel = '$focusgoal'";
		
	$stmt = $this->conn->prepare($query);
    	$stmt->execute();
        
	return $stmt;
        } // end readUserIndicators()
	
	function readOneIndicator($indicatorvalue){

	// select all query
	$query = "SELECT distinct
	            indicator,factor
		FROM
		    " . $this->indicators_table. "
                WHERE indicatorlabel = '$indicatorvalue'
		LIMIT 0,1";
		
	$stmt = $this->conn->prepare($query);
    	$stmt->execute();
        
	return $stmt;
	} // end readUserIndicators() 
	
	function readAboutGoal($goal){

		// select all query
		$query = "SELECT distinct
			   *
			FROM
			    " . $this->aboutgoals_table. "
			WHERE goalid = '$goal'
			LIMIT 0,1";
			
		$stmt = $this->conn->prepare($query);
		    $stmt->execute();
		
		return $stmt;
	} // end readUserIndicators() 

	function readTargets($focusgoal){

		// select all query
		$query = "SELECT distinct
					target
			FROM
				" . $this->indicators_table. "
					WHERE goallabel = '$focusgoal'";
			
		$stmt = $this->conn->prepare($query);
			$stmt->execute();
			
		return $stmt;
			} // end readTargets()

		function readTeam(){

		// select all query
		$query = "SELECT distinct
					*
			FROM
				" . $this->team_table ;
			
		$stmt = $this->conn->prepare($query);
			$stmt->execute();
			
		return $stmt;
			} // end readTeam()

		function readVolunteerByEmail($email){

				// select all query
				$query = "SELECT 
							*
					FROM
						" . $this->volunteer_table. " 
							WHERE email = '$email' 
					 LIMIT 0,1";
					
				$stmt = $this->conn->prepare($query);
				$stmt->execute();
					
				return $stmt;
		} // readUserByEmail()

		function createVolunteer($firstName,$lastName,$email,$phone,$address,$city,$state,$country,$department){
		
			$date = time();
			$department=substr($department,0,-1);
			
			$queryy = "INSERT INTO
							" . $this->volunteer_table. "
				  SET
					firstname=:firstnamE,lastname=:lastnamE,email=:emaiL,phone=:phonE,
					address=:addresS,city=:citY,state=:statE,country=:countrY,department=:departmenT,date=:datE";
			$stmtt = $this->conn->prepare($queryy);
		
			// bind values
			$stmtt->bindParam(":firstnamE", $firstName);
			$stmtt->bindParam(":lastnamE", $lastName);
			$stmtt->bindParam(":emaiL",$email);
			$stmtt->bindParam(":phonE", $phone);
			$stmtt->bindParam(":addresS", $address);
			$stmtt->bindParam(":citY", $city);
			$stmtt->bindParam(":statE", $state);
			$stmtt->bindParam(":countrY", $country);
			$stmtt->bindParam(":departmenT", $department);
			$stmtt->bindParam(":datE", $date);
			
			
			// execute query
			if($stmtt->execute()){
				return true;
			}else{
				return false;
			}
		
	   } // end createVolunteer()

	   function readPartnerByEmail($email){

		// select all query
		$query = "SELECT 
					*
			FROM
				" . $this->partner_table. " 
					WHERE email = '$email' 
			 LIMIT 0,1";
			
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
			
		return $stmt;
} //readPartnerByEmail()

	   function createPartner($OrganisationName,$Website,$email,$phone,$address,$city,$state,$country,$AboutOrganisation,$Comment,$Sdgs){
		
		$date = time();
		$Sdgs=substr($Sdgs,0,-1);
		
		$queryy = "INSERT INTO
						" . $this->partner_table. "
			  SET
			  orgname=:orgnamE,website=:websitE,email=:emaiL,phone=:phonE,
				address=:addresS,city=:citY,state=:statE,country=:countrY,
				sdgs=:sdgS,about=:abouT,comment=:commenT,date=:datE";
		$stmtt = $this->conn->prepare($queryy);
	
		// bind values
		$stmtt->bindParam(":orgnamE", $OrganisationName);
		$stmtt->bindParam(":websitE", $Website);
		$stmtt->bindParam(":emaiL",$email);
		$stmtt->bindParam(":phonE", $phone);
		$stmtt->bindParam(":addresS", $address);
		$stmtt->bindParam(":citY", $city);
		$stmtt->bindParam(":statE", $state);
		$stmtt->bindParam(":countrY", $country);
		$stmtt->bindParam(":abouT", $AboutOrganisation);
		$stmtt->bindParam(":commenT", $Comment);
		$stmtt->bindParam(":sdgS", $Sdgs);
		$stmtt->bindParam(":datE", $date);
		
		
		// execute query
		if($stmtt->execute()){
			return true;
		}else{
			return false;
		}
	
   } // end createVolunteer()
	
}	
?>	
	
	
	
	
	
	
	
	
	
	
	
	
	

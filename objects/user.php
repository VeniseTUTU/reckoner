<?php
class UserManager{

	// database connection and table name
	private $conn;
	private $users_table = "users";
        private $indicators_table = "indicators";
	private $states_table = "states";
	private $datasets_table = "datasets";

	
	public $itemId;
	
	// constructor with $db as database connection
	public function __construct($db){
		$this->conn = $db;
	}
	
	
	function validateUser($user){

	// select all query
	$query = "SELECT 
	            *
		FROM
		    " . $this->users_table. " 
                WHERE email = '$user' AND status = 'verified'
		ORDER BY
		    date DESC";
		
	$stmt = $this->conn->prepare($query);
    	$stmt->execute();
        
	return $stmt;
	}// end validateUser()

	function verifycode($code,$userID){

		// select all query
		$query = "SELECT 
					*
			FROM
				" . $this->users_table. " 
					WHERE userid = '$userID' AND verifystring = '$code' AND status = 'verified'
					LIMIT 0,1";
			
		$stmt = $this->conn->prepare($query);
			$stmt->execute();
			
		return $stmt;
		}// end verifycode()

	function updateVerifyString($userid,$code){

		// query to update record	
		   $query = "UPDATE
		   " . $this->users_table . " 
		   SET verifystring = '$code'
		   WHERE userid = '$userid'
		   AND status = 'verified' ";
   
		   // prepare query
		   $stmt = $this->conn->prepare($query);
   
		   if($stmt->execute()){
		   return true;
		   }else{
		   return false;
		   }	
   
	   } // end updateVerifyString()

	   function updatePassword($userId,$passWord){

		$PassWord = password_hash($passWord, PASSWORD_DEFAULT);
		// query to update record	
		   $query = "UPDATE
		   " . $this->users_table . " 
		   SET password = '$PassWord'
		   WHERE userid = '$userId'
		   AND status = 'verified' ";
   
		   // prepare query
		   $stmt = $this->conn->prepare($query);
   
		   if($stmt->execute()){
		   return true;
		   }else{
		   return false;
		   }	
   
	   } // end updatePassword()
	
	function readUser($userid){

	// select all query
	$query = "SELECT 
	            *
		FROM
		    " . $this->users_table. " 
                WHERE userid = '$userid' AND status = 'verified'
		ORDER BY
		    date DESC LIMIT 0,1";
		
	$stmt = $this->conn->prepare($query);
    	$stmt->execute();
        
	return $stmt;
        }
	function readUserByEmail($email){

	// select all query
	$query = "SELECT 
	            *
		FROM
		    " . $this->users_table. " 
                WHERE email = '$email' 
		ORDER BY
		    date DESC LIMIT 0,1";
		
	$stmt = $this->conn->prepare($query);
    	$stmt->execute();
        
	return $stmt;
        }
	
	function readUserGoals($focusgoal){

	// select all query
	$query = "SELECT
	            *
		FROM
		    " . $this->indicators_table. " 
                WHERE goalabbrv = '$focusgoal'
		GROUP BY goalabbrv";
		
	$stmt = $this->conn->prepare($query);
    	$stmt->execute();
        
	return $stmt;
        }// end readUserGoals()
	
	function readUserIndicators($focusgoal){

	// select all query
	$query = "SELECT
	            *
		FROM
		    " . $this->indicators_table. " 
                WHERE goalabbrv = '$focusgoal'";
		
	$stmt = $this->conn->prepare($query);
    	$stmt->execute();
        
	return $stmt;
        } // end readUserIndicators()
	
	function readUserstates($country){

	// select all query
	$query = "SELECT distinct
	            states 
		FROM
		    ". $this->states_table. " 
                WHERE country = '$country'";
		
	$stmt = $this->conn->prepare($query);
    	$stmt->execute();
        
	return $stmt;
        } // end readUserstates()
	
	function readOneIndicator($indicator){

	// select all query
	$query = "SELECT
	            *
		FROM
		    " . $this->indicators_table. " 
                WHERE indicator= '$indicator' LIMIT 0,1";
		
	$stmt = $this->conn->prepare($query);
    	$stmt->execute();
        
	return $stmt;
        } // end readUserIndicators()
	
	function readuserData($indicator,$year,$state,$bearer){

	// select all query
	$query = "SELECT
	            *
		FROM
		    " . $this->datasets_table. " 
                WHERE indicator= '$indicator' AND year = '$year' AND states = '$state' AND bearer = '$bearer'
		LIMIT 0,1";
		
	$stmt = $this->conn->prepare($query);
    	$stmt->execute();
        
	return $stmt;
        } // end readUserData()
	
	function createUserData($dataid,$indicator,$goal,$states,$year,$label,$value,$bearer,$country){
		
	$date = time();
	
	$query = "SELECT
	            direction,nationalsignal,unmaxsignal,nationaltarget,unmaxtarget
		FROM
		    " . $this->indicators_table. " 
                WHERE indicatorlabel= '$indicator' LIMIT 0,1";
		
	$stmt = $this->conn->prepare($query);
    	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	extract($row);
	
	
	$queryy = "INSERT INTO
                    " . $this->datasets_table . "
		  SET
		    dataid=:dataiD,indicator=:indicatoR,goal=:goaL,states=:stateS,year=:yeaR,label=:labeL,value=:valuE,bearer=:beareR,country=:countrY,
		    direction=:directioN,nationalsignal=:nationalsignaL,unmaxsignal=:unmaxsignaL,nationaltarget=:nationaltargeT,unmaxtarget=:unmaxtargeT,date=:datE";
	$stmtt = $this->conn->prepare($queryy);

	// bind values
	$stmtt->bindParam(":dataiD", $dataid);
	$stmtt->bindParam(":indicatoR", $indicator);
	$stmtt->bindParam(":goaL", $goal);
	$stmtt->bindParam(":stateS", $states);
	$stmtt->bindParam(":yeaR", $year);
	$stmtt->bindParam(":labeL", $label);
	$stmtt->bindParam(":valuE", $value);
	$stmtt->bindParam(":beareR", $bearer);
	$stmtt->bindParam(":countrY", $country);
	$stmtt->bindParam(":directioN", $direction);
	$stmtt->bindParam(":nationalsignaL", $nationalsignal);
	$stmtt->bindParam(":unmaxsignaL", $unmaxsignal);
	$stmtt->bindParam(":nationaltargeT", $nationaltarget);
	$stmtt->bindParam(":unmaxtargeT", $unmaxtarget);
	$stmtt->bindParam(":datE", $date);
	
	
	// execute query
	if($stmtt->execute()){
		return true;
	}else{
		return false;
	}

        } // end createUserData()
	
	function updateUserData($indicator,$goal,$states,$year,$label,$value,$bearer,$country){
	
	$query = "SELECT
	            direction,nationalsignal,unmaxsignal,nationaltarget,unmaxtarget
		FROM
		    " . $this->indicators_table. " 
                WHERE indicatorlabel= '$indicator' LIMIT 0,1";
		
	$stmt = $this->conn->prepare($query);
    	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	extract($row);
	
        $queryy = "UPDATE
	   " . $this->datasets_table . "
	   SET indicator = '$indicator',goal='$goal',states='$states',year='$year',label='$label',value='$value',bearer='$bearer',country='$country',
	   direction='$direction',nationalsignal='$nationalsignal',unmaxsignal='$unmaxsignal',nationaltarget='$nationaltarget',unmaxtarget='$unmaxtarget'
	   WHERE bearer = '$bearer' AND year = '$year' AND indicator = '$indicator' AND states = '$states' ";
	  
	
// prepare query
$stmtt = $this->conn->prepare($queryy);

if($stmtt->execute()){
		return true;
	}else{
		return false;
	}	
	}// end updateUserData()
	

	function EditUserData($updateid,$indicatorlabel,$goal,$state,$year,$label,$value,$bearer,$country){
	
		$query = "SELECT
					direction,nationalsignal,unmaxsignal,nationaltarget,unmaxtarget
			FROM
				" . $this->indicators_table. " 
					WHERE indicatorlabel= '$indicatorlabel' LIMIT 0,1";
			
		$stmt = $this->conn->prepare($query);
			$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		extract($row);
		
			$queryy = "UPDATE
		   " . $this->datasets_table . "
		   SET indicator = '$indicatorlabel',goal='$goal',states='$state',year='$year',label='$label',value='$value',bearer='$bearer',country='$country',
		   direction='$direction',nationalsignal='$nationalsignal',unmaxsignal='$unmaxsignal',nationaltarget='$nationaltarget',unmaxtarget='$unmaxtarget'
		   WHERE dataid = '$updateid' ";
		  
		
	// prepare query
	$stmtt = $this->conn->prepare($queryy);
	
	if($stmtt->execute()){
			return true;
		}else{
			return false;
		}	
		}// end EditUserData()

    
    function readOneData($dataid){

	// select all query
	$query = "SELECT distinct
	            indicators.*,datasets.states,datasets.year,datasets.value,datasets.authourize
		FROM
		    datasets
                LEFT OUTER JOIN
                    indicators
		ON datasets.indicator = indicators.indicatorlabel
                WHERE dataid = '$dataid'
		LIMIT 0,1";

	// prepare query statement
	$stmt = $this->conn->prepare($query);

	// execute query
	$stmt->execute();

	return $stmt;
        } // end readOneData()
	
	function readUserDataHistory($userid){

	// select all query
	$query = "SELECT
	            indicators.*,datasets.dataid,datasets.states,datasets.year,datasets.value,datasets.authourize,datasets.date
		FROM
		    datasets
                LEFT OUTER JOIN
                    indicators
		ON datasets.indicator = indicators.indicatorlabel
                WHERE bearer = '$userid'
		ORDER BY date DESC";

	// prepare query statement
	$stmt = $this->conn->prepare($query);

	// execute query
	$stmt->execute();

	return $stmt;
        } // end readUserData()
	
	function dateFormat($date,$formatteDate){
$dateNum = intval($date);
  $Currentdate = time()- $dateNum;
  $day = date('z',$Currentdate);
  
  if($day <=0){
     $rdate= "Today";
  }elseif($day <=1){
     $rdate = "Yesterday";
  }elseif($day <=14){
     $rdate = "$day days ago";
  }elseif($day >14){
     $rdate = $formatteDate;
  }
  return $rdate;
	}// end of dateFormat
	
	function createUser_Org($userid,$organisation,$email,$password,$sdgs,$address,$country){
		
	$date = time();
	$status = 'verified';
	$type = 'organisation';
	$sdgs = str_replace(' ',',',trim($sdgs));
	$password = password_hash($password, PASSWORD_DEFAULT);
	
	$queryy = "INSERT INTO
                    " . $this->users_table . "
		  SET
		    status=:statuS,type=:typE,userid=:useriD,focusgoals=:focusgoalS,orgname=:orgnamE,
		    email=:emaiL,password=:passworD,address=:addresS,country=:countrY,
		    date=:datE";
	$stmtt = $this->conn->prepare($queryy);

	// bind values
	$stmtt->bindParam(":statuS", $status);
	$stmtt->bindParam(":typE", $type);
	$stmtt->bindParam(":useriD", $userid);
	$stmtt->bindParam(":focusgoalS", $sdgs);
	$stmtt->bindParam(":orgnamE", $organisation);
	$stmtt->bindParam(":emaiL", $email);
	$stmtt->bindParam(":passworD", $password);
	$stmtt->bindParam(":addresS", $address);
	$stmtt->bindParam(":countrY", $country);
	$stmtt->bindParam(":datE", $date);
	
	
	// execute query
	if($stmtt->execute()){
		return true;
	}else{
		return false;
	}

		} // end createUserData()
		
	function updateUserr($UserId,$organisation,$sdgs,$address,$country){
	
			  $queryy = "UPDATE
			   " . $this->users_table . " 
			   SET orgname = '$organisation',focusgoals='$sdgs',address='$address', country='$country'
			   WHERE userid = '$UserId' ";
			  
			
		// prepare query
		$stmtt = $this->conn->prepare($queryy);
		
		if($stmtt->execute()){
				return true;
			}else{
				return false;
			}	
			}// end updateUserData()
}	
?>	
	
	
	
	
	
	
	
	
	
	
	
	
	

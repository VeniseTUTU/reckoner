<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require "../PHPMailer/PHPMailerAutoload.php";
require '../constants.php';

include_once '../config/database.php';
include_once '../objects/utilities.php';

$database = new Database();
$db = $database->getConnection();

$UtilitiesManager = new UtilitiesManager($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if(
	!empty($data->FirstName) &&
	!empty($data->LastName) && !empty($data->Email) &&
	!empty($data->Phone) && !empty($data->Address) && !empty($data->Country)
){
	$firstName = htmlspecialchars(strip_tags($data->FirstName));
	$lastName = htmlspecialchars(strip_tags($data->LastName));
	$email = htmlspecialchars(strip_tags($data->Email));
    $phone = htmlspecialchars(strip_tags($data->Phone));
	$address = htmlspecialchars(strip_tags($data->Address));
	$city = htmlspecialchars(strip_tags($data->City));
	$state = htmlspecialchars(strip_tags($data->State));
	$country = htmlspecialchars(strip_tags($data->Country));
	$department = htmlspecialchars(strip_tags($data->Department));
		
		
	$stmt = $UtilitiesManager->readVolunteerByEmail($email);
        $num = $stmt->rowCount();
                
        if($num <= 0 ){  
       
       // create the product
	if($UtilitiesManager->createVolunteer($firstName,$lastName,$email,$phone,$address,$city,$state,$country,$department)){

$mailBody=<<<_MAIL_
<div style="width:100%;">
    <h3 style="margin:20px 0;text-align:left;font-size:20px;font-weight:bold;">Volunteer Notification</h3>

    <div style="width:100%;padding:20px;border:1px solid #a09e9e;border-radius:5px">

     <div style="width:100%;padding:20px;border-bottom:1px solid #bbbbbb;margin-bottom:10px">
       <h4 style="text-align:left;font-size:14px;color:#444;margin-bottom:5px"}}>
         Name
       </h4>
       <p style="text-align:left;font-size:16px;color:#444">
	   $firstName &nbsp; $lastName
       </p>
	 </div>
	 <div style="width:100%;padding:20px;border-bottom:1px solid #bbbbbb;margin-bottom:10px">
       <h4 style="text-align:left;font-size:14px;color:#444;margin-bottom:5px"}}>
         Department(s)
       </h4>
       <p style="text-align:left;font-size:16px;color:#444">
	     $department
       </p>
	 </div>
	 <div style="width:100%;padding:20px;border-bottom:1px solid #bbbbbb;margin-bottom:10px">
       <h4 style="text-align:left;font-size:14px;color:#444;margin-bottom:5px"}}>
         Email
       </h4>
       <p style="text-align:left;font-size:16px;color:#444">
	   $email
       </p>
	 </div>
	 <div style="width:100%;padding:20px;border-bottom:1px solid #bbbbbb;margin-bottom:10px">
       <h4 style="text-align:left;font-size:14px;color:#444;margin-bottom:5px"}}>
         Phone
       </h4>
       <p style="text-align:left;font-size:16px;color:#444">
	   $phone
       </p>
	 </div>
	 
	 <div style="width:100%;padding:20px;border-bottom:1px solid #bbbbbb;margin-bottom:10px">
       <h4 style="text-align:left;font-size:14px;color:#444;margin-bottom:5px"}}>
         Address
       </h4>
       <p style="text-align:left;font-size:16px;color:#444">
	    $address &nbsp; $city,$state
       </p>
	 </div>
	 <div style="width:100%;padding:20px;border-bottom:1px solid #bbbbbb;margin-bottom:10px">
       <h4 style="text-align:left;font-size:14px;color:#444;margin-bottom:5px"}}>
         City, State
       </h4>
       <p style="text-align:left;font-size:16px;color:#444">
	    $city, $state
       </p>
	 </div>
	 <div style="width:100%;padding:20px;border-bottom:1px solid #bbbbbb;margin-bottom:10px">
       <h4 style="text-align:left;font-size:14px;color:#444;margin-bottom:5px"}}>
         Country
       </h4>
       <p style="text-align:left;font-size:16px;color:#444">
	     $country
       </p>
	 </div>
	 
    </div>
</div>
_MAIL_;


$mail = new PHPMailer;
$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = SMTPserver;  		      // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = NoReply;       // SMTP username
$mail->Password = NoReplyPass;        // SMTP password
$mail->SMTPSecure = SMTIPencrypt;                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = TCPport;                                    // TCP port to connect to
$mail->setFrom(SMTPsendermail, 'no-reply - CAFSED');

$mail->addAddress(SMTPVolunteermail, 'VOLUNTEER NOTIFICATION');     // Add a recipient
$mail->isHTML(true);                                  // Set email format to HTML
$mail->Subject = "VOLUNTEER NOTIFICATION";
$mail->Body= $mailBody;
$mail->send();
	
	
http_response_code(200);
echo json_encode(array("message" => "Registration Successful."));
		
		
	}else{
 
	    http_response_code(503);
            echo json_encode(array("message" => "Unable to store User. Try again."));
	}
		
	}else{

	    http_response_code(503);
            echo json_encode(array("message" => "User Exists."));
	}
    
    }
	else{

	http_response_code(400);
        echo json_encode(array("message" => "Unable to store User. Data Incomplete."));
}

?>
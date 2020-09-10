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
	!empty($data->Email)
){

	$user = htmlspecialchars(strip_tags($data->Email));
       
    $stmt = $UserManager->validateUser($user);
    $num = $stmt->rowCount();
                
    if($num > 0 ){

		$Items=array();
	    $Items["data"]=array();
		
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	          extract($row);
		  
		   $item=array(
		   "UserId" => $userid,
		   "Email" => $email
		   
		   );

		   array_push($Items["data"], $item);

		   $code='';
		   for ($i = 0; $i < 7; $i++) {
			 $code .= mt_rand('0','9');
		   }

$UserManager->updateVerifyString($userid,$code);

$osEnv=getenv('OS');
$ipAddress=$_SERVER['REMOTE_ADDR'];
$userAgent=$_SERVER['HTTP_USER_AGENT'];
date_default_timezone_set('Africa/lagos');
$date1=date('l, F j, Y');
$date2=date('g:i:s A, Y');

$MessagE=<<<_HTML_
		<section style="width: 95%;border: 1px solid #cfcfcf;border-radius:4px;margin: 0 auto;font-family:Arial;font-size: 12px;">
			  <div style="padding: 20px;">
				  <img src="https://www.cafsed.org/utilities/images/logo.png" alt="cafsed_logo" style="width:215px;height:70px"/>
			  </div>
			  <div style="padding: 40px 20px;font-size: 25px; background-color: #005959;color: #fff;">
				  <span style="font-size: 18px;">Hi</span> $orgname,
			  </div>
			  <div style="padding:20px;color: #5f5f5f;font-weight:bold;font-size: 15px;">
				  $code
			  </div>
			  <div style="color: #5f5f5f;padding: 0 20px 20px 20px;line-height: 20px;">
				 Someone recently requested to reset your account password for "Cafsed.org".
				  Enter the above code into the "Security Code" field in your browser to complete the password reset. <br/><br/>
				<strong>Note:</strong> For your protection, if you did not initiate this request, contact support.
			
			<div style="color: #8c8c8c;line-height: 25px;margin-top: 20px;border-top:2px solid #999999; padding-top: 10px;">
			This notice is the result of a request made by a computer with the IP address of $ipAddress
			through the main site.<br/>
			The remote computer's location appears to be: Nigeria (NG). <br/>
	  
			The remote computer's browser is: $userAgent <br/>
			
			The remote computer's operating system appears to be: $osEnv. <br/>
			
			The system generated this notice on $date1 at $date2 UTC. <br/> <br/>
			Do not reply to this automated message. 
			</div>  
			  </div>
		  </section>
_HTML_;
require "../PHPMailer/PHPMailerAutoload.php";
require '../constants.php';

$mail = new PHPMailer;
$mail->isSMTP();            // Set mailer to use SMTP
$mail->Host = SMTPserver;  	// Specify main and backup SMTP servers
$mail->SMTPAuth = true;      // Enable SMTP authentication
$mail->Username = NoReply;       // SMTP username
$mail->Password = NoReplyPass;        // SMTP password
$mail->SMTPSecure = SMTIPencrypt;      // Enable TLS encryption, `ssl` also accepted
$mail->Port = TCPport;               // TCP port to connect to
$mail->setFrom(NoReplymail, 'CAFSED (NO-REPLY)');

$mail->addAddress($email, '');     // Add a recipient
$mail->isHTML(true);           // Set email format to HTML
$mail->Subject = "Password Reset";
$mail->Body= $MessagE;
$mail->send();
	
		   
		    http_response_code(200);
        echo json_encode($Items);
				 
		}
	
	  }else{

	http_response_code(503);
        echo json_encode(array("message" => "Email does not exist in record"));
	}
}else{

	http_response_code(400);
        echo json_encode(array("message" => "Incomplete Data"));
	}
?>
<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if(
	!empty($data->Name) && 	!empty($data->Email) && !empty($data->Subject) && !empty($data->Message) 
){
        $name = htmlspecialchars(strip_tags($data->Name));
	$email = htmlspecialchars(strip_tags($data->Email));
        $subject = htmlspecialchars(strip_tags($data->Subject));
	$message = htmlspecialchars(strip_tags($data->Message));
	
$mailBody=<<<_MAIL_
$name wrote from the contact page:<br/><br/>
$message <br/><br/>
Reply to $name via: $email
_MAIL_;

require "../PHPMailer/PHPMailerAutoload.php";
require '../constants.php';

$mail = new PHPMailer;
$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = SMTPserver;  		      // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = SMTPuser;                 // SMTP username
$mail->Password = SMTPpassword;                           // SMTP password
$mail->SMTPSecure = SMTIPencrypt;                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = TCPport;                                    // TCP port to connect to
$mail->setFrom(SMTPsendermail, 'CAFSED - Contact');

$mail->addAddress(Reciever, 'INFO EMAIL - CAFSED');     // Add a recipient
$mail->isHTML(true);                                  // Set email format to HTML
$mail->Subject = "$subject";
$mail->Body= $mailBody;

if($mail->send()){
	http_response_code(200);
	echo json_encode(array("message" => "Mail sent."));
}else{
	http_response_code(400);
        echo json_encode(array("message" => "Unable to send mail"));
}



	
}else{

	http_response_code(400);
        echo json_encode(array("message" => "Unable to send mai"));
}
?>
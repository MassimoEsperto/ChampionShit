<?php

require_once '../config/connect_local.php';

// Get the posted data.
$postdata = file_get_contents("php://input");

if(isset($postdata) && !empty($postdata))
{
  // Extract the data.
  $request = json_decode($postdata);
	

  
    // Sanitize.
	$username = mysqli_real_escape_string($con, trim($request->data->username));
	$email = mysqli_real_escape_string($con, trim($request->data->email));  
  
	$subject = "Richiesta Iscrizione Shitcup";

	$message = "
	<html>
	<head>
	<title>HTML email</title>
	</head>
	<body>
	<p>Richiesta Iscrizione Shitcup!</p>
	<table>
	<tr>
	<th>Username</th>
	<th>Email</th>
	</tr>
	<tr>
	<td>".$username."</td>
	<td>".$email."</td>
	</tr>
	</table>
	</body>
	</html>
	";

	// Always set content-type when sending HTML email
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$headers .= 'From: <WorldWideFantashit@shitcup.com>' . "\r\n";  
	

	// Store.
	$sql = "select email from utenti where username = 'MESSOMALE' "; 
  
	$result = mysqli_query( $con , $sql );
	
	while($row = mysqli_fetch_assoc($result))
	{
        $emailTo = $row['email'];
	}
	
    
	try {
    			mail($emailTo,$subject,$message,$headers);
                echo json_encode(['data'=>'ok']);
		} catch (Exception $e) {
                header("HTTP/1.1 500 Internal Server Error");
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode(array('message' => $e->getMessage(), 'code' => 400)));
		}
  
}
else
{
	die('valori non prelevati'. mysqli_error($con));
}
?>

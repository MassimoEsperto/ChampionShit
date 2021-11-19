<?php

require_once '../config/connect_local.php';

// Get the posted data.
$postdata = file_get_contents("php://input");

if(isset($postdata) && !empty($postdata))
{
  // Extract the data.
  $request = json_decode($postdata);
	

  // Validate.
  if(trim($request->data->username) === '' || trim($request->data->ruolo)=== '' )
  {
    die('valori non prelevati'. mysqli_error($con));
  }
	
	// Sanitize.
	$username = mysqli_real_escape_string($con, trim($request->data->username));
	$email = mysqli_real_escape_string($con, trim($request->data->email));
	$id_ruolo=2; 
	$ruolo = mysqli_real_escape_string($con, trim($request->data->ruolo)); 
  
   	$subject = "Modifica account";

	$message = "
	<html>
	<head>
	<title>HTML email</title>
	</head>
	<body>
	<p>Account WorldWideFantashit aggiornato!</p>
	<table>
	<tr>
	<th>Username</th>
	<th>Email</th>
	<th>Ruolo</th>
	</tr>
	<tr>
	<td>".$username."</td>
	<td>".$email."</td>
	<td>".$ruolo."</td>
	</tr>
	</table>
    <br>
    <a href='http://fantashitcup.altervista.org/WorldWideFantashit/ChampionShit'>Visit WorldWideFantashit.com!</a>
	</body>
	</html>
	</body>
	</html>
	";

	// Always set content-type when sending HTML email
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

	// More headers
	$headers .= 'From: <WorldWideFantashit@shitcup.com>' . "\r\n";

	// Update.
	$sql = "UPDATE `utenti` SET `ruolo`='{$id_ruolo}' WHERE `username` = '{$username}' LIMIT 1";

	if(mysqli_query($con,$sql))
	{
		http_response_code(201);
		$ele = [
		  'username' => $username,
		  'email' => $email,
		  'ruolo' => $ruolo
		];
		try {
    			mail($email,$subject,$message,$headers);
                echo json_encode(['data'=>$ele]);
		} catch (Exception $e) {
                header("HTTP/1.1 500 Internal Server Error");
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode(array('message' => $e->getMessage(), 'code' => 400)));
		}
	}
	else
	{
		header("HTTP/1.1 500 Internal Server Error");
		header('Content-Type: application/json; charset=UTF-8');
		die(json_encode(array('message' => 'query errata', 'code' => 400)));
	}
}
else
{
	die('valori non prelevati'. mysqli_error($con));
}
?>
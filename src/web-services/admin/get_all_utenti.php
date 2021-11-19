<?php

require_once '../config/connect_local.php';
    
$element = [];
$sql = "SELECT id_utente,username,email,ruolo,squadra FROM utenti where id_utente < 100";

if($result = mysqli_query($con,$sql))
{
	$ele = 0;
	while($row = mysqli_fetch_assoc($result))
	{
		$element[$ele]['username'] = $row['username'];
		$element[$ele]['squadra'] = $row['squadra'];
		$element[$ele]['email'] = $row['email'];
		$element[$ele]['ruolo'] = $row['ruolo'];
        $element[$ele]['id'] = $row['id_utente'];
		$ele++;
	}
    
	echo json_encode(['data'=>$element]);
}
else
{
	header("HTTP/1.1 500 Internal Server Error");
	header('Content-Type: application/json; charset=UTF-8');
	die(json_encode(array('message' => 'query errata', 'code' => 400)));
}
?>
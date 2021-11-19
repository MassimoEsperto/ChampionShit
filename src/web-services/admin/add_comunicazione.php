<?php

require_once '../config/connect_local.php';

// Get the posted data.
$postdata = file_get_contents("php://input");

if(isset($postdata) && !empty($postdata))
{
	// Extract the data.
	$request = json_decode($postdata);
  
	$dati= $request->data;
    $lista= $dati->utenti;
	$ele = 1;
    
    $id_notifica = mysqli_real_escape_string($con, (int)$dati->notifica);
	
	foreach($lista as $item) 
	{
		// Sanitize.
		$id_utente = mysqli_real_escape_string($con, (int)($item));
		
        // Store.
		$sql .= "INSERT INTO utente_com (comunicazione_id, utente_id) ";
		$sql .= "VALUES ({$id_notifica},{$id_utente}); ";
		$ele++;
	}
	
	if ($con->multi_query($sql) === TRUE) 
	{
		echo json_encode(['data'=>'ok']);
	} 
	else 
	{
		header("HTTP/1.1 500 Internal Server Error");
		header('Content-Type: application/json; charset=UTF-8');
		die(json_encode(array('message' => 'Valori sballati', 'code' => 400)));
	}
	
}
else
{
	die('valori non prelevati'. mysqli_error($con));
}

?>
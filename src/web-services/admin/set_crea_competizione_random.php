<?php

require_once '../config/connect_local.php';

// Get the posted data.
$postdata = file_get_contents("php://input");

if(isset($postdata) && !empty($postdata))
{
	// Extract the data.
	$request = json_decode($postdata);
  
	$dati= $request->data;
    $utenti= $dati->utenti;
	$girone= $dati->girone;
	
	$sql .= "REPLACE INTO calendario(girone,giornata,utente_casa,utente_trasferta) VALUES ('{$girone}',1,{$utenti[0]},{$utenti[1]});";
	$sql .= "REPLACE INTO calendario(girone,giornata,utente_casa,utente_trasferta) VALUES ('{$girone}',1,{$utenti[2]},{$utenti[3]});";
	$sql .= "REPLACE INTO calendario(girone,giornata,utente_casa,utente_trasferta) VALUES ('{$girone}',2,{$utenti[1]},{$utenti[2]});";
	$sql .= "REPLACE INTO calendario(girone,giornata,utente_casa,utente_trasferta) VALUES ('{$girone}',2,{$utenti[3]},{$utenti[0]});";
	$sql .= "REPLACE INTO calendario(girone,giornata,utente_casa,utente_trasferta) VALUES ('{$girone}',3,{$utenti[0]},{$utenti[2]});";
	$sql .= "REPLACE INTO calendario(girone,giornata,utente_casa,utente_trasferta) VALUES ('{$girone}',3,{$utenti[3]},{$utenti[1]});";
	$sql .= "REPLACE INTO calendario(girone,giornata,utente_casa,utente_trasferta) VALUES ('{$girone}',4,{$utenti[1]},{$utenti[3]});";
	$sql .= "REPLACE INTO calendario(girone,giornata,utente_casa,utente_trasferta) VALUES ('{$girone}',4,{$utenti[2]},{$utenti[0]});";
	$sql .= "REPLACE INTO calendario(girone,giornata,utente_casa,utente_trasferta) VALUES ('{$girone}',5,{$utenti[0]},{$utenti[3]});";
	$sql .= "REPLACE INTO calendario(girone,giornata,utente_casa,utente_trasferta) VALUES ('{$girone}',5,{$utenti[2]},{$utenti[1]});";
	$sql .= "REPLACE INTO calendario(girone,giornata,utente_casa,utente_trasferta) VALUES ('{$girone}',6,{$utenti[1]},{$utenti[0]});";
	$sql .= "REPLACE INTO calendario(girone,giornata,utente_casa,utente_trasferta) VALUES ('{$girone}',6,{$utenti[3]},{$utenti[2]});";
    
	
	if ($con->multi_query($sql) === TRUE) 
	{
		$ritono = [
					  'stato' => $con->affected_rows,
					  'risposta' => 'ok'
					];
		echo json_encode(['data'=>$ritono]);
	} 
	else 
	{
		header("HTTP/1.1 500 Internal Server Error");
		header('Content-Type: application/json; charset=UTF-8');
		die(json_encode(array('message' => 'valori sballati', 'code' => 400)));
	}
	
}
else
{
	die('valori non prelevati'. mysqli_error($con));
}

?>
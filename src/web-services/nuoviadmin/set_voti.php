<?php

require_once '../config/connect_local.php';

// Get the posted data.
$postdata = file_get_contents("php://input");

if(isset($postdata) && !empty($postdata))
{
	// Extract the data.
	$request = json_decode($postdata);
  
	$dati= $request->data;
	
	foreach($dati as $squadra) 
	{
		$lista= $squadra->lista;
		$id_partita = mysqli_real_escape_string($con, (int)$squadra->id_partita);
		$id_utente = mysqli_real_escape_string($con, (int)$squadra->id_utente);
		
		foreach($lista as $item) 
		{
			// Sanitize.
			$id_calciatore = mysqli_real_escape_string($con, (int)($item->id_calciatore));
			$voto = mysqli_real_escape_string($con, trim($item->voto));
			
			// Store.
			$sql .= "UPDATE formazioni SET voto='{$voto}' ";
			$sql .="WHERE id_partita={$id_partita} AND id_utente={$id_utente} AND id_calciatore={$id_calciatore};";
			
		}
		
	}
    
    
	
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
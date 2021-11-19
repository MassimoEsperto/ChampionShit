<?php

require_once '../config/connect_local.php';

// Get the posted data.
$postdata = file_get_contents("php://input");

if(isset($postdata) && !empty($postdata))
{
	// Extract the data.
	$request = json_decode($postdata);
  
	$dati= $request->data;
	
	foreach($dati as $partita) 
	{
		$id_partita = mysqli_real_escape_string($con, (int)$partita->id_partita);
		$gol_casa = mysqli_real_escape_string($con, (int)$partita->gol_casa);
		$gol_trasferta = mysqli_real_escape_string($con, (int)$partita->gol_trasferta);
		$pt_casa = mysqli_real_escape_string($con, (int)$partita->pt_casa);
		$pt_trasferta = mysqli_real_escape_string($con, (int)$partita->pt_trasferta);
		
		$sql .= "UPDATE calendario SET calcolato=1,gol_casa={$gol_casa},gol_trasferta={$gol_trasferta},";
        $sql .= " pt_casa={$pt_casa},pt_trasferta={$pt_trasferta} ";
		$sql .="WHERE id_partita={$id_partita} ;";
		
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
<?php

require_once '../config/connect_local.php';
require_once '../config/decode.php';

// Get the posted data.
$postdata = file_get_contents("php://input");

if(isset($postdata) && !empty($postdata))
{
	// Extract the data.
	$request = json_decode($postdata);
  
	$dati= $request->data;
    $lista= $dati->lista;
	$ele = 1;
    
    $id_partita = mysqli_real_escape_string($con, (int)$dati->id_partita);
	
	foreach($lista as $item) 
	{
		// Sanitize.
		$id_calciatore = mysqli_real_escape_string($con, (int)($item));
		
        // Store.
		$sql .= "REPLACE INTO formazioni(id_partita, schieramento, id_utente, id_calciatore) ";
		$sql .= "VALUES ({$id_partita},{$ele},'{$id_utente}',{$id_calciatore});";
		$ele++;
	}
	
	//controllo
	$count = "SELECT * FROM data_partite WHERE now() BETWEEN data_inizio AND data_fine"; 

	$controllo = mysqli_query( $con , $count );

	if ($controllo->num_rows > 0) 
	{
		header("HTTP/1.1 500 Internal Server Error");
		header('Content-Type: application/json; charset=UTF-8');
		die(json_encode(array('message' => 'Non è possibile schierare formazioni per questa data!', 'code' => 400)));

	} 
	else 
	{
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
	
	
	
}
else
{
	die('valori non prelevati'. mysqli_error($con));
}

?>
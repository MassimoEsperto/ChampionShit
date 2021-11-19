<?php

require_once '../config/connect_local.php';

// Get the posted data.
$postdata = file_get_contents("php://input");

if(isset($postdata) && !empty($postdata))
{
	// Extract the data.
	$request = json_decode($postdata);
  
	$dati= $request->data;
	
    $testo= $dati->testo;
	$titolo= $dati->titolo;
	
	$sql .= "INSERT INTO comunicazioni(titolo, messaggio) ";
	$sql .= "VALUES ('{$titolo}','{$testo}');";
    
	
	if ($con->multi_query($sql) === TRUE) 
	{
		echo json_encode(['data'=>'ok']);
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
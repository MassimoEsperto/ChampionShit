<?php

require_once '../config/connect_local.php';

// Get the posted data.
$postdata = file_get_contents("php://input");

if(isset($postdata) && !empty($postdata))
{
  // Extract the data.
  $request = json_decode($postdata);
  
    // Sanitize.
	$dati= $request->data;
	$id_utente = mysqli_real_escape_string($con, trim($dati->id_utente)); 
    $lega = mysqli_real_escape_string($con, trim($dati->lega)); 
	
	foreach($dati->lista as $item) 
	{
		
        $id_calciatore = mysqli_real_escape_string($con, trim($item->id_calciatore));
		
		$sql .= "INSERT INTO `rosa_utente`(`id_utente`,`id_calciatore`) VALUES ('{$id_utente}','{$id_calciatore}');";
		
	}
    
    $sql .= "UPDATE utenti SET lega='{$lega}' WHERE id_utente = {$id_utente} LIMIT 1";

		
	if ($con->multi_query($sql) === TRUE)
	{
    http_response_code(201);
  }
  else
  {
    header("HTTP/1.1 500 Internal Server Error");
	header('Content-Type: application/json; charset=UTF-8');
	die(json_encode(array('message' => 'Valori già esistenti', 'code' => 400)));
  }
}
else
{
	die('valori non prelevati'. mysqli_error($con));
}
?>

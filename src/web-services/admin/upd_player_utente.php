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
    $player_in = mysqli_real_escape_string($con, trim($dati->player_in)); 
    $player_out = mysqli_real_escape_string($con, trim($dati->player_out)); 
	
    
    $sql = "UPDATE rosa_utente SET id_calciatore='{$player_in}' WHERE id_utente = {$id_utente} AND id_calciatore = {$player_out} LIMIT 1";
	
    if(mysqli_query($con, $sql))
    {
        http_response_code(204);
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
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
	
	foreach($dati as $item) 
	{
		$ruolo = mysqli_real_escape_string($con, trim($item->tipo)); 
        $nome_calciatore = mysqli_real_escape_string($con, trim($item->nome));
		
		$sql .= "INSERT INTO `lista_calciatori`(`nome_calciatore`,`ruolo`) VALUES ('{$nome_calciatore}','{$ruolo}');";
		
	}
    
		

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

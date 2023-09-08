<?php

require_once '../config/connect_local.php';

// Get the posted data.
$postdata = file_get_contents("php://input");

if(isset($postdata) && !empty($postdata))
{
  
	$last_id = 0;
	$request = json_decode($postdata);
	$dati= $request->data;

	$username = mysqli_real_escape_string($con, trim($dati->username));
	$email = mysqli_real_escape_string($con, trim($dati->email));  
	$pass = mysqli_real_escape_string($con, trim($dati->password));
	$squadra = mysqli_real_escape_string($con, trim($dati->squadra));  
	$lega = mysqli_real_escape_string($con, trim($dati->lega));  
    $account = mysqli_real_escape_string($con, trim($dati->account)); 
	$players= $dati->players;

	$sql1 .= "INSERT INTO utenti (username,email,password,squadra,lega,account) ";
	$sql1 .= "VALUES ('{$username}','{$email}','{$pass}','{$squadra}','{$lega}','{$account}') ";
	
    if ($con->multi_query($sql1) === TRUE) 
	{
		$last_id = $con->insert_id;
	}
	else
	{
		header("HTTP/1.1 500 Internal Server Error");
		header('Content-Type: application/json; charset=UTF-8');
		die(json_encode(array('message' => 'Utente già esistente', 'code' => 400)));
	}


	foreach($players as $item) 
	{

		$id_calciatore = mysqli_real_escape_string($con, trim($item->id_calciatore));

		$sql2 .= "INSERT INTO rosa_utente (id_utente,id_calciatore) VALUES ('{$last_id}','{$id_calciatore}');";

	}

	if ($con->multi_query($sql2) === TRUE)
	{
		echo json_encode(['data'=>'ok']);
	}
	else
	{
		header("HTTP/1.1 500 Internal Server Error");
		header('Content-Type: application/json; charset=UTF-8');
		die(json_encode(array('message' => 'Rosa non percepita', 'code' => 400)));
	}
}
else
{
	die('valori non prelevati'. mysqli_error($con));
}

?>
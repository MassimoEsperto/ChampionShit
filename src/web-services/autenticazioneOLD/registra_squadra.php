<?php

require_once '../config/connect_local.php';
require_once '../config/post_data.php';


$id_utente = mysqli_real_escape_string($con, trim($dati->id_utente));
$squadra = mysqli_real_escape_string($con, trim($dati->squadra));  
$lega = mysqli_real_escape_string($con, trim($dati->lega));  
$account = mysqli_real_escape_string($con, trim($dati->account)); 

$players= $dati->players;

$sql1 .= "INSERT INTO squadre (utente_id,squadra,lega,account) ";
$sql1 .= "VALUES ('{$id_utente}','{$squadra}','{$lega}','{$account}') ";
	
if ($con->multi_query($sql1) === TRUE) 
{
	$last_id = $con->insert_id;
}
else
{
	errorMessage('query errata: inserimento squadra');
}


foreach($players as $item) 
{

	$id_calciatore = mysqli_real_escape_string($con, trim($item->id_calciatore));

	$sql2 .= "INSERT INTO rosa_utente (id_squadra,id_calciatore) VALUES ('{$last_id}','{$id_calciatore}');";

}

if ($con->multi_query($sql2) === TRUE)
{
	echo json_encode(['data'=>'ok']);
}
else
{
	errorMessage('query errata: inserimento rosa');
}


?>
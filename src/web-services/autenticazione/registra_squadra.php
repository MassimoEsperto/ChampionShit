<?php

require_once '../config/connect_local.php';
require_once '../config/post_data.php';


$id_utente = mysqli_real_escape_string($con, trim($dati->id_utente));
$squadra = mysqli_real_escape_string($con, trim($dati->squadra));  
$lega = mysqli_real_escape_string($con, trim($dati->lega));  
$account = mysqli_real_escape_string($con, trim($dati->account)); 

$players = $dati->players;

$sql1 .= "INSERT INTO squadre (utente_id,squadra,lega,account) ";
$sql1 .= "VALUES ('{$id_utente}',UPPER('{$squadra}'),'{$lega}','{$account}') ";


$controllo1 = "SELECT * FROM squadre WHERE squadra = '{$squadra}' ";
$controllo2 = "SELECT * FROM squadre WHERE lega = '{$lega}' AND account = '{$account}' ";

$result = mysqli_query( $con , $controllo1 );

if(! $result ) 
{
   errorMessage('query errata: controllo squadra');
}

if ($result->num_rows > 0) 
{
	 errorMessage('squadra gia presente');
}

$result = mysqli_query( $con , $controllo2 );

if(! $result ) 
{
   errorMessage('query errata: controllo squadra');
}

if ($result->num_rows > 0) 
{
	 errorMessage('squadra e account gia presente');
}


	
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

	$sql2 .= "INSERT INTO rose (squadra_id,calciatore_id) VALUES ('{$last_id}','{$id_calciatore}');";

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
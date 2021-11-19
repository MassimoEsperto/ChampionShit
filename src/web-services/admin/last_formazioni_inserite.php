<?php

require_once '../config/connect_local.php';
    
$element = [];
$sql = "SELECT MAX(id_partita) as id_partita,id_utente,schieramento ,id_calciatore FROM formazioni WHERE is_inserita=1 group by id_utente,schieramento";

if($result = mysqli_query($con,$sql))
{
	$ele = 0;
	while($row = mysqli_fetch_assoc($result))
	{
		$element[$ele]['id_partita'] = $row['id_partita'];
		$element[$ele]['id_utente'] = $row['id_utente'];
		$element[$ele]['schieramento'] = $row['schieramento'];
		$element[$ele]['id_calciatore'] = $row['id_calciatore'];
		$ele++;
	}
    
	echo json_encode(['data'=>$element]);
}
else
{
	header("HTTP/1.1 500 Internal Server Error");
	header('Content-Type: application/json; charset=UTF-8');
	die(json_encode(array('message' => 'query errata', 'code' => 400)));
}
?>
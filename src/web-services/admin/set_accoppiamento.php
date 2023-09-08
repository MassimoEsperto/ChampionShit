<?php

require_once '../config/connect_local.php';
require_once '../config/post_data.php';

$casa = mysqli_real_escape_string($con, trim($dati->casa)); 
$trasferta = mysqli_real_escape_string($con, trim($dati->trasferta)); 
$giornata = mysqli_real_escape_string($con, trim($dati->giornata)); 


$sql .= "REPLACE INTO calendario(giornata_id) VALUES ({$giornata});";
$sql .= "REPLACE INTO risultati(luogo,squadra_id,calendario_id) VALUES ('CASA',{$casa},(SELECT MAX(id_calendario) FROM calendario));";
$sql .= "REPLACE INTO risultati(luogo,squadra_id,calendario_id) VALUES ('TRASFERTA',{$trasferta},(SELECT MAX(id_calendario) FROM calendario));";


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
	errorMessage('query errata: accoppiamento ');
}
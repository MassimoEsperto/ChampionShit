<?php

require_once '../config/connect_local.php';
require_once '../config/post_data.php';


$squadre= $dati->squadre;
$girone= $dati->girone;

//giornata 1
$sql .= "REPLACE INTO calendario(girone,giornata_id) VALUES ('{$girone}',1);";
$sql .= "REPLACE INTO risultati(luogo,squadra_id,calendario_id) VALUES ('CASA',{$squadre[0]},(SELECT MAX(id_calendario) FROM calendario));";
$sql .= "REPLACE INTO risultati(luogo,squadra_id,calendario_id) VALUES ('TRASFERTA',{$squadre[1]},(SELECT MAX(id_calendario) FROM calendario));";
$sql .= "REPLACE INTO calendario(girone,giornata_id) VALUES ('{$girone}',1);";
$sql .= "REPLACE INTO risultati(luogo,squadra_id,calendario_id) VALUES ('CASA',{$squadre[2]},(SELECT MAX(id_calendario) FROM calendario));";
$sql .= "REPLACE INTO risultati(luogo,squadra_id,calendario_id) VALUES ('TRASFERTA',{$squadre[3]},(SELECT MAX(id_calendario) FROM calendario));";


//giornata 2
$sql .= "REPLACE INTO calendario(girone,giornata_id) VALUES ('{$girone}',2);";
$sql .= "REPLACE INTO risultati(luogo,squadra_id,calendario_id) VALUES ('CASA',{$squadre[1]},(SELECT MAX(id_calendario) FROM calendario));";
$sql .= "REPLACE INTO risultati(luogo,squadra_id,calendario_id) VALUES ('TRASFERTA',{$squadre[2]},(SELECT MAX(id_calendario) FROM calendario));";
$sql .= "REPLACE INTO calendario(girone,giornata_id) VALUES ('{$girone}',2);";
$sql .= "REPLACE INTO risultati(luogo,squadra_id,calendario_id) VALUES ('CASA',{$squadre[3]},(SELECT MAX(id_calendario) FROM calendario));";
$sql .= "REPLACE INTO risultati(luogo,squadra_id,calendario_id) VALUES ('TRASFERTA',{$squadre[0]},(SELECT MAX(id_calendario) FROM calendario));";


//giornata 3
$sql .= "REPLACE INTO calendario(girone,giornata_id) VALUES ('{$girone}',3);";
$sql .= "REPLACE INTO risultati(luogo,squadra_id,calendario_id) VALUES ('CASA',{$squadre[0]},(SELECT MAX(id_calendario) FROM calendario));";
$sql .= "REPLACE INTO risultati(luogo,squadra_id,calendario_id) VALUES ('TRASFERTA',{$squadre[2]},(SELECT MAX(id_calendario) FROM calendario));";
$sql .= "REPLACE INTO calendario(girone,giornata_id) VALUES ('{$girone}',3);";
$sql .= "REPLACE INTO risultati(luogo,squadra_id,calendario_id) VALUES ('CASA',{$squadre[3]},(SELECT MAX(id_calendario) FROM calendario));";
$sql .= "REPLACE INTO risultati(luogo,squadra_id,calendario_id) VALUES ('TRASFERTA',{$squadre[1]},(SELECT MAX(id_calendario) FROM calendario));";


//giornata 4
$sql .= "REPLACE INTO calendario(girone,giornata_id) VALUES ('{$girone}',4);";
$sql .= "REPLACE INTO risultati(luogo,squadra_id,calendario_id) VALUES ('CASA',{$squadre[1]},(SELECT MAX(id_calendario) FROM calendario));";
$sql .= "REPLACE INTO risultati(luogo,squadra_id,calendario_id) VALUES ('TRASFERTA',{$squadre[3]},(SELECT MAX(id_calendario) FROM calendario));";
$sql .= "REPLACE INTO calendario(girone,giornata_id) VALUES ('{$girone}',4);";
$sql .= "REPLACE INTO risultati(luogo,squadra_id,calendario_id) VALUES ('CASA',{$squadre[2]},(SELECT MAX(id_calendario) FROM calendario));";
$sql .= "REPLACE INTO risultati(luogo,squadra_id,calendario_id) VALUES ('TRASFERTA',{$squadre[0]},(SELECT MAX(id_calendario) FROM calendario));";


//giornata 5
$sql .= "REPLACE INTO calendario(girone,giornata_id) VALUES ('{$girone}',5);";
$sql .= "REPLACE INTO risultati(luogo,squadra_id,calendario_id) VALUES ('CASA',{$squadre[0]},(SELECT MAX(id_calendario) FROM calendario));";
$sql .= "REPLACE INTO risultati(luogo,squadra_id,calendario_id) VALUES ('TRASFERTA',{$squadre[3]},(SELECT MAX(id_calendario) FROM calendario));";
$sql .= "REPLACE INTO calendario(girone,giornata_id) VALUES ('{$girone}',5);";
$sql .= "REPLACE INTO risultati(luogo,squadra_id,calendario_id) VALUES ('CASA',{$squadre[2]},(SELECT MAX(id_calendario) FROM calendario));";
$sql .= "REPLACE INTO risultati(luogo,squadra_id,calendario_id) VALUES ('TRASFERTA',{$squadre[1]},(SELECT MAX(id_calendario) FROM calendario));";


//giornata 6
$sql .= "REPLACE INTO calendario(girone,giornata_id) VALUES ('{$girone}',6);";
$sql .= "REPLACE INTO risultati(luogo,squadra_id,calendario_id) VALUES ('CASA',{$squadre[1]},(SELECT MAX(id_calendario) FROM calendario));";
$sql .= "REPLACE INTO risultati(luogo,squadra_id,calendario_id) VALUES ('TRASFERTA',{$squadre[0]},(SELECT MAX(id_calendario) FROM calendario));";
$sql .= "REPLACE INTO calendario(girone,giornata_id) VALUES ('{$girone}',6);";
$sql .= "REPLACE INTO risultati(luogo,squadra_id,calendario_id) VALUES ('CASA',{$squadre[3]},(SELECT MAX(id_calendario) FROM calendario));";
$sql .= "REPLACE INTO risultati(luogo,squadra_id,calendario_id) VALUES ('TRASFERTA',{$squadre[2]},(SELECT MAX(id_calendario) FROM calendario));";



	
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
	errorMessage('query errata: calendario ');
}
	

?>
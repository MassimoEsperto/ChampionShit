<?php

require_once '../config/connect_local.php';
require_once '../config/post_data.php';


$utenti= $dati->utenti;
$girone= $dati->girone;

//giornata 1
$sql .= "REPLACE INTO calendarioNEW(girone,giornata_id) VALUES ('{$girone}',1);";
$sql .= "REPLACE INTO risultati(luogo,utente_id,calendario_id) VALUES ('CASA',{$utenti[0]},(SELECT MAX(id_calendario) FROM calendarioNEW));";
$sql .= "REPLACE INTO risultati(luogo,utente_id,calendario_id) VALUES ('TRASFERTA',{$utenti[1]},(SELECT MAX(id_calendario) FROM calendarioNEW));";
$sql .= "REPLACE INTO calendarioNEW(girone,giornata_id) VALUES ('{$girone}',1);";
$sql .= "REPLACE INTO risultati(luogo,utente_id,calendario_id) VALUES ('CASA',{$utenti[2]},(SELECT MAX(id_calendario) FROM calendarioNEW));";
$sql .= "REPLACE INTO risultati(luogo,utente_id,calendario_id) VALUES ('TRASFERTA',{$utenti[3]},(SELECT MAX(id_calendario) FROM calendarioNEW));";


//giornata 2
$sql .= "REPLACE INTO calendarioNEW(girone,giornata_id) VALUES ('{$girone}',2);";
$sql .= "REPLACE INTO risultati(luogo,utente_id,calendario_id) VALUES ('CASA',{$utenti[1]},(SELECT MAX(id_calendario) FROM calendarioNEW));";
$sql .= "REPLACE INTO risultati(luogo,utente_id,calendario_id) VALUES ('TRASFERTA',{$utenti[2]},(SELECT MAX(id_calendario) FROM calendarioNEW));";
$sql .= "REPLACE INTO calendarioNEW(girone,giornata_id) VALUES ('{$girone}',2);";
$sql .= "REPLACE INTO risultati(luogo,utente_id,calendario_id) VALUES ('CASA',{$utenti[3]},(SELECT MAX(id_calendario) FROM calendarioNEW));";
$sql .= "REPLACE INTO risultati(luogo,utente_id,calendario_id) VALUES ('TRASFERTA',{$utenti[0]},(SELECT MAX(id_calendario) FROM calendarioNEW));";


//giornata 3
$sql .= "REPLACE INTO calendarioNEW(girone,giornata_id) VALUES ('{$girone}',3);";
$sql .= "REPLACE INTO risultati(luogo,utente_id,calendario_id) VALUES ('CASA',{$utenti[0]},(SELECT MAX(id_calendario) FROM calendarioNEW));";
$sql .= "REPLACE INTO risultati(luogo,utente_id,calendario_id) VALUES ('TRASFERTA',{$utenti[2]},(SELECT MAX(id_calendario) FROM calendarioNEW));";
$sql .= "REPLACE INTO calendarioNEW(girone,giornata_id) VALUES ('{$girone}',3);";
$sql .= "REPLACE INTO risultati(luogo,utente_id,calendario_id) VALUES ('CASA',{$utenti[3]},(SELECT MAX(id_calendario) FROM calendarioNEW));";
$sql .= "REPLACE INTO risultati(luogo,utente_id,calendario_id) VALUES ('TRASFERTA',{$utenti[1]},(SELECT MAX(id_calendario) FROM calendarioNEW));";


//giornata 4
$sql .= "REPLACE INTO calendarioNEW(girone,giornata_id) VALUES ('{$girone}',4);";
$sql .= "REPLACE INTO risultati(luogo,utente_id,calendario_id) VALUES ('CASA',{$utenti[1]},(SELECT MAX(id_calendario) FROM calendarioNEW));";
$sql .= "REPLACE INTO risultati(luogo,utente_id,calendario_id) VALUES ('TRASFERTA',{$utenti[3]},(SELECT MAX(id_calendario) FROM calendarioNEW));";
$sql .= "REPLACE INTO calendarioNEW(girone,giornata_id) VALUES ('{$girone}',4);";
$sql .= "REPLACE INTO risultati(luogo,utente_id,calendario_id) VALUES ('CASA',{$utenti[2]},(SELECT MAX(id_calendario) FROM calendarioNEW));";
$sql .= "REPLACE INTO risultati(luogo,utente_id,calendario_id) VALUES ('TRASFERTA',{$utenti[0]},(SELECT MAX(id_calendario) FROM calendarioNEW));";


//giornata 5
$sql .= "REPLACE INTO calendarioNEW(girone,giornata_id) VALUES ('{$girone}',5);";
$sql .= "REPLACE INTO risultati(luogo,utente_id,calendario_id) VALUES ('CASA',{$utenti[0]},(SELECT MAX(id_calendario) FROM calendarioNEW));";
$sql .= "REPLACE INTO risultati(luogo,utente_id,calendario_id) VALUES ('TRASFERTA',{$utenti[3]},(SELECT MAX(id_calendario) FROM calendarioNEW));";
$sql .= "REPLACE INTO calendarioNEW(girone,giornata_id) VALUES ('{$girone}',5);";
$sql .= "REPLACE INTO risultati(luogo,utente_id,calendario_id) VALUES ('CASA',{$utenti[2]},(SELECT MAX(id_calendario) FROM calendarioNEW));";
$sql .= "REPLACE INTO risultati(luogo,utente_id,calendario_id) VALUES ('TRASFERTA',{$utenti[1]},(SELECT MAX(id_calendario) FROM calendarioNEW));";


//giornata 6
$sql .= "REPLACE INTO calendarioNEW(girone,giornata_id) VALUES ('{$girone}',6);";
$sql .= "REPLACE INTO risultati(luogo,utente_id,calendario_id) VALUES ('CASA',{$utenti[1]},(SELECT MAX(id_calendario) FROM calendarioNEW));";
$sql .= "REPLACE INTO risultati(luogo,utente_id,calendario_id) VALUES ('TRASFERTA',{$utenti[0]},(SELECT MAX(id_calendario) FROM calendarioNEW));";
$sql .= "REPLACE INTO calendarioNEW(girone,giornata_id) VALUES ('{$girone}',6);";
$sql .= "REPLACE INTO risultati(luogo,utente_id,calendario_id) VALUES ('CASA',{$utenti[3]},(SELECT MAX(id_calendario) FROM calendarioNEW));";
$sql .= "REPLACE INTO risultati(luogo,utente_id,calendario_id) VALUES ('TRASFERTA',{$utenti[2]},(SELECT MAX(id_calendario) FROM calendarioNEW));";



	
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
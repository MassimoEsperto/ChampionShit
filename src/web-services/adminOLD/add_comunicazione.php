<?php

require_once '../config/connect_local.php';
require_once '../config/post_data.php';

$lista= $dati->utenti;
$ele = 1;
    
$id_notifica = mysqli_real_escape_string($con, (int)$dati->notifica);
	
foreach($lista as $item) 
{
	// Sanitize.
	$id_utente = mysqli_real_escape_string($con, (int)($item));
	
    // Store.
	$sql .= "INSERT INTO utente_com (comunicazione_id, utente_id) ";
	$sql .= "VALUES ({$id_notifica},{$id_utente}); ";
	$ele++;
}
	
if ($con->multi_query($sql) === TRUE) 
{
	echo json_encode(['data'=>'ok']);
} 
else {
	errorMessage('query errata: aggiungi comunicazione');
}


?>
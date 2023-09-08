<?php

require_once '../config/turno.php';
require_once '../config/decode.php';
require_once '../config/post_data.php';

$lista= $dati->lista;
$ele = 1;
    
$id_partita = mysqli_real_escape_string($con, (int)$dati->id_partita);

foreach($lista as $item) 
{
	// Sanitize.
	$id_calciatore = mysqli_real_escape_string($con, (int)($item));
	
	$sql .= "REPLACE INTO formazioni(id_partita, schieramento, id_utente, id_calciatore) ";
	$sql .= "VALUES ({$id_partita},{$ele},'{$id_utente}',{$id_calciatore});";
	$ele++;
}

//controllo
if ($turno['periodo'] != 1) 
{
	errorMessage('Non è possibile schierare formazioni per questa data!');
} 
else 
{
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
        errorMessage('valori sballati');
	}
}

?>
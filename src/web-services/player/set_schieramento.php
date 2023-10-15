<?php
require_once '../config/connect_local.php';
require_once '../common/turno.php';
require_once '../config/decode.php';
require_once '../config/post_data.php';


$lista = $dati->lista;
$ele = 1;
    
$id_risultato = mysqli_real_escape_string($con, (int)$dati->id_risultato);
$id_modulo = mysqli_real_escape_string($con, (int)$dati->id_modulo);

foreach($lista as $item) 
{
	// Sanitize.
	$id_calciatore = mysqli_real_escape_string($con, (int)($item));
	
	$sql .= "REPLACE INTO formazioni(risultato_id, schieramento, calciatore_id) ";
	$sql .= "VALUES ({$id_risultato}, {$ele}, {$id_calciatore}); ";
	$ele++;
}

$sql .=  "UPDATE risultati SET modulo_id = {$id_modulo} ";
$sql .= "WHERE id_risultato = {$id_risultato} LIMIT 1 ; ";

//controllo
if ($turno_['periodo'] != 1) 
{
	errorMessage('Non è possibile schierare formazioni per questa data!');
} 
else 
{
	if ($con->multi_query($sql) === TRUE) 
	{
		$ritono = [
					 'stato' => $con->affected_rows,
                     'risposta' =>  $sql
					];
		echo json_encode(['data'=>$ritono]);
	} 
	else 
	{
        errorMessage('valori sballati');
	}
}

?>
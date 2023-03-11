<?php

require_once '../config/connect_local.php';
require_once '../config/post_data.php';

$tabella = mysqli_real_escape_string($con, trim($dati->tabella)); 
$id_nome = mysqli_real_escape_string($con, trim($dati->id_nome)); 
$id_valore = mysqli_real_escape_string($con, trim($dati->id_valore)); 
 

$sql = "DELETE FROM {$tabella} WHERE {$id_nome} = {$id_valore} ";

if(mysqli_query($con, $sql))
{
	http_response_code(204);
}
else
{

	errorMessage('query errata: elimina' . $tabella);
}

		
?>
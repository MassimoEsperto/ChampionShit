<?php

require_once '../config/connect_local.php';
require_once '../config/post_data.php';


$id_utente = mysqli_real_escape_string($con, trim($dati->id_utente)); 
$lega = mysqli_real_escape_string($con, trim($dati->lega)); 
 


foreach($dati->lista as $item) 
{		
    $id_calciatore = mysqli_real_escape_string($con, trim($item->id_calciatore));
		
	$sql .= "INSERT INTO `rosa_utente`(`id_utente`,`id_calciatore`) VALUES ('{$id_utente}','{$id_calciatore}');";		
}
    
$sql .= "UPDATE utenti SET lega='{$lega}' WHERE id_utente = {$id_utente} LIMIT 1";
    
	
if ($con->multi_query($sql) === TRUE) 
{
	http_response_code(201);
}else{
	errorMessage('query errata: calcola giornata');
}



?>
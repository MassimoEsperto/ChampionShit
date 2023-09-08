<?php

require_once '../config/connect_local.php';
require_once '../config/post_data.php';


$id_squadra = mysqli_real_escape_string($con, trim($dati->id_squadra)); 
$lega = mysqli_real_escape_string($con, trim($dati->lega)); 
 


foreach($dati->lista as $item) 
{		
    $id_calciatore = mysqli_real_escape_string($con, trim($item->id_calciatore));
		
	$sql .= "INSERT INTO `rose`(`squadra_id`,`calciatore_id`) VALUES ('{$id_squadra}','{$id_calciatore}');";		
}
    
$sql .= "UPDATE squadre SET lega='{$lega}' WHERE id_squadra = {$id_squadra} LIMIT 1";
    
	
if ($con->multi_query($sql) === TRUE) 
{
	http_response_code(201);
}else{
	errorMessage('query errata: aggiorna rosa');
}



?>
<?php

require_once '../config/connect_local.php';
require_once '../config/post_data.php';


foreach($dati as $item) 
{
	$ruolo = mysqli_real_escape_string($con, trim($item->tipo)); 
    $nome_calciatore = mysqli_real_escape_string($con, trim($item->nome));
		
	$sql .= "INSERT INTO `lista_calciatori`(`nome_calciatore`,`nickname`,`ruolo`) VALUES ('{$nome_calciatore}','{$nome_calciatore}','{$ruolo}');";
		
}
    
	
if ($con->multi_query($sql) === TRUE) 
{
	http_response_code(201);
}else{
	errorMessage('query errata: calcola giornata');
}



?>
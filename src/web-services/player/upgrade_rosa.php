<?php

require_once '../config/connect_local.php';
require_once '../config/decode.php';
require_once '../config/post_data.php';

$lega = mysqli_real_escape_string($con, trim($dati->lega)); 

//elimina rosa esistente
$sql1 = "DELETE FROM rose WHERE squadra_id = {$id_squadra} ";

if(mysqli_query($con, $sql1))
{
    
}
else
{
     errorMessage('query elimina errata');
}

//assegna la nuova rosa	
foreach($dati->lista as $item) 
{	
	$id_calciatore = mysqli_real_escape_string($con, trim($item->id_calciatore));	
	$sql2 .= "INSERT INTO `rose`(`squadra_id`,`calciatore_id`) VALUES ('{$id_squadra}','{$id_calciatore}');";		
}

		
if ($con->multi_query($sql2) === TRUE)
{
	$ritono = [
				'stato' => $con->affected_rows,
				'risposta' => 'ok'
			  ];
	echo json_encode(['data'=>$ritono]);
}
else
{
     errorMessage('query update errata');
}
		
?>
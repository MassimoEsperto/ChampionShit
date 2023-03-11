<?php

require_once '../config/connect_local.php';
require_once '../config/post_data.php';

$testo= $dati->testo;
$titolo= $dati->titolo;
	
$sql .= "INSERT INTO comunicazioni(titolo, messaggio) ";
$sql .= "VALUES ('{$titolo}','{$testo}');";

if(mysqli_query($con, $sql))
{
	echo json_encode(['data'=>'ok']);
}
else
{

	errorMessage('query errata: comunicazioni ');
}

		
?>


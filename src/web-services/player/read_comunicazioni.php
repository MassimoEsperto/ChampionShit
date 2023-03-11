<?php
//da eliminare
require_once '../config/connect_local.php';
require_once '../config/decode.php';

$sql = "UPDATE utente_com SET visualizzata=1 WHERE utente_id = {$id_utente} ";

if(mysqli_query($con,$sql))
{
	echo json_encode(['data'=>'ok']);
}
else
{
	errorMessage('query errata: comunicazioni');
}

?>

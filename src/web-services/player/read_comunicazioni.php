<?php

require_once '../config/connect_local.php';
require_once '../config/decode.php';

$sql = "UPDATE utente_com SET visualizzata=1 WHERE utente_id = {$id_utente} ";

if(mysqli_query($con,$sql))
{
	echo json_encode(['data'=>'ok']);
}
else
{
	header("HTTP/1.1 500 Internal Server Error");
	header('Content-Type: application/json; charset=UTF-8');
	die(json_encode(array('message' => 'query errata', 'code' => 400)));
}

?>

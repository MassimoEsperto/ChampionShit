<?php

require_once '../config/connect_local.php';

// Extract, validate and sanitize the id.
$id_utente = ($_GET['id_utente'] !== null && $_GET['id_utente'] !== '')? mysqli_real_escape_string($con, trim($_GET['id_utente'])) : false;


if(!$id_utente)
{
  die('valori non prelevati'. mysqli_error($con));
}

// Delete.
$sql = "DELETE FROM `rosa_utente` WHERE `id_utente` ='{$id_utente}' ";

if(mysqli_query($con, $sql))
{
	http_response_code(204);
}
else
{
	header("HTTP/1.1 500 Internal Server Error");
	header('Content-Type: application/json; charset=UTF-8');
	die(json_encode(array('message' => 'query errata', 'code' => 400)));
}
?>
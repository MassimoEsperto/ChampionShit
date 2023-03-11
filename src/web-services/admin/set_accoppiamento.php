<?php

require_once '../config/connect_local.php';
require_once '../config/post_data.php';

$casa = mysqli_real_escape_string($con, trim($dati->casa)); 
$trasferta = mysqli_real_escape_string($con, trim($dati->trasferta)); 
$giornata = mysqli_real_escape_string($con, trim($dati->giornata)); 
$fase = mysqli_real_escape_string($con, trim($dati->fase)); 

$sql .= "INSERT INTO calendario(fase, giornata,utente_casa,utente_trasferta) ";
$sql .= "VALUES ('{$fase}','{$giornata}','{$casa}','{$trasferta}');";


if(mysqli_query($con, $sql))
{
     http_response_code(204);
}
else
{
     errorMessage('query errata');
}
<?php

require_once '../config/connect_local.php';
require_once '../config/post_data.php';

$id_partita = mysqli_real_escape_string($con, trim($dati->id_partita)); 
$casa = mysqli_real_escape_string($con, trim($dati->casa)); 
$trasferta = mysqli_real_escape_string($con, trim($dati->trasferta)); 
$giornata = mysqli_real_escape_string($con, trim($dati->giornata)); 
$fase = mysqli_real_escape_string($con, trim($dati->fase)); 

$sql  ="UPDATE calendario ";
$sql .="SET fase='{$fase}',giornata='{$giornata}',utente_casa='{$casa}',utente_trasferta='{$trasferta}' ";
$sql .="WHERE id_partita = {$id_partita} LIMIT 1";

if(mysqli_query($con, $sql))
{
     http_response_code(204);
}
else
{
     errorMessage('query errata');
}
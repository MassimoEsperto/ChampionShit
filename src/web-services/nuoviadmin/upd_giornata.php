<?php

require_once '../config/connect_local.php';
require_once '../config/post_data.php';

$inizio_giornata = mysqli_real_escape_string($con, trim($dati->inizio_giornata)); 
$prima_partita = mysqli_real_escape_string($con, trim($dati->prima_partita)); 
$ultima_partita = mysqli_real_escape_string($con, trim($dati->ultima_partita)); 
$fine_giornata = mysqli_real_escape_string($con, trim($dati->fine_giornata)); 
$serie_a= mysqli_real_escape_string($con, trim($dati->serie_a)); 
$fase = mysqli_real_escape_string($con, trim($dati->fase)); 
$is_upgrade = mysqli_real_escape_string($con, trim($dati->is_upgrade));

$giornata = mysqli_real_escape_string($con, trim($dati->giornata)); 

$sql  ="UPDATE giornate ";
$sql .="SET inizio_giornata='{$inizio_giornata}',prima_partita='{$prima_partita}' , ";
$sql .="ultima_partita='{$ultima_partita}',fine_giornata='{$fine_giornata}', ";
$sql .="serie_a={$serie_a},fase_id={$fase},is_upgrade={$is_upgrade} ";
$sql .="WHERE id_giornata = {$giornata} LIMIT 1";

if(mysqli_query($con, $sql))
{
     http_response_code(204);
}
else
{
     errorMessage('query errata' . $sql);
}
		
?>
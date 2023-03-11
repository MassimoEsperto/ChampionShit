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

$sql  = "INSERT INTO giornate(id_giornata, inizio_giornata,prima_partita,ultima_partita,fine_giornata ,serie_a,fase_id,is_upgrade) ";
$sql .= "VALUES ({$giornata},'{$inizio_giornata}','{$prima_partita}','{$ultima_partita}','{$fine_giornata}',{$serie_a},{$fase},{$is_upgrade});";

if(mysqli_query($con, $sql))
{
     http_response_code(204);
}
else
{
     errorMessage('query errata: ' . $sql);
}
		
?>
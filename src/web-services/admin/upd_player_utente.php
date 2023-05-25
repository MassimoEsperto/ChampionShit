<?php

require_once '../config/connect_local.php';
require_once '../config/post_data.php';


$id_utente = mysqli_real_escape_string($con, trim($dati->id_utente)); 
$player_in = mysqli_real_escape_string($con, trim($dati->player_in)); 
$player_out = mysqli_real_escape_string($con, trim($dati->player_out));
 

$sql = "UPDATE rosa_utente SET id_calciatore='{$player_in}' ";
$sql .="WHERE id_utente = {$id_utente} AND id_calciatore = {$player_out} LIMIT 1";
	
if(mysqli_query($con, $sql))
{
    http_response_code(204);
}
else
{
    errorMessage('query errata: inserimento voti');
}



?>
<?php

require_once '../config/connect_local.php';
require_once '../config/post_data.php';


$id_squadra = mysqli_real_escape_string($con, trim($dati->id_squadra)); 
$player_in = mysqli_real_escape_string($con, trim($dati->player_in)); 
$player_out = mysqli_real_escape_string($con, trim($dati->player_out));
 

$sql = "UPDATE rose SET calciatore_id='{$player_in}' ";
$sql .="WHERE squadra_id = {$id_squadra} AND calciatore_id = {$player_out} LIMIT 1";
	
if(mysqli_query($con, $sql))
{
    http_response_code(204);
}
else
{
    errorMessage('query errata: inserimento voti');
}



?>
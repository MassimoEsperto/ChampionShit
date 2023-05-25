<?php

require_once '../config/connect_local.php';
require_once '../config/decode.php';
require_once '../config/post_data.php';

$avatar = mysqli_real_escape_string($con, trim($dati->id_avatar)); 
$email = mysqli_real_escape_string($con, trim($dati->email)); 
$squadra = mysqli_real_escape_string($con, trim($dati->squadra)); 
$username = mysqli_real_escape_string($con, trim($dati->username)); 


$sql =  "UPDATE utenti SET username='{$username}',avatar_id='{$avatar}',email='{$email}',squadra='{$squadra}' ";
$sql .= "WHERE id_utente = {$id_utente} LIMIT 1 ";


if(mysqli_query($con,$sql))
{
    echo json_encode(['data'=>'ok']);
    
}else{
	errorMessage('query errata: update utente');
}



?>
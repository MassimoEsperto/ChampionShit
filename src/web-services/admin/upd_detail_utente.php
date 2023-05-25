<?php

require_once '../config/connect_local.php';
require_once '../config/post_data.php';

$account = mysqli_real_escape_string($con, trim($dati->account)); 
$id = mysqli_real_escape_string($con, trim($dati->id)); 
$email = mysqli_real_escape_string($con, trim($dati->email)); 
$squadra = mysqli_real_escape_string($con, trim($dati->squadra)); 
$username = mysqli_real_escape_string($con, trim($dati->username)); 
$stato = mysqli_real_escape_string($con, trim($dati->stato)); 


$sql =  "UPDATE utenti SET username='{$username}',account='{$account}',email='{$email}', ";
$sql .= "squadra='{$squadra}',ruolo_id='{$stato}' WHERE id_utente = {$id} LIMIT 1 ";


if(mysqli_query($con,$sql))
{
    echo json_encode(['data'=>'ok']);
    
}else{
	errorMessage('query errata: update utente' . $sql);
}



?>
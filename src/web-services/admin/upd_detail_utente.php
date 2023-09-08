<?php

require_once '../config/connect_local.php';
require_once '../config/post_data.php';

$account = mysqli_real_escape_string($con, trim($dati->account)); 
$id = mysqli_real_escape_string($con, trim($dati->id_utente)); 
$email = mysqli_real_escape_string($con, trim($dati->email)); 
$username = mysqli_real_escape_string($con, trim($dati->username));
$ruolo = mysqli_real_escape_string($con, trim($dati->ruolo));
$squadra = mysqli_real_escape_string($con, trim($dati->squadra));


$stato_squadra = mysqli_real_escape_string($con, trim($dati->stato)); 
$id_squadra_in = mysqli_real_escape_string($con, trim($dati->id_squadra)); 

$sql =  "UPDATE utenti SET username='{$username}',email='{$email}',ruolo_id='{$ruolo}' ";
$sql .= "WHERE id_utente = {$id} LIMIT 1; ";
$sql .= "UPDATE squadre SET stato_id=$stato_squadra,squadra='{$squadra}',account='{$account}' ";
$sql .= "WHERE id_squadra = {$id_squadra_in} LIMIT 1 ";


if ($con->multi_query($sql) === TRUE) 
{
    echo json_encode(['data'=>'ok']);
    
}else{
	errorMessage('query errata: update utente' . $sql);
}



?>
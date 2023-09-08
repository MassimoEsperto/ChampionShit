<?php

require_once '../config/connect_local.php';
require_once '../config/post_data.php';

$username = mysqli_real_escape_string($con, trim($dati->username));
$email = mysqli_real_escape_string($con, trim($dati->email));  
$pass = mysqli_real_escape_string($con, trim($dati->password));

$sql = "INSERT INTO utenti (username,email,password) ";
$sql .= "VALUES ('{$username}','{$email}','{$pass}') ";
	
if ($con->multi_query($sql) === TRUE) 
{
    echo json_encode(['data'=>$con->insert_id]);
}
else
{
	errorMessage('query errata: inserimento utente');
}




?>
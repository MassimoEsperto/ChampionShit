<?php

require_once '../config/connect_local.php';
require_once '../config/post_data.php';

$username = mysqli_real_escape_string($con, trim($dati->username));
$email = mysqli_real_escape_string($con, trim($dati->email));  
$pass = mysqli_real_escape_string($con, trim($dati->password));

$sql = "INSERT INTO utenti (username,email,password) ";
$sql .= "VALUES ('{$username}','{$email}','{$pass}') ";

$controllo = "SELECT * FROM utenti WHERE username = '{$username}' ";

$result = mysqli_query( $con , $controllo );

if(! $result ) 
{
   errorMessage('query errata: controllo utente');
}

if ($result->num_rows > 0) 
{
	 errorMessage('nome utente gia presente');
}
	
if(mysqli_query($con,$sql))
{
    echo json_encode(['data'=>$con->insert_id]);
}
else
{
	errorMessage('query errata: inserimento utente');
}




?>
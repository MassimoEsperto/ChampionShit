<?php

require_once '../config/connect_local.php';
require_once '../config/decode.php';
require_once '../config/post_data.php';

$id_squadra_del = mysqli_real_escape_string($con, trim($dati->id)); 

//elimina rosa esistente
$sql =  "DELETE rose, squadre FROM rose JOIN squadre ";
$sql .= "ON rose.squadra_id = squadre.id_squadra WHERE rose.squadra_id = {$id_squadra_del}";

if(mysqli_query($con, $sql))
{
	echo json_encode(['data'=>'ok']);
}
else
{
     errorMessage('query elimina errata ' .$sql);
}

		
?>
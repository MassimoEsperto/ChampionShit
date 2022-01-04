<?php

require_once '../config/connect_local.php';
require_once '../config/post_data.php';

$data_inizio = mysqli_real_escape_string($con, trim($dati->data_inizio)); 
$data_fine = mysqli_real_escape_string($con, trim($dati->data_fine)); 
$giornata = mysqli_real_escape_string($con, trim($dati->giornata)); 

$sql  ="UPDATE data_partite ";
$sql .="SET data_inizio='{$data_inizio}',data_fine='{$data_fine}' ";
$sql .="WHERE giornata = {$giornata} LIMIT 1";

if(mysqli_query($con, $sql))
{
     http_response_code(204);
}
else
{
     errorMessage('query errata');
}
		
?>
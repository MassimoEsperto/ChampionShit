<?php

require_once '../config/connect_local.php';
require_once '../config/post_data.php';

$casa = mysqli_real_escape_string($con, trim($dati->casa)); 
$trasferta = mysqli_real_escape_string($con, trim($dati->trasferta)); 
$giornata = mysqli_real_escape_string($con, trim($dati->giornata)); 


$sql  ="UPDATE risultati ";
$sql .="INNER JOIN calendario ON risultati.calendario_id = calendario.id_calendario AND calendario.giornata_id={$giornata} ";
$sql .="SET squadra_id = (case when luogo = 'CASA' then {$casa} when luogo = 'TRASFERTA' then {$trasferta} end) ";

if(mysqli_query($con, $sql))
{
     http_response_code(204);
}
else
{
     errorMessage('query errata');
}

?>

<?php

require_once '../config/connect_local.php';
require_once '../config/post_data.php';


$giornata = mysqli_real_escape_string($con, trim($dati->giornata)); 
$lista= $dati->lista;
 

foreach($lista as $item) 
{
		$id_calciatore = mysqli_real_escape_string($con, (int)($item->id_calciatore));
		$voto = mysqli_real_escape_string($con, trim($item->voto));
			
		$sql .="UPDATE formazioniNEW ";
		$sql .="INNER JOIN risultati ON formazioniNEW.risultati_id = risultati.id_risultato ";
		$sql .="INNER JOIN calendarioNEW ON risultati.calendario_id = calendarioNEW.id_calendario AND calendarioNEW.giornata_id={$giornata} ";
		$sql .="SET voto={$voto} WHERE id_calciatore={$id_calciatore};";
	
}
    
	
if ($con->multi_query($sql) === TRUE) 
{
	http_response_code(201);
}else{
	errorMessage('query errata: inserimento voti');
}



?>

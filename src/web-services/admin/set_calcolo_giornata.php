<?php

require_once '../config/connect_local.php';
require_once '../config/post_data.php';


$risultati= $dati->risultati;
$giornata = mysqli_real_escape_string($con, trim($dati->giornata)); 
 
$sql =  "UPDATE giornate SET is_calcolata=1 WHERE id_giornata = {$giornata} LIMIT 1 ;";

foreach($risultati as $risultato) 
{
	$casa = $risultato->CASA;
    $trasferta = $risultato->TRASFERTA;
    
	$id = mysqli_real_escape_string($con, (int)$casa->id_risultato);
	$goals = mysqli_real_escape_string($con, (int)$casa->goals);
	$punti = mysqli_real_escape_string($con, (int)$casa->punti);
	$somma = mysqli_real_escape_string($con, (int)$casa->somma);
		
	$sql .= "UPDATE risultati SET goals={$goals},punti={$punti},somma={$somma} ";
	$sql .="WHERE id_risultato={$id} ;";
    
    $schieramento = $casa->schieramento;
    
    foreach($schieramento as $item) 
    {
            $id_calciatore = mysqli_real_escape_string($con, (int)($item->id));
            $voto = mysqli_real_escape_string($con, trim($item->voto));

            $sql .="UPDATE formazioniNEW ";
            $sql .="INNER JOIN risultati ON formazioniNEW.risultato_id = risultati.id_risultato ";
            $sql .="INNER JOIN calendarioNEW ON risultati.calendario_id = calendarioNEW.id_calendario AND calendarioNEW.giornata_id={$giornata} ";
            $sql .="SET voto={$voto} WHERE calciatore_id={$id_calciatore};";

    }
    
    $id = mysqli_real_escape_string($con, (int)$trasferta->id_risultato);
	$goals = mysqli_real_escape_string($con, (int)$trasferta->goals);
	$punti = mysqli_real_escape_string($con, (int)$trasferta->punti);
	$somma = mysqli_real_escape_string($con, (int)$trasferta->somma);
		
	$sql .= "UPDATE risultati SET goals={$goals},punti={$punti},somma={$somma} ";
	$sql .="WHERE id_risultato={$id} ;";
    
    $schieramento = $trasferta->schieramento;
    
    foreach($schieramento as $item) 
    {
            $id_calciatore = mysqli_real_escape_string($con, (int)($item->id));
            $voto = mysqli_real_escape_string($con, trim($item->voto));

            $sql .="UPDATE formazioniNEW ";
            $sql .="INNER JOIN risultati ON formazioniNEW.risultato_id = risultati.id_risultato ";
            $sql .="INNER JOIN calendarioNEW ON risultati.calendario_id = calendarioNEW.id_calendario AND calendarioNEW.giornata_id={$giornata} ";
            $sql .="SET voto={$voto} WHERE calciatore_id={$id_calciatore};";

    }
		
}



if ($con->multi_query($sql) === TRUE) 
{
	$ritono = [
				'stato' => $con->affected_rows,
				'risposta' => 'ok'
			];
	echo json_encode(['data'=>$ritono]);
	} 
else{
	errorMessage('query errata: calcola giornata');
}



?>

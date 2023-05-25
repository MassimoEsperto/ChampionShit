<?php

$recuperate_ = [];

//ultime formazioni inserite
$sql_recuperate = "SELECT r.utente_id,c.giornata_id,f.schieramento,r.id_risultato,f.calciatore_id,r.modulo_id "; 
$sql_recuperate .="FROM formazioniNEW f ";
$sql_recuperate .="INNER JOIN risultati r  ON f.risultato_id = r.id_risultato  ";
$sql_recuperate .="INNER JOIN calendarioNEW c ON c.id_calendario = r.calendario_id ";
$sql_recuperate .="AND c.giornata_id = ";
$sql_recuperate .="( ";
$sql_recuperate .="SELECT MAX(c2.giornata_id)  ";
$sql_recuperate .="FROM formazioniNEW f2,calendarioNEW c2,risultati r2 ";
$sql_recuperate .="WHERE f2.risultato_id = r2.id_risultato AND r2.is_inserita=1 ";
$sql_recuperate .="AND r2.utente_id = r.utente_id  AND r2.calendario_id=c2.id_calendario ";
$sql_recuperate .=") ";
$sql_recuperate .="GROUP BY r.utente_id,f.schieramento ORDER BY r.utente_id,c.giornata_id,f.schieramento";

if($result = mysqli_query($con,$sql_recuperate))
{
	$ele = -1;
    $utente_tmp = 0;
    $sc = 0;
	while($row = mysqli_fetch_assoc($result))
	{
    	if($utente_tmp != $row['utente_id']){
        	$utente_tmp = $row['utente_id'];
        	$ele++;
            $sc = 0;
        }
    	$recuperate_[$ele]['id_utente'] = $row['utente_id'];
		$recuperate_[$ele]['giornata'] = $row['giornata_id'];
        $recuperate_[$ele]['id_risultato'] = $row['id_risultato'];
        $recuperate_[$ele]['id_modulo'] = $row['modulo_id'];
		$recuperate_[$ele]['schieramento'][$sc] = $row['calciatore_id'];
        $sc++;
	}
    
}


?>
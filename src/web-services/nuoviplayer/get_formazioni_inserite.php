<?php

require_once '../common/turno.php';
//require_once '../config/validate.php';
    
$elements = [];

$sql = "SELECT c.id_calendario,r.luogo,r.utente_id,l.id_calciatore,l.nickname,l.nome_calciatore,  ";
$sql .="m.descrizione,m.bonus,u.squadra,f.schieramento  "; 
$sql .="FROM calendarioNEW c  ";
$sql .="INNER JOIN risultati r  ON c.id_calendario = r.calendario_id ";
$sql .="LEFT JOIN formazioniNEW f ON f.risultato_id = r.id_risultato ";
$sql .="LEFT JOIN lista_calciatori l on l.id_calciatore = f.calciatore_id ";
$sql .="LEFT JOIN moduli m on m.id_modulo = r.modulo_id ";
$sql .="LEFT JOIN utenti u on u.id_utente = r.utente_id ";
$sql .="INNER JOIN giornate g ON g.id_giornata = c.giornata_id  ";
$sql .="WHERE c.giornata_id = {$turno['giornata']} ORDER BY c.id_calendario,r.luogo,f.schieramento ";


if($result = mysqli_query($con,$sql))
{
	$ele = -1;
    $tmp_calendario = 0;
	$tmp_utente = 0;
    $count_c = -1;
    
	while($row = mysqli_fetch_assoc($result))
	{
    	 if($tmp_calendario != $row['id_calendario']){
        	$count_c++;
            $tmp_calendario = $row['id_calendario'];
            $ele = -1;
        }
 		
        if($tmp_utente != $row['utente_id']){
          $tmp_utente = $row['utente_id'];
          $ele = -1;
        }
        
		$ele++;
		$elements[$count_c]['id_calendario'] = $tmp_calendario;
		$elements[$count_c][$row['luogo']]['bonus'] = $row['bonus'];
        $elements[$count_c][$row['luogo']]['squadra'] = $row['squadra'];
     
        $elements[$count_c][$row['luogo']]['schieramento'][$ele]['nickname'] = $row['nickname'];
		$elements[$count_c][$row['luogo']]['schieramento'][$ele]['calciatore'] = $row['nome_calciatore'];
        $elements[$count_c][$row['luogo']]['schieramento'][$ele]['voto'] = "-";
       
 
	}
    
	echo json_encode(['data'=>$elements]);
}
else
{
	errorMessage('query errata: schieramenti');
}


?>
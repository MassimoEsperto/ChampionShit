<?php

require_once '../config/connect_local.php';
//require_once '../config/validate.php';
    
$elements = [];

$sql = "SELECT g.id_giornata,DATE_FORMAT(g.prima_partita,'%d/%m/%Y') AS data_giornata,g.serie_a,g.fase_id, ";
$sql .="c.girone,c.id_calendario,r.luogo,r.somma,r.goals,u.squadra,a.nome as avatar "; 
$sql .="FROM giornate g  ";
$sql .="INNER JOIN calendarioNEW c  ON c.giornata_id = g.id_giornata ";
$sql .="INNER JOIN risultati r  ON c.id_calendario = r.calendario_id ";
$sql .="INNER JOIN utenti u on u.id_utente = r.utente_id ";
$sql .="INNER JOIN avatar a on a.id_avatar = u.avatar_id ";
$sql .="ORDER BY g.id_giornata,c.girone, c.id_calendario,r.luogo ";


if($result = mysqli_query($con,$sql))
{
	$ele = -1;
    $tmp_giornata = 0; 
    $tmp_calendario = 0;
    $count_g = -1;
    
	while($row = mysqli_fetch_assoc($result))
	{
    	if($tmp_giornata != $row['id_giornata']){
        	$count_g++;
            $tmp_giornata = $row['id_giornata'];
            $elements[$count_g]['giornata'] = $row['id_giornata'];
        	$elements[$count_g]['data'] = $row['data_giornata'];
        	$elements[$count_g]['serie_a'] = $row['serie_a'];
        	$elements[$count_g]['fase'] = $row['fase_id'];
            $ele = -1;
        }
 		
        if($tmp_calendario != $row['id_calendario']){
          $tmp_calendario = $row['id_calendario'];
          $ele++;
        }
		$elements[$count_g]['partite'][$ele]['id_calendario'] = $tmp_calendario;
		$elements[$count_g]['partite'][$ele]['girone'] = $row['girone'];
        $elements[$count_g]['partite'][$ele]['indice'] = $ele;
     
        $elements[$count_g]['partite'][$ele][$row['luogo']]['somma'] = $row['somma'];
        $elements[$count_g]['partite'][$ele][$row['luogo']]['goals'] = $row['goals'];
        $elements[$count_g]['partite'][$ele][$row['luogo']]['squadra'] = $row['squadra'];
        $elements[$count_g]['partite'][$ele][$row['luogo']]['avatar'] = $row['avatar'];
 
	}
    
	echo json_encode(['data'=>$elements]);
}
else
{
	errorMessage('query errata: risultati');
}


?>
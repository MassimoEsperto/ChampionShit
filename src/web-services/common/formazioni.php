<?php

require_once 'turno.php';
    
$formazioni_ = [];

$sql_formazioni = "SELECT c.id_calendario,r.luogo,l.id_calciatore,l.nickname,l.nome_calciatore,  ";
$sql_formazioni .="m.descrizione,m.bonus,s.squadra,f.schieramento,u.id_utente,s.id_squadra  "; 
$sql_formazioni .="FROM calendario c  ";
$sql_formazioni .="INNER JOIN risultati r  ON c.id_calendario = r.calendario_id ";
$sql_formazioni .="LEFT JOIN formazioni f ON f.risultato_id = r.id_risultato ";
$sql_formazioni .="LEFT JOIN lista_calciatori l on l.id_calciatore = f.calciatore_id ";
$sql_formazioni .="LEFT JOIN moduli m on m.id_modulo = r.modulo_id ";
$sql_formazioni .="LEFT JOIN squadre s on s.id_squadra = r.squadra_id ";
$sql_formazioni .="LEFT JOIN utenti u on u.id_utente = s.utente_id ";
$sql_formazioni .="INNER JOIN giornate g ON g.id_giornata = c.giornata_id  ";
$sql_formazioni .="WHERE c.giornata_id = {$turno_['giornata']}-1 ORDER BY c.id_calendario,r.luogo,f.schieramento ";


if($result = mysqli_query($con,$sql_formazioni))
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
		$formazioni_[$count_c]['id_calendario'] = $tmp_calendario;
		$formazioni_[$count_c][$row['luogo']]['bonus'] = $row['bonus'];
        $formazioni_[$count_c][$row['luogo']]['squadra'] = $row['squadra'];
     	$formazioni_[$count_c][$row['luogo']]['id_utente'] = $row['id_utente'];
        $formazioni_[$count_c][$row['luogo']]['id_squadra'] = $row['id_squadra'];
        
        $formazioni_[$count_c][$row['luogo']]['schieramento'][$ele]['nickname'] = $row['nickname'];
		$formazioni_[$count_c][$row['luogo']]['schieramento'][$ele]['calciatore'] = $row['nome_calciatore'];
        $formazioni_[$count_c][$row['luogo']]['schieramento'][$ele]['voto'] = "-";
       
 
	}
    

}


?>
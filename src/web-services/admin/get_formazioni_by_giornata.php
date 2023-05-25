<?php

require_once '../config/connect_local.php';

$giornata = $_GET['giornata'];($_GET['giornata'] !== null && $_GET['giornata'] !== '')? mysqli_real_escape_string($con, $_GET['giornata']) : false;

$formazioni_ = [];

$sql_formazioni = "SELECT c.id_calendario,r.luogo,r.utente_id,l.id_calciatore,l.nickname,l.nome_calciatore,  ";
$sql_formazioni .="m.descrizione,m.bonus,u.squadra,f.schieramento,r.id_risultato,r.somma,r.goals,f.voto  "; 
$sql_formazioni .="FROM calendarioNEW c  ";
$sql_formazioni .="INNER JOIN risultati r  ON c.id_calendario = r.calendario_id ";
$sql_formazioni .="LEFT JOIN formazioniNEW f ON f.risultato_id = r.id_risultato ";
$sql_formazioni .="LEFT JOIN lista_calciatori l on l.id_calciatore = f.calciatore_id ";
$sql_formazioni .="LEFT JOIN moduli m on m.id_modulo = r.modulo_id ";
$sql_formazioni .="LEFT JOIN utenti u on u.id_utente = r.utente_id ";
$sql_formazioni .="INNER JOIN giornate g ON g.id_giornata = c.giornata_id  ";
$sql_formazioni .="WHERE c.giornata_id = $giornata ORDER BY c.id_calendario,r.luogo,f.schieramento ";


if($result = mysqli_query($con,$sql_formazioni))
{
	$ele = -1;
    $l=0;
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
     	$formazioni_[$count_c][$row['luogo']]['id_utente'] = $row['utente_id'];
        $formazioni_[$count_c][$row['luogo']]['id_risultato'] = $row['id_risultato'];
        $formazioni_[$count_c][$row['luogo']]['somma'] = $row['somma'];
        $formazioni_[$count_c][$row['luogo']]['goals'] = $row['goals'];
        
        
        $formazioni_[$count_c][$row['luogo']]['schieramento'][$ele]['nickname'] = $row['nickname'];
		$formazioni_[$count_c][$row['luogo']]['schieramento'][$ele]['calciatore'] = $row['nome_calciatore'];
        $formazioni_[$count_c][$row['luogo']]['schieramento'][$ele]['id'] = $row['id_calciatore'];
        $formazioni_[$count_c][$row['luogo']]['schieramento'][$ele]['voto'] = "-";
       /*
       	$formazioni_['lista'][$l]['id_calciatore']=$row['id_calciatore'];
       	$formazioni_['lista'][$l]['nome_calciatore']=$row['nome_calciatore'];
       	$formazioni_['lista'][$l]['voto']= $row['voto'];
       	$formazioni_['lista'][$l]['id_risultato'] = $row['id_risultato'];
 	   	$l++;
       
       	$formazioni_['giornata'] = $giornata;
        */
	}
   
   echo json_encode(['data'=>$formazioni_]);

}


?>
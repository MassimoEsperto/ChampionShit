<?php

require_once '../config/connect_local.php';
//require_once '../config/validate.php';

$schieramento = [];
$totObj = [];

$match = $_GET['match'];


 // Validate.
if(trim($match) === '')
{
   die('valori non prelevati'. mysqli_error($con));
}



$sql5 = "SELECT c.girone,r.luogo,r.utente_id,r.somma,r.goals,l.id_calciatore,l.nickname,l.ruolo, ";
$sql5 .="m.descrizione,u.squadra,f.schieramento,f.voto ";
$sql5 .="FROM calendarioNEW c "; 
$sql5 .="INNER JOIN risultati r  ON c.id_calendario = r.calendario_id  ";
$sql5 .="LEFT JOIN formazioniNEW f ON f.risultato_id = r.id_risultato ";
$sql5 .="LEFT JOIN lista_calciatori l on l.id_calciatore = f.calciatore_id "; 
$sql5 .="LEFT JOIN moduli m on m.id_modulo = r.modulo_id ";
$sql5 .="LEFT JOIN utenti u on u.id_utente = r.utente_id ";
$sql5 .="WHERE id_calendario = {$match} ORDER BY r.luogo,f.schieramento ";

if($result = mysqli_query($con,$sql5))
{
  
  $cs = 0;
  $tr = 0;
  $count = 0;
  while($row = mysqli_fetch_assoc($result))
  {
  
  	$count = $row['luogo'] == 'CASA' ? $cs : $tr ;
    $cs = $row['luogo'] == 'CASA' ? $cs+1 : $cs ;
    $tr = $row['luogo'] == 'TRASFERTA' ? $tr+1 : $tr ;
    
    $schieramento[$row['luogo']]['id_utente'] = $row['utente_id'];
	$schieramento[$row['luogo']]['somma'] = $row['somma'];
    $schieramento[$row['luogo']]['goals'] = $row['goals'];
   	$schieramento[$row['luogo']]['squadra'] = $row['squadra'];
    $schieramento[$row['luogo']]['modulo'] = $row['descrizione'];
   
   	$schieramento[$row['luogo']]['formazione'][$count]['nome'] = $row['nickname'];
   	$schieramento[$row['luogo']]['formazione'][$count]['ruolo'] = $row['ruolo'];
   	$schieramento[$row['luogo']]['formazione'][$count]['voto'] = $row['voto'];
   
  }
 	
}
else
{
  http_response_code(405);
}

$totObj=['data'=>$schieramento];

$myJSON = json_encode($totObj);
echo $myJSON;

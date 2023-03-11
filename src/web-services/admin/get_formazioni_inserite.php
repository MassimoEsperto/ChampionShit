<?php

require_once '../config/connect_local.php';

$giornata = $_GET['giornata'];($_GET['giornata'] !== null && $_GET['giornata'] !== '')? mysqli_real_escape_string($con, $_GET['giornata']) : false;


 // Validate.
if(trim($giornata) === '')
{
   die('valori non prelevati'. mysqli_error($con));
}

//formazioni inserite
$sql = "SELECT t.id_partita,t.girone,t.squadra,t.id_utente,t.schieramento,t.id_calciatore,t.calciatore,t.ruolo,t.voto ";
$sql .="FROM ( ";
$sql .="SELECT f.id_partita,c.girone,u.squadra,f.id_utente,f.schieramento,f.id_calciatore,l.nome_calciatore as calciatore,l.ruolo,f.voto, "; 
$sql .="CASE WHEN f.id_utente = c.utente_casa THEN 1 ELSE 2 END AS priorita  ";
$sql .="FROM formazioni f,calendario c, lista_calciatori l , utenti u  ";
$sql .="WHERE f.id_partita=c.id_partita  ";
$sql .="and l.id_calciatore=f.id_calciatore and u.id_utente=f.id_utente and c.giornata={$giornata} ";
$sql .="union ";
$sql .="SELECT c.id_partita,c.girone,u.squadra,u.id_utente,0 as schieramento,0 as id_calciatore,'NULLO' as calciatore,'N' as ruolo,null as voto,1 AS priorita ";
$sql .="FROM calendario c,utenti u  ";
$sql .="WHERE u.id_utente=c.utente_casa and c.giornata={$giornata} ";
$sql .="union ";
$sql .="SELECT c.id_partita,c.girone,u.squadra,u.id_utente,0 as schieramento,0 as id_calciatore,'NULLO' as calciatore,'N' as ruolo,null as voto,2 AS priorita ";
$sql .="FROM calendario c,utenti u  ";
$sql .="WHERE u.id_utente=c.utente_trasferta and c.giornata={$giornata}  ";
$sql .=") t ORDER BY t.id_partita,t.priorita,t.id_utente,t.schieramento  ";

if($result = mysqli_query($con,$sql))
{
	//indici
	$ele = -1;
	$squadre = -1;
	$numero = -1;
	
	//tmp
	$id_partita = 0;
	$id_utente = 0;
	
	while($row = mysqli_fetch_assoc($result))
	{
		if($row['id_partita']!=$id_partita)
		{
			$squadre = -1;
			$ele++;	
			$formazioni[$ele]['id_partita'] = $row['id_partita'];
			$formazioni[$ele]['girone'] =   		$row['girone'];	
		}
			
		if($row['id_utente']!=$id_utente)
		{
			$numero = -1;
			$squadre++;
			
			$formazioni[$ele]['match'][$squadre]['squadra'] 	= str_replace(' ', '', $row['squadra']);
			$formazioni[$ele]['match'][$squadre]['id_utente'] 	= $row['id_utente'];
            $formazioni[$ele]['match'][$squadre]['punti'] 		=  0;
            $formazioni[$ele]['match'][$squadre]['gol'] 		=  0;
			$id_utente = $row['id_utente'];
			$formazioni[$ele]['match'][$squadre]['schieramento'] = [];
		}
		
		if($numero > -1){
          $formazioni[$ele]['match'][$squadre]['schieramento'][$numero]['id_calciatore'] = $row['id_calciatore'];
          $formazioni[$ele]['match'][$squadre]['schieramento'][$numero]['calciatore'] = $row['calciatore'];
          $formazioni[$ele]['match'][$squadre]['schieramento'][$numero]['ruolo'] = $row['ruolo'];
          $formazioni[$ele]['match'][$squadre]['schieramento'][$numero]['voto'] = 4;
		}
		$numero++;
		$id_partita = $row['id_partita'];
		
	}
 
}
else
{
	errorMessage('query errata: formazioni attuali');
}

echo json_encode(['data'=>$formazioni]);

?>
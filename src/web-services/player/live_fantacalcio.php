<?php

require_once '../config/connect_local.php';
//require_once '../config/decode.php';
    
//dichiarazione variabili	
$seriea = 0;
$formazioni = [];
$giornata = [];
$team = ["5","8","129","18","10","6","1","21","138","15","16","2","19","17","11","137","13","20","12","9"];
    

//giornata attuale
$sql1 = "SELECT DATE_FORMAT(data_inizio,'%d-%m-%Y %H:%i') as data_inizio,giornata ";
$sql1 .="FROM `data_partite` WHERE data_inizio>now() ";
$sql1 .="ORDER BY giornata LIMIT 1";
if($result = mysqli_query($con,$sql1))
{
	while($row = mysqli_fetch_assoc($result))
	{
		$giornata['attuale'] =   $row['giornata'];
		$giornata['precedente'] =   ((int)$row['giornata'])-1;
        $giornata['limbo'] = $limbo == 1;
        $giornata['tmp'] =   $row['data_inizio'];
	}
}
else
{
	header("HTTP/1.1 500 Internal Server Error");
	header('Content-Type: application/json; charset=UTF-8');
	die(json_encode(array('message' => 'query errata: giornata attuale', 'code' => 400)));
}



$sql2 = "SELECT t.id_partita,t.girone,t.squadra,t.id_utente,t.schieramento,t.id_calciatore,t.calciatore,t.ruolo,t.voto ";
$sql2 .="FROM ( ";
$sql2 .="SELECT f.id_partita,c.girone,u.squadra,f.id_utente,f.schieramento,f.id_calciatore,l.nome_calciatore as calciatore,l.ruolo,f.voto, "; 
$sql2 .="CASE WHEN f.id_utente = c.utente_casa THEN 1 ELSE 2 END AS priorita  ";
$sql2 .="FROM formazioni f,calendario c, lista_calciatori l , utenti u  ";
$sql2 .="WHERE f.id_partita=c.id_partita  ";
$sql2 .="and l.id_calciatore=f.id_calciatore and u.id_utente=f.id_utente and c.giornata={$giornata['precedente']} ";
$sql2 .="union ";
$sql2 .="SELECT c.id_partita,c.girone,u.squadra,u.id_utente,0 as schieramento,0 as id_calciatore,'NULLO' as calciatore,'N' as ruolo,null as voto,1 AS priorita ";
$sql2 .="FROM calendario c,utenti u  ";
$sql2 .="WHERE u.id_utente=c.utente_casa and c.giornata={$giornata['precedente']} ";
$sql2 .="union ";
$sql2 .="SELECT c.id_partita,c.girone,u.squadra,u.id_utente,0 as schieramento,0 as id_calciatore,'NULLO' as calciatore,'N' as ruolo,null as voto,2 AS priorita ";
$sql2 .="FROM calendario c,utenti u  ";
$sql2 .="WHERE u.id_utente=c.utente_trasferta and c.giornata={$giornata['precedente']}  ";
$sql2 .=") t ORDER BY t.id_partita,t.priorita,t.id_utente,t.schieramento  ";

if($result = mysqli_query($con,$sql2))
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
			
			$formazioni[$ele]['match'][$squadre]['squadra'] = str_replace(' ', '', $row['squadra']);
			$formazioni[$ele]['match'][$squadre]['id_utente'] =   	$row['id_utente'];
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
	header("HTTP/1.1 500 Internal Server Error");
	header('Content-Type: application/json; charset=UTF-8');
	die(json_encode(array('message' => 'query errata: giornata attuale', 'code' => 400)));
}


//giornata serie a attuale
$sql3 = "SELECT serie_a ";
$sql3 .="FROM data_partite WHERE data_fine>now() ";
$sql3 .="ORDER BY giornata LIMIT 1";
if($result = mysqli_query($con,$sql3))
{
	while($row = mysqli_fetch_assoc($result))
	{
		$seriea =   $row['serie_a'];
	}
}
else
{
	header("HTTP/1.1 500 Internal Server Error");
	header('Content-Type: application/json; charset=UTF-8');
	die(json_encode(array('message' => 'query errata: giornata attuale', 'code' => 400)));
}



//risultato
$myObj->formazioni = $formazioni;
$myObj->team = $team;
$myObj->seriea = $seriea;
$totObj=['data'=>$myObj];

$myJSON = json_encode($totObj);
echo $myJSON;

?>
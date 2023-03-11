<?php

require_once '../config/connect_local.php';
$tabelle = "lista_calciatori order by nome_calciatore,comunicazioni ORDER BY id_comunicazione DESC,ruoli"; 
require_once '../common/get_all_objects.php';
require_once '../common/utenti.php';
require_once '../common/giornate.php';
require_once '../common/rose.php';
    
//dichiarazione variabili	

$incalcolate=[];
$calcolate=[];
$giornate_calcolate=[];

$formazioni=[];
$formazioni_attuali = [];




//Query ed elaborazioni








//giornate calcolate
$sql4 = "SELECT calcolato,giornata FROM calendario GROUP BY giornata ";

if($result = mysqli_query($con,$sql4))
{
	$ele_c = 0;
    $ele_i = 0;
    $incalcolate[$ele_i] = 0;
	while($row = mysqli_fetch_assoc($result))
	{
    	if($row['calcolato']==1){
          $calcolate[$ele_c] = $row['giornata'];
          $ele_c++;
        }else{
          $incalcolate[$ele_i] = $row['giornata'];
          $ele_i++;
        }
	}
	$giornate_calcolate['calcolate'] = $calcolate;
	$giornate_calcolate['incalcolate'] = $incalcolate;
    
}
else
{
	errorMessage('query errata: ultime formazioni inserite');
}





//ultime formazioni inserite
$sql6 = "SELECT f.id_partita,f.id_utente,f.schieramento ,f.id_calciatore, c.giornata "; 
$sql6 .="FROM formazioni f, calendario c WHERE f.is_inserita=1 and f.id_partita=c.id_partita ";
$sql6 .="and c.giornata=( select MAX(a.giornata) FROM formazioni o,calendario a where o.id_utente = f.id_utente and o.is_inserita=1 and o.id_partita=a.id_partita) ";
$sql6 .="group by id_utente,schieramento";

if($result = mysqli_query($con,$sql6))
{
	$ele = 0;
	while($row = mysqli_fetch_assoc($result))
	{
		$formazioni[$ele]['id_partita'] = $row['id_partita'];
		$formazioni[$ele]['id_utente'] = $row['id_utente'];
		$formazioni[$ele]['schieramento'] = $row['schieramento'];
		$formazioni[$ele]['id_calciatore'] = $row['id_calciatore'];
		$ele++;
	}
    
}
else
{
	errorMessage('query errata: ultime formazioni inserite');
}





//formazioni inserite
$sql8 = "SELECT t.id_partita,t.girone,t.squadra,t.id_utente,t.schieramento,t.id_calciatore,t.calciatore,t.ruolo,t.voto ";
$sql8 .="FROM ( ";
$sql8 .="SELECT f.id_partita,c.girone,u.squadra,f.id_utente,f.schieramento,f.id_calciatore,l.nome_calciatore as calciatore,l.ruolo,f.voto, "; 
$sql8 .="CASE WHEN f.id_utente = c.utente_casa THEN 1 ELSE 2 END AS priorita  ";
$sql8 .="FROM formazioni f,calendario c, lista_calciatori l , utenti u  ";
$sql8 .="WHERE f.id_partita=c.id_partita  ";
$sql8 .="and l.id_calciatore=f.id_calciatore and u.id_utente=f.id_utente and c.giornata={$giornate_calcolate['incalcolate'][0]} ";
$sql8 .="union ";
$sql8 .="SELECT c.id_partita,c.girone,u.squadra,u.id_utente,0 as schieramento,0 as id_calciatore,'NULLO' as calciatore,'N' as ruolo,null as voto,1 AS priorita ";
$sql8 .="FROM calendario c,utenti u  ";
$sql8 .="WHERE u.id_utente=c.utente_casa and c.giornata={$giornate_calcolate['incalcolate'][0]} ";
$sql8 .="union ";
$sql8 .="SELECT c.id_partita,c.girone,u.squadra,u.id_utente,0 as schieramento,0 as id_calciatore,'NULLO' as calciatore,'N' as ruolo,null as voto,2 AS priorita ";
$sql8 .="FROM calendario c,utenti u  ";
$sql8 .="WHERE u.id_utente=c.utente_trasferta and c.giornata={$giornate_calcolate['incalcolate'][0]}  ";
$sql8 .=") t ORDER BY t.id_partita,t.priorita,t.id_utente,t.schieramento  ";

if($result = mysqli_query($con,$sql8))
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
			$formazioni_attuali[$ele]['id_partita'] = $row['id_partita'];
			$formazioni_attuali[$ele]['girone'] =   		$row['girone'];	
		}
			
		if($row['id_utente']!=$id_utente)
		{
			$numero = -1;
			$squadre++;
			
			$formazioni_attuali[$ele]['match'][$squadre]['squadra'] = str_replace(' ', '', $row['squadra']);
			$formazioni_attuali[$ele]['match'][$squadre]['id_utente'] =   	$row['id_utente'];
			$id_utente = $row['id_utente'];
			$formazioni_attuali[$ele]['match'][$squadre]['schieramento'] = [];
		}
		
		if($numero > -1){
          $formazioni_attuali[$ele]['match'][$squadre]['schieramento'][$numero]['id_calciatore'] = $row['id_calciatore'];
          $formazioni_attuali[$ele]['match'][$squadre]['schieramento'][$numero]['calciatore'] = substr($row['calciatore'],0,12);
          $formazioni_attuali[$ele]['match'][$squadre]['schieramento'][$numero]['ruolo'] = $row['ruolo'];
		}
		$numero++;
		$id_partita = $row['id_partita'];
		
	}
 
}
else
{
	errorMessage('query errata: formazioni attuali');
}







//risultato
$myObj->comunicazioni = $oggetti_['comunicazioni ORDER BY id_comunicazione DESC'];
$myObj->utenti = $utenti_;
$myObj->lista_calciatori = $oggetti_['lista_calciatori order by nome_calciatore'];
$myObj->ultime_formazioni_inserite = $formazioni;
$myObj->giornate_calcolate = $giornate_calcolate;
$myObj->formazioni_attuali = $formazioni_attuali;
$myObj->rose = $rose_;
$myObj->periodo_partite = $giornate_;
$myObj->ruoli = $oggetti_['ruoli'];


$totObj=['data'=>$myObj];

$myJSON = json_encode($totObj);

echo $myJSON;

?>
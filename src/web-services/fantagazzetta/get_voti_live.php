<?php

require_once '../config/connect_local.php';
    
//dichiarazione variabili
$formazioni = [];
$live_gazzetta = [];
$giornata = [];
//team corrispoindenti alle chiamate di fantagazzetta
$team = ["5","8","129","18","10","6","1","21","138","15","16","2","19","17","11","137","13","20","12","9"];
//bonus e malus di fantagazzetta
$bonusmalus = array('3'=>3 , '9'=>3, '1'=>-0.5, '4'=>-1, '22'=>1, '8'=>-3, '10'=>-2, '2'=>-1);


//giornata in corso
$sql1 = "SELECT DATE_FORMAT(data_inizio,'%d-%m-%Y %H:%i') as data_inizio,giornata,serie_a ";
$sql1 .="FROM `data_partite` WHERE data_fine>now() ";
$sql1 .="ORDER BY giornata LIMIT 1";
if($result = mysqli_query($con,$sql1))
{
	while($row = mysqli_fetch_assoc($result))
	{
		$giornata['giornata'] =   $row['giornata'];
        $giornata['tmp'] =   $row['data_inizio'];
        $giornata['serie_a'] =   $row['serie_a'];
	}
}
else
{
	header("HTTP/1.1 500 Internal Server Error");
	header('Content-Type: application/json; charset=UTF-8');
	die(json_encode(array('message' => 'query errata: giornata attuale', 'code' => 400)));
}


//voti live
for($i=0;$i<count($team);$i++)
{
	$live_gazzetta_tmp =  file_get_contents('https://www.fantacalcio.it/api/live/'.$team[$i].'?g='.$giornata['serie_a'].'&i=16');
	$live_gazzetta_dec = json_decode($live_gazzetta_tmp);
     
    for($j=0;$j<count($live_gazzetta_dec);$j++)
    {
      	$player=$live_gazzetta_dec[$j]->nome;
      	//$player = str_replace(' ','',$player);
      	$player = str_replace("'","",$player);
      	$player = str_replace('.','',$player);
      	$live_gazzetta_dec[$j]->nome=$player;

      	$evento= explode(",", $live_gazzetta_dec[$j]->evento);
      	$valore=0;

      	for($x=0;$x<count($evento);$x++)
      	{
        	$valore = $bonusmalus[strval($evento[$x])]?$bonusmalus[$evento[$x]]:0;
        	$live_gazzetta_dec[$j]->voto=$live_gazzetta_dec[$j]->voto+$valore;
      	}
      
     	$live_gazzetta_dec[$j]->voto=$live_gazzetta_dec[$j]->voto==55?4:$live_gazzetta_dec[$j]->voto;
	 	$live_gazzetta[$live_gazzetta_dec[$j]->nome] = $live_gazzetta_dec[$j]->voto;
    }

}



//formazioni inserite
$sql2 = "SELECT t.id_partita,t.girone,t.squadra,t.id_utente,t.schieramento,t.id_calciatore,t.calciatore,t.ruolo,t.voto ";
$sql2 .="FROM ( ";
$sql2 .="SELECT f.id_partita,c.girone,u.squadra,f.id_utente,f.schieramento,f.id_calciatore,l.nome_calciatore as calciatore,l.ruolo,f.voto, "; 
$sql2 .="CASE WHEN f.id_utente = c.utente_casa THEN 1 ELSE 2 END AS priorita  ";
$sql2 .="FROM formazioni f,calendario c, lista_calciatori l , utenti u  ";
$sql2 .="WHERE f.id_partita=c.id_partita  ";
$sql2 .="and l.id_calciatore=f.id_calciatore and u.id_utente=f.id_utente and c.giornata={$giornata['giornata']} ";
$sql2 .="union ";
$sql2 .="SELECT c.id_partita,c.girone,u.squadra,u.id_utente,0 as schieramento,0 as id_calciatore,'NULLO' as calciatore,'N' as ruolo,null as voto,1 AS priorita ";
$sql2 .="FROM calendario c,utenti u  ";
$sql2 .="WHERE u.id_utente=c.utente_casa and c.giornata={$giornata['giornata']} ";
$sql2 .="union ";
$sql2 .="SELECT c.id_partita,c.girone,u.squadra,u.id_utente,0 as schieramento,0 as id_calciatore,'NULLO' as calciatore,'N' as ruolo,null as voto,2 AS priorita ";
$sql2 .="FROM calendario c,utenti u  ";
$sql2 .="WHERE u.id_utente=c.utente_trasferta and c.giornata={$giornata['giornata']}  ";
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
          $formazioni[$ele]['match'][$squadre]['schieramento'][$numero]['calciatore'] = substr($row['calciatore'],0,12);
          $formazioni[$ele]['match'][$squadre]['schieramento'][$numero]['ruolo'] = $row['ruolo'];
          $tmp_voto=$live_gazzetta[$row['calciatore']]?$live_gazzetta[$row['calciatore']]:'-';
          $formazioni[$ele]['match'][$squadre]['schieramento'][$numero]['voto'] = $tmp_voto;
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



//risultato
echo json_encode(['data'=>$formazioni]);


?>
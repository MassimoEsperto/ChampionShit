<?php

require_once '../config/connect_local.php';

    
//dichiarazione variabili	
$comunicazioni = [];
$utenti = [];
$datatime;
$incalcolate=[];
$calcolate=[];
$giornate_calcolate=[];
$lista_calciatori= array();
$formazioni=[];
$rose=[];
$ruoli=[];
$formazioni_attuali = [];
$periodo_partite=[];



//Query ed elaborazioni

//comunicazioni
$sql1 = "SELECT id_comunicazione,titolo FROM comunicazioni ORDER BY id_comunicazione DESC";
if($result = mysqli_query($con,$sql1))
{
	$ele = 0;
	while($row = mysqli_fetch_assoc($result))
	{
 
		$comunicazioni[$ele]['id'] = $row['id_comunicazione'];
        $comunicazioni[$ele]['titolo'] = $row['titolo'];
		$ele++;
	}
}
else
{
	errorMessage('query errata: comunicazioni');
}


//utenti
$sql2 = "SELECT u.id_utente,u.username,u.email,u.ruolo_id,u.squadra,u.account,r.stato ";
$sql2 .="FROM utenti u,ruoli r where id_utente < 100 AND r.id_ruolo=u.ruolo_id";


if($result = mysqli_query($con,$sql2))
{
	$ele = 0;
	while($row = mysqli_fetch_assoc($result))
	{
		$utenti[$ele]['username'] = $row['username'];
		$utenti[$ele]['squadra'] = $row['squadra'];
		$utenti[$ele]['email'] = $row['email'];
		$utenti[$ele]['ruolo'] = $row['ruolo_id'];
        $utenti[$ele]['stato'] = $row['stato'];
        $utenti[$ele]['id'] = $row['id_utente'];
        $utenti[$ele]['account'] = $row['account'];
		$ele++;
	}
}
else
{
	errorMessage('query errata: utenti');
}


//periodo partite
$sql3 = "SELECT id_giornata,prima_partita,inizio_giornata,ultima_partita,fine_giornata,serie_a,is_upgrade FROM giornate";

if($result = mysqli_query($con,$sql3))
{
	$ele = 0;
	while($row = mysqli_fetch_assoc($result))
	{
		$periodo_partite[$ele]['giornata'] = $row['id_giornata'];
		$periodo_partite[$ele]['serie_a'] = $row['serie_a'];
		$periodo_partite[$ele]['inizio_giornata'] = $row['inizio_giornata'];
		$periodo_partite[$ele]['prima_partita'] = $row['prima_partita'];
        $periodo_partite[$ele]['ultima_partita'] = $row['ultima_partita'];
		$periodo_partite[$ele]['fine_giornata'] = $row['fine_giornata'];
		$periodo_partite[$ele]['is_upgrade'] = $row['is_upgrade'];
		$ele++;
	}
    
}
else
{
	errorMessage('query errata: periodo partite');
}


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


//lista calciatori
$sql5 = "SELECT * FROM lista_calciatori order by nome_calciatore";
if($result = mysqli_query($con,$sql5))
{
	while($row = mysqli_fetch_assoc($result))
	{

		 $lista_calciatori[] = $row;
	}
}
else
{
	errorMessage('query errata: lista calciatori');
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


//rose utenti
$sql7 = "SELECT u.id_utente,u.squadra,l.nome_calciatore as nome,l.ruolo,r.nome as avatar,l.id_calciatore,u.username,u.lega  "; 
$sql7 .="FROM lista_calciatori l,rosa_utente a,utenti u ,avatar r  ";
$sql7 .="WHERE a.id_utente=u.id_utente and a.id_calciatore=l.id_calciatore and u.avatar_id=r.id_avatar  ";
$sql7 .="ORDER BY u.squadra,l.ruolo DESC, l.nome_calciatore";

if($result = mysqli_query($con,$sql7))
{
	$ele = -1;
	$utente = 0;
	$list = 0;
	
	while($row = mysqli_fetch_assoc($result))
	{
		if($row['id_utente']!=$utente)
		{
			$list = 0;
			$ele++;
		}
		
		$rose[$ele]['squadra'] = str_replace(' ', '', $row['squadra']);
        $rose[$ele]['id_utente'] = $row['id_utente'];	
        $rose[$ele]['avatar'] = $row['avatar'];
		$rose[$ele]['username'] = $row['username'];	
        $rose[$ele]['lega'] = $row['lega'];
		
        $rose[$ele]['lista'][$list]['id_calciatore'] = $row['id_calciatore'];
		$rose[$ele]['lista'][$list]['calciatore'] = $row['nome'];
		$rose[$ele]['lista'][$list]['ruolo'] = $row['ruolo'];
		
		$utente = $row['id_utente'];
		$list++;
	
	}
}
else
{
	errorMessage('query errata: liste rose utenti');
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


//comunicazioni
$sql9 = "SELECT id_ruolo,stato FROM ruoli ORDER BY id_ruolo ASC";
if($result = mysqli_query($con,$sql9))
{
	$ele = 0;
	while($row = mysqli_fetch_assoc($result))
	{
 
		$ruoli[$ele]['id'] = $row['id_ruolo'];
        $ruoli[$ele]['stato'] = $row['stato'];
		$ele++;
	}
}
else
{
	errorMessage('query errata: ruoli');
}





//risultato
$myObj->comunicazioni = $comunicazioni;
$myObj->utenti = $utenti;
$myObj->lista_calciatori = $lista_calciatori;
$myObj->ultime_formazioni_inserite = $formazioni;
$myObj->giornate_calcolate = $giornate_calcolate;
$myObj->formazioni_attuali = $formazioni_attuali;
$myObj->rose = $rose;
$myObj->periodo_partite = $periodo_partite;
$myObj->ruoli = $ruoli;

$totObj=['data'=>$myObj];

$myJSON = json_encode($totObj);

echo $myJSON;

?>
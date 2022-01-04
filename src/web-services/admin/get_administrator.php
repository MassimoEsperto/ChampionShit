<?php

require_once '../config/connect_local.php';

    
//dichiarazione variabili	
$comunicazioni = [];
$utenti = [];
$data_partite=[];
$incalcolate=[];
$calcolate=[];
$giornate_calcolate=[];
$lista_calciatori= array();
$formazioni=[];
$rose=[];



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
$sql2 = "SELECT id_utente,username,email,ruolo,squadra,is_pay FROM utenti where id_utente < 100";

if($result = mysqli_query($con,$sql2))
{
	$ele = 0;
	while($row = mysqli_fetch_assoc($result))
	{
		$utenti[$ele]['username'] = $row['username'];
		$utenti[$ele]['squadra'] = $row['squadra'];
		$utenti[$ele]['email'] = $row['email'];
		$utenti[$ele]['ruolo'] = $row['ruolo'];
        $utenti[$ele]['id'] = $row['id_utente'];
        $utenti[$ele]['is_pay'] = $row['is_pay'] == 1;
		$ele++;
	}
}
else
{
	errorMessage('query errata: utenti');
}


//date partite
$sql3 = "SELECT giornata,data_inizio,data_fine,serie_a FROM data_partite";

if($result = mysqli_query($con,$sql3))
{
	$ele = 0;
	while($row = mysqli_fetch_assoc($result))
	{
		$data_partite[$ele]['giornata'] = $row['giornata'];
		$data_partite[$ele]['serie_a'] = $row['serie_a'];
		$data_partite[$ele]['data_inizio'] = $row['data_inizio'];
		$data_partite[$ele]['data_fine'] = $row['data_fine'];
		$ele++;
	}
    
}
else
{
	errorMessage('query errata: date partite');
}


//giornate calcolate
$sql4 = "SELECT calcolato,giornata FROM calendario GROUP BY giornata ";

if($result = mysqli_query($con,$sql4))
{
	$ele_c = 0;
    $ele_i = 0;
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











//risultato
$myObj->comunicazioni = $comunicazioni;
$myObj->utenti = $utenti;
$myObj->data_partite = $data_partite;
$myObj->lista_calciatori = $lista_calciatori;
$myObj->formazioni = $formazioni;
$myObj->giornate_calcolate = $giornate_calcolate;
$myObj->rose = $rose;


$totObj=['data'=>$myObj];

$myJSON = json_encode($totObj);

echo $myJSON;

?>
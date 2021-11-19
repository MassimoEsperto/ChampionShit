<?php

require_once '../config/connect_local.php';
require_once '../config/decode.php';
    
//dichiarazione variabili	
$risultati = [];
$giornata = [];
$limbo = 0; 
$prossimo_match;
$live = 0; 


//Query ed elaborazioni
//limbro
$sql1 = "SELECT COUNT(*) AS limbo FROM data_partite WHERE now() BETWEEN data_inizio AND data_fine ";
if($result = mysqli_query($con,$sql1))
{
	while($row = mysqli_fetch_assoc($result))
	{
		$limbo =   $row['limbo'];
		$giornata['attuale']='1';
	}
}
else
{
	header("HTTP/1.1 500 Internal Server Error");
	header('Content-Type: application/json; charset=UTF-8');
	die(json_encode(array('message' => 'query errata: count limbo', 'code' => 400)));
}


//giornata attuale
$sql2 = "SELECT DATE_FORMAT(data_inizio,'%d-%m-%Y %H:%i') as data_inizio,giornata ";
$sql2 .="FROM `data_partite` WHERE data_inizio>now() ";
$sql2 .="ORDER BY giornata LIMIT 1";
if($result = mysqli_query($con,$sql2))
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


//risultati
$sql3 = "SELECT c.id_partita,c.girone,c.fase,c.giornata,u.id_utente as id_casa,u.squadra as casa,t.id_utente as id_trasferta, t.squadra as trasferta,d.serie_a, ";
$sql3 .="c.gol_casa,c.gol_trasferta,c.calcolato,DATE_FORMAT(d.data_inizio,'%d/%m/%Y') AS data_giornata, au.nome as avatar_casa,at.nome as avatar_trasferta "; 
$sql3 .="FROM calendario c,utenti u,utenti t, data_partite d ,avatar au,avatar at  ";
$sql3 .="WHERE u.id_utente=c.utente_casa and t.id_utente=c.utente_trasferta and d.giornata=c.giornata ";
$sql3 .="AND au.id_avatar=u.avatar_id AND at.id_avatar=t.avatar_id AND c.giornata={$giornata['precedente']} ";
$sql3 .="ORDER BY c.giornata,c.girone,c.id_partita ";

if($result = mysqli_query($con,$sql3))
{
	$ele = 0;
	while($row = mysqli_fetch_assoc($result))
	{
 
		$risultati[$ele]['partita'] = $row['id_partita'];
		$risultati[$ele]['girone'] = $row['girone'];
        $risultati[$ele]['fase'] = $row['fase'];
		$risultati[$ele]['giornata'] = $row['giornata'];
        $risultati[$ele]['calcolato'] = $row['calcolato'];
		$risultati[$ele]['casa'] = str_replace(' ', '', $row['casa']);
        $risultati[$ele]['id_casa'] = $row['id_casa'];
		$risultati[$ele]['trasferta'] = str_replace(' ', '', $row['trasferta']);
		$risultati[$ele]['id_trasferta'] = $row['id_trasferta'];
        $risultati[$ele]['gol_casa'] = $row['gol_casa'];
		$risultati[$ele]['gol_trasferta'] = $row['gol_trasferta'];
        $risultati[$ele]['data'] = $row['data_giornata'];
        $risultati[$ele]['serie_a'] = $row['serie_a'];
        $risultati[$ele]['avatar_casa'] = $row['avatar_casa'];
		$risultati[$ele]['avatar_trasferta'] = $row['avatar_trasferta'];
		$ele++;
	}
    
}
else
{
	header("HTTP/1.1 500 Internal Server Error");
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode(array('message' => 'query errata: risultati', 'code' => 404)));
}


//prossimo match
$live = $giornata['limbo'] ? $giornata['precedente'] : $giornata['attuale'];

$sql4 = "SELECT c.id_partita,c.girone,c.fase,c.giornata,u.id_utente as id_casa,u.squadra as casa,t.id_utente as id_trasferta, t.squadra as trasferta,d.serie_a, ";
$sql4 .="c.gol_casa,c.gol_trasferta,c.calcolato,DATE_FORMAT(d.data_inizio,'%d/%m/%Y') AS data_giornata, au.nome as avatar_casa,at.nome as avatar_trasferta "; 
$sql4 .="FROM calendario c,utenti u,utenti t, data_partite d ,avatar au,avatar at  ";
$sql4 .="WHERE u.id_utente=c.utente_casa and t.id_utente=c.utente_trasferta and d.giornata=c.giornata ";
$sql4 .="AND au.id_avatar=u.avatar_id AND at.id_avatar=t.avatar_id AND c.giornata={$live} ";
$sql4 .="AND (c.utente_casa = {$id_utente} OR c.utente_trasferta = {$id_utente} ) LIMIT 1";


if($result = mysqli_query($con,$sql4))
{
	$ele = 0;
	while($row = mysqli_fetch_assoc($result))
	{
 
		$prossimo_match['partita'] = $row['id_partita'];
		$prossimo_match['girone'] = $row['girone'];
        $prossimo_match['fase'] = $row['fase'];
		$prossimo_match['giornata'] = $row['giornata'];
        $prossimo_match['calcolato'] = $row['calcolato'];
		$prossimo_match['casa'] = str_replace(' ', '', $row['casa']);
        $prossimo_match['id_casa'] = $row['id_casa'];
		$prossimo_match['trasferta'] = str_replace(' ', '', $row['trasferta']);
		$prossimo_match['id_trasferta'] = $row['id_trasferta'];
        $prossimo_match['gol_casa'] = $row['gol_casa'];
		$prossimo_match['gol_trasferta'] = $row['gol_trasferta'];
        $prossimo_match['data'] = $row['data_giornata'];
        $prossimo_match['serie_a'] = $row['serie_a'];
        $prossimo_match['avatar_casa'] = $row['avatar_casa'];
		$prossimo_match['avatar_trasferta'] = $row['avatar_trasferta'];
		$ele++;
	}
    
}
else
{
	header("HTTP/1.1 500 Internal Server Error");
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode(array('message' => 'query errata: prossimo match', 'code' => 404)));
}




//risultato
$myObj->turno = $giornata;
$myObj->risultati = $risultati;
$myObj->prossimo_match = $prossimo_match;
$totObj=['data'=>$myObj];

$myJSON = json_encode($totObj);
echo $myJSON;

?>
<?php

require_once '../config/connect_local.php';

$giornata = $_GET['giornata'];($_GET['giornata'] !== null && $_GET['giornata'] !== '')? mysqli_real_escape_string($con, $_GET['giornata']) : false;


 // Validate.
if(trim($giornata) === '')
{
   die('valori non prelevati'. mysqli_error($con));
}


$sql = "SELECT t.id_partita,t.girone,t.squadra,t.id_utente,t.schieramento,t.id_calciatore,t.calciatore,t.ruolo,t.voto ";
$sql .="FROM ( ";
$sql .="SELECT f.id_partita,c.girone,u.squadra,f.id_utente,f.schieramento,f.id_calciatore,l.nome_calciatore as calciatore,l.ruolo,f.voto, "; 
$sql .="CASE WHEN f.id_utente = c.utente_casa THEN 1 ELSE 2 END AS priorita  ";
$sql .="FROM formazioni f,calendario c, lista_calciatori l , utenti u  ";
$sql .="WHERE f.id_partita=c.id_partita  ";
$sql .="and l.id_calciatore=f.id_calciatore and u.id_utente=f.id_utente and c.giornata='{$giornata}' ";
$sql .="union ";
$sql .="SELECT c.id_partita,c.girone,u.squadra,u.id_utente,0 as schieramento,0 as id_calciatore,'NULLO' as calciatore,'N' as ruolo,null as voto,1 AS priorita ";
$sql .="FROM calendario c,utenti u  ";
$sql .="WHERE u.id_utente=c.utente_casa and c.giornata='{$giornata}' ";
$sql .="union ";
$sql .="SELECT c.id_partita,c.girone,u.squadra,u.id_utente,0 as schieramento,0 as id_calciatore,'NULLO' as calciatore,'N' as ruolo,null as voto,2 AS priorita ";
$sql .="FROM calendario c,utenti u  ";
$sql .="WHERE u.id_utente=c.utente_trasferta and c.giornata='{$giornata}'  ";
$sql .=") t ORDER BY t.id_partita,t.priorita,t.id_utente,t.schieramento  ";
$result = mysqli_query( $con , $sql );

if(! $result ) 
{
   die('query failed'. mysqli_error($con));
}

if ($result->num_rows > 0) 
{
	$rows = array();
    while($r = mysqli_fetch_assoc($result)) {
        $rows[] = $r;
	}

 	echo json_encode(['data'=>$rows]);

} 
else 
{
	 header("HTTP/1.1 500 Internal Server Error");
     header('Content-Type: application/json; charset=UTF-8');
     die(json_encode(array('message' => 'giornata inesistente', 'code' => 400)));
}

?>
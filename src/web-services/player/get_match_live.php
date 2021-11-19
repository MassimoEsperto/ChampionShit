<?php

require_once '../config/connect_local.php';
require_once '../config/validate.php';


$squadra_casa = [];
$squadra_trasferta = [];
$formazione_casa = [];
$formazione_trasferta = [];
$totObj = [];

$match = $_GET['match'];($_GET['match'] !== null && $_GET['match'] !== '')? mysqli_real_escape_string($con, $_GET['match']) : false;


 // Validate.
if(trim($match) === '')
{
   die('valori non prelevati'. mysqli_error($con));
}

$sql1 = "SELECT f.schieramento,f.id_calciatore,REPLACE(l.nome_calciatore, ' ', '') as calciatore,l.ruolo,f.voto ";
$sql1 .="FROM formazioni f,calendario c, lista_calciatori l , utenti u ";
$sql1 .="WHERE f.id_partita=c.id_partita and l.id_calciatore=f.id_calciatore and u.id_utente=f.id_utente and c.id_partita={$match} and f.id_utente = c.utente_casa "; 
$sql1 .="ORDER BY f.id_partita,f.id_utente,f.schieramento ";

$sql2 = "SELECT f.id_partita,c.girone,REPLACE(u.squadra, ' ', '') as squadra,u.id_utente,sum(f.voto) as somma ";
$sql2 .="FROM formazioni f,calendario c, utenti u ";
$sql2 .="WHERE f.id_partita=c.id_partita  and u.id_utente=f.id_utente and c.id_partita={$match} and f.id_utente = c.utente_casa"; 


$sql3 = "SELECT f.schieramento,f.id_calciatore,REPLACE(l.nome_calciatore, ' ', '') as calciatore,l.ruolo,f.voto ";
$sql3 .="FROM formazioni f,calendario c, lista_calciatori l , utenti u ";
$sql3 .="WHERE f.id_partita=c.id_partita and l.id_calciatore=f.id_calciatore and u.id_utente=f.id_utente and c.id_partita={$match} and f.id_utente = c.utente_trasferta "; 
$sql3 .="ORDER BY f.id_partita,f.id_utente,f.schieramento ";


$sql4 = "SELECT f.id_partita,c.girone,REPLACE(u.squadra, ' ', '') as squadra,u.id_utente,sum(f.voto) as somma ";
$sql4 .="FROM formazioni f,calendario c, utenti u ";
$sql4 .="WHERE f.id_partita=c.id_partita and u.id_utente=f.id_utente and c.id_partita={$match} and f.id_utente = c.utente_trasferta"; 

//inizializzo le liste
$mul = 0;
while ($mul <= 4) {

	$formazione_casa[$mul]['schieramento'] = strval($mul+1);
	$formazione_casa[$mul]['id_calciatore'] = "0";
	$formazione_casa[$mul]['nome_calciatore'] = " - ";
	$formazione_casa[$mul]['ruolo'] = "N";
	$formazione_casa[$mul]['voto'] = null;
    
    $formazione_trasferta[$mul]['schieramento'] = strval($mul+1);
	$formazione_trasferta[$mul]['id_calciatore'] = "0";
	$formazione_trasferta[$mul]['nome_calciatore'] = " - ";
	$formazione_trasferta[$mul]['ruolo'] = "N";
	$formazione_trasferta[$mul]['voto'] = null;

	$mul++;
}



if($result = mysqli_query($con,$sql1))
{
  $count = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $formazione_casa[$count]['schieramento'] = $row['schieramento'];
    $formazione_casa[$count]['id_calciatore'] = $row['id_calciatore'];
	$formazione_casa[$count]['nome_calciatore'] = $row['calciatore'];
	$formazione_casa[$count]['ruolo'] = $row['ruolo'];
	$formazione_casa[$count]['voto'] = $row['voto'];
    $count++;
  }

}
else
{
  http_response_code(402);
}

if($result = mysqli_query($con,$sql2))
{
  while($row = mysqli_fetch_assoc($result))
  {
    $squadra_casa['id_partita'] = $match;
    $squadra_casa['girone'] = $row['girone'];
    $squadra_casa['squadra'] = $row['squadra'];
    $squadra_casa['id_utente'] = $row['id_utente'];
	$squadra_casa['somma'] = $row['somma'];
    $squadra_casa['formazione'] = $formazione_casa;
  }

}
else
{
  http_response_code(403);
}


if($result = mysqli_query($con,$sql3))
{
  $count = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $formazione_trasferta[$count]['schieramento'] = $row['schieramento'];
    $formazione_trasferta[$count]['id_calciatore'] = $row['id_calciatore'];
	$formazione_trasferta[$count]['nome_calciatore'] = $row['calciatore'];
	$formazione_trasferta[$count]['ruolo'] = $row['ruolo'];
	$formazione_trasferta[$count]['voto'] = $row['voto'];
    $count++;
  }

}
else
{
  http_response_code(402);
}


if($result = mysqli_query($con,$sql4))
{

  while($row = mysqli_fetch_assoc($result))
  {
    $squadra_trasferta['id_partita'] = $match;
    $squadra_trasferta['girone'] = $row['girone'];
    $squadra_trasferta['squadra'] = $row['squadra'];
    $squadra_trasferta['id_utente'] = $row['id_utente'];
	$squadra_trasferta['somma'] = $row['somma'];
    $squadra_trasferta['formazione'] = $formazione_trasferta;
  }
 	
    $myObj->casa = $squadra_casa;
	$myObj->trasferta = $squadra_trasferta;

	$totObj=['data'=>$myObj];

	$myJSON = json_encode($totObj);
	echo $myJSON;
}
else
{
  http_response_code(405);
}

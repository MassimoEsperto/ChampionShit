<?php

require_once '../config/connect_local.php';

    
//dichiarazione variabili	
$calendario = [];
$utenti = [];
$fasi = [];
$periodo_partite = [];


//Query ed elaborazioni

//calendario
$sql1  = "SELECT c.id_partita,c.fase,c.giornata,uc.squadra as casa,ut.squadra as trasferta,uc.id_utente as id_casa,ut.id_utente as id_trasferta ";
$sql1 .= "FROM calendario c,utenti uc,utenti ut "; 
$sql1 .= "WHERE uc.id_utente=c.utente_casa AND ut.id_utente=c.utente_trasferta "; 
$sql1 .=" ORDER BY c.giornata,c.id_partita DESC";

if($result = mysqli_query($con,$sql1))
{
	$ele = 0;
	while($row = mysqli_fetch_assoc($result))
	{
 
		$calendario[$ele]['id'] = $row['id_partita'];
        $calendario[$ele]['fase'] = $row['fase'];
        $calendario[$ele]['giornata'] = $row['giornata'];
        $calendario[$ele]['casa'] = $row['casa'];
        $calendario[$ele]['trasferta'] = $row['trasferta'];
        $calendario[$ele]['id_casa'] = $row['id_casa'];
        $calendario[$ele]['id_trasferta'] = $row['id_trasferta'];
		$ele++;
	}
}
else
{
	errorMessage('query errata: calendario');
}


//utenti
$sql2 = "SELECT id_utente,squadra FROM utenti ORDER BY squadra";

if($result = mysqli_query($con,$sql2))
{
	$ele = 0;
	while($row = mysqli_fetch_assoc($result))
	{
     	$utenti[$ele]['id'] = $row['id_utente'];
		$utenti[$ele]['squadra'] = $row['squadra'];
		$ele++;
	}
}
else
{
	errorMessage('query errata: utenti');
}







$orari = array('00:30:00','01:00:00','01:30:00','02:00:00','02:30:00','03:00:00','03:30:00','04:00:00','04:30:00','05:00:00',
				'05:30:00','06:00:00','06:30:00','07:00:00','07:30:00','08:00:00','08:30:00','09:00:00','09:30:00','10:00:00',
				'10:30:00','11:00:00','11:30:00','12:00:00','12:30:00','13:00:00','13:30:00','14:00:00','14:30:00','15:00:00',
				'15:30:00','16:00:00','16:30:00','17:00:00','17:30:00','18:00:00','18:30:00','19:00:00','19:30:00','20:00:00',
				'20:30:00','21:00:00','21:30:00','22:00:00','22:30:00','23:00:00','23:30:00','24:00:00');

$mesi = array('01','02','03','04','05','06','07','08','09','10','11','12');

$giorni = array('01','02','03','04','05','06','07','08','09','10',
				'11','12','13','14','15','16','17','18','19','20',
                '21','22','23','24','25','26','27','28','29','30','31');

$anni = array('2022','2023','2024');

$date_possibili->orari = $orari;
$date_possibili->mesi = $mesi;
$date_possibili->anni = $anni;
$date_possibili->giorni = $giorni;


//fasi
$sql2 = "SELECT id_fase,descrizione FROM fasi ORDER BY id_fase ASC";

if($result = mysqli_query($con,$sql2))
{
	$ele = 0;
	while($row = mysqli_fetch_assoc($result))
	{
     	$fasi[$ele]['id'] = $row['id_fase'];
		$fasi[$ele]['descrizione'] = $row['descrizione'];
		$ele++;
	}
}
else
{
	errorMessage('query errata: fasi');
}

//periodo partite
$sql9 = "SELECT id_giornata,prima_partita,inizio_giornata,ultima_partita,fine_giornata,serie_a,is_upgrade,fase_id FROM giornate";

if($result = mysqli_query($con,$sql9))
{
	$ele = 0;
	while($row = mysqli_fetch_assoc($result))
	{
		$periodo_partite[$ele]['giornata'] = $row['id_giornata'];
		$periodo_partite[$ele]['fase'] = $row['fase_id'];
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
	errorMessage('query errata: date partite');
}



//risultato
$myObj->date_possibili = $date_possibili;
$myObj->calendario = $calendario;
$myObj->utenti = $utenti;
$myObj->fasi = $fasi;
$myObj->periodo_partite = $periodo_partite;




$totObj=['data'=>$myObj];

$myJSON = json_encode($totObj);

echo $myJSON;

?>
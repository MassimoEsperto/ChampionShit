<?php

//dichiarazione variabili	
$giornate_ = [];


//Query ed elaborazioni
//giornate
$sql_giornate = "SELECT id_giornata,prima_partita,inizio_giornata,ultima_partita, ";
$sql_giornate .="fine_giornata,serie_a,is_upgrade,fase_id ";
$sql_giornate .="FROM giornate ";

if($result = mysqli_query($con,$sql_giornate))
{
	$ele = 0;
	while($row = mysqli_fetch_assoc($result))
	{
		$giornate_[$ele]['giornata'] = $row['id_giornata'];
		$giornate_[$ele]['fase'] = $row['fase_id'];
        $giornate_[$ele]['serie_a'] = $row['serie_a'];
		$giornate_[$ele]['inizio_giornata'] = $row['inizio_giornata'];
		$giornate_[$ele]['prima_partita'] = $row['prima_partita'];
        $giornate_[$ele]['ultima_partita'] = $row['ultima_partita'];
		$giornate_[$ele]['fine_giornata'] = $row['fine_giornata'];
		$giornate_[$ele]['is_upgrade'] = $row['is_upgrade'];
		$ele++;
	}
    
}

?>
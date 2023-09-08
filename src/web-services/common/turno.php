<?php
    
//dichiarazione variabili	
$turno_ = [];


//inizializzo a 0 nel caso fossero null
$turno_['giornata'] = 0;
$turno_['periodo'] = 0;


//Query ed elaborazioni
//turno in essere
$sql_turno = "SELECT DATE_FORMAT(prima_partita,'%d-%m-%Y %H:%i') as data_inizio,id_giornata,is_upgrade, ";
$sql_turno .="CASE ";
$sql_turno .="WHEN now() BETWEEN inizio_giornata AND prima_partita THEN 1 ";
$sql_turno .="WHEN now() BETWEEN prima_partita AND ultima_partita THEN 2 ";
$sql_turno .="ELSE 3 END as periodo ";
$sql_turno .="FROM giornate ";
$sql_turno .="WHERE now() BETWEEN inizio_giornata AND fine_giornata ";
$sql_turno .="ORDER BY id_giornata LIMIT 1";

if($result = mysqli_query($con,$sql_turno))
{
	while($row = mysqli_fetch_assoc($result))
	{
		$turno_['data_inizio'] = $row['data_inizio'];
		$turno_['giornata'] = $row['id_giornata'];
        $turno_['is_upgrade'] = $row['is_upgrade'];
		$turno_['periodo'] = $row['periodo'];
	}
    
}


 
?>
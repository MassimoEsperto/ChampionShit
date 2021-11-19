<?php

require_once '../config/connect_local.php';
require_once '../config/validate.php';
    
$elements = [];


$sql = "SELECT c.id_partita,c.girone,c.fase,c.giornata,u.id_utente as id_casa,u.squadra as casa,t.id_utente as id_trasferta, t.squadra as trasferta,d.serie_a, ";
$sql .="c.gol_casa,c.gol_trasferta,c.calcolato,DATE_FORMAT(d.data_inizio,'%d/%m/%Y') AS data_giornata, au.nome as avatar_casa,at.nome as avatar_trasferta "; 
$sql .="FROM calendario c,utenti u,utenti t, data_partite d ,avatar au,avatar at  ";
$sql .="WHERE u.id_utente=c.utente_casa and t.id_utente=c.utente_trasferta and d.giornata=c.giornata ";
$sql .="AND au.id_avatar=u.avatar_id AND at.id_avatar=t.avatar_id  ";
$sql .="ORDER BY c.giornata,c.girone,c.id_partita ";


if($result = mysqli_query($con,$sql))
{
	$ele = 0;
	while($row = mysqli_fetch_assoc($result))
	{
 
		$elements[$ele]['partita'] = $row['id_partita'];
		$elements[$ele]['girone'] = $row['girone'];
        $elements[$ele]['fase'] = $row['fase'];
		$elements[$ele]['giornata'] = $row['giornata'];
        $elements[$ele]['calcolato'] = $row['calcolato'];
		$elements[$ele]['casa'] = $row['casa'];
        $elements[$ele]['id_casa'] = $row['id_casa'];
		$elements[$ele]['trasferta'] = $row['trasferta'];
		$elements[$ele]['id_trasferta'] = $row['id_trasferta'];
        $elements[$ele]['gol_casa'] = $row['gol_casa'];
		$elements[$ele]['gol_trasferta'] = $row['gol_trasferta'];
        $elements[$ele]['data'] = $row['data_giornata'];
        $elements[$ele]['serie_a'] = $row['serie_a'];
        $elements[$ele]['avatar_casa'] = $row['avatar_casa'];
		$elements[$ele]['avatar_trasferta'] = $row['avatar_trasferta'];
		$ele++;
	}
    
    //sleep(1);
	echo json_encode(['data'=>$elements]);
}
else
{
	header("HTTP/1.1 500 Internal Server Error");
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode(array('message' => 'ERROR', 'code' => 404)));
}

?>
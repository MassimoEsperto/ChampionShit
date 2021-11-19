<?php

require_once '../config/connect_local.php';
    
$team = [];
$occupati = [];
$gironi = [];

$sql1 = "SELECT id_utente,squadra,lega FROM utenti WHERE id_utente not in ( SELECT utente_casa FROM calendario ) AND id_utente < 100 order by lega";
$sql2 = "SELECT DISTINCT girone FROM calendario";


if($result = mysqli_query($con,$sql1))
{
	$ele = 0;
	while($row = mysqli_fetch_assoc($result))
	{
		$team[$ele]['descrizione'] = $row['squadra'].' ('.$row['lega'].')';
        $team[$ele]['id'] = $row['id_utente'];
		$ele++;
	}
}
else
{
	header("HTTP/1.1 500 Internal Server Error");
	header('Content-Type: application/json; charset=UTF-8');
	die(json_encode(array('message' => 'query errata', 'code' => 400)));
}

if($result = mysqli_query($con,$sql2))
{
	$ele = 0;
	while($row = mysqli_fetch_assoc($result))
	{
      $occupati[$ele] = $row['girone'];
      $ele++;  
	}

    $gironi = array_values(array_diff(["A","B","C","D","E","F"], $occupati));

}
else
{
	header("HTTP/1.1 500 Internal Server Error");
	header('Content-Type: application/json; charset=UTF-8');
	die(json_encode(array('message' => 'query errata', 'code' => 400)));
}

$myObj->gironi = $gironi;
$myObj->utenti = $team;
$totObj=['data'=>$myObj];

$myJSON = json_encode($totObj);
echo $myJSON;

?>
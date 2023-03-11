<?php

require_once '../config/connect_local.php';
    
$team = [];
$occupati = [];
$gironi = [];

$sql1 = "SELECT id_utente,squadra,lega FROM utenti WHERE id_utente not in ( SELECT utente_id FROM risultati ) AND id_utente < 100 order by lega";
$sql2 = "SELECT DISTINCT girone FROM calendarioNEW";


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
	errorMessage('query errata: utenti disponibili');
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
	errorMessage('query errata: gironi disponibili');
}

$myObj->gironi = $gironi;
$myObj->utenti = $team;
$totObj=['data'=>$myObj];

$myJSON = json_encode($totObj);
echo $myJSON;

?>
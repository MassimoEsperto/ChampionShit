<?php

require_once '../config/connect_local.php';
    
$team = [];
$occupati = [];
$gironi = [];

$sql1 = "SELECT id_squadra,squadra,lega,account,utente_id FROM squadre WHERE id_squadra not in ( SELECT squadra_id FROM risultati ) order by utente_id,lega";
$sql2 = "SELECT DISTINCT girone FROM calendario";


if($result = mysqli_query($con,$sql1))
{
	$ele = 0;
	while($row = mysqli_fetch_assoc($result))
	{
		$team[$ele]['descrizione'] = $row['utente_id'] .')' . $row['squadra'].' ('.$row['lega'].')';
        $team[$ele]['id'] = $row['id_squadra'];
		$ele++;
	}
}
else
{
	errorMessage('query errata: squadre disponibili');
}

if($result = mysqli_query($con,$sql2))
{
	$ele = 0;
	while($row = mysqli_fetch_assoc($result))
	{
      $occupati[$ele] = $row['girone'];
      $ele++;  
	}

    $gironi = array_values(array_diff(["A","B","C","D","E","F","G","H","I"], $occupati));

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
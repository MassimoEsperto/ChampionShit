<?php
require_once '../config/connect_local.php';
require_once '../config/decode.php';

//variabili
$squadre_utente = [];


$sql1 = "SELECT id_squadra,squadra,account,lega FROM squadre WHERE utente_id = {$id_utente} ";

if($result = mysqli_query($con,$sql1))
{
	$ele = 0;		
	while($row = mysqli_fetch_assoc($result))
	{
		
          $squadre_utente[$ele]['id_squadra'] = $row['id_squadra'];
          $squadre_utente[$ele]['squadra'] = $row['squadra'];
          $squadre_utente[$ele]['account'] = $row['account'];
          $squadre_utente[$ele]['lega'] = $row['lega'];
          
          $ele++;
	}
}
else
{
    errorMessage('query errata: squadre ');
}



//risultato
$myObj->squadre = $squadre_utente;
$myObj->query = $sql1;

$totObj=['data'=>$myObj];

$myJSON = json_encode($totObj);
echo $myJSON;

?>
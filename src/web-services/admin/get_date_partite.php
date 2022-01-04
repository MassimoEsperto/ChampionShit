<?php

require_once '../config/connect_local.php';
    
$element = [];
$sql = "SELECT giornata,data_inizio,data_fine,serie_a FROM data_partite";

if($result = mysqli_query($con,$sql))
{
	$ele = 0;
	while($row = mysqli_fetch_assoc($result))
	{
		$element[$ele]['giornata'] = $row['giornata'];
		$element[$ele]['serie_a'] = $row['serie_a'];
		$element[$ele]['data_inizio'] = $row['data_inizio'];
		$element[$ele]['data_fine'] = $row['data_fine'];
		$ele++;
	}
    
	echo json_encode(['data'=>$element]);
}
else
{
	errorMessage('query errata');
}
?>
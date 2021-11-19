<?php

require_once '../config/connect_local.php';

$sql  ="SELECT id_comunicazione,titolo FROM comunicazioni ORDER BY id_comunicazione DESC"; 

$element = [];

if($result = mysqli_query($con,$sql))
{
	$ele = 0;
	while($row = mysqli_fetch_assoc($result))
	{
 
		$element[$ele]['id'] = $row['id_comunicazione'];
        $element[$ele]['titolo'] = $row['titolo'];
		$ele++;
	}
    
	echo json_encode(['data'=>$element]);
}
else
{
	header("HTTP/1.1 500 Internal Server Error");
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode(array('message' => 'Nessuna Comunicazione', 'code' => 404)));
}

?>
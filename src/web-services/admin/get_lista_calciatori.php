<?php

require_once '../config/connect_local.php';
    
$element = [];
$sql = "SELECT * FROM lista_calciatori order by nome_calciatore";

if($result = mysqli_query($con,$sql))
{
	$rows = array();
    while($r = mysqli_fetch_assoc($result)) {
        $rows[] = $r;
	}

 	echo json_encode(['data'=>$rows]);
}
else
{
	header("HTTP/1.1 500 Internal Server Error");
	header('Content-Type: application/json; charset=UTF-8');
	die(json_encode(array('message' => 'query errata', 'code' => 400)));
}
?>
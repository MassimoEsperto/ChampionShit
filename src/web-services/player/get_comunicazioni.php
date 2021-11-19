<?php

require_once '../config/connect_local.php';
require_once '../config/decode.php';

$sql  ="SELECT id_comunicazione,data,utente_id,titolo,messaggio,visualizzata FROM comunicazioni,utente_com "; 
$sql .="WHERE id_comunicazione=comunicazione_id AND utente_id='{$id_utente}' ORDER BY id_comunicazione DESC"; 

$element = [];

if($result = mysqli_query($con,$sql))
{
	$ele = 0;
	while($row = mysqli_fetch_assoc($result))
	{
 
		$element[$ele]['id_comunicazione'] = $row['id_comunicazione'];
        $element[$ele]['titolo'] = $row['titolo'];
        $element[$ele]['tempo'] = $row['data'];
		$element[$ele]['messaggio'] = $row['messaggio'];
        $element[$ele]['visualizzata'] = $row['visualizzata'];
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
<?php

require_once '../config/connect_local.php';
require '../config/validate.php';

$element = [];

$sql = "SELECT u.id_utente,u.squadra,l.nome_calciatore as nome,l.ruolo,r.nome as avatar,l.id_calciatore,u.username,u.lega  "; 
$sql .="FROM lista_calciatori l,rosa_utente a,utenti u ,avatar r  ";
$sql .="WHERE a.id_utente=u.id_utente and a.id_calciatore=l.id_calciatore and u.avatar_id=r.id_avatar  ";
$sql .="ORDER BY u.squadra,l.ruolo DESC, l.nome_calciatore";

if($result = mysqli_query($con,$sql))
{
	$ele = -1;
	$utente = 0;
	$list = 0;
	
	while($row = mysqli_fetch_assoc($result))
	{
		if($row['id_utente']!=$utente)
		{
			$list = 0;
			$ele++;
		}
		
		$element[$ele]['squadra'] = str_replace(' ', '', $row['squadra']);
        $element[$ele]['id_utente'] = $row['id_utente'];	
        $element[$ele]['avatar'] = $row['avatar'];
		$element[$ele]['username'] = $row['username'];	
        $element[$ele]['lega'] = $row['lega'];
		
        $element[$ele]['lista'][$list]['id_calciatore'] = $row['id_calciatore'];
		$element[$ele]['lista'][$list]['calciatore'] = $row['nome'];
		$element[$ele]['lista'][$list]['ruolo'] = $row['ruolo'];
		
		$utente = $row['id_utente'];
		$list++;
	
	}
 
	echo json_encode(['data'=>$element]);
}
else
{
	header("HTTP/1.1 500 Internal Server Error");
	header('Content-Type: application/json; charset=UTF-8');
	die(json_encode(array('message' => 'query errata', 'code' => 400)));
}
?>
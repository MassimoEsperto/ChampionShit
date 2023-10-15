<?php

require_once '../config/connect_local.php';
require '../config/validate.php';

$element = [];

$sql = "SELECT s.squadra,s.lega,s.id_squadra,s.account, "; 
$sql .="l.nome_calciatore as nome,l.ruolo,l.id_calciatore, ";
$sql .="a.nome as avatar,u.id_utente,u.username ";
$sql .="FROM lista_calciatori l,rose r,utenti u ,avatar a ,squadre s ";
$sql .="WHERE r.squadra_id=s.id_squadra AND s.utente_id = u.id_utente ";
$sql .="AND r.calciatore_id=l.id_calciatore AND s.avatar_id=a.id_avatar ";
$sql .="ORDER BY s.squadra,l.ruolo DESC, l.nome_calciatore ";

if($result = mysqli_query($con,$sql))
{
	$ele = -1;
	$squadrone = 0;
	$list = 0;
	
	while($row = mysqli_fetch_assoc($result))
	{
		if($row['id_squadra']!=$squadrone)
		{
			$list = 0;
			$ele++;
		}
		
		$element[$ele]['squadra'] = $row['squadra'];
        $element[$ele]['id_utente'] = $row['id_utente'];	
        $element[$ele]['id_squadra'] = $row['id_squadra'];	
        $element[$ele]['avatar'] = $row['avatar'];
		$element[$ele]['username'] = $row['username'];	
        $element[$ele]['lega'] = $row['lega'];
        $element[$ele]['account'] = $row['account'];
		
        $element[$ele]['lista'][$list]['id_calciatore'] = $row['id_calciatore'];
		$element[$ele]['lista'][$list]['calciatore'] = $row['nome'];
		$element[$ele]['lista'][$list]['ruolo'] = $row['ruolo'];
        $element[$ele]['lista'][$list]['id_utente'] = $row['id_utente'];
        $element[$ele]['lista'][$list]['id_squadra'] = $row['id_squadra'];
		
		$squadrone = $row['id_squadra'];
		$list++;
	
	}
 
	echo json_encode(['data'=>$element]);
}
else
{
	errorMessage('query errata: rose competizione');
}
?>
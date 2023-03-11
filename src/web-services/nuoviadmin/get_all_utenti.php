<?php

require_once '../config/connect_local.php';
    
$element = [];
//utenti
$sql = "SELECT u.id_utente,u.username,u.email,u.ruolo_id,u.squadra,u.account,r.stato ";
$sql .="FROM utenti u,ruoli r where id_utente < 100 AND r.id_ruolo=u.ruolo_id";


if($result = mysqli_query($con,$sql))
{
	$ele = 0;
	while($row = mysqli_fetch_assoc($result))
	{
		$utenti[$ele]['username'] = $row['username'];
		$utenti[$ele]['squadra'] = $row['squadra'];
		$utenti[$ele]['email'] = $row['email'];
		$utenti[$ele]['ruolo'] = $row['ruolo_id'];
        $utenti[$ele]['stato'] = $row['stato'];
        $utenti[$ele]['id'] = $row['id_utente'];
        $utenti[$ele]['account'] = $row['account'];
		$ele++;
	}
    echo json_encode(['data'=>$utenti]);
}
else
{
	errorMessage('query errata: utenti');
}


?>
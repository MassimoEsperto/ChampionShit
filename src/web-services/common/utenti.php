<?php
    
//dichiarazione variabili	
$utenti_ = [];

//Query ed elaborazioni
//all utenti
$sql_utenti = "SELECT u.id_utente,u.username,u.email,u.ruolo_id,u.squadra,u.account,r.stato ";
$sql_utenti .="FROM utenti u,ruoli r where id_utente < 100 AND r.id_ruolo=u.ruolo_id";


if($result = mysqli_query($con,$sql_utenti))
{
	$ele = 0;
	while($row = mysqli_fetch_assoc($result))
	{
		$utenti_[$ele]['username'] = $row['username'];
		$utenti_[$ele]['squadra'] = $row['squadra'];
        $utenti_[$ele]['descrizione'] = $row['squadra'];
		$utenti_[$ele]['email'] = $row['email'];
		$utenti_[$ele]['ruolo'] = $row['ruolo_id'];
        $utenti_[$ele]['stato'] = $row['stato'];
        $utenti_[$ele]['id'] = $row['id_utente'];
        $utenti_[$ele]['account'] = $row['account'];
		$ele++;
	}
   
}


?>
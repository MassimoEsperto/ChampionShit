<?php
    
//dichiarazione variabili	
$utenti_ = [];

//Query ed elaborazioni
//all utenti
$sql_utenti = "SELECT id_utente,username,email,language,ruolo_id, ";
$sql_utenti .="(SELECT COUNT(*) FROM squadre WHERE utente_id=id_utente) AS qta ";
$sql_utenti .="FROM utenti ";


if($result = mysqli_query($con,$sql_utenti))
{
	$ele = 0;
	while($row = mysqli_fetch_assoc($result))
	{
    	$utenti_[$ele]['id'] = $row['id_utente'];
		$utenti_[$ele]['username'] = $row['username'];
		$utenti_[$ele]['email'] = $row['email'];
        $utenti_[$ele]['language'] = $row['language'];
        $utenti_[$ele]['qta'] = $row['qta'];
        $utenti_[$ele]['ruolo'] = $row['ruolo_id'];
		$ele++;
	}
   
}


?>
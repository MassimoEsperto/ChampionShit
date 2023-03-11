<?php
    
//dichiarazione variabili	
$rose_ = [];

//rose utenti
$sql_rose = "SELECT u.id_utente,u.squadra,l.nome_calciatore as nome,l.ruolo,r.nome as avatar,l.id_calciatore,u.username,u.lega  "; 
$sql_rose .="FROM lista_calciatori l,rosa_utente a,utenti u ,avatar r  ";
$sql_rose .="WHERE a.id_utente=u.id_utente and a.id_calciatore=l.id_calciatore and u.avatar_id=r.id_avatar  ";
$sql_rose .="ORDER BY u.squadra,l.ruolo DESC, l.nome_calciatore";

if($result = mysqli_query($con,$sql_rose))
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
		
		$rose_[$ele]['squadra'] = str_replace(' ', '', $row['squadra']);
        $rose_[$ele]['id_utente'] = $row['id_utente'];	
        $rose_[$ele]['avatar'] = $row['avatar'];
		$rose_[$ele]['username'] = $row['username'];	
        $rose_[$ele]['lega'] = $row['lega'];
		
        $rose_[$ele]['lista'][$list]['id_calciatore'] = $row['id_calciatore'];
		$rose_[$ele]['lista'][$list]['calciatore'] = $row['nome'];
		$rose_[$ele]['lista'][$list]['ruolo'] = $row['ruolo'];
		
		$utente = $row['id_utente'];
		$list++;
	
	}
}

?>
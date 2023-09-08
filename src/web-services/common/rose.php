<?php
    
//dichiarazione variabili	
$rose_ = [];

//rose utenti
$sql_rose = "SELECT u.id_utente,s.id_squadra,s.squadra,l.nome_calciatore as nome, "; 
$sql_rose .="l.ruolo,r.nome as avatar,l.id_calciatore,u.username,s.lega  ";
$sql_rose .="FROM lista_calciatori l,rose a,utenti u ,avatar r, squadre s ";
$sql_rose .="WHERE a.squadra_id=s.id_squadra AND a.calciatore_id=l.id_calciatore ";
$sql_rose .="AND s.avatar_id=r.id_avatar AND s.utente_id = u.id_utente ";
$sql_rose .="ORDER BY s.squadra,l.ruolo DESC, l.nome_calciatore";

if($result = mysqli_query($con,$sql_rose))
{
	$ele = -1;
	$utente = 0;
	$list = 0;
	
	while($row = mysqli_fetch_assoc($result))
	{
		if($row['id_squadra']!=$utente)
		{
			$list = 0;
			$ele++;
		}
		
		$rose_[$ele]['squadra'] = str_replace(' ', '', $row['squadra']);
        $rose_[$ele]['id_squadra'] = $row['id_squadra'];
        $rose_[$ele]['id_utente'] = $row['id_utente'];
        $rose_[$ele]['avatar'] = $row['avatar'];
		$rose_[$ele]['username'] = $row['username'];	
        $rose_[$ele]['lega'] = $row['lega'];
		
        $rose_[$ele]['lista'][$list]['id_calciatore'] = $row['id_calciatore'];
		$rose_[$ele]['lista'][$list]['calciatore'] = $row['nome'];
		$rose_[$ele]['lista'][$list]['ruolo'] = $row['ruolo'];
		
		$utente = $row['id_squadra'];
		$list++;
	
	}
}

?>
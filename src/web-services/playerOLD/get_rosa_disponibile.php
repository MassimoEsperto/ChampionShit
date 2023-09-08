<?php

require_once '../common/turno.php';
require_once '../config/decode.php';

//variabili
$id_sfidante = 0;//inizializzo a 0 in caso giochi in casa
$rosa = [];
$indisponibili = [];
$formazione = [];
$schierata = [0,0,0,0,0];
$id_partita;
$moduli = [];


$sql1 = "SELECT CASE utente_casa WHEN {$id_utente} THEN 0 ELSE utente_casa END as sfidante,id_partita ";
$sql1 .="FROM calendario  ";
$sql1 .="WHERE giornata = {$turno['giornata']} ";
$sql1 .="AND (utente_trasferta={$id_utente} or utente_casa={$id_utente})";

if($result = mysqli_query($con,$sql1))
{
	while($row = mysqli_fetch_assoc($result))
	{
		$id_sfidante =   $row['sfidante'];
		$id_partita =   $row['id_partita'];
	}
}
else
{
	errorMessage('query errata: match');
}


$sql3 .="SELECT f.id_calciatore, l.nome_calciatore,l.ruolo,f.id_utente ";
$sql3 .="FROM formazioni f,lista_calciatori l WHERE f.id_calciatore = l.id_calciatore ";
$sql3 .="AND f.id_partita = {$id_partita} AND f.id_utente ={$id_utente}";

if($result = mysqli_query($con,$sql3))
{
	$ele = 0;
	while($row = mysqli_fetch_assoc($result))
	{
		$schierata[$ele] = $row['id_calciatore'];
		$formazione[$ele]['id'] = $row['id_calciatore'];
		$formazione[$ele]['nome'] = $row['nome_calciatore'];
		$formazione[$ele]['tipo'] = $row['ruolo'];
		$ele++;
	}
    
}
else
{
	errorMessage('query errata: formazione');
}


 
$sql2 = "SELECT l.id_calciatore ,l.nome_calciatore ,l.ruolo ,a.id_utente,true as disabled,false as preselect ";
$sql2 .="FROM lista_calciatori l,rosa_utente a  ";
$sql2 .="WHERE a.id_utente='{$id_utente}' and a.id_calciatore=l.id_calciatore  ";
$sql2 .="and l.id_calciatore in(SELECT l.id_calciatore as id ";
$sql2 .="FROM lista_calciatori l,rosa_utente a  ";
$sql2 .="WHERE a.id_utente='{$id_sfidante}' and a.id_calciatore=l.id_calciatore ) ";
$sql2 .="UNION  ";
$sql2 .="SELECT l.id_calciatore ,l.nome_calciatore ,l.ruolo ,a.id_utente,false as disabled, ";
$sql2 .="CASE l.id_calciatore WHEN $schierata[0] THEN true WHEN $schierata[1] THEN true WHEN $schierata[2] THEN true ";
$sql2 .="WHEN $schierata[3] THEN true WHEN $schierata[4] THEN true ELSE false END as preselect ";
$sql2 .="FROM lista_calciatori l,rosa_utente a  ";
$sql2 .="WHERE a.id_utente='{$id_utente}' and a.id_calciatore=l.id_calciatore  ";
$sql2 .="and l.id_calciatore not in(SELECT l.id_calciatore as id ";
$sql2 .="FROM lista_calciatori l,rosa_utente a  ";
$sql2 .="WHERE a.id_utente='{$id_sfidante}' and a.id_calciatore=l.id_calciatore ) ";
$sql2 .="ORDER BY ruolo DESC,id_calciatore ";


if($result = mysqli_query($con,$sql2))
{
	$dip = 0;
	$non = 0;
    
    $indisponibili[$non]['id'] = 1;
	$indisponibili[$non]['nome'] = ' ';
	$indisponibili[$non]['tipo'] = '?????';
    
	while($row = mysqli_fetch_assoc($result))
	{
		if($row['disabled']==false)
		{
			$rosa[$dip]['id'] = $row['id_calciatore'];
			$rosa[$dip]['nome'] = $row['nome_calciatore'];
			$rosa[$dip]['tipo'] = $row['ruolo'];
			$rosa[$dip]['id_utente'] = $row['id_utente'];
			$rosa[$dip]['selected'] = $row['preselect'] == 1;
            $rosa[$dip]['percentuale'] = '0%';
			$dip++;
		}else{
			$indisponibili[$non]['id'] = $row['id_calciatore'];
			$indisponibili[$non]['nome'] = $row['nome_calciatore'];
			$indisponibili[$non]['tipo'] = $row['ruolo'];
			$indisponibili[$non]['id_utente'] = $row['id_utente'];
			$non++;
		}
	}

} 
else 
{
	 errorMessage('query errata: rosa');
}

$sql3 = "SELECT m.id_modulo,m.descrizione,m.bonus,m.indice ";
$sql3 .="FROM moduli m ";

if($result = mysqli_query($con,$sql3))
{
	while($row = mysqli_fetch_assoc($result))
	{
		
         $moduli[$row['indice']]['id'] = $row['id_modulo'];
         $moduli[$row['indice']]['bonus'] = $row['bonus'];
		 $moduli[$row['indice']]['descrizione'] = $row['descrizione'];
	}
}
else
{
    errorMessage('query errata: moduli');
}



//risultato
$myObj->rosa = $rosa;
$myObj->indisponibili = $indisponibili;
$myObj->id_partita = $id_partita;
$myObj->schierata = $formazione;
$myObj->moduli = $moduli;
$totObj=['data'=>$myObj];

/*
SELECT * 
FROM rosa_utente r
LEFT JOIN formazioni f ON r.id_calciatore=f.id_calciatore AND f.id_partita = 55 
LEFT JOIN rosa_utente a ON r.id_calciatore=a.id_calciatore AND a.id_utente=18
WHERE r.id_utente=1 

SELECT * 
FROM rosa_utente my
INNER JOIN calendario c ON c.id_partita = 55 
INNER JOIN risultati r  ON c.id_partita = r.partita_id AND r.utente_id=my.id_utente
LEFT JOIN formazioni f ON my.id_calciatore=f.id_calciatore AND f.id_partita = c.id_partita 
LEFT JOIN rosa_utente a ON my.id_calciatore=a.id_calciatore AND a.id_utente=18 AND r.luogo = 'TRASFERTA'
WHERE my.id_utente=1 
*/

$myJSON = json_encode($totObj);
echo $myJSON;

?>
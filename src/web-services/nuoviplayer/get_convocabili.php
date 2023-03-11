<?php

require_once '../common/turno.php';
$id_utente=1;//require_once '../config/decode.php';

//variabili
$moduli = [];
$convovabili = [];
$indisponibili = [];
$id_risultato = 0;
$schierata = [];



$sql1 = "SELECT m.id_modulo,m.descrizione,m.bonus,m.indice ";
$sql1 .="FROM moduli m ";

if($result = mysqli_query($con,$sql1))
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


$sql2 = "SELECT l.id_calciatore,l.nome_calciatore,l.nickname,l.ruolo, ";
$sql2 .="r.modulo_id,r.luogo,f.schieramento,a.id_calciatore as inibito,r.id_risultato ";
$sql2 .="FROM rosa_utente my ";
$sql2 .="INNER JOIN lista_calciatori l on l.id_calciatore = my.id_calciatore ";
$sql2 .="INNER JOIN calendarioNEW c ON c.giornata_id = {$turno['giornata']} ";
$sql2 .="INNER JOIN risultati r  ON c.id_calendario = r.calendario_id AND r.utente_id=my.id_utente ";
$sql2 .="LEFT JOIN formazioniNEW f ON my.id_calciatore=f.calciatore_id AND f.risultato_id = r.id_risultato ";
$sql2 .="INNER JOIN risultati s  ON c.id_calendario = s.calendario_id AND s.utente_id!=my.id_utente ";
$sql2 .="LEFT JOIN rosa_utente a ON my.id_calciatore=a.id_calciatore AND a.id_utente=s.utente_id AND s.luogo = 'TRASFERTA' ";
$sql2 .="WHERE my.id_utente=1 ORDER BY l.ruolo DESC ";

if($result = mysqli_query($con,$sql2))
{
	$ele = 0;
    $ind = 0;
    $frm = 0;
	while($row = mysqli_fetch_assoc($result))
	{
		 if($row['inibito'] == null){
           $rosa[$ele]['id'] = $row['id_calciatore'];
           $rosa[$ele]['nome'] = $row['nome_calciatore'];
           $rosa[$ele]['tipo'] = $row['ruolo'];
           $rosa[$ele]['modulo'] = $row['modulo_id'];
           $rosa[$ele]['luogo'] = $row['luogo'];
           $rosa[$ele]['percentuale'] = '0%';  
           $rosa[$ele]['selected'] = $row['schieramento'] != null;


           if($rosa[$ele]['selected']){
              $schierata[$frm]['id'] = $row['id_calciatore'];
              $schierata[$frm]['nome'] = $row['nome_calciatore'];
              $schierata[$frm]['tipo'] = $row['ruolo'];
              $frm++;
           }
           $ele++;
           
         }else{
         	$indisponibili[$ind]['id'] = $row['id_calciatore'];
            $indisponibili[$ind]['nome'] = $row['nome_calciatore'];
         	$indisponibili[$ind]['tipo'] = $row['ruolo'];
            $ind++;
         }
         $id_risultato = $row['id_risultato'];
	}
}
else
{
    errorMessage('query errata: convocabili');
}



//risultato
$myObj->moduli = $moduli;
$myObj->rosa = $rosa;
$myObj->schierata = $schierata;
$myObj->indisponibili = $indisponibili;
$myObj->id_risultato = $id_risultato;

$totObj=['data'=>$myObj];

$myJSON = json_encode($totObj);
echo $myJSON;

?>
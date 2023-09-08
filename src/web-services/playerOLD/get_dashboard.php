<?php
require_once '../config/connect_local.php';
require_once '../common/turno.php';
require_once '../config/decode.php';
    
//dichiarazione variabili	
$risultati = [];
$match_in_corso = [];
$statistiche = [];

$formazione_casa = [];
$formazione_trasferta = [];

$calendario = [];
$totali = [];
$percorso = [];

//inizializzo a 0 nel caso fossero null
$match = 0;


//Query ed elaborazioni
if($turno['periodo'] == 3)
{
  //risultati
  $sql3 = "SELECT c.id_partita,c.girone,d.fase_id,c.giornata,u.id_utente as id_casa,u.squadra as casa,t.id_utente as id_trasferta, t.squadra as trasferta,d.serie_a, ";
  $sql3 .="c.gol_casa,c.gol_trasferta,c.calcolato,DATE_FORMAT(d.prima_partita,'%d/%m/%Y') AS data_giornata, au.nome as avatar_casa,at.nome as avatar_trasferta "; 
  $sql3 .="FROM calendario c,utenti u,utenti t, giornate d ,avatar au,avatar at  ";
  $sql3 .="WHERE u.id_utente=c.utente_casa and t.id_utente=c.utente_trasferta and d.id_giornata=c.giornata ";
  $sql3 .="AND au.id_avatar=u.avatar_id AND at.id_avatar=t.avatar_id AND c.giornata = {$turno['giornata']} ";
  $sql3 .="ORDER BY c.giornata,c.girone,c.id_partita ";

  if($result = mysqli_query($con,$sql3))
  {
      $ele = 0;
      while($row = mysqli_fetch_assoc($result))
      {

          $risultati[$ele]['partita'] = $row['id_partita'];
          $risultati[$ele]['girone'] = $row['girone'];
          $risultati[$ele]['fase'] = $row['fase_id'];
          $risultati[$ele]['giornata'] = $row['giornata'];
          $risultati[$ele]['calcolato'] = $row['calcolato'];
          $risultati[$ele]['casa'] = str_replace(' ', '', $row['casa']);
          $risultati[$ele]['id_casa'] = $row['id_casa'];
          $risultati[$ele]['trasferta'] = str_replace(' ', '', $row['trasferta']);
          $risultati[$ele]['id_trasferta'] = $row['id_trasferta'];
          $risultati[$ele]['gol_casa'] = $row['gol_casa'];
          $risultati[$ele]['gol_trasferta'] = $row['gol_trasferta'];
          $risultati[$ele]['data'] = $row['data_giornata'];
          $risultati[$ele]['serie_a'] = $row['serie_a'];
          $risultati[$ele]['avatar_casa'] = $row['avatar_casa'];
          $risultati[$ele]['avatar_trasferta'] = $row['avatar_trasferta'];
          $ele++;
      }

  }
  else
  {
      errorMessage('query errata: risultati');
  }
}


if($turno['periodo'] != 3)
{

  //match in corso
  $sql4 = "SELECT c.id_partita,u.id_utente as id_casa,u.squadra as casa,t.id_utente as id_trasferta, t.squadra as trasferta, ";
  $sql4 .="au.nome as avatar_casa,at.nome as avatar_trasferta "; 
  $sql4 .="FROM calendario c,utenti u,utenti t,avatar au,avatar at  ";
  $sql4 .="WHERE u.id_utente=c.utente_casa and t.id_utente=c.utente_trasferta ";
  $sql4 .="AND au.id_avatar=u.avatar_id AND at.id_avatar=t.avatar_id AND c.giornata = {$turno['giornata']} ";
  $sql4 .="AND (c.utente_casa = {$id_utente} OR c.utente_trasferta = {$id_utente} ) LIMIT 1";


  if($result = mysqli_query($con,$sql4))
  {
      $ele = 0;
      while($row = mysqli_fetch_assoc($result))
      {

          $match_in_corso['partita'] = $row['id_partita'];
          $match_in_corso['casa'] = str_replace(' ', '', $row['casa']);
          $match_in_corso['id_casa'] = $row['id_casa'];
          $match_in_corso['trasferta'] = str_replace(' ', '', $row['trasferta']);
          $match_in_corso['id_trasferta'] = $row['id_trasferta'];
          $match_in_corso['avatar_casa'] = $row['avatar_casa'];
          $match_in_corso['avatar_trasferta'] = $row['avatar_trasferta'];
          $match = $row['id_partita'];
          $ele++;
      }

  }
  else
  {
      errorMessage('query errata: prossimo match');
  }


  if($turno['periodo'] == 1)
  {
    $sql5 = "SELECT f.schieramento,f.id_calciatore,l.nickname,l.ruolo,f.id_utente ";
    $sql5 .="FROM formazioni f,lista_calciatori l ";
    $sql5 .="WHERE f.id_partita={$match} and l.id_calciatore=f.id_calciatore "; 
    $sql5 .="ORDER BY f.id_utente,f.schieramento ";

    if($result = mysqli_query($con,$sql5))
    {
      $countC = 0;
      $countT = 0;
      while($row = mysqli_fetch_assoc($result))
      {
        if($row['id_utente'] == $match_in_corso['id_casa']){
            $formazione_casa[$countC]['schieramento'] = $row['schieramento'];
            $formazione_casa[$countC]['id_calciatore'] = $row['id_calciatore'];
            $formazione_casa[$countC]['nome_calciatore'] = $row['nickname'];
            $formazione_casa[$countC]['ruolo'] = $row['ruolo'];
            $countC++;
        }
        if($row['id_utente'] == $match_in_corso['id_trasferta']){
            $formazione_trasferta[$countT]['schieramento'] = $row['schieramento'];
            $formazione_trasferta[$countT]['id_calciatore'] = $row['id_calciatore'];
            $formazione_trasferta[$countT]['nome_calciatore'] = $row['nickname'];
            $formazione_trasferta[$countT]['ruolo'] = $row['ruolo'];
            $countT++;
        }
      }

        $match_in_corso['formazione_casa'] = $formazione_casa;
        $match_in_corso['formazione_trasferta'] = $formazione_trasferta;
    }
    else
    {
        errorMessage('query errata: schieramenti');
    }

  }
  
}



//statistiche
$sql6 = "SELECT c.id_partita,u.id_utente as id_casa,u.squadra as casa,t.id_utente as id_trasferta,  ";
$sql6 .="t.squadra as trasferta,c.gol_casa,c.gol_trasferta,c.pt_casa,c.pt_trasferta,c.fase, ";
$sql6 .="u.ruolo_id as r_casa,t.ruolo_id as r_trasferta ";
$sql6 .="FROM calendario c,utenti u,utenti t ";
$sql6 .="WHERE u.id_utente=c.utente_casa and t.id_utente=c.utente_trasferta ";
$sql6 .="AND c.calcolato=1 AND ( u.id_utente={$id_utente} OR t.id_utente={$id_utente} ) ";
$sql6 .="ORDER BY c.giornata,c.id_partita ";
if($result = mysqli_query($con,$sql6))
{
	$ind=0;
    $per=0;
    $fase=0;
    $v=0;
    $p=0;
    $s=0;
    $g=0;
	while($row = mysqli_fetch_assoc($result))
	{
        
    	if($row['fase'] != $fase){
        	if($fase != 0){

              $percorso[$per]['fase'] =   $fase;
              $percorso[$per]['calendario'] = $calendario;
              $per++;
              $ind = 0;
              $calendario = [];
            }
            
        	$fase = $row['fase']; 
        }
        
        
		$calendario[$ind]['casa'] =  str_replace(' ', '', $row['casa']);
		$calendario[$ind]['trasferta'] =  str_replace(' ', '', $row['trasferta']);
        $calendario[$ind]['gol_casa'] =   $row['gol_casa'];
        $calendario[$ind]['gol_trasferta'] =   $row['gol_trasferta'];
        $calendario[$ind]['fase'] =   $row['fase'];
        
        if($row['id_casa']==$id_utente){
         	$calendario[$ind]['is_casa'] =   true;
            $statistiche['stato'] = $row['r_casa'];
        	$g= $g+$row['gol_casa'];
        	switch ($row['pt_casa']) {
            case "3":
              $v++;
              break;
            case "1":
              $p++;
              break;
            case "0":
              $s++;
              break;
			}
        }else{
        	$calendario[$ind]['is_casa'] =   false;
        	$g= $g+$row['gol_trasferta'];
            $statistiche['stato'] = $row['r_trasferta'];
        	switch ($row['pt_trasferta']) {
            case "3":
              $v++;
              break;
            case "1":
              $p++;
              break;
            case "0":
              $s++;
              break;
			}
        }
          
        $ind++;
	}
    
    $percorso[$per]['fase'] =   $fase!=1||$fase!=2?$fase+1:$fase;
    $percorso[$per]['calendario'] = $calendario;
    
    $totali['win']=$v;
    $totali['los']=$s;
    $totali['par']=$p;
    $totali['gol']=$g;
    $totali['num']=$p+$s+$v;
    
   	$statistiche['totali'] = $totali;
    $statistiche['percorso'] = $percorso;
}
else
{
	errorMessage('query errata: giornata attuale');
}


    
    

//risultato
$myObj->turno = $turno;
$myObj->risultati = $risultati;
$myObj->match = $match_in_corso;
$myObj->statistiche = $statistiche;



$totObj=['data'=>$myObj];

$myJSON = json_encode($totObj);
echo $myJSON;

?>
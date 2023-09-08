<?php

require_once '../config/connect_local.php';
//require_once '../config/validate.php';
    
//dichiarazione variabili	
$gironi = [];
$factory = [];

//ripescaggio
$pos_ripescabili = array(3,4);
$tmp_ripescabili = [];
$ripescate =[];
$num_ripescate = 1;


//Query ed elaborazioni
//gironi
$sql1 = "SELECT c.girone,c.utente as id,sum(c.punti) as tot_pt,sum(c.goal) as tot_goal,u.squadra,av.nome as avatar, ";
$sql1 .="((AVG(c.media_gol)*3)+AVG(c.media_pt))*10 as factory FROM( ";
$sql1 .="SELECT c.utente_casa as utente,sum(c.pt_casa) as punti,c.girone,sum(c.gol_casa) as goal,'casa' as testo, ";
$sql1 .="AVG(c.gol_casa) as media_gol, AVG(c.pt_casa) as media_pt  ";
$sql1 .="FROM calendario c ";
$sql1 .="WHERE c.fase=1 AND c.calcolato=1 or c.giornata=1 GROUP BY utente ";
$sql1 .="UNION ";
$sql1 .="SELECT c.utente_trasferta as utente,sum(c.pt_trasferta) as punti,c.girone,sum(c.gol_trasferta) as goal, ";
$sql1 .="'trasferta' as testo ,AVG(c.gol_trasferta) as media_gol, AVG(c.pt_trasferta) as media_pt ";
$sql1 .="FROM calendario c ";
$sql1 .="WHERE c.fase=1 AND c.calcolato=1 or c.giornata=1 GROUP BY utente ";
$sql1 .=")c ,utenti u,avatar av WHERE u.id_utente=c.utente and u.avatar_id=av.id_avatar ";
$sql1 .="GROUP BY id ORDER BY  c.girone,tot_pt DESC,factory DESC ";




if($result = mysqli_query($con,$sql1))
{
	$ele = -1;
    $item = [];
    $teams = [];
    $gir=" ";
	while($row = mysqli_fetch_assoc($result))
	{
 		if($gir!=$row['girone']){
        	if($ele>=0){
              $gironi[$ele]['girone']=$gir;
              $gironi[$ele]['teams']=$teams;
              $teams = [];
            }
            $gir=$row['girone'];
            $ele++;
        }
		
		$item['id_utente'] = $row['id'];
        $item['squadra'] = $row['squadra'];
		$item['punti'] = $row['tot_pt'];
		$item['gol'] = $row['tot_goal'];
        $item['avatar'] = $row['avatar'];
        $item['factory'] = $row['factory'];
        $item['posizione'] = count($teams) + 1;
 
        array_push($teams, $item);
        
        if (in_array($item['posizione'], $pos_ripescabili)) {
             array_push($tmp_ripescabili, $item);
        }
	}
    
      $gironi[$ele]['girone']=$gir;
      $gironi[$ele]['teams']=$teams;

}
else
{
	errorMessage('query errata: classifica gironi');
}



//factory
$sql2 = "SELECT c.utente as id,u.squadra,av.nome as avatar,u.ruolo_id, ";
$sql2 .="((AVG(c.media_gol)*3)+AVG(c.media_pt))*10 as factory FROM(  ";
$sql2 .="SELECT c.utente_casa as utente, AVG(c.gol_casa) as media_gol , AVG(c.pt_casa) as media_pt ";
$sql2 .="FROM calendario c ";
$sql2 .="WHERE c.calcolato=1 or c.giornata=1 GROUP BY utente ";
$sql2 .="UNION ";
$sql2 .="SELECT c.utente_trasferta as utente, AVG(c.gol_trasferta) as media_gol, AVG(c.pt_trasferta) as media_pt ";
$sql2 .="FROM calendario c ";
$sql2 .="WHERE c.calcolato=1 or c.giornata=1 GROUP BY utente ";
$sql2 .=")c ,utenti u,avatar av WHERE u.id_utente=c.utente and u.avatar_id=av.id_avatar ";
$sql2 .="GROUP BY id ORDER BY  factory DESC ";


if($result = mysqli_query($con,$sql2))
{
	$ele = 0;

	while($row = mysqli_fetch_assoc($result))
	{
		$factory[$ele]['id_utente'] = $row['id'];
      	$factory[$ele]['squadra'] = $row['squadra'];
        $factory[$ele]['factory'] = $row['factory']>10?str_replace(".","",substr($row['factory'],0,3)):1;
      	$factory[$ele]['avatar'] = $row['ruolo_id'] == 4 || $row['ruolo_id'] == 2 || $row['ruolo_id'] == 6 ? "ELIMINATO" : $row['avatar'];
       	$factory[$ele]['eliminato'] = $row['ruolo_id'] == 4 || $row['ruolo_id'] == 2;
        $ele++;
	}
    
}
else
{
	errorMessage('query errata: classifica factory');
}

//aggiorna la posizione del ripescaggio

function DescSort($val1,$val2)
{
  #check if both the values are equal
    if ($val1['factory'] == $val2['factory']) return 0;
  #check if not equal, then compare values
    return ($val1['factory'] < $val2['factory']) ? 1 : -1;
}

usort($tmp_ripescabili,'DescSort');

for($x=0;$x<$num_ripescate;$x++)
{
 	array_push($ripescate, $tmp_ripescabili[$x]['id_utente']);
}

for($i=0;$i<count($gironi);$i++)
{
 	for($y=0;$y<count($gironi[$i]['teams']);$y++)
	{
       if (in_array($gironi[$i]['teams'][$y]['id_utente'], $ripescate)) {
               $gironi[$i]['teams'][$y]['posizione'] = 2;
          }
	}
 
}





//risultato
$myObj->gironi = $gironi;
$myObj->factory = $factory;


$totObj=['data'=>$myObj];

$myJSON = json_encode($totObj);
echo $myJSON;

?>
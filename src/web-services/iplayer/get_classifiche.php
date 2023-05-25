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
$sql1 = "SELECT r.utente_id as id,sum(r.goals) as tot_goal,sum(r.punti) as tot_pt,c.girone, ";
$sql1 .="((AVG(r.goals)*3)+AVG(r.punti))*10 as factory,u.squadra,a.nome as avatar ";
$sql1 .="FROM giornate g ";
$sql1 .="JOIN calendarioNEW c ON c.giornata_id = g.id_giornata  ";
$sql1 .="JOIN risultati r ON r.calendario_id = c.id_calendario ";
$sql1 .="JOIN utenti u ON u.id_utente = r.utente_id ";
$sql1 .="JOIN avatar a ON a.id_avatar = u.avatar_id ";
$sql1 .="WHERE g.fase_id = 1 and g.is_calcolata = 1 ";
$sql1 .="GROUP BY r.utente_id ORDER BY c.girone,tot_pt DESC,factory DESC ";


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
$sql2 = "SELECT r.utente_id as id,((AVG(r.goals)*3)+AVG(r.punti))*10 as factory, ";
$sql2 .="u.squadra,a.nome as avatar,u.ruolo_id ";
$sql2 .="FROM giornate g  ";
$sql2 .="JOIN calendarioNEW c ON c.giornata_id = g.id_giornata  ";
$sql2 .="JOIN risultati r ON r.calendario_id = c.id_calendario ";
$sql2 .="JOIN utenti u ON u.id_utente = r.utente_id ";
$sql2 .="JOIN avatar a ON a.id_avatar = u.avatar_id ";
$sql2 .="WHERE g.is_calcolata = 1 GROUP BY r.utente_id ORDER BY factory DESC ";


if($result = mysqli_query($con,$sql2))
{
	$ele = 0;

	while($row = mysqli_fetch_assoc($result))
	{
		$factory[$ele]['id_utente'] = $row['id'];
      	$factory[$ele]['squadra'] = $row['squadra'];
        $factory[$ele]['factory'] = $row['factory']>10?str_replace(".","",substr($row['factory'],0,3)):1;
      	$factory[$ele]['avatar'] = $row['ruolo_id'] == 4 || $row['ruolo_id'] == 2 ? "ELIMINATO" : $row['avatar'];
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
<?php

require_once '../config/connect_local.php';
require_once '../config/validate.php';
    
$elements = [];

$sql = "SELECT c.girone,c.utente as id,sum(c.punti) as tot_pt,sum(c.goal) as tot_goal,u.squadra,av.nome as avatar FROM( ";
$sql .="SELECT c.utente_casa as utente,sum(c.pt_casa) as punti,c.girone,sum(c.gol_casa) as goal,'casa' as testo ";
$sql .="FROM calendario c ";
$sql .="WHERE c.fase=1 AND c.calcolato=1 or c.giornata=1 GROUP BY utente ";
$sql .="UNION ";
$sql .="SELECT c.utente_trasferta as utente,sum(c.pt_trasferta) as punti,c.girone,sum(c.gol_trasferta) as goal,'trasferta' as testo ";
$sql .="FROM calendario c ";
$sql .="WHERE c.fase=1 AND c.calcolato=1 or c.giornata=1 GROUP BY utente ";
$sql .=")c ,utenti u,avatar av WHERE u.id_utente=c.utente and u.avatar_id=av.id_avatar ";
$sql .="GROUP BY id ORDER BY  c.girone,tot_pt DESC,tot_goal DESC ";




if($result = mysqli_query($con,$sql))
{
	$ele = -1;
    $item = [];
    $teams = [];
    $gir=" ";
	while($row = mysqli_fetch_assoc($result))
	{
 		if($gir!=$row['girone']){
        	if($ele>=0){
              $elements[$ele]['girone']=$gir;
              $elements[$ele]['teams']=$teams;
              //$elements[$ele]['elemento']=$ele;
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
 
        array_push($teams, $item);
	}
    
      $elements[$ele]['girone']=$gir;
      $elements[$ele]['teams']=$teams;
      //$elements[$ele]['elemento']=$ele;
    
	echo json_encode(['data'=>$elements]);
}
else
{
	header("HTTP/1.1 500 Internal Server Error");
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode(array('message' => 'ERROR', 'code' => 404)));
}

?>
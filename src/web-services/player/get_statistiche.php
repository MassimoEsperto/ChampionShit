<?php

require_once '../config/connect_local.php';
require_once '../config/decode.php';
    
//dichiarazione variabili	
$risultati = [];
$giocate = []; 
$prossimo_match;



//Query ed elaborazioni
//limbro
$sql1 = "SELECT COUNT(*) AS limbo FROM data_partite WHERE now() BETWEEN data_inizio AND data_fine ";
if($result = mysqli_query($con,$sql1))
{
	while($row = mysqli_fetch_assoc($result))
	{
		$limbo =   $row['limbo'];
		$giornata['attuale']='1';
	}
}
else
{
	header("HTTP/1.1 500 Internal Server Error");
	header('Content-Type: application/json; charset=UTF-8');
	die(json_encode(array('message' => 'query errata: count limbo', 'code' => 400)));
}


//partite giocate
$sql = "SELECT c.id_partita,u.id_utente as id_casa,u.squadra as casa,t.id_utente as id_trasferta, t.squadra as trasferta,c.gol_casa,c.gol_trasferta,c.pt_casa,c.pt_trasferta ";
$sql .="FROM calendario c,utenti u,utenti t ";
$sql .="WHERE u.id_utente=c.utente_casa and t.id_utente=c.utente_trasferta ";
$sql .="AND c.calcolato=1 AND ( u.id_utente={$id_utente} OR t.id_utente={$id_utente} ) ";
$sql .="ORDER BY c.giornata,c.id_partita ";
if($result = mysqli_query($con,$sql))
{
	$ind=0;
    $v=0;
    $p=0;
    $s=0;
    $g=0;
	while($row = mysqli_fetch_assoc($result))
	{
		$giocate[$ind]['casa'] =   $row['casa'];
		$giocate[$ind]['trasferta'] =   $row['trasferta'];
        $giocate[$ind]['gol_casa'] =   $row['gol_casa'];
        $giocate[$ind]['gol_trasferta'] =   $row['gol_trasferta'];
        $ind++;
        
        if($row['id_casa']==$id_utente){
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
        	$g= $g+$row['gol_trasferta'];
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
	}
    $risultati['win']=$v;
    $risultati['los']=$s;
    $risultati['par']=$p;
    $risultati['gol']=$g;
    $risultati['num']=$ind;
}
else
{
	errorMessage('query errata: giornata attuale');
}





//risultato
$myObj->giocate = $giocate;
$myObj->risultati = $risultati;
$myObj->query = $sql;
$totObj=['data'=>$myObj];

$myJSON = json_encode($totObj);
echo $myJSON;

?>
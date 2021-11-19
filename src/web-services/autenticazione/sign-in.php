<?php

require_once '../config/connect_local.php';

$user = $_GET['user'];($_GET['user'] !== null && $_GET['user'] !== '')? mysqli_real_escape_string($con, $_GET['user']) : false;
$pass = ($_GET['pass'] !== null && $_GET['pass'] !== '')? mysqli_real_escape_string($con, $_GET['pass']) : false;


include_once '../config/JWT.php';

$objJWT = new JWT();//inizialize token


 // Validate.
if(trim($user) === '' || trim($pass) === '')
{
   die('valori non prelevati'. mysqli_error($con));
}



$sql = "select id_utente,username,email,ruolo,squadra,nome as avatar,avatar_id,language, "; 
$sql .="(SELECT count(*) FROM utente_com WHERE utente_id=id_utente AND visualizzata=0) as num_msg "; 
$sql .="from utenti,avatar where password = '{$pass}' and username='{$user}' and avatar_id=id_avatar"; 


$result = mysqli_query( $con , $sql );

if(! $result ) 
{
   header("HTTP/1.1 500 Internal Server Error");
   header('Content-Type: application/json; charset=UTF-8');
   die(json_encode(array('message' => 'query fallita', 'code' => 400)));
}

if ($result->num_rows > 0) 
{
	$rows = array();
    while($r = mysqli_fetch_assoc($result)) {
        $rows[] = $r;
	}

	// Create JWT
	$jwt = $objJWT->getJwt(['id_utente' => $rows[0]['id_utente'],
							'username' => $rows[0]['username'],
							'email' => $rows[0]['email'],
							'ruolo' => $rows[0]['ruolo'],
							'squadra' => $rows[0]['squadra'],
							'avatar' => $rows[0]['avatar'],
                            'id_avatar' => $rows[0]['avatar_id'],
                            'num_msg' => $rows[0]['num_msg'],
                            'language' => $rows[0]['language'],                         
							'login_dt' => new DateTime()]);
                            

 	echo json_encode(['token'=>$jwt]);

} 
else 
{
	 header("HTTP/1.1 500 Internal Server Error");
     header('Content-Type: application/json; charset=UTF-8');
     die(json_encode(array('message' => 'Dati errati!', 'code' => 400)));
}

?>
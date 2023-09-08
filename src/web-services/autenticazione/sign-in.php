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



$sql = "SELECT u.id_utente,u.username,u.email,u.language,u.ruolo_id, "; 
$sql .="(SELECT count(*) FROM utente_com uc WHERE uc.utente_id=u.id_utente AND uc.visualizzata=0) as num_msg, "; 
$sql .="s.id_squadra,s.squadra,s.lega,s.account,s.stato_id, "; 
$sql .="a.id_avatar,a.nome as avatar "; 
$sql .="FROM utenti u "; 
$sql .="LEFT JOIN squadre s ON s.utente_id = u.id_utente "; 
$sql .="LEFT JOIN avatar a ON s.avatar_id = a.id_avatar "; 
$sql .="WHERE u.password = '{$pass}' and u.username = '{$user}' "; 
$sql .="ORDER BY s.is_default ASC "; 


$result = mysqli_query( $con , $sql );

if(! $result ) 
{
   header("HTTP/1.1 500 Internal Server Error");
   header('Content-Type: application/json; charset=UTF-8');
   die(json_encode(array('message' => 'query fallita', 'code' => 400)));
}

$ele = 0;
if ($result->num_rows > 0) 
{
	$element = [];
    $element['squadre'] = [];
	
    while($row = mysqli_fetch_assoc($result)) {
   	
		$element['id_utente'] = $row['id_utente'];
        $element['username'] = $row['username'];
        $element['email'] = $row['email'];
        $element['ruolo'] = $row['ruolo_id'];
		$element['language'] = $row['language'];
		$element['num_msg'] = $row['num_msg'];
		
        if($row['id_squadra'] != null){
          $element['squadre'][$ele]['id_squadra'] = $row['id_squadra'];
          $element['squadre'][$ele]['squadra'] = $row['squadra'];
          $element['squadre'][$ele]['lega'] = $row['lega'];
          $element['squadre'][$ele]['account'] = $row['account'];
          $element['squadre'][$ele]['stato'] = $row['stato_id'];
          $element['squadre'][$ele]['id_avatar'] = $row['id_avatar'];
          $element['squadre'][$ele]['avatar'] = $row['avatar'];

          $ele++;
        }
        
        
         $element['squadra']['id_squadra'] = $row['id_squadra'];
         $element['squadra']['squadra'] = $row['squadra'];
         $element['squadra']['lega'] = $row['lega'];
         $element['squadra']['account'] = $row['account'];
         $element['squadra']['stato'] = $row['stato_id'];
         $element['squadra']['id_avatar'] = $row['id_avatar'];
         $element['squadra']['avatar'] = $row['avatar'];
	}

	// Create JWT
	$jwt = $objJWT->getJwt(['id_utente' => $element['id_utente'],
							'username' => $element['username'],
							'email' => $element['email'],
                            'language' => $element['language'],  
                            'ruolo' => $element['ruolo'],  
                            'num_msg' => $element['num_msg'],
                            'squadre' => $element['squadre'],
                            'selezionata' => $element['squadra'],
                            'qta' => $ele,
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
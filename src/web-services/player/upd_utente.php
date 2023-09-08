<?php

require_once '../config/connect_local.php';
require_once '../config/decode.php';
require_once '../config/post_data.php';

include_once '../config/JWT.php';

$objJWT = new JWT();//inizialize token


$email = mysqli_real_escape_string($con, trim($dati->email));  
$username = mysqli_real_escape_string($con, trim($dati->username));
$language = mysqli_real_escape_string($con, trim($dati->language));
$num_msg = mysqli_real_escape_string($con, trim($dati->num_msg));
$qta = mysqli_real_escape_string($con, trim($dati->qta));
$squadre = $dati->squadre;

$selezionata = $dati->selezionata;
$id_avatar = mysqli_real_escape_string($con, trim($selezionata->id_avatar)); 
$avatar = mysqli_real_escape_string($con, trim($selezionata->avatar)); 
$squadra = mysqli_real_escape_string($con, trim($selezionata->squadra));
$id_squadra_in = mysqli_real_escape_string($con, trim($selezionata->id_squadra));

$sql =  "UPDATE utenti SET username='{$username}',email='{$email}' ";
$sql .= "WHERE id_utente = {$id_utente} ; ";

$sql .= "UPDATE squadre SET is_default=0 WHERE utente_id = {$id_utente} ; ";

$sql .= "UPDATE squadre SET squadra=UPPER('{$squadra}'),avatar_id='{$id_avatar}',is_default=1 ";
$sql .= "WHERE id_squadra = {$id_squadra_in} ; ";


	// Create JWT
	$jwt = $objJWT->getJwt(['id_utente' => $id_utente,
							'username' => $username,
							'email' => $email,
                            'language' => $language,  
                            'num_msg' => $num_msg,
                            'squadre' => $squadre,
                            'selezionata' => $selezionata,
                            'qta' => $qta,
							'login_dt' => new DateTime()]);


if ($con->multi_query($sql) === TRUE)
{
    echo json_encode(['data'=>$jwt]);
    
}else{
	errorMessage('query errata: update utente');
}



?>
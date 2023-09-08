<?php

include_once 'JWT.php';


try {    

  $objJWT = new JWT();
  
  
  if (!$objJWT->checkJwt()) 
  	{        
		die(json_encode(array('errorToken' => 'Token Scaduto', 'data' => [])));
	}
    
  }catch(Exception $var) {
         die(json_encode(array('errorToken' => 'Token Non Valido', 'data' => [])));
}

//decode
$decodeJwt = $objJWT->decodeJwt();
$id_utente = $decodeJwt['id_utente'];
$username	= $decodeJwt['username'];
$email = $decodeJwt['email'];

$selezionata = $decodeJwt['selezionata'];
$id_squadra = $selezionata['id_squadra'];


?>
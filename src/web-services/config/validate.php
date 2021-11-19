<?php

include_once 'JWT.php';

try {    

  $objJWT = new JWT();
  
  if (!$objJWT->checkJwt()) 
  	{        
		die(json_encode(array('errorToken' => 'Token Non Scaduto', 'data' => [])));
	}
    
  }catch(Exception $var) {
         die(json_encode(array('errorToken' => 'Token Non Valido', 'data' => [])));
}



?>
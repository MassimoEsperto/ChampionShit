<?php

require_once '../config/connect_local.php';
    
//info
$myObj->versione = "5.1";
$myObj->whatsapp = "https://chat.whatsapp.com/JICQWVbYoqCKh9gek92EIW";



$totObj=['data'=>$myObj];
$myJSON = json_encode($totObj);
echo $myJSON;

?>
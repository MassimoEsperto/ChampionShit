<?php

require_once '../config/connect_local.php';
$tabelle = "lista_calciatori order by nome_calciatore"; 
require_once '../common/all_objects.php';
require_once '../common/utenti.php';


//risultato
$myObj->utenti = $utenti_;
$myObj->lista_calciatori = $oggetti_['lista_calciatori order by nome_calciatore'];



$totObj=['data'=>$myObj];

$myJSON = json_encode($totObj);

echo $myJSON;

?>
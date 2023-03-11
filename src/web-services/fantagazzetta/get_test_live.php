<?php

require_once '../config/connect_local.php';

$probabili = [];

$response =  file_get_contents('https://www.fantapiu3.com/voti-globali/fantacalcio-voti-fantagazzetta-serie-a.php');
$page = explode("team-name", $response);

$arr_length = count($page);

for($i=1;$i<$arr_length;$i++)
{
  	$singolo_tmp = explode("table-text bold", $page[$i]);
   
    echo json_encode(['data'=>$singolo_tmp]);

/*
    if(count($singolo_tmp)>1)
   	{
    	$nome = explode("</", $singolo_tmp[0]);
   
    	$player = str_replace('">','',$nome[0]);
        $player = str_replace('&#39;','',$player);
        $player = str_replace('.','',$player);
        $player = strtoupper($player);
      
       	$percentuale= explode("</", $singolo_tmp[1]);
       	$percentuale = str_replace('">','',$percentuale);
  
        $probabili[$player] = $percentuale[0];
  
    }*/
  
}

//echo json_encode(['data'=>$probabili]);

?>
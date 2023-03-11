<?php

require_once '../config/connect_local.php';

$probabili = [];

$response =  file_get_contents('https://www.fantacalcio.it/probabili-formazioni-serie-a');
$page = explode('target="_self">', $response);

$arr_length = count($page);

for($i=1;$i<$arr_length;$i++)
{
  	$singolo_tmp = explode("percentage", $page[$i]);

    if(count($singolo_tmp)>1)
   	{
    	$nome = explode("</", $singolo_tmp[0]);
   
   		$player = preg_replace('/\n+/', '', $nome[0]);
    	$player = str_replace('&#39;','',$player);
    	$player = str_replace('&#x27;','',$player);
    	$player = str_replace('"','',$player);
        $player = str_replace('.','',$player); //aggiunta
    	$player = ltrim($player, ' ');
        $player = strtoupper($player);
        
       	$percentuale= explode("</", $singolo_tmp[1]);
       	$percentuale = str_replace('">','',$percentuale);
  
        $probabili[$player] = $percentuale[0];
  
    }
  
}

echo json_encode(['data'=>$probabili]);

?>
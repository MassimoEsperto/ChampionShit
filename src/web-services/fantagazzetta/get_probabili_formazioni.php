<?php

require_once '../config/connect_local.php';

$probabili = [];

$response =  file_get_contents('https://www.fantacalcio.it/probabili-formazioni-serie-a');
$page = explode('target="_self">', $response);

$arr_length = count($page);

for($i=1;$i<$arr_length;$i++)
{
  	$singolo_tmp = explode("aria-valuenow", $page[$i]);

    if(count($singolo_tmp)>1)
   	{
    	$nome = explode("</", $singolo_tmp[0]);
   
   		$player = preg_replace('/\n+/', '', $nome[0]);
    	$player = str_replace('&#39;','',$player);
    	$player = str_replace('&#x27;','',$player);
    	$player = str_replace('"','',$player);
        $player = str_replace('.','',$player); //aggiunta
    	$player = ltrim($player, ' ');
        $player = str_replace('</span>','',$player);
    	$player = str_replace('<span>','',$player);
        $player = strtoupper($player);
        
       	$valore= explode("aria-valuemin", $singolo_tmp[1]);
        $percentuale = trim($valore[0]);
        $percentuale = str_replace('"','',$percentuale);
        $percentuale = str_replace('=','',$percentuale);
       
        $probabili[$player] = $percentuale . "%";
  
    }
  
}

echo json_encode(['data'=>$probabili]);

?>
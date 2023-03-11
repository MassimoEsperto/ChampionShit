<?php

require_once '../config/connect_local.php';

$live = [];
$player='';
$voto=4;

$response =  file_get_contents('https://www.fantacalcio.it/voti-fantacalcio-serie-a');
$page = explode('2022-23" target="_self">', $response);
 
$arr_length = count($page);


for($i=1;$i<$arr_length;$i++)
{
  	$player_tmp = explode("</a>", $page[$i]);
   
    $player = preg_replace('/\n+/', '', $player_tmp[0]);
     //$player = preg_replace('/\s+/', '', $player);
    $player = str_replace('&#39;','',$player);
    $player = str_replace('&#x27;','',$player);
    $player = str_replace('"','',$player);
    $player = str_replace('.','',$player); //aggiunta
    $player = ltrim($player, ' ');
    $player = str_replace('</span>','',$player);
    $player = str_replace('<span>','',$player);
    $player = strtoupper($player);
    
 // echo $player;
   
    $voto_tmp1=  explode('player-fanta-grade" data-value="', $player_tmp[1]);
    $voto_tmp2=  explode('</span>', $voto_tmp1[1]);
    $voto = str_replace('">','',$voto_tmp2[0]);
    $voto = str_replace('55','4',$voto);
    
      //  echo $voto;
      
   //  $live[$i-1]['player'] = $player.trim();
     $live[$player.trim()] = $voto;
  
}

//echo $live['OKOLI'];
//echo 'voto ';
 echo json_encode(['data'=>$live]);

?>
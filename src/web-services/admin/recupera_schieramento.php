<?php
require_once '../config/connect_local.php';
require_once '../config/post_data.php';
require_once '../common/recuperate.php';
require_once '../common/rose.php';
 
$id_squadra = mysqli_real_escape_string($con, (int)$dati->id_squadra);
$id_avversario = mysqli_real_escape_string($con, (int)$dati->id_avversario);
$id_risultato = mysqli_real_escape_string($con, (int)$dati->id_risultato);
$id_calendario = mysqli_real_escape_string($con, (int)$dati->id_calendario);
$ele = 1;

$idrec = array_search($id_squadra, array_column($recuperate_, 'id_squadra'));
$idrosa = array_search($id_avversario, array_column($rose_, 'squadra_id'));
$rosa_avversario = ($idrosa !== false ? $rose_[$idrosa]['lista'] : []);
$recuperata = $recuperate_[$idrec];

foreach($recuperata['schieramento'] as $tmp) 
{
    $id_tmp = array_search($tmp, array_column($rosa_avversario, 'id_calciatore'));

	$id_calciatore = $id_tmp !== false ? 1 : $tmp ;
    
	$sql .= "REPLACE INTO formazioni(risultato_id, schieramento, calciatore_id) ";
	$sql .= "VALUES ({$id_risultato}, {$ele}, {$id_calciatore}); ";
	$ele++;
}

$sql .=  "UPDATE risultati SET modulo_id = {$recuperata['id_modulo']} ,is_inserita = 0 ";
$sql .= "WHERE id_risultato = {$id_risultato} LIMIT 1 ;";

if ($con->multi_query($sql) === TRUE) 
{
	$ritono = [
				  'stato' => $con->affected_rows,
				  'risposta' => 'ok'
				];
	echo json_encode(['data'=>$ritono]);
} 
else 
{
    errorMessage('valori sballati');
}


?>
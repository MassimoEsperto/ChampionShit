<?php

require_once '../config/connect_local.php';
$tabelle = "lista_calciatori order by nome_calciatore,comunicazioni ORDER BY id_comunicazione DESC,ruoli,stati_squadre"; 
require_once '../common/all_objects.php';
require_once '../common/utenti.php';
require_once '../common/giornate.php';
require_once '../common/rose.php';
require_once '../common/formazioni.php';
require_once '../common/squadre.php';
    
//dichiarazione variabili	
$calcolato=[];
$recuperate=[];


//Query ed elaborazioni

//giornate calcolate
$sql4 = "SELECT is_calcolata,id_giornata FROM giornate GROUP BY id_giornata ";

if($result = mysqli_query($con,$sql4))
{
	$incalcolate=[];
	$calcolate=[];
	$ele_c = 0;
    $ele_i = 0;
    $incalcolate[$ele_i] = 0;
	while($row = mysqli_fetch_assoc($result))
	{
    	if($row['is_calcolata']==1){
          $calcolate[$ele_c] = $row['id_giornata'];
          $ele_c++;
        }else{
          $incalcolate[$ele_i] = $row['id_giornata'];
          $ele_i++;
        }
	}
	$calcolato['SI'] = $calcolate;
	$calcolato['NO'] = $incalcolate;
    
}
else
{
	errorMessage('query errata: ultime formazioni inserite');
}







//risultato

$myObj->comunicazioni = $oggetti_['comunicazioni ORDER BY id_comunicazione DESC'];
$myObj->utenti = $utenti_;
$myObj->lista_calciatori = $oggetti_['lista_calciatori order by nome_calciatore'];
$myObj->calcolato = $calcolato;
$myObj->formazioni = $formazioni_;
$myObj->rose = $rose_;
$myObj->giornate = $giornate_;
$myObj->ruoli = $oggetti_['ruoli'];
$myObj->stati_squadre = $oggetti_['stati_squadre'];
$myObj->squadre = $squadre_;


$totObj=['data'=>$myObj];

$myJSON = json_encode($totObj);

echo $myJSON;

?>
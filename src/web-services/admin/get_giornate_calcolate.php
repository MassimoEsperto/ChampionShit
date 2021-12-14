<?php

require_once '../config/connect_local.php';
    
$incalcolate=[];
$calcolate=[];

$sql = "SELECT calcolato,giornata ";
$sql .="FROM calendario ";
$sql .="GROUP BY giornata ";


if($result = mysqli_query($con,$sql))
{
	$ele_c = 0;
    $ele_i = 0;
	while($row = mysqli_fetch_assoc($result))
	{
    	if($row['giornata']==1){
          $calcolate[$ele_c] = $row['giornata'];
          $ele_c++;
        }else{
          $incalcolate[$ele_i] = $row['giornata'];
          $ele_i++;
        }
	}
}
else
{
	header("HTTP/1.1 500 Internal Server Error");
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode(array('message' => 'ERROR', 'code' => 404)));
}

$myObj->calcolate = $calcolate;
$myObj->incalcolate = $incalcolate;
$totObj=['data'=>$myObj];

$myJSON = json_encode($totObj);
echo $myJSON;

?>
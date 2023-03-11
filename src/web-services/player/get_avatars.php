<?php

require_once '../config/connect_local.php';
require '../config/validate.php';

$sql = "SELECT id_avatar,nome FROM avatar ORDER BY nome ASC"; 

$result = mysqli_query( $con , $sql );

if(! $result ) 
{
   die('query failed'. mysqli_error($con));
}

if ($result->num_rows > 0) 
{
	$rows = array();
    while($r = mysqli_fetch_assoc($result)) {
        $rows[] = $r;
	}

 	echo json_encode(['data'=>$rows]);

} 
else 
{
	 errorMessage('query errata: avatars');
}

?>
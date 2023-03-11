<?php
    
//dichiarazione variabili	
$fasi_ = [];


//inizializzo a 0 nel caso fossero null
$ele = 0;
$fasi_[$ele]['id'] = 0;
$fasi_[$ele]['descrizione'] = "vuoto";


//Query ed elaborazioni
//fasi possibili
$sql_fasi = "SELECT id_fase,descrizione FROM fasi ORDER BY id_fase ASC";

if($result = mysqli_query($con,$sql_fasi))
{
	$ele = 0;
	while($row = mysqli_fetch_assoc($result))
	{
     	$fasi_[$ele]['id'] = $row['id_fase'];
		$fasi_[$ele]['descrizione'] = $row['descrizione'];
		$ele++;
	}
}


?>
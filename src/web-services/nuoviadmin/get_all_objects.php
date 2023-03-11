<?php

require_once '../config/connect_local.php';

$oggetti = [];
$tabella = "";
$tabelle = ($_GET['tabelle'] !== null && $_GET['tabelle'] !== '')? mysqli_real_escape_string($con, $_GET['tabelle']) : false;
//accetta una lista di tabelle separate dalla virgola

 // Validate.
if(trim($tabelle) === '')
{
   die('valori non prelevati'. mysqli_error($con));
}

$tabella = explode(',', $tabelle);
$arr_length = count($tabella);

for($i=0;$i<$arr_length;$i++)
{

  $sql = "SELECT * FROM {$tabella[$i]}";

  if($result = mysqli_query($con,$sql))
  {
      $ele = 0;
      while($row = mysqli_fetch_assoc($result))
      {
      	  unset($row['password']);
          unset($row['messaggio']);
          $oggetti[$tabella[$i]][$ele] = $row;
          $ele++;
      }
  }
  else
  {
      errorMessage('query errata: get all table: ' . $tabella[$i]);
  }
  
}
    

echo json_encode(['data'=>$oggetti]);

?>
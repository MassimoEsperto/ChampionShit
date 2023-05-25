<?php

$oggetti_ = [];
$tabella = "";


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
          $oggetti_[$tabella[$i]][$ele] = $row;
          $ele++;
      }
  }
  
}
    

?>
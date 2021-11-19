<?php
error_reporting(E_ALL);

$file = "https://www.fantacalcio.it/probabili-formazioni-serie-a/index.html";
$file_txt = file($file);
$n = count($file_txt) - 3;  

echo $file_txt[2] ;
?>
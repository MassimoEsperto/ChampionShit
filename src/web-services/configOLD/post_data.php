<?php

// Get the posted data.
$postdata = file_get_contents("php://input");

if(!isset($postdata))
{
	die('valori non prelevati isset'. mysqli_error($con));
}

if(empty($postdata))
{
	die('valori non prelevati empty'. mysqli_error($con));
}

// Extract the data.
$request = json_decode($postdata);

// Sanitize.
$dati= $request->data;
	

?>
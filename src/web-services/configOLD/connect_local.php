<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');
// db credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'fantashitcup');
define('DB_PASS', '');
define('DB_NAME', 'my_fantashitcup');


// Connect with the database.
function connect()
{
  $connect = mysqli_connect(DB_HOST ,DB_USER ,DB_PASS ,DB_NAME);

  if (mysqli_connect_errno($connect)) {
    die("Failed to connect:" . mysqli_connect_error());
  }

  mysqli_set_charset($connect, "utf8");

  return $connect;
}

//error message
function errorMessage($emessage) {
  header("HTTP/1.1 500 Internal Server Error");
  header('Content-Type: application/json; charset=UTF-8');
  die(json_encode(array('message' => $emessage, 'code' => 400)));
}

$con = connect();

$base_link_cup = "https://fantashitcup.altervista.org/WorldWideFantashit/ShitCup";


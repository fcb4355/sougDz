<?php

$dsn = "mysql:host=localhost;dbname=market";    // host of the server
$username = "root";     // user name of server
$password = "";         // password of the server
$db_name = "market";      // name of the dataBase

$option = array(
  PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
);

try {
  $conn = new PDO($dsn, $username, $password, $option);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo "Failed To Connect" . $e->getMessage();
}

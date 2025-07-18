<?php

$host = "localhost";
$name = "root";

$db = new PDO("mysql:host=$host;dbname=ToDolistDB", $name);

$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// echo "Database get";

?>
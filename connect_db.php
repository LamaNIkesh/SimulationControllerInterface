<?php

$user = 'root';
$pass = 'cncr2018';
$db = 'Registration';

$db = new mysqli('localhost', $user, $pass, $db) or die("Unable to connect");
echo "Connected to the database";



?>
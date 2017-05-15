<?php
//session start since no header is included before. 
//The flag info is passed on to the header.
session_start();
$_SESSION['flag'] = 0;
include ("home.php");

?>